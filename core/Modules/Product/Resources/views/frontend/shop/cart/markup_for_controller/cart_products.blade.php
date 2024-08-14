@foreach($cart_data as $key => $data)
    <tr class="table-cart-row" data-product-id="{{$key}}" data-varinat-id="{{$data->variant_id}}">
        <td class="ff-jost" data-label="Product">
            <div class="product-name-table productWrap d-flex align-items-center" >
                {!! render_image_markup_by_attachment_id($data?->options?->image) !!}
                <div class="carts-contents cartCap">
                    <h5>{{$data->name}}</h5>
                    <span class="name-subtitle d-block mt-2">
                    @if($data?->options?->attributes)
                            {{__('Color:')}} {{$data?->options?->color_name}}, {{__('Size:')}} {{$data?->options?->size_name}}
                            <br>
                            @foreach($data?->options?->attributes as $key => $attribute)
                                {{$key.':'}} {{$attribute}}{{!$loop->last ? ',' : ''}}
                            @endforeach
                        @endif
                    </span >
                </div>
            </div>
        </td>
        <td class="price-td" data-label="Price"> {{amount_with_currency_symbol($data->price)}} </td>
        <td class="ff-jost" data-label="Quantity">
            <div class="product-quantity">
                <div class="countWrap">
                    <div class="numberCount">
                 <span class="value-button minus substract">
                        <i class="las la-minus"></i>
                  </span>

                <input class="quantity-input" type="number" value="{{$data->qty}}">

                <span class="value-button plus">
                    <i class="las la-plus"></i>
                 </span>

            </div>
            </div>
            </div>
        </td>
        @php
            $subtotal = $data->price * $data->qty;
        @endphp
        <td class="price-td" data-label="Subtotal"> {{amount_with_currency_symbol($subtotal)}} </td>
        <td class="ff-jost" data-label="Close" data-product_hash_id="{{$data->rowId}}">
            <div class="close-table-cart">
                <i class="las la-trash-alt icon"></i>
            </div>
        </td>
    </tr>
@endforeach
