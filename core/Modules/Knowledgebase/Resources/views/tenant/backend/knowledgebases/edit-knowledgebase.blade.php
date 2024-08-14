@extends(route_prefix().'admin.admin-master')

@section('title')
    {{__('Edit Knowledgebase')}}
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
        $lang_slug = request()->get('lang') ?? default_lang();
    @endphp
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <x-admin.header-wrapper>
                            <x-slot name="left">
                                <h4 class="card-title mb-5">  {{__('Edit Knowledgebase')}}</h4>
                            </x-slot>
                            <x-slot name="right" class="d-flex">
                                <form action="{{route('tenant.admin.knowledgebase.edit',$knowledgebase->id)}}" method="get">
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

                        <form class="forms-sample" method="post" action="{{route('tenant.admin.knowledgebase.update',$knowledgebase->id)}}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <div class="col-md-8">
                                    <x-fields.input type="hidden" name="lang" value="{{$lang_slug}}"/>
                                    <x-fields.input type="text" name="title" label="{{__('Title')}}" class="title" id="title" value="{{$knowledgebase->getTranslation('title',$lang_slug)}}"/>

                                    <x-slug.edit-markup value="{{$knowledgebase->slug}}"/>
                                    <x-summernote.textarea name="description" label="{{__('Knowledgebase Description')}}" value="{!! $knowledgebase->getTranslation('description',$lang_slug) !!}"/>
                                    <x-knowledgebase::meta-data.edit-meta-markup :knowledgebase="$knowledgebase"/>

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
                                                                    <option value="{{$cat->id}}" @selected($cat->id == $knowledgebase->category_id)>{{$cat->getTranslation('title',$lang_slug)}}</option>
                                                                @endforeach
                                                            </x-fields.select>


                                                            <x-fields.select name="status" class="form-control" id="status" title="{{__('Status')}}">
                                                                <option value="{{\App\Enums\StatusEnums::DRAFT}}" @selected(\App\Enums\StatusEnums::DRAFT == $knowledgebase->status)>{{__("Draft")}}</option>
                                                                <option value="{{\App\Enums\StatusEnums::PUBLISH}}" @selected(\App\Enums\StatusEnums::PUBLISH == $knowledgebase->status)>{{__("Publish")}}</option>
                                                            </x-fields.select>

                                                            <x-landlord-others.edit-media-upload-image :label="'Knowledgebase Image'" :name="'image'" :value="$knowledgebase->image"/>

                                                            <div class="files">
                                                                <strong class="">{{__('Upload Files (single or multiple)')}}</strong>
                                                                <input type="file" name="files[]" multiple class="btn btn-primary btn-sm my-3">
                                                                {{--for tracking method--}}
                                                                <input type="hidden" name="file_update" value="update">
                                                                {{--for tracking method--}}
                                                            </div>

                                                            @php
                                                                $files_decode = json_decode($knowledgebase->files) ?? [];
                                                            @endphp

                                                                @if(count($files_decode) > 0)
                                                                    <div class="old">
                                                                        <strong>{{__('Old Files')}} <i class="las la-long-arrow-alt-down"></i> </strong>
                                                                        <ol class="mt-2">
                                                                            @foreach($files_decode as $file)
                                                                                <li>
                                                                                    <a href="{{ url('assets/uploads/article-files/',$file) }}" target="_blank">{{$file ?? ''}}</a>
                                                                                </li>
                                                                            @endforeach
                                                                        </ol>
                                                                    </div>
                                                                @endif


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
        </div>
    </div>
    <x-media-upload.markup/>
@endsection
@section('scripts')
    <script src="{{global_asset('assets/landlord/admin/js/bootstrap-tagsinput.js')}}"></script>
    <script src="{{global_asset('assets/common/js/jquery.timepicker.min.js')}}"></script>
    <x-summernote.js/>
    <x-media-upload.js/>
    <x-slug.js.edit :module="'knowledgebase'"/>

    <script>
        (function($){
            "use strict";
            $(document).ready(function () {
                $('.timepicker').timepicker();

                $(document).on('change','select[name="lang"]',function (e){
                    $(this).closest('form').trigger('submit');
                    $('input[name="lang"]').val($(this).val());
                });
                <x-btn.update/>



            });
        })(jQuery)
    </script>

@endsection
