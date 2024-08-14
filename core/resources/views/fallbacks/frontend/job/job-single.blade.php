@extends('tenant.frontend.frontend-page-master')

@php
    $post_img = null;
    $user_lang = get_user_lang();
@endphp

@section('page-title')
    {{ $job->getTranslation('title',$user_lang) }}
@endsection

@section('title')
    {{ $job->getTranslation('title',$user_lang) }}
@endsection

@section('meta-data')
    {!!  render_page_meta_data($job) !!}
@endsection

@section('content')

    <div class="jobDetails section-padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="singleJobDetails mb-24">
                        <div class="company">
                            <!-- Clients -->
                            <div class="companyCap">
                                <div class="companyLogo">
                                    {!! render_image_markup_by_attachment_id($job->image) !!}
                                </div>
                                <div class="companyTitle">
                                    <span class="title">{{$job->getTranslation('title',$user_lang)}} </span>
                                    <div class="d-flex align-items-center">
                                        <p class="c-title">{{$job->getTranslation('company_name',$user_lang)}}</p>
                                        <span class="location"><i class="fa-solid fa-location-dot icon"></i>{{$job->getTranslation('job_location',$user_lang)}}</span>
                                    </div>
                                    <p class="pera">{{ $job->created_at?->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- job requirement -->
                    <ul class="jobRequirement mb-40">
                        <li class="listItem"><span>{{get_static_option('job_experience_area_'.get_user_lang().'_title',__('Experience'))}} : </span> {{ $job->experience }}</li>
                        <li class="listItem"><span>{{get_static_option('job_employee_type_area_'.get_user_lang().'_title',__('Employee type'))}} : </span> {{ \Modules\Job\Enums\EmployeeTypeEnum::getText($job->employee_type) }}</li>
                        <li class="listItem"><span>{{get_static_option('job_salary_offer_area_'.get_user_lang().'_title',__('Salary offer'))}} : </span> {{ amount_with_currency_symbol($job->salary_offer) }}</li>
                        <li class="listItem"><span>{{get_static_option('job_working_days_area_'.get_user_lang().'_title',__('Working days'))}} : </span> {{ $job->working_days }}</li>
                        <li class="listItem"><span>{{get_static_option('job_working_type_area_'.get_user_lang().'_title',__('Working type'))}} : </span> {{ \Modules\Job\Enums\WorkingTypeEnum::getText($job->working_type) }}</li>
                        <li class="listItem"><span>{{get_static_option('job_location_area_'.get_user_lang().'_title',__('Job Location'))}} :  </span> {{ $job->getTranslation('job_location',$user_lang) }}</li>
                        <li class="listItem"><span>{{get_static_option('job_designation_area_'.get_user_lang().'_title',__('Job Designation'))}}: </span> {{ $job->getTranslation('designation',$user_lang) }}</li>
                        <li class="listItem"><span>{{get_static_option('job_deadline_area_'.get_user_lang().'_title',__('Deadline'))}} : </span> {{ $job->deadline }}</li>

                    </ul>

                    <div class="description mb-40">
                        {!! $job->getTranslation('description',$user_lang) !!}
                    </div>

                    <!-- job requirement -->
                    <ul class="jobRequirement mb-40">
                        @if(!empty(get_static_option('job_application_fee_show_hide')))
                            @if(!empty($job->application_fee_status))
                                <li class="listItem text-primary"><strong class="text-primary">{{get_static_option('job_application_fee_area_'.get_user_lang().'_title',__('Application Fee'))}}  : </strong> {{ amount_with_currency_symbol($job->application_fee) }}</li>
                            @endif
                        @endif

                    </ul>

                    <div class="btn-wrapper d-flex flex-wrap mb-30">
                        @if(!empty(get_static_option('job_apply_show_hide')))
                            <a href="{{ route('tenant.frontend.job.payment',$job->slug) }}" class="cmn-btn4 hero-btn2 mr-15 mb-10">{{get_static_option('job_apply_area_'.get_user_lang().'_title',__('Apply'))}}</a>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
    @if(!empty(get_static_option('job_related_area_show_hide')))
            <x-job::frontend.job.related-job :allRelatedJobs="$all_related_jobs"/>
    @endif
@endsection


@section('scripts')
    @yield("custom-ajax-scripts")

@endsection
