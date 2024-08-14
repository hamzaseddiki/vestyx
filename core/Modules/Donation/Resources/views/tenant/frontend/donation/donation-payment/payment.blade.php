@extends('tenant.frontend.frontend-page-master')

@section('title')
   {{__('Donation Payment ')}} : {{ $donation->getTranslation('title',get_user_lang())}}
@endsection

@section('page-title')
    {{__('Donation Payment ')}} : {{ $donation->getTranslation('title',get_user_lang())}}
@endsection

@section('content')
    <div class="packageArea section-padding">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-7 col-md-6 packageWrapper mb-40">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="btn-wrapper mb-20">
                                <a href="{{route('tenant.dynamic.page','donation') ?? url('/') }}" class="cmn-btn-outline3"> {{__('Go Back')}} </a>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="col-lg-12">
                                @php
                                    $custom_amounts = get_static_option('donation_custom_amount');
                                    $explode_custom_amounts = explode(',',$custom_amounts);
                                    $default_amount = amount_with_currency_symbol(get_static_option('donation_default_amount'));
                                    $default_amount_without_currency = get_static_option('donation_default_amount');
                                    $auth_user = auth()->guard('web');
                                @endphp
                                <ul class="selectPricing mb-20">
                                    @foreach($explode_custom_amounts ?? [] as $amount)
                                        <li class="listItem" data-amount="{{$amount}}">{{ amount_with_currency_symbol($amount) }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <x-error-msg/>

                        <form action=" {{route('tenant.frontend.donation.payment.form')}}" method="post" enctype="multipart/form-data">
                            @csrf

                        <input type="hidden" name="donation_id" value="{{ $donation->id }}">

                        <div class="col-lg-12 col-md-12">
                            <label class="catTittle"> {{__('Amount')}}</label>
                            <div class="input-form input-form2">
                                <input type="number" name="amount" placeholder="" min="1" value="{{ $default_amount_without_currency }}" class="left_default_amount">
                            </div>
                        </div>

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

                        <div class="col-sm-12">
                            <div class="btn-wrapper">
                                <button type="submit" class="cmn-btn1 hero-btn">{{__('Donate Now')}} <i class="fas fa-heart icon ZoomTwo"></i></button>
                            </div>
                        </div>

                        </form>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-5 col-md-6">
                    <div class="paymentDetails mb-24">
                        <h4 class="priceTittle mb-30">{{__('Your Donation Details')}}</h4>
                        <div class="donationPostUser mb-30">
                            {!! render_image_markup_by_attachment_id($donation->image) !!}
                            <div class="cap">
                                <a href="#" class="tittle">{{ $donation->getTranslation('title',get_user_lang()) }}</a>
                                <p>{{$donation->creator?->name}}</p>
                            </div>
                        </div>
                        <div class="priceListing">
                            <ul class="listing">
                                <li class="listItem"><p class="leftCap">{{__('Your Donation')}}</p> <p class="rightCap total_donation_amount">??</p></li>
                                <li class="listItem"><p class="leftCap rightCap">{{__('Total')}}</p> <p class="rightCap total_donation_amount">??</p></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        $(document).ready(function(){

            let price_right_parent = $('.priceListing');
            let left_default_amount = $('.left_default_amount').val();
                price_right_parent.find('.total_donation_amount').text('{{ site_currency_symbol() }}'+ left_default_amount);

                $(document).on('click','.selectPricing li',function(){
                    let el = '{{ site_currency_symbol() }}' + $(this).data('amount');
                    price_right_parent.find('.total_donation_amount').text(el);
                    $('.left_default_amount').val($(this).data('amount'))
                });

            $(document).on('keyup','.left_default_amount ',function(){
                let el = $(this).val();
                price_right_parent.find('.total_donation_amount').text('{{ site_currency_symbol() }}'+ el);
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
        });
    </script>
@endsection


