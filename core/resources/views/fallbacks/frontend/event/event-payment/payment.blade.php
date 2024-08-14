@extends('tenant.frontend.frontend-page-master')

@section('title')
   {{__('Even Ticket Booking ')}} : {{ $event->getTranslation('title',get_user_lang())}}
@endsection

@section('page-title')
    {{__('Even Ticket Booking ')}} : {{ $event->getTranslation('title',get_user_lang())}}
@endsection

@section('content')
    <div class="PaymentArea section-padding">
        <div class="container">
            @php
                $auth_user_check = auth()->guard('web')->check();
                $auth_user = auth()->guard('web')->user();
            @endphp

            <x-flash-msg/>
            <x-error-msg/>

           <form action="{{route('tenant.frontend.event.payment.form')}}" class="order-form event_order_form" method="post" enctype="multipart/form-data">
             @csrf
            <div class="row g-4">
                <div class="col-xl-7 col-lg-7 col-md-6 packageWrapper">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <label class="catTittle"> {{__('Name')}}</label>
                            <div class="input-form input-form2">
                                <input type="text" name="name" placeholder="Enter your name here" value="{{ $auth_user_check ? $auth_user->name : '' }}">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <label class="catTittle"> {{__('Email')}}</label>
                            <div class="input-form input-form2">
                                <input type="email" name="email" placeholder="Enter your email here" value="{{ $auth_user_check ? $auth_user->email : '' }}">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <label class="catTittle"> {{__('Phone')}}</label>
                            <div class="input-form input-form2">
                                <input type="number" name="phone" placeholder="Enter your phone number" value="{{ $auth_user_check ? $auth_user->mobile  : ''}}">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <label class="catTittle"> {{__('Address')}}</label>
                            <div class="input-form input-form2">
                                <input type="text" name="address" placeholder="Enter your address" value="{{ $auth_user_check ? $auth_user->address : '' }}">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <label class="catTittle"> {{__('Number of ticket')}}</label>
                            <div class="countWrap">
                                <div class="numberCount">
                                    <div class="value-button minus decrease qty_minus"><i class="las fa-minus qty_minus"></i></div>
                                    <input type="text" id="quantity" class="qty_ qty_input" value="1" max="5">
                                    <div class="value-button plus increase qty_plus"><i class="las fa-plus qty_plus"></i></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12">
                            <label class="catTittle"> {{__('Order Note (optional)')}} </label>
                            <div class="input-form input-form2">
                                <textarea placeholder="write anything" name="note"></textarea>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-xl-5 col-lg-5 col-md-6">
                    <div class="paymentDetails mb-24">
                        <h4 class="priceTittle mb-30">{{__('Booking Details')}}</h4>
                        <div class="priceListing mb-50">
                            <ul class="listing">
                                <li class="listItem"><p class="leftCap">{{__('Ticket Price')}}</p> <p class="rightCap "> {{ site_currency_symbol()}}<span class="ticket_price">{{ $event->cost }}</span> </p></li>
                                <li class="listItem"><p class="leftCap">{{__('Quantity')}}</p> <p class="rightCap"><span class="ticket_qty">{{1}}</span></p></li>
                                <li class="listItem"><p class="leftCap">{{__('Total')}}</p> <p class="rightCap">{{ site_currency_symbol() }}<span class="total_amount">{{$event->cost}}</span></p></li>
                            </ul>
                        </div>

                        <input type="hidden" name="event_id" value="{{$event->id}}">
                        <input type="hidden" name="event_total_ticket_qty" class="event_total_ticket_qty" value="1">
                        <input type="hidden" name="event_total_amount" class="event_total_amount" value="{{$event->cost}}">

                            <div class="col-sm-12">
                                <div class="order-form mt-20 mb-40">
                                    {!! render_payment_gateway_for_form() !!}
                                    <div class="form-group manual_payment_transaction_field d-none">
                                        <label class="label mb-2">{{__('Attach Your Bank Document')}}</label>
                                        <input class="form-control btn btn-warning btn-sm" type="file" name="manual_payment_attachment">
                                        <span class="help-info mt-3">{!! get_manual_payment_description() !!}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="btn-wrapper pt-20">
                                <button class="cmn-btn1 hero-btn w-100" type="submit">{{__('Book Ticket')}}</button>
                            </div>
                    </div>
                </div>
             </div>
           </form>
        </div>
    </div>

@endsection


@section('scripts')
    <script>
        $(document).ready(function(){

            let form = $('.event_order_form');
            let ticket_price = form.find('.ticket_price');
            let ticket_qty = form.find('.ticket_qty');
            let total_amount = form.find('.total_amount');

            //passing value to form hidden input
            function get_qty_and_total_amount(total_qty,total_amount)
            {
                form.find('.event_total_ticket_qty').val(total_qty);
                form.find('.event_total_amount').val(total_amount);
            }

            $(document).on('keyup','.qty_input',function(){
                let el = $(this).val();
                    ticket_qty.text(el);
                let calculation = ticket_qty.text() * ticket_price.text();
                total_amount.text(calculation)
                //calling function
                get_qty_and_total_amount(ticket_qty.text(),total_amount.text());
            });

            $(document).on('click','.qty_minus',function(){
                let input_value = $('.qty_input').val();
                ticket_qty.text(input_value);
                let calculation = ticket_qty.text() * ticket_price.text();
                total_amount.text(calculation)
                //calling function
                get_qty_and_total_amount(ticket_qty.text(),total_amount.text());
            });

            $(document).on('click','.qty_plus',function(){
                let input_value = $('.qty_input').val();
                ticket_qty.text(input_value);
                let calculation = ticket_qty.text() * ticket_price.text();
                total_amount.text(calculation)
                //calling function
                get_qty_and_total_amount(ticket_qty.text(),total_amount.text());
            });

            $(document).on('click','.payment-gateway-list .single-gateway-item',function(){
                $('#slected_gateway_from_helper').val($(this).data('gateway'))

                let gateway = $(this).data('gateway');

                if (gateway == 'manual_payment_') {
                    $('.manual_payment_transaction_field').removeClass('d-none');
                } else {
                    $('.manual_payment_transaction_field').addClass('d-none');
                }
            });

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


