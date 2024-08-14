@php
    if(!isset($inventory)){
        $inventory = null;
    }

    if(!isset($uom)){
        $uom = null;
    }
@endphp

<h4 class="dashboard-common-title-two"> {{ __("Product Inventory") }} </h4>

<div class="dashboard-input mt-4">
    <label class="dashboard-label color-light mb-2"> {{ __("Sku") }} </label>
    <input type="text" class="form--control radius-10" name="sku" value="{{ $inventory?->sku }}">
    <p>{{ __("Custom Unique Code for this product.") }}</p>
</div>

<div class="dashboard-input mt-4">
    <label class="dashboard-label color-light mb-2"> {{ __("Quantity") }} </label>
    <input type="tel" class="form--control radius-10" name="quantity" value="{{ $inventory?->stock_count }}">
    <p>{{ __("This will be replaced with the sum of inventory items. if any inventory  item is registered..") }}</p>
</div>

<div class="dashboard-input mt-4">
    <label class="dashboard-label color-light mb-2"> {{ __("Unit") }} </label>

    <div class="nice-select-two">
        <select name="unit_id" class="form--control">
            <option value="">{{ __("Select Unit") }}</option>
            @foreach($units as $unit)
                <option {{ $unit->id === $uom?->unit_id ? "selected" : "" }} value="{{ $unit->id }}">{{ $unit->name }}</option>
            @endforeach
        </select>
        <small>{{ __("Select Unit") }}</small>
    </div>
</div>

<div class="dashboard-input mt-4">
    <label class="dashboard-label color-light mb-2"> {{ __("Unit Of Measurement") }} </label>
    <input type="number" name="uom" class="form--control radius-10" value="{{ $uom?->quantity }}" placeholder="{{ __("Enter Unit Of Measurement") }}">
    <small>{{ __("Enter the number here") }}</small>
</div>
