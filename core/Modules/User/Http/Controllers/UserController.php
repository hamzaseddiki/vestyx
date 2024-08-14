<?php

namespace Modules\User\Http\Controllers;

use App\Http\Services\Media;
use Modules\User\Entities\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\User\Http\Requests\StoreUserRequest;
use Modules\User\Http\Requests\UpdatePasswordRequest;
use Modules\User\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:user-list|user-create|user-edit|user-delete',['only' => ['all_user']]);
        $this->middleware('permission:user-create',['only' => ['new_user','new_user_add']]);
        $this->middleware('permission:user-delete',['only' => ['bulk_action','new_user_delete']]);
        $this->middleware('permission:user-edit',['only' => ['user_password_change','user_update','email_status']]);
    }
    public function all_user()
    {
        $all_user = User::all();

        return view('user::admin.all-user')->with(['all_user' => $all_user]);
    }
    public function user_password_change(UpdatePasswordRequest $request)
    {
        $user = User::findOrFail($request->ch_user_id);
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->back()->with(['msg' => __('Password Change Success..'), 'type' => 'success']);
    }
    public function user_update(UpdateUserRequest $request) {
        User::find($request->user_id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'zipcode' => $request->zipcode,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'phone' => $request->phone,
        ]);
        return redirect()->back()->with(['msg' => __('User Profile Update Success..'), 'type' => 'success']);
    }
    public function new_user_delete(Request $request, $id)
    {
        User::find($id)->delete();
        return redirect()->back()->with(['msg' => __('User Profile Deleted..'), 'type' => 'danger']);
    }

    public function new_user()
    {
        return view('user::admin.add-new-user');
    }

    public function new_user_add(StoreUserRequest $request){
        User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'zipcode' => $request->zipcode,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'phone' => $request->phone,
            'password' => Hash::make($request->password)
        ]);

        return redirect()->back()->with(['msg' => __('New User Created..'), 'type' => 'success']);
    }

    public function bulk_action(Request $request)
    {
        $all = User::find($request->ids);
        foreach ($all as $item) {
            $item->delete();
        }
        return response()->json(['status' => 'ok']);
    }

    public function email_status(Request $request)
    {
        User::where('id', $request->user_id)->update([
            'email_verified' => $request->email_verified == 0 ? 1 : 0
        ]);
        return redirect()->back()->with(['msg' => __('Email Verify Status Changed..'), 'type' => 'success']);
    }
}
