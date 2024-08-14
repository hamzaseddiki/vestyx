<?php

namespace Modules\Product\Http\Controllers;

use App\Facades\GlobalLanguage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Attributes\Entities\Category;
use Modules\Attributes\Entities\ChildCategory;
use Modules\Attributes\Entities\SubCategory;

class CategoryController extends Controller
{
    public function getCategory(Request $request): JsonResponse
    {
        // need to fetch all categories and return as laravel eloquent object
        $categories = Category::all();
        $default_lang = $request->lang ?? GlobalLanguage::default_slug();
        $options = view("product::category.option", compact("categories","default_lang"));
        return response()->json(["success" => true,"html" => $options]);
    }

    public function getSubCategory(Request $req)
    {
        // fetch sub category from category
        $categories = SubCategory::where("category_id" , $req->category_id)->get();

        // load view file for select option
        $default_lang = $req->lang ?? GlobalLanguage::default_slug();
        $options = view("product::category.option", compact("categories","default_lang"))->render();
        return response()->json(["success" => true,"html" => $options]);
    }

    public function getChildCategory(Request $req)
    {
        // fetch sub category from category
        $categories = ChildCategory::where("sub_category_id" , $req->sub_category_id)->get();
        // load view file for select option
        $default_lang = $req->lang ?? GlobalLanguage::default_slug();
        $options = view("product::category.option", compact("categories","default_lang"))->render();
        return response()->json(["success" => true,"html" => $options]);
    }
}
