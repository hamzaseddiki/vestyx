@extends(route_prefix().'admin.admin-master')
@section('title')   {{__('Edit Donation Activity')}} @endsection
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
                                <h4 class="card-title mb-5">  {{__('Edit Donation Activity')}}</h4>
                            </x-slot>
                            <x-slot name="right" class="d-flex">
                                <form action="{{route('tenant.admin.donation.activity.edit',$activity->id)}}" method="get">
                                    <x-fields.select name="lang" title="{{__('Language')}}">
                                        @foreach(\App\Facades\GlobalLanguage::all_languages() as $lang)
                                            <option value="{{$lang->slug}}" @if($lang->slug === $lang_slug) selected @endif>{{$lang->name}}</option>
                                        @endforeach
                                    </x-fields.select>
                                </form>
                                <p></p>
                                <x-link-with-popover url="{{route('tenant.admin.donation.activity')}}" extraclass="ml-3">
                                    {{__('All Donation Activity')}}
                                </x-link-with-popover>
                            </x-slot>
                        </x-admin.header-wrapper>

                        <x-error-msg/>
                        <x-flash-msg/>


                        <form class="forms-sample" method="post" action="{{route('tenant.admin.donation.activity.update',$activity->id)}}">
                            @csrf
                            <div class="row">
                                <div class="col-md-8">
                                    <x-fields.input type="hidden" name="lang" value="{{$lang_slug}}"/>
                                    <x-fields.input type="text" name="title" label="{{__('Title')}}" class="title" id="title" value="{{$activity->getTranslation('title',$lang_slug)}}"/>

                                    <div class="form-group permalink_label">
                                        <label class="text-dark">{{__('Permalink / Slug * : ')}}
                                            <span id="slug_show" class="display-inline"></span>
                                            <span id="slug_edit" class="display-inline">
                                         <button class="btn btn-info btn-sm slug_edit_button px-2 py-1 ml-1"> <i class="las la-edit"></i> </button>
                                          <input type="text" name="slug" value="{{$activity->slug}}" class="form-control blog_slug mt-2" >
                                          <button class="btn btn-info btn-sm slug_update_button mt-2 px-2 py-1" >{{__('Update')}}</button>
                                    </span>
                                        </label>
                                    </div>

                                    <x-summernote.textarea label="{{__('Activity Content')}}" name="description" value="{!! $activity->getTranslation('description',$lang_slug) !!}"/>
                                    <x-donation::backend.common-meta-data.edit-meta-markup :data="$activity"/>

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
                                                                @foreach($all_categories as $cat)
                                                                    <option value="{{$cat->id}}" {{$cat->id == $activity->category_id ? 'selected' : ''}}>{{$cat->getTranslation('title',$lang_slug)}}</option>
                                                                @endforeach
                                                            </x-fields.select>


                                                            <x-fields.select name="status" class="form-control" id="status" title="{{__('Status')}}">
                                                                <option value="{{\App\Enums\StatusEnums::DRAFT}}" {{$activity->status == \App\Enums\StatusEnums::DRAFT ? 'selected' : ''}}>{{__("Draft")}}</option>
                                                                <option value="{{\App\Enums\StatusEnums::PUBLISH}}" {{$activity->status == \App\Enums\StatusEnums::PUBLISH ? 'selected' : ''}}>{{__("Publish")}}</option>
                                                            </x-fields.select>

                                                            <x-landlord-others.edit-media-upload-image :label="__('Activity Image')" :name="'image'" :value="$activity->image"/>

                                                            <div class="submit_btn mt-5">
                                                                <button type="submit" class="btn btn-gradient-primary pull-right">{{__('Update Activity')}}</button>
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
    <script>
        (function($){
            "use strict";
            $(document).ready(function () {

                $(document).on('change','select[name="lang"]',function (e){
                    $(this).closest('form').trigger('submit');
                    $('input[name="lang"]').val($(this).val());
                });
                <x-btn.update/>


                function converToSlug(slug){
                    let finalSlug = slug.replace(/[^a-zA-Z0-9]/g, ' ');
                    finalSlug = slug.replace(/  +/g, ' ');
                    finalSlug = slug.replace(/\s/g, '-').toLowerCase().replace(/[^\w-]+/g, '-');
                    return finalSlug;
                }

                //Permalink Code
                var sl =  $('.blog_slug').val();
                var url = `{{url('/donation-activity/')}}/` + sl;
                var data = $('#slug_show').text(url).css('color', 'blue');
                var form = $('#blog_new_form');

                //Slug Edit Code
                $(document).on('click', '.slug_edit_button', function (e) {
                    e.preventDefault();
                    $('.blog_slug').removeClass('d-none');
                    $(this).hide();
                    $('.slug_update_button').removeClass('d-none');
                });

                //Slug Update Code
                $(document).on('click', '.slug_update_button', function (e) {
                    e.preventDefault();
                    $(this).hide();
                    $('.slug_edit_button').removeClass('d-none');
                    var update_input = $('.blog_slug').val();
                    var slug = converToSlug(update_input);
                    var url = `{{url('/donation-activity/')}}/` + slug;
                    $('#slug_show').text(url);
                    $('.blog_slug').addClass('d-none');
                });

            });
        })(jQuery)
    </script>

@endsection
