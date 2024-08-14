@php
    $end_date = !empty($data["campaign"]->end_date) ? $data["campaign"]->end_date : '';
    $button_text = $data['button_text'];
@endphp

<section class="productCampaign">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row mb-40">
            <div class="col-xl-6 col-lg-12 col-md-10 col-sm-10">
                <div class="section-tittle mb-0">
                    <h2 class="title">{{$data['title']}} </h2>
                </div>
            </div>
            <div class="col-xl-6 col-lg-12 col-md-10 col-sm-10">
                <div class="d-flex align-items-center flex-wrap float-xl-end">
                    <div class="small-tittle mb-10 mr-10">
                        <h2 class="tittle">{{$data['campaign_right_title']}}</h2>
                    </div>
                    <!-- Timer -->
                    <div class="dateTimmerGlobal wow fadeInRight flash-countdown" data-date="{{ $end_date }}"  data-wow-delay="0.0s">
                        <div class="donation_countdown">
                            <div class="single">
                                <div class="cap">
                                    <span class="time counter-days"></span>
                                    <p class="cap">{{__('Days')}}</p>
                                </div>
                            </div>
                            <div class="single">
                                <span class="time counter-hours"></span>
                                <p class="cap">{{__('Hours')}}</p>
                            </div>
                            <div class="single">
                                <span class="time counter-minutes"></span>
                                <p class="cap">{{__('Mins')}}</p>
                            </div>
                            <div class="single">
                                <span class="time counter-seconds"></span>
                                <p class="cap">{{__('Secs')}}</p>
                            </div>
                        </div>
                    </div>
                    <!-- /E n d -->
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($data["products"] as $pro)
                @php
                    $product = optional($pro->product);
                @endphp
                @php
                    $data = get_product_dynamic_price($product);
                    $campaign_name = $data['campaign_name'];
                    $regular_price = $data['regular_price'];
                    $sale_price = $data['sale_price'];
                    $discount = $data['discount'];

                    $url = !empty($product->slug) ? route('tenant.shop.product.details',$product->slug) : '';
                    $image = render_image_markup_by_attachment_id($product->image_id)
                @endphp
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                    <div class="singleProduct text-center mb-24">
                        <div class="productImg imgEffect2">

                            <a href="{{$url}}">
                                {!! $image !!}
                            </a>

                            <div class="sticky-wrap">
                                @if(!empty($product->badge))
                                    <span class="sticky stickyStye ">{{$product?->badge?->name}}</span>
                                @endif

                                @if($product->inventory?->stock_count < 1)
                                    <span class="sticky stickyStye outStock">{{__('Out of stock')}}</span>
                                @endif
                            </div>
                            @include('product::frontend.option-feature')
                        </div>
                        <div class="productCap">
                            <h5><a href="{{ !empty($product->slug) ?route('tenant.shop.product.details',$product->slug) : ''}}" class="title">{{ $product->name }}</a></h5>
                            {!! render_product_star_rating_markup_with_count($product) !!}

                            @if($product->inventory?->stock_count > 0)
                                <span class="quintity avilable">{{__('In Stock')}} <span class="quintityNumber">({{$product->inventory?->stock_count}})</span> </span>
                            @else
                                <span class="quintity text-danger">{{__('Stock Out')}} <span class="quintityNumber">({{$product->inventory?->stock_count}})</span> </span>
                            @endif
                            <div class="productPrice">
                                <strong class="regularPrice">{{$regular_price != null ? amount_with_currency_symbol( $regular_price) : ''}}</strong>
                                <span class="offerPrice">{{amount_with_currency_symbol($sale_price)}}</span>
                            </div>
                            <div class="btn-wrapper">
                                @if(count($product->inventoryDetail ?? []) > 0 )
                                    <a href="{{route('tenant.shop.product.details',$product->slug)}}" class="cmn-btn-outline3 w-100">{{__('View')}}</a>
                                @else
                                    <a href="#!" data-product_id="{{ $product->id }}" class="cmn-btn-outline3 w-100 add-to-buy-now-btn">{{$button_text}}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>


