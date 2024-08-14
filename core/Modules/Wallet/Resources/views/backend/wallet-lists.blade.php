@extends('landlord.admin.admin-master')

@section('title')
    {{__('Wallet Lists')}}
@endsection

@section('style')
    <x-media-upload.css/>
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
                                <h4 class="header-title">{{__('Wallet Lists')}}</h4>
                                <p class="text-primary mb-3">{{ __('You can active inactive status from here. If status is inactive user will not be able to use his/her wallet balance.') }}</p>
                            </div>
                        </div>
                        <div class="table-wrap table-responsive">
                            <table class="table table-default" id="all_jobs">
                                <thead>
                                <th>{{__('#No')}}</th>
                                <th>{{__('User Details')}}</th>
                                <th>{{__('Wallet Balance')}}</th>
                                <th>{{__('Status')}}</th>
                                </thead>
                                <tbody>
                                @forelse($wallet_lists ?? [] as $data)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>
                                            <ul>
                                                <li><strong>{{__('Name')}}: </strong>{{optional($data->user)->name}}</li>
                                                <li><strong>{{__('Email')}}: </strong>{{ optional($data->user)->email}}</li>
                                                <li><strong>{{__('Phone')}}: </strong>{{ optional($data->user)->mobile }}</li>
                                            </ul>
                                        </td>
                                        <td>{{ float_amount_with_currency_symbol($data->balance) }}</td>
                                        <td>
                                            @php
                                                $status = ['text-danger', 'text-success'];
                                            @endphp
                                            <strong>{{__('Wallet Status')}}:</strong>
                                            <span class="{{$status[$data->status]}}">{{ $data->status == 0 ? __('Inactive') : __('Active') }}</span>
                                            <span class="mx-2">
                                                <x-status-change :url="route('landlord.admin.wallet.status',$data->id)"/>
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center py-4" colspan="4">{{__('No Data Available')}}</td>
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

