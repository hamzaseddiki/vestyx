<section class="single-page-area section-padding">
    <div class="container container-one">
        <div class="single-page-wrapper center-text">
            <div class="single-page text-center">
                <div class="single-page-thumb ecommerce_cart_empty">
                    <img src="{{global_asset('assets/img/single-product/cart_blank.png')}}" alt="">
                </div>
                @php

                    $text = request()->route()->getName() == 'tenant.shop.wishlist.page' ? __('No Products in your Wishlist!') : __('No Products in your Cart!');
                @endphp
                <div class="single-page-contents mt-4 mt-lg-5">
                    <h2 class="single-page-contents-title fw-600"> {{$text}} </h2>
                    <div class="btn-wrapper">
                        <a href="{{url('/')}}" class="cmn-btn cmn-btn-bg-2 radius-0 mt-4"> {{__('Back to Home')}} </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
