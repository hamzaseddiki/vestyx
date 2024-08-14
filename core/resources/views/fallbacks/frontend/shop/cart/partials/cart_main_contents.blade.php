
<div class="wishList">
    <div class="container">
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <table class="table-wishList custom--table table-border radius-10">
                    <thead>
                    <tr>
                        <th> {{__('Product')}} </th>
                        <th> {{__('Price')}} </th>
                        <th> {{__('Quantity')}} </th>
                        <th> {{__('Subtotal')}} </th>
                        <th> {{__('Action')}} </th>
                    </tr>
                    </thead>
                    <tbody class="tableBody" id="cart_tbody">
                      @foreach($cart_data as $key => $data)
                        <tr data-product-id="{{$key}}" data-varinat-id="{{$data->variant_id}}">
                            <td class="productWrap d-flex align-items-center">
                                {!! render_image_markup_by_attachment_id($data?->options?->image) !!}
                                <div class="cartCap">
                                    <h5>{{$data->name}}</h5>
                                    <span class="name-subtitle d-block mt-2">
                                        @if($data?->options?->color_name)
                                            {{__('Color:')}} {{$data?->options?->color_name}},
                                        @endif

                                        @if($data?->options?->size_name)
                                            {{__('Size:')}} {{$data?->options?->size_name}}
                                        @endif

                                        @if($data?->options?->attributes)
                                            <br>
                                            @foreach($data?->options?->attributes as $key => $attribute)
                                                {{$key.':'}} {{$attribute}}{{!$loop->last ? ',' : ''}}
                                            @endforeach
                                        @endif
                                     </span>
                                </div>
                            </td>
                            <td>{{amount_with_currency_symbol($data->price)}}</td>
                            <td class="ff-jost" data-label="Quantity">
                                <div class="countWrap">
                                    <div class="numberCount">

                                        @if(!$wishlist)
                                           <div class="value-button minus substract">
                                               <i class="las la-minus"></i>
                                           </div>
                                        @endif
                                          <input type="number" class="qty_ quantity-input" {{ $wishlist ? "disabled='true' readonly='true'" : "" }} value="{{$data->qty}}">
                                        @if(!$wishlist)
                                           <div class="value-button plus">
                                               <i class="las la-plus"></i>
                                           </div>
                                        @endif

                                    </div>
                                </div>
                            </td>
                            @php
                                $subtotal = $data->price * $data->qty;
                            @endphp
                            <td class="price-td" data-label="Subtotal">{{amount_with_currency_symbol($subtotal)}}</td>

                            <td class="ff-jost {{ $wishlist ? "d-flex justify-content-around align-items-center" : "" }}" data-label="Close" data-product_hash_id="{{$data->rowId}}">

                                @if($wishlist)
                                    <div class="move-to-wishlist">
                                        <i class="las la-cart-arrow-down align-items-center"></i>
                                    </div>
                                @endif

                                <div class="close-table-{{ $wishlist ? "wishlist" : "cart" }}">
                                    <i class="las la-trash-alt icon"></i>
                                </div>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="col-lg-12">
                <div class="d-flex justify-content-between flex-wrap mt-40">
                    <div class="btn-wrapper mb-10">
                        @if(!$wishlist)
                            <a href="javascript:void(0)" class="cmn-btn-outline2 mr-15 clear-cart-btn"> {{__('Clear Cart')}} </a>
                        @endif
                        <a href="{{url('shop')}}" class="cmn-btn-outline2 ">{{__('Continue Shopping')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





