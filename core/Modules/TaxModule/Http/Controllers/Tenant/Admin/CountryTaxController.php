<?php

namespace Modules\TaxModule\Http\Controllers\Tenant\Admin;

use App\Helpers\FlashMsg;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CountryManage\Entities\Country;
use Modules\TaxModule\Entities\CountryTax;
use Modules\TaxModule\Http\Requests\StoreCountryTaxRequest;
use Modules\TaxModule\Http\Requests\UpdateCountryTaxRequest;

class CountryTaxController extends Controller
{
    private const BASE_PATH = 'taxmodule::tenant.admin.';

    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:country-tax-list|country-tax-create|country-tax-edit|country-tax-delete', ['only', ['index']]);
        $this->middleware('permission:country-tax-create', ['only', ['store']]);
        $this->middleware('permission:country-tax-edit', ['only', ['update']]);
        $this->middleware('permission:country-tax-delete', ['only', ['destroy', 'bulk_action']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $all_countries = Country::all();
        $all_country_tax = CountryTax::with('country')->get();
        return view(self::BASE_PATH.'country-tax', compact('all_country_tax', 'all_countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCountryTaxRequest $request
     * @return RedirectResponse
     */
    public function store(StoreCountryTaxRequest $request)
    {
        $country_tax = CountryTax::create($request->validated());

        return $country_tax->id
            ? back()->with(FlashMsg::create_succeed('Country Tax'))
            : back()->with(FlashMsg::create_failed('Country Tax'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreCountryTaxRequest $request
     * @return RedirectResponse
     */
    public function update(UpdateCountryTaxRequest $request)
    {
        $updated = CountryTax::findOrFail($request->id)->update($request->validated());

        return $updated
            ? back()->with(FlashMsg::update_succeed('Country Tax'))
            : back()->with(FlashMsg::update_failed('Country Tax'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CountryTax $item
     * @return RedirectResponse
     */
    public function destroy(CountryTax $item)
    {
        return $item->delete()
            ? back()->with(FlashMsg::delete_succeed('Country Tax'))
            : back()->with(FlashMsg::delete_failed('Country Tax'));
    }

    public function bulk_action(Request $request)
    {
        $deleted = CountryTax::where('id', $request->ids)->delete();
        if ($deleted) {
            return 'ok';
        }
    }
}
