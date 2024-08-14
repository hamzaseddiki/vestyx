@php
    $data_base_features = $plan->plan_features?->pluck('feature_name')->toArray();
@endphp

@foreach($all_payment_gateways as $gateway)
    <x-fields.switcher name="payment_gateways[]" value="{{ in_array($gateway->name,$data_base_features) ? $gateway->name : '' }}" dataValue="{{ $gateway->name }}" label="{{ str_replace('_',' ',ucfirst($gateway->name)) }}"/>
@endforeach
