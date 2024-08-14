
@extends('tenant.frontend.frontend-page-master')

@section('title')
    {!! $campaign->title !!}
@endsection

@section('page-title')
    {!! $campaign->title !!}
@endsection

@section('content')
    <section class="productCampaign section-padding">
        <div class="container" >
            <h3 class=" mb-40">{{__('Campaign')}} : {{ $campaign->title }}</h3>
       <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12">
        <div class="row all_product_container">
            @foreach($products as $pro)
                @php
                    $product = optional($pro->product);
                    $data = get_product_dynamic_price($product);
                    $campaign_name = $data['campaign_name'];
                    $regular_price = $data['regular_price'];
                    $sale_price = $data['sale_price'];
                    $discount = $data['discount'];
                @endphp

                <div class="col-xl-3">
                    <div class="singleProduct mb-24">
                        <div class="productImg imgEffect2">
                            <a href="{{route('tenant.shop.product.details',$product->slug)}}">
                                {!! render_image_markup_by_attachment_id($product->image_id) !!}
                            </a>

                            <div class="sticky-wrap">
                                <!-- sticker -->
                                @if(!empty($product->badge))
                                    <span class="sticky stickyStye ratedStock"><i class="fa-solid fa-medal icon"></i>{{$product?->badge?->getTranslation('name',get_user_lang())}}</span>
                                @endif

                                @if($product->inventory?->stock_count < 1)
                                    <span class="sticky stickyStye outStock">{{__('Out of stock')}}</span>
                                @endif
                            </div>

                            @include('product::frontend.option-feature')

                        </div>

                        <div class="productCap">

                            <h5>
                                <a href="{{route('tenant.shop.product.details', $product->slug)}}" class="title">{{Str::words($product->getTranslation('name',get_user_lang()), 4)}} </a>
                            </h5>

                            {!! render_product_star_rating_markup_with_count($product) !!}

                            @if($product->inventory?->stock_count > 0)
                                <span class="quintity avilable">{{__('In Stock')}} <span class="quintityNumber">({{$product->inventory?->stock_count}})</span> </span>
                            @else
                                <span class="quintity text-danger">{{__('Stock Out')}} <span class="quintityNumber">({{$product->inventory?->stock_count}})</span> </span>
                            @endif


                            <div class="d-flex align-items-center flex-wrap justify-content-between">
                                <div class="productPrice">
                                    {!! product_prices($product, 'color-two') !!}
                                </div>
                                <div class="btn-wrapper mb-15">
                                    <a href="#!" data-product_id="{{ $product->id }}" class="cmn-btn-outline3 add-to-buy-now-btn">{{__('Buy Now')}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    </div>
    </section>
@endsection

