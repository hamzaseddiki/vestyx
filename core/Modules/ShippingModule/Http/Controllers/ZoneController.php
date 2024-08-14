<?php

namespace Modules\ShippingModule\Http\Controllers;

use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\CountryManage\Entities\Country;
use Modules\ShippingModule\Entities\Zone;
use Modules\ShippingModule\Entities\ZoneRegion;

class ZoneController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:shipping-zone-list|shipping-zone-create|shipping-zone-edit|shipping-zone-delete', ['only', ['index']]);
        $this->middleware('permission:shipping-zone-create', ['only', ['store']]);
        $this->middleware('permission:shipping-zone-edit', ['only', ['update']]);
        $this->middleware('permission:shipping-zone-delete', ['only', ['destroy', 'bulk_action']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_zones = Zone::all();
        $all_countries = Country::where('status', 'publish')->get();
        return view('shippingmodule::tenant.admin.zone.all', compact('all_zones', 'all_countries'));
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
            'name' => 'required|string|max:191',
            'country' => 'nullable|array',
            'state' => 'nullable|array',
        ]);

        foreach ($request->country as $country)
        {
            foreach ($request->state ?? [] as $key => $state)
            {
                $if_zone_exist = ZoneRegion::whereJsonContains('country', $country)->whereJsonContains('state', $state)->first();
                if ($if_zone_exist != null)
                {
                    return back()->with(FlashMsg::create_failed('Please select unique states in each zone. Zone'));
                }
            }
        }


        $zone = Zone::create(['name' => $request->name]);
        $zone_region = ZoneRegion::create([
            'zone_id' => $zone->id,
            'country' => json_encode($request->country),
            'state' => json_encode($request->state),
        ]);

        return $zone->id
            ? back()->with(FlashMsg::create_succeed('Zone'))
            : back()->with(FlashMsg::create_failed('Zone'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'country' => 'nullable|array',
            'state' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();
            $zone_updated = Zone::find($request->id)->update(['name' => $request->name]);

            ZoneRegion::where('zone_id', $request->id)->delete();

            $zone_region = ZoneRegion::create([
                'zone_id' => $request->id,
                'country' => json_encode($request->country),
                'state' => json_encode($request->state),
            ]);
            DB::commit();
            return back()->with(FlashMsg::update_succeed('Zone'));
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with(FlashMsg::update_failed('Zone'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Zone $item)
    {
        try {
            DB::beginTransaction();
            $item->delete();
            ZoneRegion::where('zone_id', $item->id)->delete();
            DB::commit();
            return back()->with(FlashMsg::delete_succeed('Zone'));
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with(FlashMsg::delete_failed('Zone'));
        }
    }

    public function bulk_action(Request $request)
    {
        try {
            DB::beginTransaction();
            Zone::whereIn('id', $request->ids)->delete();
            ZoneRegion::whereIn('zone_id', $request->ids)->delete();
            DB::commit();
            return FlashMsg::delete_succeed('Zone');
        } catch (\Throwable $th) {
            DB::rollBack();
            return FlashMsg::delete_failed('Zone');
        }
    }
}
