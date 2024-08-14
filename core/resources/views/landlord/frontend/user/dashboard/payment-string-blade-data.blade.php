
@foreach($featured_gateways as $payment)
    <option value="{{$payment}}" data-gateway="{{$payment}}">{{ ucwords($payment) }}</option>
@endforeach
