
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{__('Event Ticket')}}</title>
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

            <h1 style="margin-left: 280px">{{__('Event Ticket')}}</h1>
        </td>

    </tr>

    <tr>
        <td valign="top">
            <p><strong>{{__('Ticket No')}}</strong>: {{$event_details->id}}</p>
            <p><strong>{{__('Event Attender Name')}}</strong>: {{$event_details->name}}</p>
            <p><strong>{{__('User Attender Email')}}</strong>: {{$event_details->email}}</p>
        </td>
        <td align="right">
        </td>
    </tr>
</table>

<table class="table-footer" >
    <thead>
    <tr>
        <th>{{__('Description')}}</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td valign="top">
            <div>
                <p class="singleItems"><strong>{{__('Event Title')}}</strong>: {{optional($event_details->event)->getTranslation('title',get_user_lang())}}</p>
                <p class="singleItems"><strong>{{__('Event Location')}}</strong>: {{$event_details->event?->venue_location}}</p>
                <p class="singleItems"><strong>{{__('Start Date')}}</strong>: {{$event_details->event?->date}}</p>
                <p class="singleItems"><strong>{{__('State Time')}}</strong>: {{$event_details->event?->time}}</p>
                <p class="singleItems"><strong>{{__('Ticket Quantity')}}</strong>: {{$event_details->ticket_qty}}</p>
            </div>
        </td>

        <td align="right">
            <div class="borderStyle">

            </div>
        </td>
    </tr>
    </tbody>

</table>


<div>
    <h4 class="text-center">{!! get_footer_copyright_text(get_user_lang()) !!}</h4>
</div>





