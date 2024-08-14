
@foreach($all_payment_gateways as $gateway)
    <x-fields.switcher name="payment_gateways[]" dataValue="{{$gateway->name}}" label="{{ str_replace('_',' ',ucfirst($gateway->name)) }}"/>
@endforeach
