<?php
    if(!isset($product)){
        $product = null;
    }
?>

<div class="general-info-wrapper px-3">
    <h4 class="dashboard-common-title-two"> Price Manage </h4>
    <div class="general-info-form mt-0 mt-lg-4">
        <div class="dashboard-input mt-4">
            <label class="dashboard-label color-light mb-2"> {{ __("Base Cost") }} </label>
            <input type="text" class="form--control radius-10" value="{{ $product?->cost }}" name="cost" placeholder="{{ __("Base Cost...") }}">
            <p>{{ __("Purchase price of this product.") }}</p>
        </div>

        <div class="dashboard-input mt-4">
            <label class="dashboard-label color-light mb-2"> {{ __("Regular Price") }} </label>
            <input type="text" class="form--control radius-10" value="{{ $product?->price }}" name="price" placeholder="{{ __("Enter Regular Price...") }}">
            <small>{{ __("This price will display like this") }} <del>( {{ site_currency_symbol() }} 10)</del></small>
        </div>

        <div class="dashboard-input mt-4">
            <label class="dashboard-label color-light mb-2"> {{ __("Sale Price") }} </label>
            <input type="text" class="form--control radius-10" value="{{ $product?->sale_price }}" name="sale_price" placeholder="{{ __("Enter Sale Price...") }}">
            <small>{{ __("This will be your product selling price") }}</small>
        </div>
    </div>
</div>