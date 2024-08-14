@php $default_lang = get_default_language(); @endphp
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
            text-transform: capitalize;
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
        @if($type == 'user')
        <p>{{__('An event attendance booking payment from')}} {{$data->name}} {{__('was successful. Event Log ID')}} #{{$data->id}} ,{{__('Event')}} "{{optional($data->event)->getTranslation('title',get_user_lang())}}" {{__('Paid Via')}} {{ucfirst(str_replace('_',' ',$data->payment_gateway))}}</p>
        @else
            <p>{{__('You get payment from')}} {{$data->name}} {{__('For event log ID') .'#'}} {{$data->id}}, {{__('event')}} {{optional($data->event)->getTranslation('title',get_user_lang())}} {{__('paid via')}} {{ucfirst(str_replace('_',' ',$data->payment_gateway))}}</p>
        @endif
        <div class="price-wrap">{{amount_with_currency_symbol($data->amount)}}</div>
        <table>
            <tr>
                <td>{{__('Ticket No')}}</td>
                <td>#{{$data->id ?? ''}}</td>
            </tr>
            <tr>
                <td>{{__('Event Title')}}</td>
                <td>{{optional($data->event)->getTranslation('title',get_user_lang()) ?? ''}}</td>
            </tr>
            <tr>
                <td>{{__('Ticket Quantity')}}</td>
                <td>{{$data->ticket_qty}}</td>
            </tr>

            <tr>
                <td>{{__('Event Cost')}}</td>
                <td>{{amount_with_currency_symbol($data->amount,true)}}</td>
            </tr>

            <tr>
                <td>{{__('Event Date')}}</td>
                <td>{{$data->event?->date}}</td>
            </tr>

            <tr>
                <td>{{__('Event Time')}}</td>
                <td>{{$data->event?->time}}</td>
            </tr>

            <tr>
                <td>{{__('Event Location')}}</td>
                <td>{{$data->event?->venue_location}}</td>
            </tr>

            <tr>
                <td>{{__('Payment Gateway')}}</td>
                <td>{{ucfirst(str_replace('_',' ',$data->payment_gateway))}}</td>
            </tr>
            <tr>
                <td>{{__('Payment Status')}}</td>
                <td>{{ \App\Enums\DonationPaymentStatusEnum::getText($data->status) }}</td>
            </tr>
            <tr>
                <td>{{__('Transaction ID')}}</td>
                <td>{{$data->transaction_id}}</td>
            </tr>

            <tr>
                <td>{{__('User Name ')}}</td>
                <td>{{$data->name}}</td>
            </tr>

            <tr>
                <td>{{__('User Email ')}}</td>
                <td>{{$data->email}}</td>
            </tr>

            <tr>
                <td>{{__('User Phone ')}}</td>
                <td>{{$data->phone}}</td>
            </tr>

            <tr>
                <td>{{__('User Address ')}}</td>
                <td>{{$data->address}}</td>
            </tr>

        </table>
    </div>
    <footer>
        {!! get_footer_copyright_text(get_user_lang()) !!}
    </footer>
</div>
</body>
</html>
