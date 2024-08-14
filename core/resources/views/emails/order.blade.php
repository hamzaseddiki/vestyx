
 <!doctype html>
<html dir="{{ \App\Facades\GlobalLanguage::user_lang_dir() }}" lang="{{ \App\Facades\GlobalLanguage::user_lang_slug() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <title>{{__('Payment Success For')}} {{get_static_option('site_title')}}</title>
    <style>
        *{
            font-family: 'Montserrat', sans-serif;
        }
        body {
            background-color: #fdfdfd;
        }
        .mail-container {
            max-width: 650px;
            margin: 50px auto;
            text-align: center;
        }

        .mail-container .logo-wrapper {
            padding: 20px 0 20px;
            border-bottom: 5px solid {{get_static_option('site_color')}};
        }
        table {
            margin: 0 auto;
        }
        table {

            border-collapse: collapse;
            width: 100%;
        }

        table td, table th {
            border: 1px solid rgba(0,0,0,.05);
            padding: 10px 20px;
            background-color: #fafafa;
            text-align: left;
            font-size: 14px;
        }

        table tr:nth-child(even){background-color: #f2f2f2;}

        table tr:hover {background-color: #ddd;}

        table th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: {{get_static_option('site_color')}};
            color: white;
        }
        footer {
            margin: 20px 0;
            font-size: 14px;
        }
        .main-content-wrap {
            background-color: #fff;
            box-shadow: 0 0 15px 0 rgba(0,0,0,.05);
            padding: 30px;
        }

        .main-content-wrap p {
            margin: 0;
            text-align: left;
            font-size: 14px;
            line-height: 26px;
        }

        .main-content-wrap p:first-child {
            margin-bottom: 10px;
        }

        .main-content-wrap .price-wrap {
            font-size: 60px;
            line-height: 70px;
            font-weight: 600;
            margin: 40px 0;
        }
        table {
            margin-bottom: 30px;
        }
        .logo-wrapper img{
            max-width: 200px;
        }

        .renew_heading{
            font-size: 20px;
        }
    </style>
</head>
<body>
<div class="mail-container">
    <div class="logo-wrapper">
        <a href="{{url('/')}}">
            {!! render_image_markup_by_attachment_id(get_static_option('site_logo')) !!}
        </a>
    </div>
    <div class="main-content-wrap">
        <p>{{__('Hello')}}</p>

        @if($user_type == 'user')
            @if(!is_null($package->renew_status))
                <p class="renew_heading"><strong>{{__('Package Renewed')}} {{$package->name ?? ''}} {{__('was successful. Package ID')}} #{{$package->id}}</strong> </p>
            @endif
            <p>{{__('A payment from')}} {{$package->name}} {{__('was successful. Package ID')}} #{{$package->id}} ,{{__('package Name')}} "{{$package->package_name ?? ''}}" {{__('Paid Via')}} {{ucfirst(str_replace('_',' ',$package->package_gateway))}}</p>
        @endif

        @if($user_type == 'admin')
            @if(!is_null($package->renew_status))
                <p class="renew_heading"> <strong>{{__('Package Renewed')}}{{$package->name ?? ''}} {{__('was successful. Package ID')}} #{{$package->id}}</strong>  </p>
            @endif
            <p>{{__('You get payment from')}} {{$package->name}} {{__('For Package ID') .'#'}} {{$package->id}}, {{__('package Name')}} {{'"'.$package->package_name ?? ''.'"'}} {{__('paid via')}} {{ucfirst(str_replace('_',' ',$package->package_gateway))}}</p>
        @endif


        @if($extra == 'custom_sub')
           <h4 class="renew_heading"> <strong>{{__('You have been assigned a subscription please complete your payment (Please contact to the administrator)')}} </strong></h4>
        @endif


        <div class="price-wrap">{{amount_with_currency_symbol($package->package_price)}}</div>
        <table>

            <tr>
                <td><strong>{{__('Package Name')}}</strong></td>
                <td>{{$package->package_name}}</td>
            </tr>

            <tr>
                <td><strong>{{__('Package Price')}}</strong></td>
                <td>{{amount_with_currency_symbol($package->package?->price)}}</td>
            </tr>

            @if(!empty($package->coupon_discount))
            <tr>
                <td><strong>{{__('Coupon Discount')}}</strong></td>
                <td>{{amount_with_currency_symbol($package->coupon_discount)}}</td>
            </tr>
            @endif

            <tr>
                <td><strong>{{__('Paid Amount')}}</strong></td>
                <td>{{amount_with_currency_symbol($package->package_price)}}</td>
            </tr>

            @if(!empty($package->start_date) && !empty($package->expire_date))
                <tr>
                    <td><strong>{{__('Package Start Date : ')}}</strong></td>
                    <td>{{$package->start_date ?? ''}}</td>
                </tr>
                <tr>
                    <td><strong>{{__('Package Expire Date : ')}}</strong></td>
                    <td>{{$package->expire_date ?? ''}}</td>
                </tr>
            @endif

            @if($user_type == 'user')

                <tr>
                    <td><strong>{{__('Your Name : ')}}</strong></td>
                    <td>{{optional($package->user)->name ?? ''}}</td>
                </tr>

                <tr>
                    <td><strong>{{__('Email : ')}}</strong></td>
                    <td>{{optional($package->user)->email ?? ''}}</td>
                </tr>

                <tr>
                    <td><strong>{{__('Your Country : ')}}</strong></td>
                    <td>{{optional(optional($package->user)->country_name)->name }}</td>
                </tr>

                <tr>
                    <td><strong>{{__('Your domain : ')}}</strong></td>
                    <td>{{ $package->tenant_id }}</td>
                </tr>

                <tr>
                    <td><strong>{{__('Your Site Url : ')}}</strong></td>
                    <td>
                        <a href="{{ tenant_url_with_protocol($package->tenant_id) .'.'. env('CENTRAL_DOMAIN') ?? ''  }}" target="_blank">
                            {{__('Click to visit your site') }}
                        </a>
                    </td>
                </tr>

                <tr>
                    <td><strong>{{__('Your Site Admin Panel URL : ')}}</strong></td>
                    <td>
                        <a href="{{tenant_url_with_protocol($package->tenant_id) .'.'. env('CENTRAL_DOMAIN')  .'/admin' ?? '' }}" target="_blank">
                            {{ __('Click to visit your site admin panel') }}
                        </a>
                    </td>

                </tr>
            @endif

        </table>
    </div>

    <footer>
        {!! get_footer_copyright_text(\App\Facades\GlobalLanguage::default_slug()) !!}
    </footer>

</div>
</body>
</html>

