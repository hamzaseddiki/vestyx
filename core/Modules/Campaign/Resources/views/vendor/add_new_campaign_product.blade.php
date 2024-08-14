<div class="card border-primary mb-3" style="border: 1px solid">
    <div class="card-header bg-transparent border-primary campaign-card-header">
        <span>{{ __('Campaign Product') }}</span>
        @if(isset($remove_btn))
            <span class="cross-btn"><i class="las la-times-circle"></i></span>
        @endif
    </div>
    <div class="card-body">
        <div class="form-group select_product">
            <label for="product_id">{{ __('Select Product') }}</label>
            <select name="product_id[]" id="product_id" class="form-control nice-select wide repeater_product_id">
                @foreach ($all_products as $product)
                    <option value="{{ $product->id }}"
                            data-price="{{ $product->price }}"
                            data-sale_price="{{ $product->sale_price }}"
                            data-stock="{{ optional($product->inventory)->stock_count ?? 0 }}"
                    >
                        {{ $product->title }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-row">
            <div class="col">
                <div class="form-group">
                    <label for="original_price">{{ __('Original Price') }}</label>
                    <input type="number" class="form-control original_price" disabled>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="campaign_price">{{ __('Price for Campaign') }}</label>
                    <input type="number" name="campaign_price[]" class="form-control campaign_price" step="0.01">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="available_num_of_units">{{ __('No. of Units Available') }}</label>
                    <input type="number" class="form-control available_num_of_units" disabled>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="units_for_sale">{{ __('No. of Units for Sale') }}</label>
                    <input type="number" name="units_for_sale[]" class="form-control units_for_sale">
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col">
                <div class="form-group">
                    <label for="start_date">{{ __('Start Date') }}</label>
                    <input type="text" name="start_date[]" class="form-control flatpickr start_date">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="end_date">{{ __('End Date') }}</label>
                    <input type="text" name="end_date[]" id="end_date" class="form-control flatpickr end_date">
                </div>
            </div>
        </div>
    </div>
</div>