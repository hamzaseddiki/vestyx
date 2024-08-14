<?php

namespace App\Http\Services;

class CustomPaginationService
{
    public static function pagination_type($all_products, $count, $type = "custom", $route=null){
        $display_item_count = $count ?? 10;
        $all_products = $all_products->paginate($display_item_count);
        // check route is present or not

        if(!empty($route)){
            $all_products->withPath($route);
        }

        if($type == "custom"){
            $current_items = (($all_products->currentPage() - 1) * $display_item_count);
            return [
                "items" => $all_products->items(),
                "current_page" => $all_products->currentPage(),
                "total_items" => $all_products->total(),
                "total_page" => $all_products->lastPage(),
                "next_page" => $all_products->nextPageUrl(),
                "previous_page" => $all_products->previousPageUrl(),
                "last_page" => $all_products->lastPage(),
                "per_page" => $all_products->perPage(),
                "path" => $all_products->path(),
                "current_list" => $all_products->count(),
                "from" => $all_products->count() ? $current_items + 1 : 0,
                "to" => $current_items + $all_products->count(),
                "on_first_page" => $all_products->onFirstPage(),
                "hasMorePages" => $all_products->hasMorePages(),
                "links" => $all_products->getUrlRange(0,$all_products->lastPage())
            ];
        }else{
            return $all_products;
        }
    }
}
