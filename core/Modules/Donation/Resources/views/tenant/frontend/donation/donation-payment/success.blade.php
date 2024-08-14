@extends('tenant.frontend.frontend-page-master')
@section('title')
    {{__('Payment Success For:')}} {{optional($donation_logs->donation)->getTranslation('title',get_user_lang())}}
@endsection

@section('page-title')
    {{__('Payment Success For:')}} {{optional($donation_logs->donation)->getTranslation('title',get_user_lang())}}
@endsection

@section('content')
    <div class="donation-success-page-content padding-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="order-success-area">
                        <h1 class="title">{{get_static_option('donation_success_page_title')}}</h1>
                        <p>{{get_static_option('donation_success_page_description')}}</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <h2 class="billing-title mb-4">{{__('Donation Details')}}</h2>
                    <ul class="billing-details">

                        <li><strong>{{__('Name')}}:</strong> {{$donation_logs->name}}</li>
                        <li><strong>{{__('Email')}}:</strong> {{$donation_logs->email}}</li>
                        <li><strong>{{__('Amount')}}:</strong> {{amount_with_currency_symbol($donation_logs->amount)}}</li>

                        <li><strong>{{__('Payment Method')}}:</strong>  {{str_replace('_',' ',$donation_logs->payment_gateway)}}</li>
                        <li><strong>{{__('Payment Status')}}:</strong> {{\App\Enums\DonationPaymentStatusEnum::getText($donation_logs->status)}}</li>
                        <li><strong>{{__('Transaction id')}}:</strong> {{$donation_logs->transaction_id}}</li>
                    </ul>
                    <div class="donation-wrap donation-success-page">
                        <div class="contribute-single-item">
                            <div class="thumb mt-3">
                                {!! render_image_markup_by_attachment_id($donation->image,'','grid') !!}
                                <div class="thumb-content">
                                </div>
                            </div>
                            <div class="content mt-4">
                                <a href="{{route('tenant.frontend.donation.single',$donation_logs->donation?->slug)}}"><h4 class="title">{{$donation->getTranslation('title',get_user_lang())}}</h4></a>
                                <p>{{strip_tags(Str::words(strip_tags($donation_logs->donation?->getTranslation('description',get_user_lang())),40))}}</p>
                                <div class="btn-wrapper mt-3">
                                    <a href="{{route('tenant.frontend.donation.payment',$donation_logs->donation?->id)}}" class="boxed-btn">{{__('Donate Again')}}</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="btn-wrapper margin-top-40">
                        @if(auth()->guard('web')->check())
                            <div class="btn-wrapper">
                                <a href="{{route('tenant.user.home')}}" class="boxed-btn reverse-color">{{__('Go To Dashboard')}}</a>
                            </div>
                        @else
                            <div class="btn-wrapper">
                                <a href="{{url('/')}}" class="boxed-btn reverse-color">{{__('Back To Home')}}</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
