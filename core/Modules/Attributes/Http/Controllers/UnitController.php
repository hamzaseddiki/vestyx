<?php

namespace Modules\Attributes\Http\Controllers;

use App\Helpers\FlashMsg;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Modules\Attributes\Entities\Unit;

class UnitController extends Controller
{
    private const BASE_PATH = 'attributes::backend.';
    public function __construct()
    {
        $this->middleware('auth:admin');

        $this->middleware('permission:product-unit-list|product-unit-create|product-unit-edit|product-unit-delete', ['only', ['index']]);
        $this->middleware('permission:product-unit-create', ['only', ['store']]);
        $this->middleware('permission:product-unit-edit', ['only', ['update']]);
        $this->middleware('permission:product-unit-delete', ['only', ['destroy', 'bulk_action']]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(): Renderable
    {
        $product_units = Unit::all();
        return view(self::BASE_PATH.'unit.index', compact('product_units'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:191|unique:units'
        ]);

        $unit = Unit::create(['name' => $request->name]);
        return $unit
            ? back()->with(FlashMsg::create_succeed('Product Unit'))
            : back()->with(FlashMsg::create_failed('Product Unit'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required','string','max:191', Rule::unique('units')->ignore($request->id)]
        ]);

        $unit = Unit::findOrFail($request->id)->update([
            'name' => $request->name
        ]);

        return $unit
            ? back()->with(FlashMsg::update_succeed('Product Unit'))
            : back()->with(FlashMsg::update_failed('Product Unit'));
    }

    /**
     * Remove the specified resource from storage.
     * @param Unit $item
     * @return RedirectResponse
     */
    public function destroy(Unit $item): RedirectResponse
    {
        return $item->delete()
            ? back()->with(FlashMsg::delete_succeed('Product Unit'))
            : back()->with(FlashMsg::delete_failed('Product Unit'));
    }

    /**
     * Remove all the specified resources from storage.
     * @param Request $request
     * @return boolean
     */
    public function bulk_action(Request $request): bool
    {
        $units = Unit::whereIn('id', $request->ids)->get();
        foreach ($units as $unit) {
            $unit->delete();
        }
        return true;
    }
}
