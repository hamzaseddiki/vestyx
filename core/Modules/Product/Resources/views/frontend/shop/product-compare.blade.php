@extends(route_prefix().'frontend.frontend-page-master')

@section('title')
    {!! __('Compare Product') !!}
@endsection

@section('page-title')
    {!! __('Compare Product') !!}
@endsection

@section('content')
<div class="compareList section-padding ">
    <div class="container">
        <div class="row">
            <section class="compare-area">
                <div class="container container-one">
                    <div class="row">
                        @forelse(\Gloudemans\Shoppingcart\Facades\Cart::instance("compare")->content() as $product)
                            @php
                                $data = get_product_dynamic_price($product);
                                $campaign_name = $data['campaign_name'];
                                $regular_price = $data['regular_price'];
                                $sale_price = $data['sale_price'];
                                $discount = $data['discount'];
                                $product_slug = \Modules\Product\Entities\Product::find($product->id);
                                $product_slug = $product_slug->slug;
                            @endphp

                            <div class="col-lg-4 col-md-6 col-xl-3">
                                <div class="singleProduct">
                                    <div class="productImg imgEffect2">
                                        <a href="{{route('tenant.shop.product.details', $product_slug)}}">
                                            {!! render_image_markup_by_attachment_id($product->options->image, '', 'grid') !!}
                                        </a>
                                    </div>
                                    <div class="productCap">
                                        <h5>
                                            <a href="{{route('tenant.shop.product.details', $product_slug)}}"> {{$product->getTranslation('name',get_user_lang())}} </a>
                                        </h5>
                                        <div class="productPrice">
                                            <strong class="regularPrice">{{amount_with_currency_symbol($product->price)}}</strong>
                                        </div>


                                        @if(!empty($product?->options['description']))
                                            <h5> {{__('SKU:')}} <strong> {{$product?->options?->sku}}</strong> </h5>
                                        @endif

                                        <ul class="single-compare-contents-list">
                                            @if(!empty($product?->options['description']))
                                                <li class="single-compare-contents-list-item"> <strong>{{__('Description:')}}</strong>
                                                    {!! $product?->options['description'] !!}
                                                </li>
                                            @endif

                                            @if(!empty($product->options["color_name"] ?? ''))
                                                <li class="single-compare-contents-list-item"> <strong>{{__('Color:')}}</strong>
                                                    <ul class="list_sub_item color-ul">
                                                        <li data-color-code="{{$product->options['color_name']}}">{{$product->options['color_name']}}</li>
                                                    </ul>
                                                </li>
                                            @endif

                                            @if(!empty($product->options["size_name"]))
                                                <li class="single-compare-contents-list-item"> <strong>{{__('Size:')}}</strong>
                                                    <ul class="list_sub_item">
                                                        <li>{{$product->options['size_name']}}</li>
                                                    </ul>
                                                </li>
                                            @endif

                                            @forelse($product->options["attributes"] ?? [] as $key => $value)
                                                <li class="single-compare-contents-list-item"> <strong>{{ $key }}</strong>
                                                    <ul class="list_sub_item">
                                                        <li>{{ $value }}</li>
                                                    </ul>
                                                </li>
                                            @empty

                                            @endforelse
                                        </ul>

                                        <div class="btn-wrapper">
                                            <button type="button" class="browseBtn w-100 remove-btn close-compare compare-remove-btn"   data-product_id="{{$product->rowId}}">
                                                {{__('Remove')}}
                                            </button>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        @empty
                            <div class="col-12 alert alert-warning">
                                <h4 class="text-center">{{__('No Product Available in Compare')}}</h4>
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>

    @include('product::frontend.shop.partials.shop-footer')
@endsection

@section('scripts')
    <script>
        $(function (){
            /* ========================================
                Compare Click Close
            ======================================== */
            $(document).on('click', '.compare-remove-btn', function () {
                let product_id = $(this).data('product_id');

                $.ajax({
                    url: '{{route('tenant.shop.compare.product.remove')}}',
                    type: 'GET',
                    data: {
                        product_id: product_id
                    },
                    beforeSend: function () {
                       CustomLoader.start();
                    },
                    success: (data) => {
                        $('.compare-area').load(location.href + " .compare-area");
                        SohanCustom.load_topbar_cart_nad_wishlist();
                        CustomLoader.end();

                    },
                    error: function (data) {
                        CustomLoader.end();
                    }
                });
            });
        });
    </script>
@endsection
