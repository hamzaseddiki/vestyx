@extends('landlord.frontend.user.dashboard.user-master')

@section('title')
    {{ __('Wallet') }}
@endsection

@section('page-title')
    {{ __('Wallet') }}
@endsection

@section('style')
    <style>
        .deposit-button{
            background-color: var(--main-color-one);
            border: var(--main-color-two);
            padding: 10px 10px;
        }
        .deposit-button:hover{
            background-color: var(--main-color-one);
            border: var(--main-color-one);
        }

        .deposit-button i{
            font-size: 23px;
            margin-right: 10px;
            vertical-align: text-bottom;
        }

        .confirm-bottom-content{
            margin-top: 30px;
        }
        .payment-gateway-wrapper ul{
            display: flex;
            flex-wrap: wrap;
        }
        .payment-gateway-wrapper li{
            display: flex;
            margin-left: 10px;
        }

        .payment-gateway-wrapper li.selected{
            border: 3px solid var(--main-color-two);
            border-radius: 5px;
        }
        .payment-gateway-wrapper li {
            width: calc(100%/4);
        }
        .payment-gateway-wrapper ul {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin: 0;
            padding: 0;
            flex-basis: 100%;
            width: 100%;
            margin-left: 30px;
        }
        .deposit-table tbody tr td:nth-child(2), .deposit-table tbody tr td:nth-child(3){
            text-transform: capitalize;
        }

        .payment_attachment{
            width: 100px;
        }
    </style>
@endsection

@section('section')
    <!-- Dashboard area Starts -->
    <div class="single-orders">
        <div class="orders-flex-content">
            <div class="icon">
                <i class="las la-dollar-sign"></i>
            </div>
            <div class="contents">
                <h2 class="order-titles">
                    @if(empty($balance->balance))
                        {{ float_amount_with_currency_symbol(0.00) }}
                    @else
                        {{ float_amount_with_currency_symbol($balance->balance) }}
                    @endif
                </h2>
                <span class="order-para">{{ __('Wallet Balance') }} </span>
            </div>
        </div>
    </div>

    <div class="dashboard-settings margin-top-55 d-flex justify-content-between">
        <div>
            <h2 class="dashboards-title">{{ __('Wallet History') }} </h2>
            <div class="notice-board">
                <p class="text-primary">{{ __('You can deposit to your wallet from here.') }}</p>
            </div>
        </div>

        <div class="dashboard-settings mt-4 text-end">
            <button type="button" class="btn btn-primary deposit-button" data-bs-toggle="modal"
                    data-bs-target="#payoutRequestModal"><i class="las la-wallet"></i> {{ __('Deposit To Your Wallet') }}</button>
        </div>
    </div>

    <div class="single-dashboard-order mt-5">
        <div class="table-responsive table-responsive--md">
            <table class="custom--table deposit-table">
                <thead>
                <tr>
                    <th> {{ __('ID') }}</th>
                    <th> {{ __('Payment Gateway') }} </th>
                    <th> {{ __('Payment Status') }} </th>
                    <th> {{ __('Deposit Amount') }} </th>
                    <th> {{ __('Deposit Date') }} </th>
                    <th> {{ __('Payment Image') }} </th>
                </tr>
                </thead>
                <tbody>
                @foreach($wallet_histories as $history)
                    <tr>
                        <td data-label="{{ __('ID') }}">{{ $history->id }} </td>
                        <td data-label="{{ __('Payment Gateway') }}">
                            @php
                                $payment_gateway = str_replace('_', ' ', $history->payment_gateway);
                            @endphp
                            {{ $payment_gateway }}
                        </td>
                        <td data-label="{{ __('Payment Status') }}">{{ $history->payment_status }}</td>
                        <td data-label="{{ __('Request Amount') }}"> {{ float_amount_with_currency_symbol($history->amount) }} </td>
                        <td data-label="{{ __('Request Date') }}">{{ $history->created_at->diffForHumans() }} </td>
                        <td data-label="{{ __('Request Date') }}">
                            @if(empty($history->manual_payment_image))
                                {{ __('No Image') }}
                            @else
                                <img class="rounded payment_attachment"
                                     src="{{ asset('assets/landlord/uploads/deposit_payment_attachments/'.$history->manual_payment_image) }}"
                                     alt="payment-image">
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="blog-pagination margin-top-55">
            <div class="custom-pagination mt-4 mt-lg-5">
                {!! $wallet_histories->links() !!}
            </div>
        </div>

    </div>



    <!--Status Modal -->
    <div class="modal fade" id="payoutRequestModal" tabindex="-1" role="dialog" aria-labelledby="editModal"
         aria-hidden="true">
        <form action="{{ route('landlord.user.wallet.deposit') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary"
                            id="couponModal">{{ __('You can deposit to your wallet from the available payment gateway.') }}</h5>
                        <button type="button" class="close btn-danger" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="">{{ __('Deposit Amount') }}</label>
                        <input type="number" class="form-control mt-2" name="amount"
                               placeholder="{{ __('Enter Deposit Amount') }}">
                        <div class="confirm-bottom-content">
                            <div class="confirm-payment payment-border">
                                <div class="single-checkbox">
                                    <div class="checkbox-inlines">
                                        <label class="checkbox-label" for="check2">
                                            {!! render_payment_gateway_for_form() !!}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Submit Deposit') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/backend/js/sweetalert2.js') }}"></script>
    <x-wallet-payment-gateway-js/>
    <script>
        (function ($) {
            "use strict";

            $(document).ready(function () {
                $(document).on('click', '.edit_status_modal', function (e) {
                    e.preventDefault();
                    let order_id = $(this).data('id');
                    let status = $(this).data('status');

                    $('#order_id').val(order_id);
                    $('#status').val(status);
                    $('.nice-select').niceSelect('update');
                });


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

        })(jQuery);
    </script>
@endsection
