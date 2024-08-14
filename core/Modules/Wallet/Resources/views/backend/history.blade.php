@extends('landlord.admin.admin-master')

@section('title')
    {{__('History Lists')}}
@endsection
@section('style')
    <x-media-upload.css/>
    <style>
        .payment_attachment{
            width: 100px;
        }
    </style>
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
                <x-error-msg/>
                <x-flash-msg/>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="header-wrap d-flex justify-content-between">
                            <div class="left-content">
                                <h4 class="header-title">{{__('Wallet History Lists')}}</h4>
                                <p class="text-primary mb-3">{{ __('All user\'s wallet history lists') }}</p>
                            </div>
                        </div>
                        <div class="table-wrap table-responsive">
                            <table class="table table-default" id="all_jobs">
                                <thead>
                                <th>{{__('#No')}}</th>
                                <th>{{__('User Details')}}</th>
                                <th>{{__('Payment Gateway')}}</th>
                                <th>{{__('Payment Status')}}</th>
                                <th>{{__('Amount')}}</th>
                                <th>{{__('Manual Payment Image')}}</th>
                                </thead>
                                <tbody>
                                @forelse($wallet_history_lists as $key=>$data)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>
                                            <ul>
                                                <li><strong>{{__('Username')}}: </strong>{{optional($data->user)->username}}</li>
                                                <li><strong>{{__('Name')}}: </strong>{{optional($data->user)->name}}</li>
                                                <li><strong>{{__('Email')}}: </strong>{{ optional($data->user)->email}}</li>
                                                <li><strong>{{__('Phone')}}: </strong>{{ optional($data->user)->mobile }}</li>
                                            </ul>
                                        </td>
                                        <td>
                                            @php
                                                $gateway = str_replace('_', ' ',$data->payment_gateway);
                                            @endphp
                                            {{ ucwords($gateway) }}
                                        </td>
                                        <td>
                                            @php
                                                $payment_status = ['pending' => 'text-warning', 'complete' => 'text-success']
                                            @endphp
                                            <span class="{{$payment_status[$data->payment_status]}}">
                                                {{ ucfirst($data->payment_status) }}
                                            </span>

                                            @if($data->payment_status == 'pending')
                                                <span class="mx-2">
                                                    <x-status-change :url="route('landlord.admin.wallet.history.status',$data->id)"/>
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ float_amount_with_currency_symbol($data->amount) }}</td>
                                        <td>
                                            @if($data->manual_payment_image)
                                                <img class="rounded payment_attachment" src="{{ asset('assets/landlord/uploads/deposit_payment_attachments/'.$data->manual_payment_image) }}" alt="payment-image">
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center py-4" colspan="6">{{__('No Data Available')}}</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <x-media-upload.js/>
    <script>
        (function ($) {
            "use strict";
            $(document).ready(function () {
                $(document).on('click','.swal_status_change',function(e){
                    e.preventDefault();
                    Swal.fire({
                        title: '{{__("Are you sure to change status?")}}',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, change it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $(this).next().find('.swal_form_submit_btn').trigger('click');
                        }
                    });
                });
            });
        })(jQuery)
    </script>
@endsection

