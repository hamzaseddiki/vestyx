<?php

namespace Modules\Wallet\Http\Controllers\Backend;

use App\Helpers\FlashMsg;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Wallet\Entities\Wallet;
use Modules\Wallet\Entities\WalletHistory;

class WalletController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:wallet-list|wallet-history',['only' => ['wallet_lists']]);
        $this->middleware('permission:wallet-history',['only' => ['wallet_history']]);
    }

    public function wallet_lists()
    {
        $wallet_lists = Wallet::with('user')->latest()->get(['id','user_id','balance', 'status']);
        return view('wallet::backend.wallet-lists',compact('wallet_lists'));
    }

    public function change_status($id)
    {
        $job = Wallet::find($id);
        $job->status === 1 ? $status = 0 : $status = 1;
        Wallet::where('id',$id)->update(['status'=>$status]);
        return redirect()->back()->with(FlashMsg::item_new('Status Changed Success'));
    }

    public function wallet_history()
    {
        $wallet_history_lists = WalletHistory::with('user')
            ->latest()
            ->where('payment_status','!=','')
            ->get(['id','user_id','payment_gateway','payment_status','amount','manual_payment_image']);
        return view('wallet::backend.history',compact('wallet_history_lists'));
    }

    public function wallet_history_status($id)
    {
        $wallet_history = WalletHistory::find($id);
        $status = $wallet_history->payment_status === 'pending' ? 'complete' : '';
        WalletHistory::where('id',$id)->update(['payment_status'=>$status]);

        $wallet = Wallet::select(['id','user_id','balance'])->where('user_id',$wallet_history->user_id)->first();
        Wallet::where('user_id',$wallet->user_id)->update([
            'balance'=>$wallet->balance+$wallet_history->amount,
        ]);
        return redirect()->back()->with(FlashMsg::item_new('Status Changed Success'));
    }

    public function wallet_settings()
    {
        return view('wallet::backend.wallet-settings');
    }

    public function wallet_settings_update(Request $request)
    {
        $request->validate([
            'user_wallet' => 'nullable'
        ]);

        update_static_option('user_wallet', $request->user_wallet);

        return back()->with(FlashMsg::update_succeed('Wallet Settings'));
    }
}
