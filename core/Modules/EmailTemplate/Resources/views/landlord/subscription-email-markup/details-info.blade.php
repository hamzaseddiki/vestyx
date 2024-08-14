@if($subscription_details->assign_status == 0)
<table>
    <tr>
        <td><strong>{{__('Package Name')}}</strong></td>
        <td>{{$subscription_details->package_name}}</td>
    </tr>

    @if($subscription_details->assign_status == 0)
        <tr>
            <td><strong>{{__('Package Price')}}</strong></td>
            <td>{{amount_with_currency_symbol($subscription_details->package?->price)}}</td>
        </tr>

        @if(!empty($subscription_details->coupon_discount))
            <tr>
                <td><strong>{{__('Coupon Discount')}}</strong></td>
                <td>{{amount_with_currency_symbol($subscription_details->coupon_discount)}}</td>
            </tr>
        @endif

        <tr>
            <td><strong>{{__('Paid Amount')}}</strong></td>
            <td>{{amount_with_currency_symbol($subscription_details->package_price)}}</td>
        </tr>
    @endif

    @if(!empty($subscription_details->start_date) && !empty($subscription_details->expire_date))
        <tr>
            <td><strong>{{__('Package Start Date : ')}}</strong></td>
            <td>{{$subscription_details->start_date ?? ''}}</td>
        </tr>
        <tr>
            <td><strong>{{__('Package Expire Date : ')}}</strong></td>
            <td>{{ \Illuminate\Support\Carbon::parse($subscription_details->expire_date)->format('d-m-Y h:i:s') ?? ''}}</td>
        </tr>
    @endif


    <tr>
        <td><strong>{{__('Name : ')}}</strong></td>
        <td>{{optional($subscription_details->user)->name ?? ''}}</td>
    </tr>

    <tr>
        <td><strong>{{__('Email : ')}}</strong></td>
        <td>{{optional($subscription_details->user)->email ?? ''}}</td>
    </tr>

    <tr>
        <td><strong>{{__('Country : ')}}</strong></td>
        <td>{{optional(optional($subscription_details->user)->country_name)->name }}</td>
    </tr>

    <tr>
        <td><strong>{{__('domain : ')}}</strong></td>
        <td>{{ $subscription_details->tenant_id }}</td>
    </tr>

    <tr>
        <td><strong>{{__('Site Url : ')}}</strong></td>
        <td>
            <a href="{{ tenant_url_with_protocol($subscription_details->tenant_id) . '.'. env('CENTRAL_DOMAIN') ?? ''  }}"
               target="_blank">
                {{__('Click to visit site') }}
            </a>
        </td>
    </tr>

    <tr>
        <td><strong>{{__('Site Admin Panel URL : ')}}</strong></td>
        <td>
            <a href="{{tenant_url_with_protocol($subscription_details->tenant_id) . '.'. env('CENTRAL_DOMAIN')  .'/admin' ?? '' }}"
               target="_blank">
                {{ __('Click to visit site admin panel') }}
            </a>
        </td>

    </tr>
</table>

@endif
