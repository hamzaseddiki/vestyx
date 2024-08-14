@extends('tenant.frontend.frontend-page-master')
@section('title')
    {{__('Payment Success For:')}} {{$job->getTranslation('title',get_user_lang())}}
@endsection

@section('page-title')
    {{__('Payment Success For:')}} {{$job->getTranslation('title',get_user_lang())}}
@endsection


@section('style')

    <style>
        /* ticket style */
        .ticket{
            max-width: 800px;
            background: #fafafa;
            padding: 80px 60px 50px 60px;
            margin: 0 auto;
        }
        .ticket .title{
            color: #000;
            font-size: 20px;
            display: block;
            font-weight: 500;
            margin-bottom:25px;
        }
        .ticket .mainTitle{
            color: #000;
            font-size: 48px;
            display: block;
            font-weight: 600;
            margin-bottom:50px;
        }
        .ticketDiscription .pera{
            color: #000;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
        }
        .ticketDiscription .barcode{
            max-width:90px;
            border-radius: 8px;
        }
        .peraMargin{
            margin-bottom: 35px !important;
        }
        .ticket table {
            width: 100%;
            display: block;
        }
        .ticket tbody {
            width: 100%;
            display: block;
        }
        .ticket tbody tr {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }
        .ticket tbody tr td {
            width: 42%;
        }
    </style>
@endsection

@section('content')

    <div class="confirmationArea section-padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-10">
                    <div class="confirmationWrapper text-center">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- Check Mark icon -->
                                <div class="success-checkmark">
                                    <div class="check-icon">
                                        <span class="icon-line line-tip"></span>
                                        <span class="icon-line line-long"></span>
                                        <div class="icon-circle"></div>
                                        <div class="icon-fix"></div>
                                    </div>
                                </div>
                                <div class="confirmInfo mb-50 mt-40">
                                    <h2 class="confirm">{{__('Job application submited successfully')}}</h2>
                                    <h6 class="info">{{__('Application No')}}</h6>
                                    <p class="pera">{{ $job_logs->id }}</p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="confirmInfo mb-40">
                                    <h6 class="info">{{__('Application Date')}}</h6>
                                    <p class="pera">{{__('Date')}}: {{$job->created_at?->format('d-m-Y')}}</p>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="confirmInfo mb-40">
                                    <h6 class="info">{{__('Job Title ')}}</h6>
                                    <p class="pera">{{$job->getTranslation('title',get_user_lang())}}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="confirmInfo mb-40">
                                    <h6 class="info">{{__('Job Location')}}</h6>
                                    <p class="pera">{{$job->getTranslation('job_location',get_user_lang())}}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="confirmInfo mb-40">
                                    <h6 class="info">{{__('Deadline')}}</h6>
                                    <p class="pera">{{$job->deadline}}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="confirmInfo mb-40">
                                    <h6 class="info">{{__('Application Fee')}}</h6>
                                    <p class="pera">{{ amount_with_currency_symbol($job_logs->amount) }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="confirmInfo mb-40">
                                    <h6 class="info">{{__('Company Name')}}</h6>
                                    <p class="pera">{{ $job->getTranslation('company_name',get_user_lang()) }}</p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="btn-wrapper pt-40">
                                    @if(auth()->guard('web')->check())
                                        <a  href="{{ route('tenant.user.home') }}" class="btn btn-dark hero-btn w-100 mt-3">{{__('GO TO DASHBOARD')}}</a>
                                    @else
                                        <a  href="{{url('/')}}" class="btn btn-dark hero-btn w-100 mt-3">{{__('GO TO HOME')}}</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

@endsection
