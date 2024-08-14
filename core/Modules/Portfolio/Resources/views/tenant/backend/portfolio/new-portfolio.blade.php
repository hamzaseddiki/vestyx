@extends(route_prefix().'admin.admin-master')
@section('title')   {{__('New Portfolio')}} @endsection
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
        $lang_slug = request()->get('lang') ?? \App\Facades\GlobalLanguage::default_slug();
    @endphp
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <x-admin.header-wrapper>
                    <x-slot name="left">
                        <h4 class="card-title mb-5">  {{__('New Portfolio')}}</h4>
                    </x-slot>
                    <x-slot name="right" class="d-flex">
                        <form action="{{route(route_prefix().'admin.portfolio.new')}}" method="get">
                            <x-fields.select name="lang" title="{{__('Language')}}">
                                @foreach(\App\Facades\GlobalLanguage::all_languages() as $lang)
                                    <option value="{{$lang->slug}}" @if($lang->slug === $lang_slug) selected @endif>{{$lang->name}}</option>
                                @endforeach
                            </x-fields.select>
                        </form>
                        <p></p>
                        <x-link-with-popover url="{{route('tenant.admin.portfolio')}}" extraclass="ml-3">
                            {{__('All Portfolio')}}
                        </x-link-with-popover>
                    </x-slot>
                </x-admin.header-wrapper>

                <x-error-msg/>
                <x-flash-msg/>

                <form class="forms-sample" method="post" action="{{route('tenant.admin.portfolio.new')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">

                        <div class="col-md-8">
                            <x-fields.input type="hidden" name="lang" value="{{$lang_slug}}"/>
                            <x-fields.input type="text" name="title" label="{{__('Title')}}" class="title" id="title" value="{{ old('title') }}"/>

                            <x-slug.add-markup/>

                            <x-summernote.textarea label="{{__('Blog Content')}}" name="description" value="{{ old('description') }}"/>
                            <x-fields.input type="text" name="url" label="{{__('URL')}}" id="url" value="{{ old('url') }}"/>

                            <x-portfolio::backend.meta-data.donation-add-meta-markup/>

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

                                                    <x-fields.input type="text" name="client" label="{{__('Client')}}" id="client" value="{{ old('client') }}"/>
                                                    <x-fields.input type="text" name="design" label="{{__('Design')}}" id="design" value="{{ old('design') }}"/>
                                                    <x-fields.input type="text" name="typography" label="{{__('Typography')}}" id="typography" value="{{ old('typography') }}"/>


                                                    <div class="form-group">
                                                        <label for="title">{{__('Tags')}}</label>
                                                        <input type="text" class="form-control" name="tags" data-role="tagsinput">
                                                    </div>

                                                    <x-fields.select name="status" class="form-control" id="status" title="{{__('Status')}}">
                                                        <option value="{{\App\Enums\StatusEnums::DRAFT}}">{{__("Draft")}}</option>
                                                        <option value="{{\App\Enums\StatusEnums::PUBLISH}}">{{__("Publish")}}</option>
                                                    </x-fields.select>

                                                    <div class="form-group">
                                                        <label for="title">{{__('Upload Portfolio File')}}</label>
                                                        <input type="file" class="form-control" name="file" accept="zip">
                                                        <small class="text-primary">{{__('Zip and images allowed')}}</small>
                                                    </div>


                                                    <x-fields.media-upload name="image" title="{{__('Image')}}" dimentions="{{__('1920 X 1280 px image recommended')}}"/>

                                                    <div class="submit_btn mt-5">
                                                        <button type="submit" class="btn btn-gradient-primary pull-right">{{__('Submit New Portfolio ')}}</button>
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
    <x-summernote.js/>
    <x-media-upload.js/>
    <x-slug.js.add :module="'portfolio'"/>

    <script>
        (function ($) {
            "use strict";

            $(document).ready(function ($) {

                $(document).on('change','select[name="lang"]',function (e){
                    $(this).closest('form').trigger('submit');
                    $('input[name="lang"]').val($(this).val());
                });


                $(document).on('change', '#langchange', function (e) {
                    $('#langauge_change_select_get_form').trigger('submit');
                });

                var el = $('.post_type_radio');
                $(document).on('change', '.post_type', function () {
                    var val = $(this).val();
                    if (val === 'option2') {
                        $('.video_section').removeClass('d-none');
                    } else {
                        $('.video_section').addClass('d-none');
                    }
                })



            });
        })(jQuery)
    </script>
@endsection
