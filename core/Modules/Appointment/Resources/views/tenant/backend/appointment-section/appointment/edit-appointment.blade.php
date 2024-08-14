@extends(route_prefix().'admin.admin-master')

@section('title')
    {{__('Edit Appointment')}}
@endsection

@section('style')
    <link rel="stylesheet" href="{{global_asset('assets/landlord/admin/css/bootstrap-tagsinput.css')}}">
    <x-summernote.css/>
    <x-media-upload.css/>
    <style>
        .nav-pills .nav-link {
            margin: 8px 0px !important;
        }
        .col-lg-4.right-side-card {
            background: aliceblue;
        }

        .sub_appointment_parent .feature-section label {
            font-size: 17px;
            line-height: 1;
            vertical-align: top;
        }

        .sub_appointment_parent .feature-section .d-inline {
            margin-right: 16px;
            margin-bottom: 14px;
            display: inline-block !important;
        }

        .sub_appointment_parent .feature-section label {
            font-size: 17px;
            line-height: 1;
            vertical-align: top;
        }

        .sub_appointment_parent .feature-section input.exampleCheck1 {
            width: 31px;
            height: 18px;
        }

        .sub_appointment_parent h4 {
            font-size: 1.125rem;
            margin-bottom: 20px;
            display: block;
        }
    </style>
@endsection
@section('content')
    @php
        $lang_slug = request()->get('lang') ?? default_lang();
    @endphp
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <x-admin.header-wrapper>
                            <x-slot name="left">
                                <h4 class="card-title mb-5">  {{__('Edit Appointment')}}</h4>
                            </x-slot>
                            <x-slot name="right" class="d-flex">
                                <form action="" method="get">
                                    <x-fields.select name="lang" title="{{__('Language')}}">
                                        @foreach(\App\Facades\GlobalLanguage::all_languages() as $lang)
                                            <option value="{{$lang->slug}}" @if($lang->slug === $lang_slug) selected @endif>{{$lang->name}}</option>
                                        @endforeach
                                    </x-fields.select>
                                </form>
                                <p></p>
                                <x-link-with-popover url="{{route('tenant.admin.appointment')}}" extraclass="ml-3">
                                    {{__('All Appointment')}}
                                </x-link-with-popover>
                            </x-slot>
                        </x-admin.header-wrapper>

                        <x-error-msg/>
                        <x-flash-msg/>


                        <form class="forms-sample" method="post" action="{{route('tenant.admin.appointment.update',$appointment->id)}}">
                            @csrf
                            <div class="row">
                                <div class="col-md-8">
                                    <x-fields.input type="hidden" name="lang" value="{{$lang_slug}}"/>
                                    <x-fields.input type="text" name="title" label="{{__('Title')}}" class="title" id="title" value="{{$appointment->getTranslation('title',$lang_slug)}}"/>

                                    <x-slug.edit-markup value="{{$appointment->slug}}"/>

                                    <x-summernote.textarea label="{{__('Description')}}" name="description" value="{!! $appointment->getTranslation('description',$lang_slug) !!}"/>

                                    <x-fields.switcher name="sub_appointment_status" value="{{$appointment->sub_appointment_status}}" label="{{__('Sub Appointments')}}"/>
                                    <div class="form-group sub_appointment_parent d-none">
                                        <h4>{{__('Select Sub Appointments')}}</h4>
                                        <div class="feature-section">
                                            <ul>
                                                @foreach($all_sub_appointments as $key => $sub)
                                                    @php
                                                        $get_ids = $appointment->additional_appointments?->pluck('sub_appointment_id')->toArray();
                                                    @endphp
                                                    <li class="d-inline ">
                                                        <input type="checkbox" name="sub_appointment_ids[]" id="{{$key}}" class="exampleCheck1 check_items" value="{{$sub->id}}" data-feature=""
                                                            {{ in_array($sub->id,$get_ids) ? 'checked' : '' }}>
                                                        <label class="ml-1" for="{{$key}}">
                                                            {{$sub->getTranslation('title',$lang_slug)}} ({{ amount_with_currency_symbol($sub->price) }})
                                                            <br>
                                                            <small class="text-dark">{{__('Person')}} : <span class="text-primary">({{ \App\Enums\AppointmentEnums::getText($sub->person) }})</span></small>
                                                        </label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>

                                   <x-appointment::backend.meta-data.edit-meta-markup :donation="$appointment"/>

                                </div>
                                <div class="col-lg-4 right-side-card">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card mt-4">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <x-fields.select name="appointment_category_id" class="appointment_category_id" title="{{__('Select Category')}}">
                                                                @foreach($all_categories as $cat)
                                                                    <option value="{{ $cat->id }}" {{ $cat->id == $appointment->appointment_category_id ? 'selected' : ''}}>{{ $cat->getTranslation('title',$lang_slug) }}</option>
                                                                @endforeach
                                                            </x-fields.select>

                                                            <x-fields.select name="appointment_subcategory_id" class="appointment_subcategory_id" title="{{__('Select Subcategory')}}">
                                                                @foreach($all_subcategories as $sub)
                                                                    <option value="{{ $sub->id }}" {{ $sub->id ==  $appointment->appointment_subcategory_id ? 'selected' : ''}}>{{ $sub->getTranslation('title',$lang_slug) }}</option>
                                                                @endforeach
                                                            </x-fields.select>

                                                            <x-fields.input type="number" name="price" label="Price" value="{{$appointment->price}}"/>

                                                            <x-fields.input type="number" name="person" label="Person" value="{{$appointment->person}}"/>

                                                            <x-fields.switcher value="{{$appointment->is_popular}}" name="is_popular" label="Popular"/>

                                                            <x-fields.switcher value="{{$appointment->tax_status}}" name="tax_status" label="Tax Status"/>

                                                            <div class="tax_type_select_container d-none">
                                                                <x-fields.select name="tax_type" class="form-control tax_type" id="status" title="{{__('Select Tax Type')}}">
                                                                    <option value="inclusive" {{ !empty($tax_info) && $tax_info->tax_type == 'inclusive' ? 'selected' : ''}}>{{__("Inclusive")}}</option>
                                                                    <option value="exclusive" {{ !empty($tax_info) && $tax_info->tax_type == 'exclusive' ? 'selected' : ''}}>{{__("Exclusive")}}</option>
                                                                </x-fields.select>
                                                            </div>

                                                            <div class="tax_amount_container d-none">
                                                                <x-fields.input type="text" name="tax_amount" label="Exclusive Tax Amount" value="{{ !empty($tax_info) ? $tax_info->tax_amount : ''}}"/>
                                                            </div>

                                                            <x-fields.select name="status" class="form-control" id="status" title="{{__('Status')}}">
                                                                <option value="{{\App\Enums\StatusEnums::DRAFT}}" {{$appointment->status == \App\Enums\StatusEnums::DRAFT ? 'selected' : ''}}>{{__("Draft")}}</option>
                                                                <option value="{{\App\Enums\StatusEnums::PUBLISH}}" {{$appointment->status == \App\Enums\StatusEnums::PUBLISH ? 'selected' : ''}}>{{__("Publish")}}</option>
                                                            </x-fields.select>

                                                            <x-landlord-others.edit-media-upload-image :label="'Image'" :name="'image'" :value="$appointment->image"/>

                                                            <div class="submit_btn mt-5">
                                                                <button type="submit" class="btn btn-gradient-primary pull-right">{{__('Update')}}</button>
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
        </div>
    </div>
    <x-media-upload.markup/>
