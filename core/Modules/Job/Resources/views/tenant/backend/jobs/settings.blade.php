@extends('tenant.admin.admin-master')

@section('title')
    {{__('All Job Settings')}}
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12">
                <x-flash-msg/>
                <x-error-msg/>
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">{{__("Job Settings")}}</h4>
                        <form action="{{route('tenant.admin.job.settings')}}" method="POST"
                              enctype="multipart/form-data">
                            @csrf

                    <x-lang-tab>
                        @foreach(\App\Facades\GlobalLanguage::all_languages() as $lang)
                            @php $slug = $lang->slug; @endphp
                            <x-slot :name="$slug">

                            <div class="form-group">
                                <label for="job_experience">{{__('Experience Area Title')}}</label>
                                <input type="text" name="job_experience_area_{{$slug}}_title" class="form-control"
                                       value="{{get_static_option('job_experience_area_'.$slug.'_title')}}">
                            </div>
                            <div class="form-group">
                                <label for="job_experience">{{__('Employee Type Area Title')}}</label>
                                <input type="text" name="job_employee_type_area_{{$slug}}_title" class="form-control"
                                       value="{{get_static_option('job_employee_type_area_'.$slug.'_title')}}">
                            </div>
                            <div class="form-group">
                                <label for="job_experience">{{__('Salary Offer Area Title')}}</label>
                                <input type="text" name="job_salary_offer_area_{{$slug}}_title" class="form-control"
                                       value="{{get_static_option('job_salary_offer_area_'.$slug.'_title')}}">
                            </div>
                            <div class="form-group">
                                <label for="job_experience">{{__('Working Days Area Title')}}</label>
                                <input type="text" name="job_working_days_area_{{$slug}}_title" class="form-control"
                                       value="{{get_static_option('job_working_days_area_'.$slug.'_title')}}">
                            </div>
                            <div class="form-group">
                                <label for="job_experience">{{__('Working type Area Title')}}</label>
                                <input type="text" name="job_working_type_area_{{$slug}}_title" class="form-control"
                                       value="{{get_static_option('job_working_type_area_'.$slug.'_title')}}">
                            </div>
                            <div class="form-group">
                                <label for="job_experience">{{__('Location Area Title')}}</label>
                                <input type="text" name="job_location_area_{{$slug}}_title" class="form-control"
                                       value="{{get_static_option('job_location_area_'.$slug.'_title')}}">
                            </div>
                            <div class="form-group">
                                <label for="job_experience">{{__('Designation Area Title')}}</label>
                                <input type="text" name="job_designation_area_{{$slug}}_title" class="form-control"
                                       value="{{get_static_option('job_designation_area_'.$slug.'_title')}}">
                            </div>
                            <div class="form-group">
                                <label for="job_experience">{{__('Deadline Area Title')}}</label>
                                <input type="text" name="job_deadline_area_{{$slug}}_title" class="form-control"
                                       value="{{get_static_option('job_deadline_area_'.$slug.'_title')}}">
                            </div>

                            <div class="form-group">
                                <label for="job_experience">{{__('Application Fee Area Title')}}</label>
                                <input type="text" name="job_application_fee_area_{{$slug}}_title" class="form-control"
                                       value="{{get_static_option('job_application_fee_area_'.$slug.'_title')}}">
                            </div>

                            <div class="form-group">
                                <label for="job_apply">{{__('Apply Area Title')}}</label>
                                <input type="text" name="job_apply_area_{{$slug}}_title" class="form-control"
                                       value="{{get_static_option('job_apply_area_'.$slug.'_title')}}">
                            </div>

                            </x-slot>
                        @endforeach
                    </x-lang-tab>
                            <div class="form-group">
                                <label for="navbar_button">{{__('Show/Hide Job Application Fee')}}</label>
                                <label class="switch">
                                    <input type="checkbox" name="job_application_fee_show_hide"
                                           @if(!empty(get_static_option('job_application_fee_show_hide'))) checked @endif >
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="navbar_button">{{__('Show/Hide Job Apply ')}}</label>
                                <label class="switch">
                                    <input type="checkbox" name="job_apply_show_hide"
                                           @if(!empty(get_static_option('job_apply_show_hide'))) checked @endif >
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="navbar_button">{{__('Show/Hide related Area')}}</label>
                                <label class="switch">
                                    <input type="checkbox" name="job_related_area_show_hide"
                                           @if(!empty(get_static_option('job_related_area_show_hide'))) checked @endif >
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <button id="update" type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
