@if($notification_type == 'new_subscription')
    <tr>
        <td>{{$notification->payment_log?->id}}</td>
        <td>
            <ul>
                <li>{{__('User Name')}} : <span>{{ $notification->payment_log?->name }}</span></li>
                <li>{{__('Package Name')}} : <span>{{ $notification->payment_log?->package_name }}</span></li>
                <li>{{__('Amount')}} : <span>{{ amount_with_currency_symbol($notification->payment_log?->package_price) }}</span></li>
                <li>{{__('Domain')}} : <span>{{$notification->payment_log?->tenant_id . '.'. env('CENTRAL_DOMAIN') }}</span></li>
                <li>{{__('Start Date')}} : <span>{{ date('d-m-Y',strtotime($notification->payment_log?->start_date)) }}</span></li>
                <li>{{__('Expire Date')}} : <span>{{ date('d-m-Y',strtotime($notification->payment_log?->expire_date)) }}</span></li>
                <li>{{__('Payment Status ')}} : <span>{{ $notification->payment_log?->payment_status }}</span></li>
            </ul>
        </td>
        <td>
            <span class="badge badge-info">{{ str_replace('_', ' ', ucfirst($notification_type)) }}</span>
        </td>
    </tr>
@endif

@if($notification_type == 'package_renew')
    <tr>
        <td>{{$notification->payment_log?->id}}</td>
        <td>
            <ul>
                <li>{{__('User Name')}} : <span>{{ $notification->payment_log?->name }}</span></li>
                <li>{{__('Package Name')}} : <span>{{ $notification->payment_log?->package_name }}</span></li>
                <li>{{__('Amount')}} : <span>{{ amount_with_currency_symbol($notification->payment_log?->package_price) }}</span></li>
                <li>{{__('Domain')}} : <span>{{$notification->payment_log?->tenant_id . '.'. env('CENTRAL_DOMAIN') }}</span></li>
                <li>{{__('Start Date')}} : <span>{{ date('d-m-Y',strtotime($notification->payment_log?->start_date)) }}</span></li>
                <li>{{__('Expire Date')}} : <span>{{ date('d-m-Y',strtotime($notification->payment_log?->expire_date)) }}</span></li>
                <li>{{__('Payment Status ')}} : <span>{{ $notification->payment_log?->payment_status }}</span></li>
                <li>{{__('Renew taken')}} : <span>{{ $notification->payment_log?->renew_status }}</span></li>
            </ul>
        </td>
        <td>
            <span class="badge badge-info">{{ str_replace('_', ' ', ucfirst($notification_type)) }}</span>
        </td>
    </tr>
@endif

@if($notification_type == 'trial')
    <tr>
        <td>{{$notification->payment_log?->id}}</td>
        <td>
            <ul>
                <li>{{__('User Name')}} : <span>{{ $notification->payment_log?->name }}</span></li>
                <li>{{__('Package Name')}} : <span>{{ $notification->payment_log?->package_name }}</span></li>
                <li>{{__('Amount')}} : <span>{{ amount_with_currency_symbol($notification->payment_log?->package_price) }}</span></li>
                <li>{{__('Domain')}} : <span>{{$notification->payment_log?->tenant_id . '.'. env('CENTRAL_DOMAIN') }}</span></li>
                <li>{{__('Start Date')}} : <span>{{ date('d-m-Y',strtotime($notification->payment_log?->start_date)) }}</span></li>
                <li>{{__('Expire Date')}} : <span>{{ date('d-m-Y',strtotime($notification->payment_log?->expire_date)) }}</span></li>
                <li>{{__('Order Status ')}} : <span>{{ $notification->payment_log?->status }}</span></li>
                <li>{{__('Payment Status ')}} : <span>{{ $notification->payment_log?->payment_status }}</span></li>
            </ul>
        </td>
        <td>
            <span class="badge badge-info">{{ str_replace('_', ' ', ucfirst($notification_type)) }}</span>
        </td>
    </tr>
@endif

