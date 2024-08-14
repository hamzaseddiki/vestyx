<?php

namespace Modules\Attributes\Http\Controllers;

use App\Facades\GlobalLanguage;
use App\Helpers\FlashMsg;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Attributes\Entities\DeliveryOption;
use Modules\Attributes\Entities\Size;

class DeliveryOptionController extends Controller
{
    CONST BASE_PATH = 'attributes::backend.delivery-option.';

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request): Renderable
    {
        $delivery_manages = DeliveryOption::all();
        $default_lang = $request->lang ?? GlobalLanguage::default_slug();
        return view(self::BASE_PATH.'index', compact('delivery_manages','default_lang'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:191|unique:delivery_options',
            'sub_title' => 'required|string|max:191',
            'icon' => 'required|string|max:191'
        ]);

        $product_size = new DeliveryOption();
        $product_size->setTranslation('title',$request->lang,$request->title);
        $product_size->setTranslation('sub_title',$request->lang,$request->sub_title);
        $product_size->icon = $request->icon;
        $product_size->save();

        return $product_size
            ? back()->with(FlashMsg::create_succeed('Delivery Option'))
            : back()->with(FlashMsg::create_failed('Delivery Option'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|unique:delivery_options,id,'.$request->id,
            'sub_title' => 'required|string|max:191',
            'icon' => 'required|string|max:191'
        ]);

        $product_size = DeliveryOption::findOrFail($request->id);
        $product_size->setTranslation('title',$request->lang,$request->title);
        $product_size->setTranslation('sub_title',$request->lang,$request->sub_title);
        $product_size->icon = $request->icon;
        $product_size->save();

        return $product_size
            ? back()->with(FlashMsg::update_succeed('Delivery Option'))
            : back()->with(FlashMsg::update_failed('Delivery Option'));
    }

    /**
     * Remove the specified resource from storage.
     * @param DeliveryOption $item
     * @return RedirectResponse
     */
    public function destroy(DeliveryOption $item): RedirectResponse
    {
        return $item->delete()
            ? back()->with(FlashMsg::delete_succeed('Delivery Option'))
            : back()->with(FlashMsg::delete_failed('Delivery Option'));
    }

    /**
     * Remove all the specified resources from storage.
     * @param Request $request
     * @return boolean
     */
    public function bulk_action(Request $request): bool
    {
        $units = DeliveryOption::whereIn('id', $request->ids)->get();
        foreach ($units as $unit) {
            $unit->delete();
        }
        return true;
    }

    /**
     * Display a listing of the soft deleted resource.
     * @return Renderable
     */
    public function trash_all(): Renderable
    {
        $delivery_manages = DeliveryOption::onlyTrashed()->get();
        return view(self::BASE_PATH.'trash', compact('delivery_manages'));
    }

    public function trash_restore($id)
    {
        $restore = DeliveryOption::withTrashed()->findOrFail($id)->restore();

        return $restore
            ? back()->with(FlashMsg::restore_succeed('Delivery Option'))
            : back()->with(FlashMsg::restore_failed('Delivery Option'));
    }

    public function trash_delete($id)
    {
        $delete = DeliveryOption::withTrashed()->findOrFail($id)->forceDelete();

        return $delete
            ? back()->with(FlashMsg::delete_succeed('Delivery Option'))
            : back()->with(FlashMsg::delete_failed('Delivery Option'));
    }

    /**
     * Remove all the specified resources from storage.
     * @param Request $request
     * @return boolean
     */
    public function trash_bulk_action(Request $request): bool
    {
        $units = DeliveryOption::withTrashed()->whereIn('id', $request->ids)->forceDelete();
        return true;
    }
}
