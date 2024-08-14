{{--
    # Params/Variables
        $key
        $colors
        $sizes
        $selected_color
        $selected_size
        $isFirst
        $allAvailableAttributes
        $inventoryDetail
--}}
@php
    if(!isset($detail)){
        $detail = null;
    }
@endphp

<div class="inventory_item shadow-sm rounded" @if(isset($key)) data-id="{{ $key }}" @endif>
    @if(isset($inventoryDetail) && !is_null($inventoryDetail))
        <input type="hidden" name="inventory_details_id[]" value="{{ $inventoryDetail->id }}">
    @endif
    <div class="row">
        <div class="col">
            <div class="form-row  row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-3 row-cols-xxl-6">
                <div class="col">
                    <div class="form-group">
                        <label for="item_size">{{ __('Item Size') }}</label>
                        <select name="item_size[]" id="item_size" class="form-control">
                            <option >{{ __('Select Size') }}</option>
                            @foreach($sizes as $size)
                                <option value="{{ $size->id }}"
                                        @if(isset($detail) && $detail->size == $size->id) selected @endif>{{ $size->getTranslation('name',default_lang()) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="item_color">{{ __('Item Color') }}</label>
                        <select name="item_color[]" id="item_color" class="form-control">
                            <option >{{ __('Select Color') }}</option>
                            @foreach($colors as $color)
                                <option value="{{ $color->id }}"
                                        @if(isset($detail) && $detail->color == $color->id) selected @endif>{{ $color->getTranslation('name',default_lang()) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="item_additional_price">{{ __('Additional Price') }}</label>
                        <input type="number" step="0.01" name="item_additional_price[]" id="item_additional_price"
                               class="form-control form--control" min="0" placeholder="{{ __('Additional price') }}"
                               value="{{ $detail?->additional_price ?? '' }}"
                        >
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="item_stock_count">{{ __('Extra cost') }} </label>
                        <input type="number" name="item_extra_cost[]" id="item_stock_count" class="form-control"
                               min="0" placeholder="{{ __('Extra cost') }}"
                               value="{{ $detail?->add_cost ?? '' }}"
                        >
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="item_stock_count">{{ __('Stock Count') }} <i class="las la-star required-filed"></i></label>
                        <input type="number" name="item_stock_count[]" id="item_stock_count" class="form-control"
                               min="0" placeholder="{{ __('Stock Count') }}"
                               value="{{ $detail->stock_count ?? '' }}"
                        >
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        @php
                            $image = isset($detail?->attr_image) ? $detail?->attr_image ?? '' : '';
                        @endphp
                        <x-fields.media-upload :id="$image->id ?? ''"
                                               :title="__('Attribute Image')"
                                               name="item_image[]"
                                               dimentions="1280x1280"/>
                    </div>
                </div>
            </div>
            <div class="item_selected_attributes">
                @if(isset($detail) && !is_null($detail) && !is_null($detail->attribute))
                    @foreach($detail->attribute as $attribute)
                        <div class="form-row row row-cols-1 row-cols-sm-2 row-cols-lg-3">
                            <div class="col">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="item_attribute_name[{{ $key }}][]" value="{{ $attribute->attribute_name }}" readonly />
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="item_attribute_value[{{ $key }}][]" value="{{ $attribute->attribute_value }}" readonly />
                                </div>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-danger remove_details_attribute" data-id="{{ $attribute->id }}"> x </button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="form-row row row-cols-1 row-cols-sm-2 row-cols-lg-3">
                <div class="col">
                    <div class="form-group">
                        <label>{{ __('Attribute Name') }}</label>
                        <select name="item_attribute_name[]" class="form-control item_attribute_name">
                            <option value="">{{ __('Select Attribute') }}</option>
                            @foreach ($allAvailableAttributes as $name => $attribute)
                                <option value="{{ $attribute->id }}"
                                        data-terms="{{ purify_html($attribute->terms) }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>{{ __('Attribute Value') }}</label>
                        <select name="item_attribute_value[]" class="form-control item_attribute_value">
                            <option value="">{{ __('Select attribute value') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-auto text-center">
                    <button type="button" class="btn btn-success margin-top-30 add_item_attribute">
                        <i class="las la-arrow-up"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-auto">
            <div class="item_repeater_add_remove">
                <div class="repeater_button">
                    <button type="button" class="btn btn-success btn-xs add"><i class="las la-plus"></i></button>
                </div>
                @if(!isset($isFirst) || !$isFirst)
                    <div class="repeater_button mt-2">
                        <button type="button" class="btn btn-danger btn-xs remove"> <i class="las la-trash-alt"></i> </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- NOT NEEDED - ONLY KEPT FOR REFERENCE --}}
@if(isset($not_needed))
<div class="variant_variant_info_repeater">
    <div class="form-row">
        <div class="col">
            <div class="form-group">
                <label for="variant_color">{{ __('Color') }}</label>
                @isset($variantId)
                    <input type="hidden" class="variant_id" name="variant_id[]" value="{{ $variantId }}">
                @endisset
                <select class="form-control" name="variant_color[]" id="variant_color">
                    <option value="">{{ __('Select Color') }}</option>
                    @foreach($colors as $color)
                        <option value="{{ $color->id }}"
                                @if(isset($selectedColor) && $selectedColor->id == $color->id) selected @endif>{{ $color->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label for="variant_size">{{ __('Size') }}</label>
                <select class="form-control" name="variant_size[]" id="variant_size">
                    <option value="">{{ __('Select Size') }}</option>
                    @foreach($sizes as $size)
                        <option value="{{ $size->id }}"
                                @if(isset($selectedSize) && $selectedSize->id == $size->id) selected @endif>{{ $size->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label for="variant_stock_count">{{ __('Quantity') }}</label>
                <input type="number" name="variant_stock_count[]" id="variant_stock_count" class="form-control"
                       step="0.01" @if(isset($quantity)) value="{{ $quantity }}" @endif>
            </div>
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-sm btn-success add_variant_info_btn"> <i class="las la-plus"></i></button>
           @if($loop != 1)
                <button type="button"
                        class="btn btn-sm btn-danger remove_this_variant_info_btn @if(isset($variantId)) remove_variant @endif"
                        @if(isset($isFirst) && $isFirst) readonly @endif > <i class="las la-trash-alt"></i>
                </button>
           @endif
        </div>
    </div>
</div>
@endif
