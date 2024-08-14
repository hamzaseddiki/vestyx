@extends('tenant.frontend.frontend-page-master')

@section('title')
   {{__('Payment')}} : {{ $order_details->getTranslation('title',get_user_lang())}}
@endsection

@section('page-title')
    {{__('Payment')}} : {{ $order_details->getTranslation('title',get_user_lang())}}
@endsection

@section('content')
    @php
        $appointment_order_data = session()->get('appointment_order_data');
        $auth_user = auth()->guard('web');
    @endphp
    <main>
        <section class="select_service_area padding-top-100 padding-bottom-100">
            <div class="container">

              <form action="{{ route('tenant.frontend.appointment.payment.store') }}" method="post" enctype="multipart/form-data">
               @csrf
                <x-error-msg/>
                <x-flash-msg/>
                <div class="row g-4 justify-content-between">
                    <div class="col-xl-6 col-lg-7">
                        <div class="barberShop__bookingInfo">
                            <div class="barberShop__bookingInfo__item barberShop-section-bg">
                                <div class="barberShop__bookingInfo__confirm">
                                    <h4 class="barberShop__bookingInfo__confirm__succcess">{{ get_static_option('appointment_payment_page_left_'.get_user_lang().'_heading') }}</h4>
                                </div>
                                <div class="barberShop__bookingInfo__beautician mt-5">
                                    <div class="barberShop__bookingInfo__beautician__author">
                                        <div class="barberShop__bookingInfo__beautician__author__thumb">
                                            {!! render_image_markup_by_attachment_id($order_details->image) !!}
                                        </div>
                                        <div class="barberShop__bookingInfo__beautician__author__contents">
                                            <p class="barberShop__bookingInfo__beautician__author__para">{{ $order_details->getTranslation('title',get_user_lang())}}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="barberShop__bookingInfo__schedule mt-5">
                                    <div class="barberShop__bookingInfo__schedule__flex">
                                        <div class="barberShop__bookingInfo__schedule__details">
                                            <span class="barberShop__bookingInfo__schedule__para">{{__('Selected Date and Time')}}</span>
                                            <h5 class="barberShop__bookingInfo__schedule__title mt-2">
                                            {{$appointment_order_data['appointment_date'] ?? ''}} <br>
                                                {{$appointment_order_data['schedule_time'] ?? ''}}
                                            </h5>
                                        </div>
                                        <div class="barberShop__bookingInfo__schedule__btn">
                                            <a href="{{ route('tenant.frontend.appointment.order.page',$order_details->slug) }}" class="barberShop_cmn_btn btn_outline_1 color_one btn_small">{{ get_static_option('appointment_payment_page_left_button_'.get_user_lang().'_text') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="barberShop__bookingInfo__item barberShop-section-bg">
                                <div class="barberShop__bookingInfo__support">
                                    <div class="barberShop__bookingInfo__support__flex">
                                        <div class="barberShop__bookingInfo__support__details">
                                            <h4 class="barberShop__bookingInfo__support__title">{{ get_static_option('appointment_payment_page_bottom_'.get_user_lang().'_title') }}</h4>
                                            <span class="barberShop__bookingInfo__support__para mt-3">{!! get_static_option('appointment_payment_page_bottom_'.get_user_lang().'_description') !!}</span>
                                        </div>
                                        <div class="barberShop__bookingInfo__support__contact">
                                            <a href="#!" class="barberShop__bookingInfo__support__contact__number"><i class="fa-solid fa-phone"></i> {{ get_static_option('appointment_payment_page_bottom_'.get_user_lang().'_phone') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="barberShop__bookingInfo padding-top-50">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="barberShop__bookingInfo__billing__header__title text-center">{{__('User Booking Information')}}</h5>
                            </div>

                        <div class="row p-3">
                            <div class="col-lg-12 col-md-12">
                                <label class="catTittle"> {{__('Name')}}</label>
                                <div class="input-form input-form2">
                                    <input type="text" name="name" placeholder="Enter your name" value="{{ $auth_user->check() ? $auth_user->user()->name : '' }}">
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12">
                                <label class="catTittle"> {{__('Email')}}</label>
                                <div class="input-form input-form2">
                                    <input type="email" name="email" placeholder="Enter your email" value="{{ $auth_user->check() ? $auth_user->user()->email : '' }}">
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12">
                                <label class="catTittle"> {{__('Phone')}}</label>
                                <div class="input-form input-form2">
                                    <input type="text" name="mobile" placeholder="Enter your Phone" value="{{ $auth_user->check() ? $auth_user->user()->mobile : '' }}">
                                </div>
                            </div>
                        </div>

                        </div>
                     </div>

                    </div>
                    <div class="col-xl-5 col-lg-5">
                        <div class="barberShop__bookingInfo__billing">
                            <div class="barberShop__bookingInfo__billing__item">
                                <div class="barberShop__bookingInfo__billing__header">
                                    <h3 class="barberShop__bookingInfo__billing__header__title">{{ get_static_option('appointment_payment_page_right_'.get_user_lang().'_heading') }}</h3>
                                </div>
                                <div class="barberShop__bookingInfo__billing__inner">
                                    <ul class="barberShop__bookingInfo__billing__list">

                                        <li class="barberShop__bookingInfo__billing__list__item">
                                            <span class="text-dark">{{ $order_details->title }}</span> <span class="price">{{ amount_with_currency_symbol($order_details->price) }}</span>
                                        </li>

                                        @foreach($sub_appointments ?? [] as $sub)
                                            <li class="barberShop__bookingInfo__billing__list__item">
                                                <span>{{ $sub->title }} <small class="text-success">({{__('Additional')}})</small> </span> <span class="price">{{ amount_with_currency_symbol($sub->price) }}</span>
                                            </li>
                                        @endforeach
                                    </ul>

                                    @php
                                        $additional_appointment_subtotal_amount = count($sub_appointments) > 0 ? array_sum($sub_appointments->pluck('price')->toArray()) : 0;
                                        $subtotal = $order_details->price + $additional_appointment_subtotal_amount;
                                        $tax_show_status = get_static_option('appointment_tax_apply_status');
                                        $tax_amount = !empty($tax_show_status) ? get_appointment_tax_amount($order_details->id,$subtotal) : 0;
                                        $total_price = $subtotal + $tax_amount;
                                    @endphp


                                        {{--Passing Hidden Values--}}
                                          <input type="hidden" name="appointment_id" class="appointment_id" value="{{ $order_details->id }}">
                                          <input type="hidden" name="subtotal" class="subtotal" value="{{ $subtotal }}">
                                          <input type="hidden" name="tax_amount" class="tax_amount" value="{{ $tax_amount }}">
                                          <input type="hidden" name="total_amount" class="total_amount" value="{{ $total_price }}">
                                        {{--Passing Hidden Values--}}

                                    <ul class="barberShop__bookingInfo__billing__list">
                                        <li class="barberShop__bookingInfo__billing__list__item">
                                            <span>{{__('Sub Total')}}</span>
                                            <span class="price">
                                                <strong>{{ amount_with_currency_symbol($subtotal) }}</strong>
                                            </span>
                                        </li>

                                        @if(!empty($tax_show_status))
                                             <li class="barberShop__bookingInfo__billing__list__item">
                                                 <span>{{__('Tax')}} {!! get_appointment_tax_amount_percentage($order_details->id) !!}</span>
                                                 <span class="price"> {{amount_with_currency_symbol($tax_amount)}}</span>
                                             </li>
                                        @endif
                                    </ul>
                                    <ul class="barberShop__bookingInfo__billing__list">
                                        <li class="barberShop__bookingInfo__billing__list__item"><span>{{__('Total')}}</span> <span class="price"><strong>{{ amount_with_currency_symbol($total_price) }}</strong></span></li>
                                    </ul>

                                    <div class="payment_container padding-top-50">
                                        {!! render_payment_gateway_for_form() !!}
                                    </div>

                                    <div class="btn_wrapper mt-4">
                                        <button type="submit" class="barberShop_cmn_btn btn_bg_1 w-100">{{ get_static_option('appointment_payment_page_right_pay_button_'.get_user_lang().'_text') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
         </div>
      </section>
   </main>
@endsection