@if($notification_type == 'support_ticket')
    <tr>
        <td>{{$notification->support_ticket?->id}}</td>
        <td>
            <ul>
                <li>{{__('Ticket ID')}} : <span>{{ $notification->support_ticket?->id }}</span></li>
                <li>{{__('User Name')}} : <span>{{ $notification->support_ticket?->user?->name }}</span></li>
                <li>{{__('Ticket Title')}} : <span>{{ $notification->support_ticket?->title }}</span></li>
                <li>{{__('Ticket Subject')}} : <span>{{ $notification->support_ticket?->subject }}</span></li>
                <li>{{__('Ticket Status')}} : <span>{{ $notification->support_ticket?->status }}</span></li>
            </ul>
        </td>
        <td>
            <span class="badge badge-info">{{ str_replace('_', ' ', ucfirst($notification_type)) }}</span>
        </td>
    </tr>
@endif

@if($notification_type == 'support_ticket_message')
    <tr>
        <td>{{$notification->support_ticket_message?->id}}</td>
        <td>
            <ul>
                <li>{{__('Message ID')}} : <span>{{ $notification->support_ticket_message?->id }}</span></li>
                <li>{{__('Message')}} : <span>{!! $notification->support_ticket_message?->message !!}</span></li>
            </ul>
        </td>
        <td>
            <span class="badge badge-info">{{ str_replace('_', ' ', ucfirst($notification_type)) }}</span>
        </td>
    </tr>
@endif


@if($notification_type == 'newsletter_subscribed')
    <tr>
        <td>{{$notification->newsletter?->id}}</td>
        <td>
            <ul>
                <li>{{__('ID')}} : <span>{{ $notification->newsletter?->id }}</span></li>
                <li>{{__('Email')}} : <span>{!! $notification->newsletter?->email !!}</span></li>
                <li>{{__('Status')}} : <span> {{$notification->newsletter?->status == 1 ? __('Verified') : __('Not Verified') }}</span></li>
            </ul>
        </td>
        <td>
            <span class="badge badge-info">{{ str_replace('_', ' ', ucfirst($notification_type)) }}</span>
        </td>
    </tr>
@endif



@if($notification_type == 'custom_domain_request')
    <tr>
        <td>{{$notification->custom_domain?->id}}</td>
        <td>
            <ul>
                <li>{{__('ID')}} : <span>{{ $notification->custom_domain?->id }}</span></li>
                <li>{{__('Old Domain')}} : <span>{!! $notification->custom_domain?->old_domain !!}</span></li>
                <li>{{__('New Domain')}} : <span>{!! $notification->custom_domain?->custom_domain !!}</span></li>
            </ul>
        </td>
        <td>
            <span class="badge badge-info">{{ str_replace('_', ' ', ucfirst($notification_type)) }}</span>
        </td>
    </tr>
@endif

@if($notification_type == 'user_registration')
    <tr>
        <td>{{$notification->user?->id}}</td>
        <td>
            <ul>
                <li>{{__('ID')}} : <span>{{ $notification->user?->id }}</span></li>
                <li>{{__('Name')}} : <span>{!! $notification->user?->name !!}</span></li>
                <li>{{__('Email')}} : <span>{!! $notification->user?->email !!}</span></li>
            </ul>
        </td>
        <td>
            <span class="badge badge-info">{{ str_replace('_', ' ', ucfirst($notification_type)) }}</span>
        </td>
    </tr>
@endif

@if($notification_type == 'wallet_deposit')
    <tr>
        <td>{{$notification->id}}</td>
        <td>
            <ul>
                <li>{{__('User Name')}} : <span>{!! $notification->wallet?->user?->name !!}</span></li>
                <li>{{__('Amount')}} : <span>{{amount_with_currency_symbol($notification->wallet?->amount)}}</span></li>
            </ul>
        </td>
        <td>
            <span class="badge badge-info">{{ str_replace('_', ' ', ucfirst($notification_type)) }}</span>
        </td>
    </tr>
@endif

