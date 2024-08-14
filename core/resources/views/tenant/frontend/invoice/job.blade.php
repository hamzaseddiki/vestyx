
<!doctype html>
<html dir="{{ \App\Facades\GlobalLanguage::user_lang_dir() }}" lang="{{ \App\Facades\GlobalLanguage::user_lang_slug() }}">
<head>
    <meta charset="UTF-8">
    <title>{{__('Event Attendance Invoice')}}</title>
    <style type="text/css">
        * {
            font-family: Verdana, Arial, sans-serif;
        }
        table {
            font-size: x-small;
        }
        td  {
            font-size: 14px;
            padding: 5px;
            vertical-align: middle !important;
        }
        table tr th {
            line-height: 20px;
            font-size: 14px;
            font-weight: 700;
            padding: 5px 5px;
        }
        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }
        .gray {
            background-color: lightgray
        }
        .table-footer tr td {
            text-align: left;
            font-size: 14px;
            padding: 5px;
        }
        .table-top td p,
        .table-footer td p {
            line-height: 18px;
            display: block;
            padding: 5px 0;
        }
        .totalAmount {
            font-width: 700;
            font-size: 25px;
            text-align: right;
            display: block;
        }
        table thead tr th {
            border: 0;
        }
        table thead tr th {
            border: 0;
        }
        table thead tr th:first-child {
            text-align: left;
            padding: 10px 30px;
        }
        table thead tr th:last-child {
            text-align: right;
            padding: 10px 30px;
        }
        .borderStyle{
            margin-bottom: 5px;
        }
        .border-top{ border-top: 2px solid #000;}

        .singleItems{
            font-size: 14px;
        }

    </style>
</head>
<body>


<table width="100%" class="table-top">
    <tr>
        <td valign="top">
            @php
                $logo = get_attachment_image_by_id(get_static_option('site_logo'));
            @endphp
            <img src="{{$logo['img_url']}}" alt="" width="150"/>
        </td>
    </tr>

    <tr>
        <td valign="top">
            <p><strong>{{__('Date')}}</strong>: {{date('d-m-Y',strtotime($job_details->created_at))}}</p>
            <p><strong>{{__('From')}}</strong>: {{get_static_option('tenant_site_global_email')}}</p>
            <p><strong>{{__('To')}}</strong>: {{$job_details->name}}</p>
            <p><strong>{{__('User Email')}}</strong>: {{$job_details->email}}</p>
        </td>

        <td align="right">
            <h3>{{get_static_option('company_name') ?? get_static_option('site_'.get_user_lang().'_title')}}</h3>
            <p>{{get_static_option('company_address')}}</p>
            <p>{{get_static_option('company_email')}}</p>
            <p>{{get_static_option('company_phone')}}</p>
        </td>
    </tr>
</table>

<table class="table-footer" width="100%">
    <thead style="background-color: #353935; color: white">
    <tr>
        <th>{{__('Description')}}</th>
        <th>{{__('Amount')}}</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td valign="top">
            <div>
                <p class="singleItems"><strong>{{__('Job Title')}}</strong>: {{optional($job_details->job)->getTranslation('title',get_user_lang())}}</p>
                <p class="singleItems"><strong>{{__('Job Location')}}</strong>: {{$job_details->job?->getTranslation('job_location',get_user_lang())}}</p>
                <p class="singleItems"><strong>{{__('Company Name')}}</strong>: {{$job_details->job?->getTranslation('company_name',get_user_lang())}}</p>
                <p class="singleItems"><strong>{{__('Job Position')}}</strong>: {{$job_details->job?->getTranslation('designation',get_user_lang())}}</p>
                <p class="singleItems"><strong>{{__('Salary Offer')}}</strong>: {{ amount_with_currency_symbol($job_details->job?->salary_offer,true)}}</p>

                <p class="singleItems"><strong>{{__('Payment Gateway')}}</strong>: {{str_replace('_',' ',$job_details->payment_gateway)}}</p>
                <p class="singleItems"><strong>{{__('Payment Status')}}</strong>: {{\App\Enums\DonationPaymentStatusEnum::getText($job_details->status) }}</p>
                <p class="singleItems"><strong>{{__('Transaction ID')}}</strong>: {{$job_details->transaction_id}}</p>
            </div>
        </td>

        <td align="right">
            <div class="borderStyle">
                <h6 class="singleItems" style="margin-bottom: 10px; display: block"><strong>{{__('Application Fee')}}</strong>: {{amount_with_currency_symbol($job_details->amount)}}</h6>
                <h2 class=" border-top" style="margin-top: 20px;margin-bottom: 20px; display: block"><strong>{{__('Total Amount')}}: </strong> {{amount_with_currency_symbol($job_details->amount)}}</h2>
            </div>
        </td>
    </tr>
    </tbody>

</table>