@endsection
@section('scripts')
    <x-repeater/>
    <script src="{{global_asset('assets/landlord/admin/js/bootstrap-tagsinput.js')}}"></script>
    <x-summernote.js/>
    <x-media-upload.js/>
    <x-slug.js.edit :module="'appointment'"/>


    <script>
        (function($){
            "use strict";
            $(document).ready(function () {

                $(document).on('change','select[name="lang"]',function (e){
                    $(this).closest('form').trigger('submit');
                    $('input[name="lang"]').val($(this).val());
                });
                <x-btn.update/>


                let sub_status = '{{ $appointment->sub_appointment_status }}'

                if(sub_status == 'on'){
                    $('.sub_appointment_parent').removeClass('d-none');
                }else{
                    $('.sub_appointment_parent').addClass('d-none');
                }

                $(document).on('change', 'input[name=sub_appointment_status]', function (e) {
                    let el = $(this);
                    if (el.prop('checked') == true) {
                        $('.sub_appointment_parent').removeClass('d-none');
                    } else {
                        $('.sub_appointment_parent').find('.check_items').val('');
                        $('.sub_appointment_parent').addClass('d-none');
                    }
                });


                //Tax
                let tax_status = '{{ $appointment->tax_status }}'
                let tax_type = '{{!empty($tax_info) ? $tax_info->tax_type : '' }}'

                if(tax_status == 'on'){
                    $('.tax_type_select_container').removeClass('d-none');
                }else{
                    $('.tax_type_select_container').addClass('d-none');
                }

                if(tax_type == 'exclusive'){
                    $('.tax_amount_container').removeClass('d-none');
                }else{
                    $('.tax_amount_container').addClass('d-none');
                }
                $(document).on('change', 'input[name=tax_status]', function (e) {
                    let el = $(this);
                    if (el.prop('checked') == true) {
                        $('.tax_type_select_container').removeClass('d-none');
                    } else {
                        $('.tax_type_select_container').find('.check_items').val('');
                        $('.tax_type_select_container').addClass('d-none');
                        $('.tax_amount_container').addClass('d-none');
                    }
                });



                //Tax Type
                $(document).on('change', '.tax_type', function (e) {
                    let el = $(this).val();
                    if (el == 'exclusive') {
                        $('.tax_amount_container').removeClass('d-none');
                    } else {
                        $('.tax_amount_container').find('.check_items').val('');
                        $('.tax_amount_container').addClass('d-none');
                    }
                });


                $(document).on('change', '.appointment_category_id', function (e) {
                    let category_id = $(this).val();
                    $.ajax({
                        url: '{{ route('tenant.admin.appointment.sub.category.via.ajax') }}',
                        type: 'get',
                        data:{
                            category_id:category_id,
                            lang: '{{ $lang_slug }}'
                        },
                        success: function (data){
                            $('.appointment_subcategory_id').html(data)
                        },
                        error: function (error){
                            console.log(error);
                        }

                    });
                });


            });
        })(jQuery)
    </script>

@endsection
