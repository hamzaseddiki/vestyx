@extends(route_prefix().'admin.admin-master')
@section('title') {{__('Add New Services')}} @endsection

@section('style')
    <link rel="stylesheet" href="{{global_asset('assets/landlord/admin/css/bootstrap-tagsinput.css')}}">
    <x-summernote.css/>
    <x-media-upload.css/>
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
                        <h4 class="card-title mb-5">{{__('New Services')}}</h4>
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


                <form action="{{route('tenant.admin.services.store')}}" method="post" enctype="multipart/form-data">
                    @csrf

                     <input type="hidden" name="lang" value="{{$default_lang}}"/>
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label for="title">{{__('Title')}}</label>
                                    <input type="text" class="form-control title"  value="{{old('title')}}"  name="title" placeholder="{{__('Title')}}">
                                </div>
                                <div class="form-group">
                                    <label for="title">{{__('Slug')}}</label>
                                    <input type="text" class="form-control" name="slug" placeholder="{{__('Slug')}}">
                                </div>

                                <div class="form-group">
                                    <label for="description">{{__('Description')}}</label>
                                    <input type="hidden" name="description" id="description" >
                                    <div class="summernote"> {{  old('description') }}</div>
                                </div>
                                <x-service::backend.common-meta-data.add-meta-markup/>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="category">{{__('Category')}}</label>
                                    <select name="category_id" id="category" class="form-control">
                                        <option value="">{{__('Select Category')}}</option>
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}">{{$category->getTranslation('title',get_user_lang())}}</option>
                                        @endforeach
                                    </select>

                                </div>

                                <div class="form-group">
                                    <label for="meta_tags">{{__('Tags')}}</label>
                                    <input type="text" name="meta_tag"  class="form-control"  value="{{old('meta_tags')}}"  data-role="tagsinput" id="meta_tags">
                                </div>

                                <div class="form-group">
                                    <label for="status">{{__('Status')}}</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1">{{__('Publish')}}</option>
                                        <option value="0">{{__('Draft')}}</option>
                                    </select>
                                </div>

                                <x-fields.media-upload name="image" title="{{__('Image')}}" dimentions="{{__('1920 x 1280 px image recommended')}}" />

                                <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Add Service')}}</button>
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

                //Permalink Code
                $('.permalink_label').hide();
                $(document).on('keyup', '.title', function (e) {
                    var slug = converToSlug($(this).val());
                    var url = `{{url('/service/')}}/` + slug;
                    $('.permalink_label').show();
                    var data = $('#slug_show').text(url).css('color', 'blue');
                    $('.blog_slug').val(slug);
                });

                //Slug Edit Code
                $(document).on('click', '.slug_edit_button', function (e) {
                    e.preventDefault();
                    $('.blog_slug').show();
                    $(this).hide();
                    $('.slug_update_button').show();
                });

                //Slug Update Code
                $(document).on('click', '.slug_update_button', function (e) {
                    e.preventDefault();
                    $(this).hide();
                    $('.slug_edit_button').show();
                    var update_input = $('.blog_slug').val();
                    var slug = converToSlug(update_input);
                    var url = `{{url('/service/')}}/` + slug;
                    $('#slug_show').text(url);
                    $('.blog_slug').hide();
                });


            });
        })(jQuery)
    </script>
@endsection
