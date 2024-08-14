@extends(route_prefix().'admin.admin-master')
@section('title') {{__('Edit Services')}} @endsection

@section('style')
    <link rel="stylesheet" href="{{global_asset('assets/landlord/admin/css/bootstrap-tagsinput.css')}}">
    <x-summernote.css/>
    <x-media-upload.css/>
@endsection

@section('content')
    @php
        $lang_slug = request()->get('lang') ?? \App\Facades\GlobalLanguage::default_slug();
        $user_lang = get_user_lang();
    @endphp
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <x-admin.header-wrapper>
                    <x-slot name="left">
                        <h4 class="card-title mb-5">{{__('Edit Services')}}</h4>
                    <x-bulk-action/>
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
                        <a class="btn btn-info btn-sm mb-3" href="{{route('tenant.admin.service')}}">{{__('All Services')}}</a>
                    </x-slot>
                </x-admin.header-wrapper>

                <x-error-msg/>
                <x-flash-msg/>

                <form action="{{route('tenant.admin.service.update')}}" method="post" enctype="multipart/form-data">
                    @csrf

                     <input type="hidden" name="lang" value="{{$default_lang}}"/>
                     <input type="hidden" name="id" value="{{$service->id}}"/>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="form-group">
                            <label for="title">{{__('Title')}}</label>
                            <input type="text" class="form-control"  name="title" value="{{$service->getTranslation('title',$lang_slug)}}">
                        </div>
                        <div class="form-group">
                            <label for="title">{{__('Slug')}}</label>
                            <input type="text" class="form-control"  value="{{$service->slug}}"  name="slug" placeholder="{{__('Slug')}}">
                        </div>

                        <div class="form-group">
                            <label>{{__('Description')}}</label>
                            <input type="hidden" name="description" value="{{$service->getTranslation('description',$lang_slug)}}">
                            <div class="summernote" data-content="{{$service->getTranslation('description',$lang_slug)}}"></div>
                        </div>
                        <x-service::backend.common-meta-data.edit-meta-markup :data="$service"/>
                    </div>


                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="category">{{__('Category')}}</label>
                            <select name="category_id" id="category" class="form-control">
                                <option value="">{{__('Select Category')}}</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}" {{$category->id == $service->category_id ? 'selected' : ''}}>{{$category->getTranslation('title',get_user_lang())}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="meta_tags">{{__('Meta Tags')}}</label>
                            <input type="text" name="meta_tag"  class="form-control"  value="{{$service->meta_tag}}"  data-role="tagsinput" id="meta_tags">
                        </div>

                        <div class="form-group">
                            <label for="status">{{__('Status')}}</label>
                            <select name="status" id="status" class="form-control">
                                <option {{$service->status == 1 ? 'selected ' : ''}} value="1">{{__('Publish')}}</option>
                                <option  {{$service->status == 0 ? 'selected ' : ''}} value="0">{{__('Draft')}}</option>
                            </select>
                        </div>

                        <x-landlord-others.edit-media-upload-image :label="'Service Image'" :name="'image'" :value="$service->image"/>
                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Service')}}</button>
                    </div>

                </div>


                </form>

            </div>
        </div>
    </div>
    <x-media-upload.markup/>
@endsection
@section('scripts')
    <script src="{{global_asset('assets/landlord/admin/js/bootstrap-tagsinput.js')}}"></script>
    <x-summernote.js/>
    <x-media-upload.js/>

    <script>
        (function ($) {
            "use strict";

            $(document).ready(function ($) {

                if($('.nice-select').length > 0){
                    $('.nice-select').niceSelect();
                }

                $(document).on('change','select[name="lang"]',function (e){
                    $(this).closest('form').trigger('submit');
                    $('input[name="lang"]').val($(this).val());
                });

                $('.summernote').summernote({
                    height: 400,   //set editable area's height
                    codemirror: { // codemirror options
                        theme: 'monokai'
                    },
                    callbacks: {
                        onChange: function (contents, $editable) {
                            $(this).prev('input').val(contents);
                        }
                    }
                });
                if ($('.summernote').length > 0) {
                    $('.summernote').each(function (index, value) {
                        $(this).summernote('code', $(this).data('content'));
                    });
                }


            });
        })(jQuery)
    </script>
@endsection
