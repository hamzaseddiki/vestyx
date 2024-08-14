@extends('tenant.frontend.frontend-page-master')
@section('title')
    {{__('Payment Success For:')}} {{optional($event_logs->event)->getTranslation('title',get_user_lang())}}
@endsection

@section('page-title')
    {{__('Payment Success For:')}} {{optional($event_logs->event)->getTranslation('title',get_user_lang())}}
@endsection

@section('style')
    <style>
        /* ticket style */
        .ticket{
            max-width: 800px;
            background: #fafafa;
            padding: 80px 60px 50px 60px;
            margin: 0 auto;
        }
        .ticket .title{
            color: #000;
            font-size: 20px;
            display: block;
            font-weight: 500;
            margin-bottom:25px;
        }
        .ticket .mainTitle{
            color: #000;
            font-size: 48px;
            display: block;
            font-weight: 600;
            margin-bottom:50px;
        }
        .ticketDiscription .pera{
            color: #000;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
        }
        .ticketDiscription .barcode{
            max-width:90px;
            border-radius: 8px;
        }
        .peraMargin{
            margin-bottom: 35px !important;
        }
        .ticket table {
            width: 100%;
            display: block;
        }
        .ticket tbody {
            width: 100%;
            display: block;
        }
        .ticket tbody tr {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }
        .ticket tbody tr td {
            width: 42%;
        }
    </style>
@endsection

@section('content')

    <div class="confirmationArea section-padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-10">
                    <div class="confirmationWrapper text-center">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- Check Mark icon -->
                                <div class="success-checkmark">
                                    <div class="check-icon">
                                        <span class="icon-line line-tip"></span>
                                        <span class="icon-line line-long"></span>
                                        <div class="icon-circle"></div>
                                        <div class="icon-fix"></div>
                                    </div>
                                </div>
                                <div class="confirmInfo mb-50 mt-40">
                                    <h2 class="confirm">{{__('Your Booking is Confirm')}}</h2>
                                    <h6 class="info">{{__('Your Ticket Number')}}</h6>
                                    <p class="pera">{{ $event_logs->id }}</p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="confirmInfo mb-40">
                                    <h6 class="info">{{__('Event Date')}}</h6>
                                    <p class="pera">{{__('Date')}}: {{$event->date}}</p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="confirmInfo mb-40">
                                    <h6 class="info">{{__('Event Time')}}</h6>
                                    <p class="pera">{{$event->time}}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="confirmInfo mb-40">
                                    <h6 class="info">{{__('Event Venue')}}</h6>
                                    <p class="pera">{{$event->venue_location}}</p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="btn-wrapper pt-40">
                                    <a class="cmn-btn1 hero-btn w-100" href="{{route('tenant.frontend.event.ticket.download',$event_logs->id)}}">{{__('Download your Ticket')}}</a>
                                    @if(auth()->guard('web')->check())
                                        <a  href="{{ route('tenant.user.home') }}" class="btn btn-dark hero-btn w-100 mt-3">{{__('Go to dashboard')}}</a>
                                    @else
                                        <a  href="{{url('/')}}" class="btn btn-dark hero-btn w-100 mt-3">{{__('Go to home')}}</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-xl-6">
                    <div class="ticket">
                        <table>
                            <tr>
                                <h6 class="title">{{__('Event Name')}}</h6>
                                <a href="{{route('tenant.frontend.event.payment',$event->slug)}}">
                                    <h1 class="mainTitle">{{$event->getTranslation('title',get_user_lang())}}</h1>
                                </a>
                            </tr>

                            <tr>
                                <td>
                                    <div class="ticketDiscription">
                                        <span class="pera">{{__('Date and Time')}}</span>
                                        <span class="pera">{{ $event->date }}</span>
                                        <span class="pera peraMargin">{{ $event->time }}</span>
                                        <span class="pera">{{__('Price')}}</span>
                                        <span class="pera peraMargin">{{amount_with_currency_symbol($event_logs->amount)}}</span>
                                        <span class="pera">{{__('Venue')}} : {{$event->venue_location}}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="ticketDiscription ticketDiscription2">
                                        <span class="pera">{{__('Name')}}</span>
                                        <span class="pera peraMargin">{{$event_logs->name}}</span>
                                        <span class="pera">{{__('Number of ticket')}}</span>
                                        <span class="pera peraMargin">{{$event_logs->ticket_qty}}</span>
                                        {!! QrCode::size(120)->generate($qr_code_markup) !!}
                                    </div>
                                     <small class="text-primary">{{ __('Scan qr code to see') }}</small>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
