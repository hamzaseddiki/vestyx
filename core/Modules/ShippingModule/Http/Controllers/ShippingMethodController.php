<?php

namespace Modules\ShippingModule\Http\Controllers;

use App\Enums\ShippingEnum;
use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\CouponManage\Entities\ProductCoupon;
use Modules\ShippingModule\Entities\ShippingMethod;
use Modules\ShippingModule\Entities\ShippingMethodOption;
use Modules\ShippingModule\Entities\Zone;

class ShippingMethodController extends Controller
{
    const BASE_URL = 'shippingmodule::tenant.admin.method.';
    const NAME = 'Shipping Method';

    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:shipping-method-list|shipping-method-create|shipping-method-edit|shipping-method-delete|shipping-method-make-default', ['only', ['index']]);
        $this->middleware('permission:shipping-method-create', ['only', ['store']]);
        $this->middleware('permission:shipping-method-edit', ['only', ['update']]);
        $this->middleware('permission:shipping-method-delete', ['only', ['destroy', 'bulk_action']]);
        $this->middleware('permission:shipping-method-make-default', ['only', ['makeDefault']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return
     */
    public function index()
    {
        $all_shipping_methods = ShippingMethod::with('zone')->get();
        return view(self::BASE_URL.'all', compact('all_shipping_methods'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $all_zones = Zone::all();
        $all_tax_status = ShippingEnum::taxStatus();
        $all_shipping_methods = ShippingMethod::all();
        $all_settings = ShippingEnum::settingPresets();
        $all_publish_status = ShippingEnum::publishStatus();
        $all_setting_presets = ShippingEnum::settingPresets();
        $all_shipping_method_names = ShippingEnum::shippingMethods();
        $all_coupons = ProductCoupon::where('expire_date', '>',date('Y-m-d'))->where("discount_on","shipping")->get();

        return view(self::BASE_URL.'new', compact(
            'all_zones',
            'all_settings',
            'all_shipping_methods',
            'all_tax_status',
            'all_publish_status',
            'all_setting_presets',
            'all_shipping_method_names',
            'all_coupons'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'zone_id' => 'required|exists:zones,id',
            'title' => 'required|string',
            'tax_status' => 'required|boolean',
            'status' => 'required|boolean',
            'setting_preset' => 'required|string',
            'cost' => 'required|numeric',
            'minimum_order_amount' => 'nullable|numeric',
            'coupon_code' => 'nullable'
        ]);

        $all_shipping_method_names = ShippingEnum::shippingMethods();

        try {
            DB::beginTransaction();

            $shipping_method_key = $request->title;
            $shipping_method_title = $all_shipping_method_names[$shipping_method_key];

            $shipping_method = ShippingMethod::create([
                'name' => $shipping_method_title,
                'zone_id' => $request->zone_id,
            ]);

            $minimum_order_amount = strlen($request->minimum_order_amount) ? $request->minimum_order_amount : 0;
            $cost = strlen($request->cost) ? $request->cost : 0;

            $shipping_method_options = ShippingMethodOption::create([
                'shipping_method_id' => $shipping_method->id,
                'title' => $shipping_method_title,
                'tax_status' => $request->tax_status,
                'status' => $request->status,
                'setting_preset' => $request->setting_preset,
                'cost' => $cost,
                'minimum_order_amount' => $minimum_order_amount,
                'coupon' => $request->coupon_code
            ]);

            DB::commit();
            return back()->with(FlashMsg::create_succeed(self::NAME));
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with(FlashMsg::create_failed(self::NAME));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Shipping\ShippingMethod  $shippingMethod
     * @return \Illuminate\Http\Response
     */
    public function show(ShippingMethod $shippingMethod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Shipping\ShippingMethod  $shippingMethod
     * @return \Illuminate\Http\Response
     */
    public function edit(ShippingMethod $item)
    {
        $all_tax_status = ShippingEnum::taxStatus();
        $all_shipping_methods = ShippingMethod::all();
        $all_settings = ShippingEnum::settingPresets();
        $all_publish_status = ShippingEnum::publishStatus();
        $all_setting_presets = ShippingEnum::settingPresets();
        $all_shipping_method_names = ShippingEnum::shippingMethods();
        $all_zones = Zone::all();
        $all_coupons = ProductCoupon::where('expire_date', '>',date('Y-m-d'))->where("discount_on","shipping")->get();

        return view(self::BASE_URL.'edit', compact(
            'item',
            'all_zones',
            'all_settings',
            'all_shipping_methods',
            'all_tax_status',
            'all_publish_status',
            'all_setting_presets',
            'all_shipping_method_names',
            'all_coupons'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Shipping\ShippingMethod  $shippingMethod
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:shipping_methods,id',
            'zone_id' => 'required|exists:zones,id',
            'title' => 'required|string',
            'tax_status' => 'required|boolean',
            'status' => 'required|boolean',
            'setting_preset' => 'required|string',
            'cost' => 'required|numeric',
            'minimum_order_amount' => 'nullable|numeric',
            'coupon_code' => 'nullable'
        ]);

        try {
            DB::beginTransaction();

            ShippingMethod::find($request->id)->update([
                'name' => $request->title,
                'zone_id' => $request->zone_id,
            ]);

            $minimum_order_amount = strlen($request->minimum_order_amount) ? $request->minimum_order_amount : 0;
            $cost = strlen($request->cost) ? $request->cost : 0;

            ShippingMethodOption::where('shipping_method_id', $request->id)->delete();

            $shipping_method_options = ShippingMethodOption::create([
                'shipping_method_id' => $request->id,
                'title' => $request->title,
                'tax_status' => $request->tax_status,
                'status' => $request->status,
                'setting_preset' => $request->setting_preset,
                'cost' => $cost,
                'minimum_order_amount' => $minimum_order_amount,
                'coupon' => $request->coupon_code
            ]);

            DB::commit();
            return back()->with(FlashMsg::update_succeed(self::NAME));
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with(FlashMsg::update_failed(self::NAME));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Shipping\ShippingMethod  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShippingMethod $item)
    {
        try {
            DB::beginTransaction();
            ShippingMethodOption::where('id', $item->id)->delete();
            $item->delete();
            DB::commit();
            return back()->with(FlashMsg::delete_succeed(self::NAME));
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with(FlashMsg::delete_failed(self::NAME));
        }
    }

    public function bulk_action(Request $request)
    {
        try {
            DB::beginTransaction();
            ShippingMethod::whereIn('id', $request->ids)->delete();
            ShippingMethodOption::whereIn('shipping_method_id', $request->ids)->delete();
            DB::commit();
            return back()->with(FlashMsg::delete_succeed(self::NAME));
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with(FlashMsg::delete_failed(self::NAME));
        }
    }

    public function makeDefault(Request $request)
    {
        try {
            DB::beginTransaction();
            ShippingMethod::where('is_default', 1)->update(['is_default' => 0]);
            ShippingMethod::where('id', $request->id)->first()->update(['is_default' => 1]);
            DB::commit();
            return back()->with(FlashMsg::explain('success', __('Shipping method set to default')));
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with(FlashMsg::explain('success', __('Shipping method set to default')));
        }
    }
}
