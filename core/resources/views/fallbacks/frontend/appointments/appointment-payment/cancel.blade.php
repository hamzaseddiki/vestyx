@extends('tenant.frontend.frontend-page-master')
@section('title')
    {{__('You have canceled the payment')}}
@endsection

@section('page-title')
    {{__('Canceled')}}
@endsection

@section('content')
    <div class="error-page-content padding-120 my-5 py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="order-cancel-area">
                        <h1 class="title text-center">{{ __('Appointment Canceled') }}</h1>

                        <div class="btn-wrapper text-center mt-5">
                            <a href="{{url('/')}}" class="barberShop_cmn_btn btn_bg_1">{{__('Back To Home')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
