@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('New Knowledgebase')}}
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
                        <h4 class="card-title mb-5">  {{__('New Knowledgebase')}}</h4>
                    </x-slot>
                    <x-slot name="right" class="d-flex">
                        <form action="{{route(route_prefix().'admin.knowledgebase.new')}}" method="get">
                            <x-fields.select name="lang" title="{{__('Language')}}">
                                @foreach(\App\Facades\GlobalLanguage::all_languages() as $lang)
                                    <option value="{{$lang->slug}}" @if($lang->slug === $lang_slug) selected @endif>{{$lang->name}}</option>
                                @endforeach
                            </x-fields.select>
                        </form>
                        <p></p>
                        <x-link-with-popover url="{{route('tenant.admin.knowledgebase')}}" extraclass="ml-3">
                            {{__('All Knowledgebase')}}
                        </x-link-with-popover>
                    </x-slot>
                </x-admin.header-wrapper>

                <x-error-msg/>
                <x-flash-msg/>

                <form class="forms-sample" method="post" action="{{route('tenant.admin.knowledgebase.new')}}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">

                        <div class="col-md-8">
                            <x-fields.input type="hidden" name="lang" value="{{$lang_slug}}"/>
                            <x-fields.input type="text" name="title" label="{{__('Title')}}" class="title" id="title" value="{{ old('title') }}"/>

                            <x-slug.add-markup/>

                            <x-summernote.textarea name="description" label="{{__('Knowledgebase Description')}}" value="{{ old('description') }}"/>
                            <x-knowledgebase::meta-data.add-meta-markup/>

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


                                                    <x-fields.select name="status" class="form-control" id="status" title="{{__('Status')}}">
                                                        <option value="{{\App\Enums\StatusEnums::DRAFT}}">{{__("Draft")}}</option>
                                                        <option value="{{\App\Enums\StatusEnums::PUBLISH}}">{{__("Publish")}}</option>
                                                    </x-fields.select>

                                                    <x-fields.media-upload name="image" title="{{__('Image')}}" dimentions="{{__('1920 X 1280 px image recommended')}}"/>

                                                    <div class="files">
                                                        <strong class="">{{__('Upload Files (single or multiple)')}}</strong>
                                                        <input type="file" name="files[]" multiple class="btn btn-primary btn-sm my-3">
                                                    </div>

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
    <x-slug.js.add :module="'knowledgebase'"/>

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
