@extends('tenant.frontend.frontend-page-master')
@section('page-title')
    {{__('Order Canceled')}}
@endsection
@section('content')
    <div class="error-page-content padding-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="order-cancel-area">
                        <div class="alert alert-warning">
                            <h5 class="title text-center">{{ __('Your Order Has been canceled') }}</h5>
                        </div>
                        <div class="btn-wrapper mt-5">
                            <a href="{{url('/')}}" class="wedding_cmn_btn btn_gradient_main ">{{__('Back To Home')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
