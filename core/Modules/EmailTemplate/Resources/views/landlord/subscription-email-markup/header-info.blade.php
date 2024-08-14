
@if($subscription_details->assign_status == 0)

    @if($type == 'user')
        @if($subscription_details->is_renew == 1)
            <p class="renew_heading">
                <strong>{{__('Package Renewed')}} {{$subscription_details->name ?? ''}}
                    {{__('was successful. Package ID')}} #{{$subscription_details->id}}
                </strong>
            </p>
        @endif

        <p>{{__('Hey,')}} {{$subscription_details->name}} {{__('Your payment was successful. Package ID')}} #{{$subscription_details->id}},
           {{__('Package Name')}} "{{$subscription_details->package_name ?? ''}}" {{__('Paid Via')}}
           {{ucfirst(str_replace('_',' ',$subscription_details->package_gateway))}}
        </p>

    @endif

    @if($type == 'admin')
        @if(!is_null($subscription_details->renew_status))
            <p class="renew_heading">
                <strong>{{ __('Package Renewed')}} {{$subscription_details->name ?? ''}}
                        {{ __('was successful. Package ID')}} #{{$subscription_details->id }}
                </strong>
            </p>
        @endif

        <p>{{__('You get payment from')}} {{$subscription_details->name}} {{__('For Package ID') .'#'}} {{$subscription_details->id}},
            {{ __('package Name')}} {{$subscription_details->package_name ?? ''}} {{__('paid via')}}
            {{ ucfirst(str_replace('_',' ',$subscription_details->package_gateway))}}
        </p>

    @endif

    <div class="price-wrap">{{amount_with_currency_symbol($subscription_details->package_price)}}</div>


@else

    @if($subscription_details->is_renew == 1)
        <span style="text-align: center; font-size: 30px;" ><strong>{{__('Subscription Renewed by Admin')}}</strong></span><br><br>
    @else
        <span style="text-align: center; font-size: 30px;" ><strong>{{__('Subscription Assigned by Admin')}}</strong></span><br><br>
    @endif

    <span>{{__('Domain')}} : {{$subscription_details->tenant_id . '.' .env('CENTRAL_DOMAIN')}}</span><br>
    <span>{{__('Package Name')}} : {{$subscription_details->package_name }} </span><br>
    <span>{{__('Start Date')}} : {{$subscription_details->start_date}}</span><br>
    <span>{{__('Expire Date')}} : {{$subscription_details->expire_date}}</span><br><br>
    <span>{{__('Site URL')}} : <a href="{{ tenant_url_with_protocol($subscription_details->tenant_id) . '.'. env('CENTRAL_DOMAIN') }}" target="_blank">{{__('Visit Website')}}</a></span><br>
    <span>{{__('Site Admin Panel')}} : <a href="{{ tenant_url_with_protocol($subscription_details->tenant_id) . '.'. env('CENTRAL_DOMAIN')  .'/admin' }}" target="_blank">{{__('Visit Admin Panel')}}</a></span><br>
@endif
