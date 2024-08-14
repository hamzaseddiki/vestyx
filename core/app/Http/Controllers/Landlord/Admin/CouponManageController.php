<?php

namespace App\Http\Controllers\Landlord\Admin;

use App\Helpers\ResponseMessage;
use App\Helpers\SanitizeInput;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CouponManageController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:coupon-list|coupon-create|coupon-edit|coupon-delete',['only' => ['index']]);
        $this->middleware('permission:coupon-create',['only' => ['store']]);
        $this->middleware('permission:coupon-edit',['only' => ['update','clone']]);
        $this->middleware('permission:coupon-delete',['only' => ['delete','bulk_action']]);
    }
    public function index()
    {
        $all_coupons = Coupon::latest()->get();
        return view('landlord.admin.coupon.index',compact('all_coupons'));
    }

    public function store(Request $request){

        $this->validate($request,[
            'title' => 'required|string',
            'code' => 'required|string|unique:coupons',
            'discount_amount' => 'required|numeric',
            'discount_type' => 'required|string',
            'expire_date' => 'required|string',
            'status' => 'nullable|string',
            'max_use_qty' => 'required',
        ]);

        $data = [
            'title' => SanitizeInput::esc_html($request->title),
            'code' => $request->code,
            'discount_amount' => $request->discount_amount,
            'discount_type' => $request->discount_type,
            'expire_date' => $request->expire_date,
            'max_use_qty' => $request->max_use_qty,
            'status' => $request->status,
        ];

        Coupon::create($data);
        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function update(Request $request){
        $request->validate([
            'title' => 'required|string',
            'code' => ['required', Rule::unique('coupons')->ignore($request->id)],
            'discount_amount' => 'required',
            'discount_type' => 'required|string',
            'expire_date' => 'required|string',
            'max_use_qty' => 'required',
            'status' => 'nullable|string',
        ]);

        Coupon::findOrFail($request->id)->update([
            'title' => SanitizeInput::esc_html($request->title),
            'code' => $request->code,
            'discount_amount' => $request->discount_amount,
            'discount_type' => $request->discount_type,
            'expire_date' => $request->expire_date,
            'max_use_qty' => $request->max_use_qty,
            'status' => $request->status,
        ]);

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function delete(Request $request,$id){
        Coupon::findOrFail($id)->delete();
        return response()->danger(ResponseMessage::delete());
    }

    public function bulk_action(Request $request){

        Coupon::whereIn('id',$request->ids)->delete();
        return redirect()->back()->with([
            'msg' => __('Coupon Delete Success...'),
            'type' => 'danger'
        ]);
    }
}
