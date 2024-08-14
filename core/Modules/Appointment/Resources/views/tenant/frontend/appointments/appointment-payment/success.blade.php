@extends('tenant.frontend.frontend-page-master')
@section('title')
    {{__('Appointment Payment Success For:')}} {{optional($payment_details->appointment)->getTranslation('title',get_user_lang())}}
@endsection

@section('page-title')
    {{__('Appointment Payment Success For:')}} {{optional($payment_details->appointment)->getTranslation('title',get_user_lang())}}
@endsection

@section('content')
    <div class="donation-success-page-content padding-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="order-success-area">
                        @if($payment_details->payment_status == 'pending')
                            <div class="alert alert-warning">
                                <h6 class="text-center">{{__('Your Payment Sent successfully..! It is in now under admin review..!')}}</h6>
                            </div>
                        @else
                        <h1 class="title mb-5 text-center">{{__('Payment Completed Successfully')}}</h1>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6">
                    <h2 class="billing-title mb-4">{{__('Appointment Details')}}</h2>
                    <ul class="billing-details">

                        <li><strong>{{__('Name')}}:</strong> {{$payment_details->name}}</li>
                        <li><strong>{{__('Email')}}:</strong> {{$payment_details->email}}</li>
                        <li><strong>{{__('Amount')}}:</strong> {{amount_with_currency_symbol($payment_details->total_amount)}}</li>

                        <li><strong>{{__('Appointment Date')}}:</strong> {{$payment_details->appointment_date}}</li>
                        <li><strong>{{__('Appointment Time')}}:</strong> {{$payment_details->appointment_time}}</li>

                        <li><strong>{{__('Payment Method')}}:</strong>  {{str_replace('_',' ',$payment_details->payment_gateway)}}</li>
                        <li><strong>{{__('Payment Status')}}:</strong> {{$payment_details->payment_status}}</li>
                        <li><strong>{{__('Transaction id')}}:</strong> {{$payment_details->transaction_id}}</li>

                    </ul>
                    <div class="donation-wrap donation-success-page">
                        <div class="contribute-single-item">
                            <div class="thumb mt-3">
                                {!! render_image_markup_by_attachment_id($payment_details->appointment->image,'','grid') !!}
                                <div class="thumb-content">
                                </div>
                            </div>
                            <div class="content mt-4">
                                <a href="{{route('tenant.frontend.appointment.single',$payment_details->appointment?->slug)}}"><h4 class="title">{{optional($payment_details->appointment)->getTranslation('title',get_user_lang())}}</h4></a>
                                <p>{{strip_tags(Str::words(strip_tags($payment_details->appointment?->getTranslation('description',get_user_lang())),40))}}</p>
                                <div class="btn-wrapper mt-3">
                                    <a href="{{route('tenant.frontend.appointment.order.page',$payment_details->appointment?->slug)}}" class="barberShop_cmn_btn btn_bg_1">{{__('Book Again')}}</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="btn-wrapper margin-top-40">
                        @if(auth()->guard('web')->check())
                            <div class="btn-wrapper">
                                <a href="{{route('tenant.user.home')}}" class="barberShop_cmn_btn btn_bg_1">{{__('Go To Dashboard')}}</a>
                            </div>
                        @else
                            <div class="btn-wrapper">
                                <a href="{{url('/')}}" class="barberShop_cmn_btn btn_bg_1">{{__('Back To Home')}}</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
