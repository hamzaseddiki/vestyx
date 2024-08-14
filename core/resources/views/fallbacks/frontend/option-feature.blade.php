<ul class="cart-icon">
    @if($product->inventory_detail_count < 1)
        <li class="lisItem" title="{{__('Add to Cart')}}">
            <a class="icon add-to-cart-btn " data-product_id="{{ $product->id }}" href="javascript:void(0)">
                <i class="flaticon-shopping-cart icon"></i>
            </a>
        </li>


        <li class="lisItem" title="{{__('Add to Wishlist')}}">
            <a class="add-to-wishlist-btn" data-product_id="{{ $product->id }}" href="javascript:void(0)">
                <i class="flaticon-like icon"></i>
            </a>
        </li>

        <li class="lisItem" title="{{__('Add to Compare')}}">
            <a class="cart-loading compare-btn" data-product_id="{{ $product->id }}" href="javascript:void(0)">
                <i class="flaticon-compare icon"></i>
            </a>
        </li>

    @else
        <li class="lisItem" title="{{__('Add to Cart')}}">
            <a class="icon cart-loading product-quick-view-ajax" href="javascript:void(0)" data-action-route="{{ route('tenant.products.single-quick-view', $product->slug) }}">
                <i class="flaticon-shopping-cart icon"></i>
            </a>
        </li>

        <li class="lisItem wishlist-btn" title="{{__('Add to Wishlist')}}">
            <a class="cart-loading product-quick-view-ajax" href="javascript:void(0)" data-action-route="{{ route('tenant.products.single-quick-view', $product->slug) }}">
                <i class="flaticon-like icon"></i>
            </a>
        </li>

        <li class="lisItem" data-bs-toggle="tooltip" data-bs-placement="top"
            title="{{__('Add to Compare')}}">
            <a class="cart-loading product-quick-view-ajax" href="javascript:void(0)" data-action-route="{{ route('tenant.products.single-quick-view', $product->slug) }}">
                <i class="flaticon-compare icon"></i>
            </a>
        </li>
    @endif


    @php
        $image_array = array();
        $img = get_attachment_image_by_id($product->image_id);
        array_push($image_array, $img['img_url'] ?? []);


        if (!empty($product->gallery_images) && count($product->gallery_images) > 0) {
            foreach ($product->gallery_images ?? [] as $image)
                {
                    $img = get_attachment_image_by_id($image->id);
                    array_push($image_array, $img['img_url']);
                }
        }
    @endphp
    <li class="lisItem">
        <a class="icon cart-loading product-quick-view-ajax" href="javascript:void(0)" data-id="{{$product->id}}" data-action-route="{{ !empty($product->slug) ? route('tenant.products.single-quick-view', $product->slug) : '' }}">
            <i class="las la-eye"></i></a>
    </li>
</ul>
