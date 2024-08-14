@extends(route_prefix().'admin.admin-master')

@section('title')
    {{__('New Job')}}
@endsection

@section('style')
    <link rel="stylesheet" href="{{global_asset('assets/landlord/admin/css/bootstrap-tagsinput.css')}}">
    <link rel="stylesheet" href="{{global_asset('assets/common/css/jquery.timepicker.min.cs')}}">
    <x-summernote.css/>
    <x-media-upload.css/>
    <style>
        .nav-pills .nav-link {
            margin: 8px 0px !important;
        }
        .col-lg-4.right-side-card {
            background: aliceblue;
        }
    </style>
@endsection

@section('content')
    @php
        $lang_slug = request()->get('lang') ?? \App\Facades\GlobalLanguage::default_slug();
    @endphp
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <x-admin.header-wrapper>
                    <x-slot name="left">
                        <h4 class="card-title mb-5">  {{__('New Job')}}</h4>
                    </x-slot>
                    <x-slot name="right" class="d-flex">
                        <form action="{{route(route_prefix().'admin.job.new')}}" method="get">
                            <x-fields.select name="lang" title="{{__('Language')}}">
                                @foreach(\App\Facades\GlobalLanguage::all_languages() as $lang)
                                    <option value="{{$lang->slug}}" @if($lang->slug === $lang_slug) selected @endif>{{$lang->name}}</option>
                                @endforeach
                            </x-fields.select>
                        </form>
                        <p></p>
                        <x-link-with-popover url="{{route('tenant.admin.job')}}" extraclass="ml-3">
                            {{__('All Jobs')}}
                        </x-link-with-popover>
                    </x-slot>
                </x-admin.header-wrapper>

                <x-error-msg/>
                <x-flash-msg/>

                <form class="forms-sample" method="post" action="{{route('tenant.admin.job.new')}}">
                    @csrf
                    <div class="row">

                        <div class="col-md-8">
                            <x-fields.input type="hidden" name="lang" value="{{$lang_slug}}"/>
                            <x-fields.input type="text" name="title" label="{{__('Title')}}" class="title" id="title"  value="{{ old('title') }}"/>
                            <x-slug.add-markup/>
                            <x-summernote.textarea name="description" label="{{__('Job Description')}}"  value="{{ old('description') }}"/>
                            <x-fields.input name="experience" label="{{__('Experience')}}"  value="{{ old('experience') }}"/>
                            <x-fields.input name="designation" label="{{__('Designation')}}"  value="{{ old('designation') }}"/>
                            <x-fields.input name="job_location" label="{{__('Job Location')}}"  value="{{ old('job_location') }}"/>
                            <x-fields.input name="company_name" label="{{__('Company Name')}}"  value="{{ old('company_name') }}"/>

                            <x-job::backend.meta-data.add-meta-markup/>

                        </div>
                        <div class="col-lg-4 right-side-card">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card mt-4">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <x-fields.select name="category_id" title="{{__('Category')}}">
                                                        <option value="" readonly="" >{{__('Select Category')}}</option>
                                                        @foreach($all_category as $cat)
                                                            <option value="{{$cat->id}}">{{$cat->getTranslation('title',$lang_slug)}}</option>
                                                        @endforeach
                                                    </x-fields.select>

                                                    <x-fields.select name="employee_type" title="{{__('Employee Type')}}">
                                                        <option selected disabled >{{__('Select Employee Type')}}</option>
                                                        <option value="{{ \Modules\Job\Enums\EmployeeTypeEnum::MALE }}">{{__('Male')}}</option>
                                                        <option value="{{ \Modules\Job\Enums\EmployeeTypeEnum::FEMALE }}">{{__('Female')}}</option>
                                                        <option value="{{ \Modules\Job\Enums\EmployeeTypeEnum::BOTH }}">{{ __('Male/Female') }}</option>
                                                    </x-fields.select>

                                                    <x-fields.select name="working_type" title="{{__('Working Type')}}">
                                                        <option selected disabled >{{__('Select Working Type')}}</option>
                                                        <option value="{{ \Modules\Job\Enums\WorkingTypeEnum::PART_TIME }}">{{__('Part Time')}}</option>
                                                        <option value="{{ \Modules\Job\Enums\WorkingTypeEnum::FULL_TIME }}">{{__('Full Time')}}</option>
                                                        <option value="{{ \Modules\Job\Enums\WorkingTypeEnum::PROJECT_BASE }}">{{ __('Project Base') }}</option>
                                                    </x-fields.select>

                                                    <x-fields.input type="number" name="salary_offer" label="Salary Offer" value="{{ old('salary_offer') }}"/>
                                                    <x-fields.input type="number" name="working_days" label="Working Days" value="{{ old('working_days') }}"/>

                                                    <x-fields.switcher name="application_fee_status" extra="application_fee_switcher" label="{{__('Apply Fee')}}"/>
                                                    <x-fields.input name="application_fee" extra="application_fee_input d-none" label="{{__('Application Fee')}}"/>
                                                    <x-fields.input type="date" name="deadline" label="Deadline" class="flat_date"/>

                                                    <x-fields.select name="status" class="form-control" id="status" title="{{__('Status')}}">
                                                        <option value="{{\App\Enums\StatusEnums::DRAFT}}">{{__("Draft")}}</option>
                                                        <option value="{{\App\Enums\StatusEnums::PUBLISH}}">{{__("Publish")}}</option>
                                                    </x-fields.select>

                                                    <x-fields.media-upload name="image" title="{{__('Image')}}" dimentions="{{__('1920 X 1280 px image recommended')}}"/>


                                                    <div class="submit_btn mt-5">
                                                        <button type="submit" class="btn btn-gradient-primary pull-right">{{__('Submit ')}}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
    <x-media-upload.markup/>
@endsection

@section('scripts')
    <x-repeater/>
    <script src="{{global_asset('assets/landlord/admin/js/bootstrap-tagsinput.js')}}"></script>
    <script src="{{global_asset('assets/common/js/jquery.timepicker.min.js')}}"></script>
    <x-summernote.js/>
    <x-media-upload.js/>
    <x-slug.js.add :module="'job'"/>

    <script>
        (function ($) {
            "use strict";

            $(document).ready(function ($) {

                $(document).on('change','.application_fee_switcher',function (){
                    let el = $(this);
                    if(el.is(':checked')){
                        $('.application_fee_input').removeClass('d-none');
                    }else{
                        $('.application_fee_input').addClass('d-none');
                    }
                })

                $(document).on('change','select[name="lang"]',function (e){
                    $(this).closest('form').trigger('submit');
                    $('input[name="lang"]').val($(this).val());
                });

                $('.timepicker').timepicker();



            });
        })(jQuery)
    </script>
@endsection
