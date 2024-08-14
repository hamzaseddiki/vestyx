@extends(route_prefix().'admin.admin-master')
@section('title')   {{__('Edit Portfolio')}} @endsection
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
                                <h4 class="card-title mb-5">  {{__('Edit Portfolio')}}</h4>
                            </x-slot>
                            <x-slot name="right" class="d-flex">
                                <form action="{{route('tenant.admin.portfolio.edit',$portfolio->id)}}" method="get">
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


                        <form class="forms-sample" method="post" action="{{route('tenant.admin.portfolio.update',$portfolio->id)}}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-8">
                                    <x-fields.input type="hidden" name="lang" value="{{$lang_slug}}"/>
                                    <x-fields.input type="text" name="title" label="{{__('Title')}}" class="title" value="{{$portfolio->getTranslation('title',$lang_slug)}}" id="title"/>

                                    <x-slug.edit-markup value="{{$portfolio->slug}}"/>
                                    <x-summernote.textarea label="{{__('Portfolio Content')}}" name="description" value="{!! $portfolio->getTranslation('description',$lang_slug) !!}"/>
                                    <x-fields.input type="text" name="url" label="{{__('URL')}}" id="url" value="{{ $portfolio->url }}"/>
                                    <x-portfolio::backend.meta-data.donation-edit-meta-markup :portfolio="$portfolio"/>

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
                                                                    <option value="{{$cat->id}}" {{ $cat->id == $portfolio->category_id ? 'selected' : '' }}>{{$cat->getTranslation('title',$lang_slug)}}</option>
                                                                @endforeach
                                                            </x-fields.select>

                                                            <x-fields.input type="text" name="client" label="{{__('Client')}}" value="{{$portfolio->getTranslation('client',$lang_slug) }}" id="client"/>
                                                            <x-fields.input type="text" name="design" label="{{__('Design')}}" value="{{$portfolio->getTranslation('design',$lang_slug) }}" id="design"/>
                                                            <x-fields.input type="text" name="typography" label="{{__('Typography')}}" value="{{$portfolio->getTranslation('typography',$lang_slug) }}" id="typography"/>


                                                            <div class="form-group">
                                                                <label for="title">{{__('Tags')}}</label>
                                                                <input type="text" class="form-control" name="tags" data-role="tagsinput" value="{{$portfolio->tags}}">
                                                            </div>
                                                            <x-fields.select name="status" class="form-control" id="status" title="{{__('Status')}}">
                                                                <option value="{{\App\Enums\StatusEnums::DRAFT}}" {{$portfolio->status == \App\Enums\StatusEnums::DRAFT ? 'selected' : ''}}>{{__("Draft")}}</option>
                                                                <option value="{{\App\Enums\StatusEnums::PUBLISH}}" {{$portfolio->status == \App\Enums\StatusEnums::PUBLISH ? 'selected' : ''}}>{{__("Publish")}}</option>
                                                            </x-fields.select>

                                                            <div class="form-group">
                                                                <label for="title">{{__('Upload Portfolio File')}}</label>
                                                                <input type="file" class="form-control" name="file" accept="zip">
                                                                <small class="text-danger">{{__('Zip and images allowed')}}</small>
                                                            </div>

                                                            @if(!empty($portfolio->file))
                                                                <div class="form-group">
                                                                    <label for="title" class="mt-1">{{__('Old Portfolio File : ')}}</label>
                                                                    <a href="{{url('assets/uploads/custom-file/'.$portfolio->file)}}" class="" target="_blank">{{$portfolio->file}}</a>
                                                                </div>
                                                            @endif

                                                            <x-landlord-others.edit-media-upload-image :label="'Portfolio Image'" :name="'image'" :value="$portfolio->image"/>
{{--                                                            <x-landlord-others.edit-media-upload-gallery :name="'image_gallery'" title="{{__('Gallery Image')}}" :value="$portfolio->image_gallery"/>--}}

                                                            <div class="submit_btn mt-5">
                                                                <button type="submit" class="btn btn-gradient-primary pull-right">{{__('Update Portfolio ')}}</button>
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
    <x-slug.js.edit :module="'portfolio'"/>

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
