
@php
    $button_text = $data['button_text'];

@endphp

<div class="allProduct">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-7 col-md-10 col-sm-10">
                <div class="section-tittle text-center mb-50">
                    <h2 class="title">{{$data['title']}}</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Left Content -->
            <div class="col-xxl-3  col-xl-3 col-lg-4 col-md-5">
                <div class="cateSidebar mb-40">
                    <!-- All Categories -->
                    <ul class="listing listScroll category_listing">
                        <li class="listItem">
                            <a href="#!" class="items list_item_all_product">
                                <i class="flaticon-product icon"></i> {{__('All Product')}}
                            </a>
                        </li>
                        @foreach($data['all_categories'] as $category)
                            <li class="listItem item" data-slug="{{$category->slug}}" data-limit="{{$data['product_limit']}}">
                                <a href="#!" class="items category_item" >
                                    <i class="flaticon-product icon"></i> {{$category->name}}
                                </a>
                            </li>
                        @endforeach

                    </ul>
                </div>

                @php
                    $deal_product = $data['deal_product'] ?? [];

                @endphp

                <div class="singleCart singleCartTwo singleCartTwoSmoll singleCartSidebar mb-24 bgColorFive tilt-effect wow fadeInUp" data-wow-delay="0.0s">
                    <div class="itemsCaption">
                        <p class="itemsCap">{{$data['deal_title']}}</p>
                        <h5><a href="#!" class="itemsTittle">{{!empty($deal_product) ? $deal_product->name : ''}}</a></h5>
                        <span class="price">{{ !empty($deal_product) ? amount_with_currency_symbol($deal_product->sale_price) : 0 }}</span>
                        <div class="btn-wrapper">
                            <a href="#!" class="cmn-btn4" tabindex="0">{{__('Buy Now')}}</a>
                        </div>
                    </div>
                    <div class="itemsImg wow fadeInUp" data-wow-delay="0.0s">
                        {!! !empty($deal_product) ? render_image_markup_by_attachment_id($deal_product->image_id)  : '' !!}
                    </div>
                </div>

            </div>
            <!-- Right Content -->
            <div class="col-xxl-9 col-xl-9 col-lg-8 col-md-7">
                <div class="row all_product_container">
                    @foreach($data['products'] as $product)
                        @php
                            $data = get_product_dynamic_price($product);
                            $campaign_name = $data['campaign_name'];
                            $regular_price = $data['regular_price'];
                            $sale_price = $data['sale_price'];
                            $discount = $data['discount'];
                        @endphp
                    <div class="col-xl-4">
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
                                        <strong class="regularPrice">{{amount_with_currency_symbol($sale_price)}}</strong>
                                        <span class="offerPrice">{{$regular_price != null ? amount_with_currency_symbol( $regular_price) : ''}}</span>
                                    </div>
                                    <div class="btn-wrapper mb-15">
                                        @if(count($product->inventoryDetail) > 0 )
                                            <a href="{{route('tenant.shop.product.details',$product->slug)}}" class="cmn-btn-outline3 w-100">{{__('View')}}</a>
                                        @else
                                            <a href="#!" data-product_id="{{ $product->id }}" class="cmn-btn-outline3 w-100 add-to-buy-now-btn">{{$button_text}}</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script>
        $(function (){
            $(document).on('click', '.category_listing .item', function (e){
                e.preventDefault();

                let el = $(this);
                let slug = el.data('slug');
                let limit = el.data('limit');

                    $.ajax({
                        type: 'GET',
                        url: "{{route('tenant.category.wise.product')}}",
                        data: {
                            category : slug,
                            limit : limit,
                        },
                        beforeSend: function (){
                           CustomLoader.start()
                        },
                        success: function (data){

                            $('.all_product_container').html(data.markup);

                            CustomLoader.end();
                        },
                        error: function (data){
                            console.log('error')
                        }
                    });

            });


            $(document).on('click','.list_item_all_product',function(e){
                    e.preventDefault();

                    CustomLoader.start();
                       $('.all_product_container').load(location.href + " .all_product_container");

                    setInterval(() => {
                        CustomLoader.end();
                    }, 300);
            });
        });
    </script>
@endsection
