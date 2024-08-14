@extends('landlord.frontend.frontend-page-master')

@section('title')
    {{__('Payment Success For:')}} {{$payment_details->package_name}}
@endsection

@section('page-title')
    {{$payment_details->package_name}}
@endsection

@section('content')

    @php
        $user_lang = get_user_lang();
    @endphp

    <div class="packageArea error-page-content" data-padding-bottom="100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="order-success-area margin-bottom-80 text-center pt-5">
                        <h1 class="title">{{get_static_option('site_order_success_page_' . $user_select_lang_slug . '_title')}}</h1>

                        @if($payment_details->is_renew == 1 && $payment_details->payment_status == 'complete')
                            <p class="order-page-description">{{__('Package renewed successfully..!')}}</p>
                         @else
                        <p class="order-page-description">{{get_static_option('site_order_success_page_' . $user_select_lang_slug . '_description')}}</p>
                        @endif

                        @if(!empty($domain) && $payment_details->is_renew == 1 && $payment_details->payment_status == 'pending')
                            <div class="alert alert-warning mt-2">{{__('Your website ready, please wait for admin approval for your renew order..! ')}}</div>
                        @endif

                        @if(!empty($domain) && $payment_details->status == 'pending' && $payment_details->payment_status == 'pending')
                            <div class="alert alert-warning mt-2">{{__('Your website ready, please wait for admin approval for your renew order..! ')}}</div>
                        @endif

                        @if(empty($domain))
                            <div class="alert alert-warning mt-2">{{__('Your website is not ready yet, it is in admin approval stage, you will get notified by email when it is ready.')}}</div>
                        @endif



                    </div>
                </div>

                <div class="col-lg-6">
                    <h2 class="billing-title mb-3">{{__('Order Details')}}</h2>
                    <ul class="billing-details">
                        <li><strong>{{__('Order ID :')}}</strong> #{{$payment_details->id}}</li>
                        <li><strong>{{__('Payment Package :')}}</strong> {{$payment_details->package_name}}</li>
                        <li><strong>{{__('Payment Package Type :')}}</strong> {{ \App\Enums\PricePlanTypEnums::getText(optional($payment_details->package)->type) }}</li>
                        <li><strong>{{__('Payment Gateway : ')}}</strong> {{ str_replace('_',' ',ucwords($payment_details->package_gateway)) }}</li>
                        <li><strong>{{__('Payment Status :')}}</strong> {{$payment_details->payment_status}}</li>
                        <li><strong>{{__('Transaction id :')}}</strong> {{$payment_details->transaction_id}}</li>
                    </ul>
                    <h2 class="billing-title mt-4 mb-2">{{__('Billing Details')}}</h2>
                    <ul class="billing-details">
                        <li><strong>{{__('Name :')}}</strong> {{$payment_details->name}}</li>
                        <li><strong>{{__('Email :')}}</strong> {{$payment_details->email}}</li>
                    </ul>
                    <div class="btn-wrapper margin-top-40">
                        <a href="{{route('landlord.homepage')}}" class="boxed-btn">{{__('Back To Home')}}</a>
                    </div>
                </div>


                <div class="col-xl-4 col-lg-5 col-md-6 ">
                    <div class="packageDetails">
                        <div class="text-center mb-60">
                            <span class="infoTitle">{{optional($payment_details->package)->getTranslation('title',$user_lang)}}</span>
                            <span class="pricing">{{amount_with_currency_symbol($payment_details->package_price)}}</span>
                            <h6 class="title text-primary">{{__('Start Date : ')}}{{$payment_details->start_date ?? ''}}</h6>
                            <h6 class="title text-danger mt-2">{{__('Expire Date : ')}}{{$payment_details->expire_date ?? 'Life Time'}}</h6>
                        </div>
                        <ul class="listing">
{{--                            {!! get_all_main_feature_create_permission($payment_details->package) !!}--}}
                            @include('landlord.admin.price-plan.partials.features-helper-markup')
                            @foreach(optional($payment_details->package)->plan_features as $key=> $item)
                                <li class="single"><img src="{{asset('assets/landlord/frontend/img/icon/check.svg')}}" class="icon" alt="image">
                                    {{__(str_replace('_', ' ',ucfirst($item->feature_name))) ?? ''}}
                                </li>
                            @endforeach
                        </ul>
                        <div class="price-all-feature btn-wrapper">
                            <a href="{{route('landlord.frontend.plan.view',$payment_details->package_id)}}" class="cmn-btn02">{{__('View All Features')}}</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
