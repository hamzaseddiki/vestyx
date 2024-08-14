@extends('tenant.frontend.frontend-page-master')
@php
    $post_img = null;
    $user_lang = get_user_lang();
    $data = session('data');
@endphp

@section('page-title')
{{__('Checkout')}}
@endsection

@section('title')
 {{__('Checkout')}}
@endsection

@section('meta-data')

@endsection

@section('style')
    <style>
        .singleBlog-details .blogCaption .cartTop {
            margin-bottom: 16px;
        }
        .singleBlog-details .blogCaption .cartTop .listItmes {
            display: inline-block;
            margin-right: 10px;
            font-size: 16px;
            font-weight: 300;
        }
        .singleBlog-details .blogCaption .cartTop .listItmes .icon {
            color: var(--peragraph-color);
            margin-right: 10px;
        }
    </style>
@endsection

@section('content')
    @php
        $lang_slug = request()->get('lang') ?? \App\Facades\GlobalLanguage::default_slug();
    @endphp

    <section class="Checkout-area section-bg-2 pat-100 pab-100">
        <div class="container">
            <x-error-msg/>
            <x-flash-msg/>
            <form action="{{route('tenant.frontend.hotel-booking.payment.store')}}" method="post">
                @csrf
            <div class="row g-4">
                <div class="col-xl-7 col-lg-7">
                    <div class="checkout-wrapper">
                        <div class="checkout-single bg-white radius-10">
                            <h4 class="checkout-title">   {{__('Booking Information Checkout')}}</h4>
                            <div class="checkout-contents mt-3">
                                <div class="checkout-form custom-form">
                                        <div class="input-flex-item">
                                            <div class="single-input mt-4">
                                                <label class="label-title">  {{__('First Name')}}</label>
                                                <input class="form--control" value="{{old('name')}}" type="text" name="name" placeholder="{{__('Type your first name')}}">
                                            </div>
                                            <div class="single-input mt-4">
                                                <label class="label-title"> {{__('Last Name')}}</label>
                                                <input class="form--control" type="text" value="{{old('lname')}}" name="lname" placeholder="{{__('Type your last name')}}">
                                            </div>
                                        </div>
                                        <div class="input-flex-item">
                                            <div class="single-input mt-4">
                                                <label class="label-title"> {{__('Mobile Number')}}</label>
                                                <input class="form--control" name="mobile" id="mobile" type="tel" value="{{old('mobile')}}" placeholder="{{__('Type Mobile Number')}}">
                                            </div>
                                            <div class="single-input mt-4">
                                                <label class="label-title">  {{__('Email Address')}}</label>
                                                <input class="form--control" type="email" name="email" value="{{old('email')}}" placeholder="{{__('Type Email')}}">
                                            </div>
                                        </div>

                                        <div class="single-input mt-4">
                                            <label class="label-title">  {{__('Country')}}</label>
                                            <div class="banner-location-single-contents-dropdown">
                                                <select name="country_id" id="country" value="{{old('country_id')}}" class="form-control nice-select w-100">
                                                    <option value=""> {{__('Select Country')}}</option>
                                                    @foreach($data['countries'] as $country)
                                                        <option value="{{ $country->id }}" {{ $country->id == old("country") ?? 0 ? "selected" : "" }}>{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="input-flex-item">
                                            <div class="single-input mt-4">
                                                <label class="label-title">  {{__('State')}}</label>
                                                <div class="banner-location-single-contents-dropdown">
                                                    <select name="city_id"  value="{{old('city_id')}}" id="city_id" class="form-control w-100">
                                                        <option value=""> {{__('Select City')}}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="single-input mt-4">
                                                <label class="label-title">  {{__('City/Town')}}</label>
                                                <input class="form--control" type="text" value="{{old('street')}}" name="street" placeholder="{{__('Type street')}}">
                                            </div>

                                            <div class="single-input mt-4">
                                                <label class="label-title">  {{__('Street')}}</label>
                                                <input class="form--control" type="text" value="{{old('street')}}" name="street" placeholder="{{__('Type street')}}">
                                            </div>
                                        </div>
                                    <div class="single-input mt-4">
                                        <label class="label-title">  {{__('Post Code')}}</label>
                                        <input class="form--control" type="text" name="post_code" value="{{old('post_code')}}" placeholder=" {{__('Type post Code')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="checkout-form custom-form">
                            <input class="text" type="hidden" name="booking_date" value="{{$data['from_date']}}">
                            <input class="text" type="hidden" name="booking_expiry_date" value="{{$data['to_date']}}">
                            <input class="text" type="hidden" name="room_type_id" value="{{$data['room_type_id']}}">
                            <input class="text" type="hidden" name="room_id" value="{{$data['room_id']}}">
                            <input class="text" type="hidden" name="hotel_id" value="{{$data['hotel_id']}}">
                            <input class="text" type="hidden" name="amount" value="{{$data['total_amount']}}">
                            <input class="text" type="hidden" name="lang" value="{{$lang_slug}}">
                            <input class="text" type="hidden" name="request_from" value="frontend">
                        </div>
                    </div>
                </div>
                <div class="col-xl-5 col-lg-5">
                    <div class="barberShop__bookingInfo__billing">
                        <div class="barberShop__bookingInfo__billing__item checkout-widget checkout-widget-padding widget bg-white radius-10">
                            <div class="barberShop__bookingInfo__billing__header">
                                <div class="checkout-sidebar">
                                    <h4 class="checkout-sidebar-title">  {{__('Booking Room Details')}}</h4>
                                    <div class="checkout-sidebar-contents">
                                        <ul class="checkout-flex-list list-style-none checkout-border-top pt-3 mt-3">
                                            <li class="list"> <span class="regular"> {{__('Checking In')}} </span> <span class="strong"> {{$data['from_date']}} </span> </li>
                                            <li class="list"> <span class="regular"> {{__('Checking Out')}} </span> <span class="strong">{{$data['to_date']}} </span> </li>
                                            <li class="list"> <span class="regular"> {{__('Number of Rooms')}} </span> <span class="strong"> {{$data['room']}} {{__('Rooms')}}</span> </li>
                                            <li class="list"> <span class="regular"> {{__('Number of Person')}} </span> <span class="strong"> {{$data['person']}} {{__('Person')}}</span> </li>
                                            <li class="list"> <span class="regular"> {{__('Number of Children')}} </span> <span class="strong"> {{$data['children']}} {{__('Children')}}</span> </li>
                                            <li class="list"> <span class="regular"> {{__('Person Max Capacity')}} </span> <span class="strong"> {{$data['room_details']->room_types->max_adult}}  {{__('Person')}}</span> </li>
                                            <li class="list"> <span class="regular">  {{__('Children Max Capacity')}}</span> <span class="strong"> {{$data['room_details']->room_types->max_child}} {{__('Children')}}  </span> </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="checkout-widget checkout-widget-padding widget bg-white radius-10">
                                <div class="checkout-sidebar">
                                    <h4 class="checkout-sidebar-title">  {{__('Invoice')}}</h4>
                                    <div class="checkout-sidebar-contents">
                                        <ul class="checkout-flex-list list-style-none checkout-border-top pt-3 mt-3">
                                            <li class="list"> <span class="regular">  {{__('Charge')}}</span> <span class="strong"> {{ amount_with_currency_symbol($data['room_details']->room_types->base_charge) }} </span> </li>
{{--                                            <li class="list"> <span class="regular">  {{__('Discount')}}</span> <span class="strong"> -$0 </span> </li>--}}
{{--                                            <li class="list"> <span class="regular">  {{__('Vat')}}</span> <span class="strong"> (0) $0 </span> </li>--}}
                                        </ul>
                                        <ul class="checkout-flex-list list-style-none checkout-border-top pt-3 mt-3">
                                            <li class="list"> <span class="regular">  {{__('Total')}}</span> <span class="strong color-one fs-20">{{ amount_with_currency_symbol($data['total_amount']) }} </span> </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="barberShop__bookingInfo__billing__inner">
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

@endsection

@section('scripts')
    @yield("custom-ajax-scripts")

    <script>
        $(document).ready(function(){
            $('select[name="country_id"]').on('change',function(){
                var country_id = $(this).val();
                if(country_id){
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type:"GET",
                        dataType:"json",
                        url:"/bookings/cities/"+country_id,
                        success:function(data){
                            if(!data.length > 0){
                                CustomSweetAlertTwo.error('{{ __("This country have no city") }}')
                            }
                            $('select[name="city_id"]').empty();
                            $('#city_id').html('<option value="">Select city</option>');
                            $.each(data, function(key, value){
                                $('select[name="city_id"]').append('<option value ="'+ value.id + '">' + value.name + '</option>');
                            });
                        },
                    });
                }else{
                    CustomSweetAlertTwo.error('{{ __("Country not found") }}')
                }
            });
        });
    </script>

    <script>
        $(document).ready(function(){

            // calling payment method list
            $(document).on('click', '.payment-gateway-list > li', function (e) {
                e.preventDefault();
                let gateway = $(this).data('gateway');
                if (gateway === 'kinetic') {
                    $('.kinetic_payment_field').removeClass('d-none');
                } else {
                    $('.kinetic_payment_field').addClass('d-none');

                }
                $(this).addClass('selected').siblings().removeClass('selected');
                $('.payment-gateway-list').find(('input')).val($(this).data('gateway'));
                $('.payment_gateway_passing_clicking_name').val(gateway);
            });
        });
    </script>

@endsection
