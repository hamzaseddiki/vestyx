@extends(route_prefix().'admin.admin-master')

@section('title')
    {{__('Edit Sub Appointment')}}
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
                                <h4 class="card-title mb-5">  {{__('Edit Sub Appointment')}}</h4>
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
                                <x-link-with-popover url="{{route('tenant.admin.sub.appointment')}}" extraclass="ml-3">
                                    {{__('All Appointment')}}
                                </x-link-with-popover>
                            </x-slot>
                        </x-admin.header-wrapper>

                        <x-error-msg/>
                        <x-flash-msg/>


                        <form class="forms-sample" method="post" action="{{route('tenant.admin.sub.appointment.update',$sub_appointment->id)}}">
                            @csrf
                            <div class="row">
                                <div class="col-md-8">
                                    <x-fields.input type="hidden" name="lang" value="{{$lang_slug}}"/>
                                    <x-fields.input type="text" name="title" label="{{__('Title')}}" class="title" id="title" value="{{$sub_appointment->getTranslation('title',$lang_slug)}}"/>

                                    <x-slug.edit-markup value="{{$sub_appointment->slug}}"/>

                                    <x-summernote.textarea label="{{__('Description')}}" name="description" value="{!! $sub_appointment->getTranslation('description',$lang_slug) !!}"/>


                                   <x-appointment::backend.meta-data.edit-meta-markup :donation="$sub_appointment"/>

                                </div>
                                <div class="col-lg-4 right-side-card">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card mt-4">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <x-fields.input type="number" name="price" label="Price" value="{{$sub_appointment->price}}"/>
                                                            <x-fields.input type="text" name="person" label="Person" value=" {{ (int) $sub_appointment->person }}"/>

                                                            <x-fields.switcher value="{{$sub_appointment->is_popular}}" name="is_popular" label="Popular"/>


                                                            <x-fields.select name="status" class="form-control" id="status" title="{{__('Status')}}">
                                                                <option value="{{\App\Enums\StatusEnums::DRAFT}}" {{$sub_appointment->status == \App\Enums\StatusEnums::DRAFT ? 'selected' : ''}}>{{__("Draft")}}</option>
                                                                <option value="{{\App\Enums\StatusEnums::PUBLISH}}" {{$sub_appointment->status == \App\Enums\StatusEnums::PUBLISH ? 'selected' : ''}}>{{__("Publish")}}</option>
                                                            </x-fields.select>

                                                            <x-landlord-others.edit-media-upload-image :label="'Image'" :name="'image'" :value="$sub_appointment->image"/>

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
    <x-slug.js.edit :module="'sub-appointment'"/>


    <script>
        (function($){
            "use strict";
            $(document).ready(function () {

                $(document).on('change','select[name="lang"]',function (e){
                    $(this).closest('form').trigger('submit');
                    $('input[name="lang"]').val($(this).val());
                });
                <x-btn.update/>


            });
        })(jQuery)
    </script>

@endsection
