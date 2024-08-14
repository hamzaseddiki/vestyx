@extends('tenant.admin.admin-master')

@section('title')
    {{__('All Appointment Settings')}}
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12">
                <x-flash-msg/>
                <x-error-msg/>
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">{{__("Appointment Settings")}}</h4>
                        <form action="{{route('tenant.admin.appointment.settings')}}" method="POST"
                              enctype="multipart/form-data">
                            @csrf

                    <x-lang-tab>
                        @foreach(\App\Facades\GlobalLanguage::all_languages(1) as $lang)
                            @php $slug = $lang->slug; @endphp
                            <x-slot :name="$slug">

                                <div class="form-group">
                                    <label for="donation_goal_text">{{__('Order Page Sub Appointment Heading')}}</label>
                                    <input type="text" name="appointment_order_page_sub_appointment_{{$slug}}_heading" class="form-control"
                                     value="{{get_static_option('appointment_order_page_sub_appointment_'.$slug.'_heading')}}">
                                    <small class="text-primary">{{__('To show the highlighted text, place your word between this code {h}YourText{/h]')}}</small>
                                </div>

                                <div class="form-group">
                                    <label for="donation_goal_text">{{__('Order Page Date Section Heading')}}</label>
                                    <input type="text" name="appointment_order_page_date_section_{{$slug}}_heading" class="form-control"
                                           value="{{get_static_option('appointment_order_page_date_section_'.$slug.'_heading')}}">
                                    <small class="text-primary">{{__('To show the highlighted text, place your word between this code {h}YourText{/h]')}}</small>
                                </div>

                                <div class="form-group">
                                    <label for="donation_goal_text">{{__('Order Page Date Selection Button Text')}}</label>
                                    <input type="text" name="appointment_order_page_date_selection_button_{{$slug}}_text" class="form-control"
                                           value="{{get_static_option('appointment_order_page_date_selection_button_'.$slug.'_text')}}">
                                </div>

                                <div class="form-group">
                                    <label for="donation_goal_text">{{__('Payment Page Left Heading')}}</label>
                                    <input type="text" name="appointment_payment_page_left_{{$slug}}_heading" class="form-control"
                                           value="{{get_static_option('appointment_payment_page_left_'.$slug.'_heading')}}">
                                </div>

                                <div class="form-group">
                                    <label for="donation_goal_text">{{__('Payment Page Left Button Text')}}</label>
                                    <input type="text" name="appointment_payment_page_left_button_{{$slug}}_text" class="form-control"
                                           value="{{get_static_option('appointment_payment_page_left_button_'.$slug.'_text')}}">
                                </div>

                                <div class="form-group">
                                    <label for="donation_goal_text">{{__('Payment Page Bottom Title')}}</label>
                                    <input type="text" name="appointment_payment_page_bottom_{{$slug}}_title" class="form-control"
                                           value="{{get_static_option('appointment_payment_page_bottom_'.$slug.'_title')}}">
                                </div>

                                <div class="form-group">
                                    <label for="donation_goal_text">{{__('Payment Page Bottom Phone')}}</label>
                                    <input type="text" name="appointment_payment_page_bottom_{{$slug}}_phone" class="form-control"
                                           value="{{get_static_option('appointment_payment_page_bottom_'.$slug.'_phone')}}">
                                </div>


                                <div class="form-group">
                                    <label for="donation_goal_text">{{__('Payment Page Bottom Description')}}</label>
                                    <textarea type="text" rows="5" name="appointment_payment_page_bottom_{{$slug}}_description" class="form-control">{{purify_html(get_static_option('appointment_payment_page_bottom_'.$slug.'_description'))}}</textarea>
                                </div>


                                <div class="form-group">
                                    <label for="donation_goal_text">{{__('Payment Page Right Heading')}}</label>
                                    <input type="text" name="appointment_payment_page_right_{{$slug}}_heading" class="form-control"
                                           value="{{get_static_option('appointment_payment_page_right_'.$slug.'_heading')}}">
                                </div>

                                <div class="form-group">
                                    <label for="donation_goal_text">{{__('Payment Page Right Pay Button Text')}}</label>
                                    <input type="text" name="appointment_payment_page_right_pay_button_{{$slug}}_text" class="form-control"
                                           value="{{get_static_option('appointment_payment_page_right_pay_button_'.$slug.'_text')}}">
                                </div>

                            </x-slot>
                        @endforeach
                    </x-lang-tab>


                            <div class="form-group">
                                <label for="navbar_button">{{__('Show/Hide Tax')}}</label>
                                <label class="switch">
                                    <input type="checkbox" name="appointment_tax_apply_status"
                                           @if(!empty(get_static_option('appointment_tax_apply_status'))) checked @endif >
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
