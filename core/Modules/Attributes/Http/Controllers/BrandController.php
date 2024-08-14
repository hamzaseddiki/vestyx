<?php

namespace Modules\Attributes\Http\Controllers;

use App\Facades\GlobalLanguage;
use App\Helpers\FlashMsg;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Attributes\Entities\Brand;
use Modules\Attributes\Entities\ProductBrand;
use Modules\Attributes\Entities\Size;
use Modules\Attributes\Http\Requests\BrandStoreRequest;

class BrandController extends Controller
{
    CONST BASE_PATH = 'attributes::backend.brand.';
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request): Renderable
    {
        $delivery_manages = ProductBrand::all();
        $default_lang = $request->lang ?? GlobalLanguage::default_slug();
        return view(self::BASE_PATH.'index', compact('delivery_manages','default_lang'));
    }

    public function store(BrandStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $slug = create_slug($data['slug'] ?? $data['name'], 'ProductBrand', true, 'Attributes');
        $data['slug'] = $slug;

        $data['banner_id'] = $data['banner_id'] ?? $data['image_id'];
        $data['url'] = $data['url'] == null ? '#' : $data['url'];

        $product_brand = new ProductBrand();
        $product_brand->setTranslation('name',$request->lang,$request->name);
        $product_brand->setTranslation('title',$request->lang,$request->title);
        $product_brand->setTranslation('description',$request->lang,$request->description);
        $product_brand->banner_id = $data['banner_id'];
        $product_brand->image_id = $data['image_id'];
        $product_brand->url = $data['url'];
        $product_brand->slug = $data['slug'];
        $product_brand->save();

        return $product_brand
            ? back()->with(FlashMsg::create_succeed('Brand'))
            : back()->with(FlashMsg::create_failed('Brand'));
    }

    public function update(BrandStoreRequest $request): RedirectResponse
    {
        $product_brand = ProductBrand::findOrFail($request->id);
        $data = $request->validated();

        if ($product_brand->slug != $request->slug)
        {
            $slug = create_slug($data['slug'] ?? $data['name'], 'ProductBrand', true, 'Attributes');
            $data['slug'] = $slug;
        }

        $data['url'] = $data['url'] == null ? '#' : $data['url'];

        $product_brand->setTranslation('name',$request->lang,$request->name);
        $product_brand->setTranslation('title',$request->lang,$request->title);
        $product_brand->setTranslation('description',$request->lang,$request->description);
        $product_brand->banner_id = $data['banner_id'];
        $product_brand->url = $data['url'];
        $product_brand->slug = $data['slug'];
        $product_brand->save();

        return $product_brand
            ? back()->with(FlashMsg::update_succeed('Brand'))
            : back()->with(FlashMsg::update_failed('Brand'));
    }

    /**
     * Remove the specified resource from storage.
     * @param Brand $item
     * @return RedirectResponse
     */
    public function destroy(ProductBrand $item): RedirectResponse
    {
        return $item->delete()
            ? back()->with(FlashMsg::delete_succeed('Brand'))
            : back()->with(FlashMsg::delete_failed('Brand'));
    }

    /**
     * Remove all the specified resources from storage.
     * @param Request $request
     * @return boolean
     */
    public function bulk_action(Request $request): bool
    {
        $units = ProductBrand::whereIn('id', $request->ids)->get();
        foreach ($units as $unit) {
            $unit->delete();
        }
        return true;
    }
}
