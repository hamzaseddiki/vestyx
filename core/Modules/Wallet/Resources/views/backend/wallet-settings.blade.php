@extends('landlord.admin.admin-master')

@section('title')
    {{__('Wallet Settings')}}
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
                                <h4 class="header-title">{{__('Wallet Settings')}}</h4>
                            </div>
                        </div>
                        <form class="forms-sample" method="post" action="{{route('landlord.admin.wallet.settings')}}">
                            @csrf

                            <x-fields.switcher value="{{get_static_option('user_wallet')}}" name="user_wallet" label="{{__('Enable/Disable Wallet for User')}}" info="{{__('Keep No to disable the wallet for all users')}}"/>

                            <button type="submit" class="btn btn-gradient-primary mt-5 me-2">{{__('Save Changes')}}</button>
                        </form>
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

