@extends('tenant.frontend.frontend-page-master')

@section('title')
   {{__('Job Apply ')}} : {{ $job->getTranslation('title',get_user_lang())}}
@endsection

@section('page-title')
    {{__('Job Apply ')}} : {{ $job->getTranslation('title',get_user_lang())}}
@endsection

@section('style')
    <style>
        .job .payment-gateway-list .single-gateway-item img {
            height: 50px !important;
        }

    </style>
@section('content')
    @php
        $auth_user_check = auth()->guard('web')->check();
        $auth_user = auth()->guard('web')->user();
    @endphp

    <div class="PaymentArea section-padding job">
        <div class="container">
            <div class="row justify-content-center">

                <div class="col-xl-8 col-lg-9">

                    <div class="applyDetails mb-24">

                        <div class="section-tittle mb-30">
                            <h2 class="tittle">{{$job->getTranslation('title',get_user_lang())}}</h2>
                        </div>

                        <x-flash-msg/>
                        <x-error-msg/>

                        <form action="{{route('tenant.frontend.job.payment.form')}}" method="post" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="job_id" value="{{$job->id}}">
                            <input type="hidden" name="amount" value="{{$job->application_fee}}">

                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <label class="catTittle"> {{__('Name')}}</label>
                                    <div class="input-form input-form2">
                                        <input type="text" name="name" value="{{$auth_user_check ? $auth_user->name : ''}}" placeholder="Enter your full name">
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <label class="catTittle">{{__('E-mail')}}</label>
                                    <div class="input-form input-form2">
                                        <input type="email" name="email" value="{{$auth_user_check ? $auth_user->email : ''}}" placeholder="enter your email">
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <label class="catTittle">{{__('Phone')}}</label>
                                    <div class="input-form input-form2">
                                        <input type="number" name="phone" value="{{$auth_user_check ? $auth_user->mobile : ''}}" placeholder="Phone">
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12">
                                    <label class="catTittle"> {{__('Comment (optional)')}} </label>
                                    <div class="input-form input-form2">
                                        <textarea placeholder="write anything" name="comment"></textarea>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12">
                                    <label class="catTittle">{{__('Upload Your Resume')}}</label>

                                    <div class="file_upload">
                                        <input type="file" name="resume" id="file1" class="input-file">
                                        <label for="file1" class="js-labelFile has-file">
                                            <span class="btn_file_upload">{{__('Choose File')}}</span>
                                            <span class="js-fileName"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                        @if(!empty($job->application_fee))
                            <div class="col-lg-12 col-md-12 my-3">
                                <label class="catTittle text-primary">{{__('Application Fee')}} <span> : {{ amount_with_currency_symbol($job->application_fee )}}</span></label>
                            </div>
                            {!! render_payment_gateway_for_form() !!}
                                <div class="form-group manual_payment_transaction_field d-none">
                                    <label class="label mb-2">{{__('Attach Your Bank Document')}}</label>
                                    <input class="form-control btn btn-warning btn-sm" type="file" name="manual_payment_attachment">
                                    <span class="help-info mt-4">{!! get_manual_payment_description() !!}</span>
                                </div>
                         @endif

                            <div class="btn-wrapper pt-30 mb-30">
                                <button class="cmn-btn2 w-100 mb-20" type="submit">{{__('Submit your application')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).on('click','.payment-gateway-list .single-gateway-item',function(){
            $('#slected_gateway_from_helper').val($(this).data('gateway'))

            let gateway = $(this).data('gateway');

            if (gateway == 'manual_payment_') {
                $('.manual_payment_transaction_field').removeClass('d-none');
            } else {
                $('.manual_payment_transaction_field').addClass('d-none');
            }
        });
    </script>
@endsection


