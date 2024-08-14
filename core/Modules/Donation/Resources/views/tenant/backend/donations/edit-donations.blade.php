@extends(route_prefix().'admin.admin-master')
@section('title')   {{__('Edit Donation')}} @endsection
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
                                <h4 class="card-title mb-5">  {{__('Edit Donation')}}</h4>
                            </x-slot>
                            <x-slot name="right" class="d-flex">
                                <form action="{{route('tenant.admin.donation.edit',$donation->id)}}" method="get">
                                    <x-fields.select name="lang" title="{{__('Language')}}">
                                        @foreach(\App\Facades\GlobalLanguage::all_languages() as $lang)
                                            <option value="{{$lang->slug}}" @if($lang->slug === $lang_slug) selected @endif>{{$lang->name}}</option>
                                        @endforeach
                                    </x-fields.select>
                                </form>
                                <p></p>
                                <x-link-with-popover url="{{route('tenant.admin.donation')}}" extraclass="ml-3">
                                    {{__('All Donations')}}
                                </x-link-with-popover>
                            </x-slot>
                        </x-admin.header-wrapper>

                        <x-error-msg/>
                        <x-flash-msg/>


                        <form class="forms-sample" method="post" action="{{route('tenant.admin.donation.update',$donation->id)}}">
                            @csrf
                            <div class="row">
                                <div class="col-md-8">
                                    <x-fields.input type="hidden" name="lang" value="{{$lang_slug}}"/>
                                    <x-fields.input type="text" name="title" label="{{__('Title')}}" class="title" id="title" value="{{$donation->getTranslation('title',$lang_slug)}}"/>

                                    <x-slug.edit-markup value="{{$donation->slug}}"/>

                                    <x-summernote.textarea label="{{__('Blog Content')}}" name="description" value="{!! $donation->getTranslation('description',$lang_slug) !!}"/>
                                    <x-fields.textarea name="excerpt" label="{{__('Excerpt')}}" value="{!! $donation->getTranslation('excerpt',$lang_slug) !!}"/>

                                   <x-donation::backend.repeater.repeater-edit-markup :donation="$donation"/>
                                   <x-donation::backend.meta-data.donation-edit-meta-markup :donation="$donation"/>

                                </div>
                                <div class="col-lg-4 right-side-card">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card mt-4">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <x-fields.input type="number" name="amount" label="Amount" value="{{$donation->amount}}"/>
                                                            <x-fields.select name="category_id" title="{{__('Category')}}">
                                                                <option value="" readonly="" >{{__('Select Category')}}</option>
                                                                @foreach($all_category as $cat)
                                                                    <option value="{{$cat->id}}" {{$cat->id == $donation->category_id ? 'selected' : ''}}>{{$cat->getTranslation('title',$lang_slug)}}</option>
                                                                @endforeach
                                                            </x-fields.select>

                                                            <x-fields.input type="date" value="{{$donation->deadline}}" name="deadline" label="Dealdline" class="flat_date"/>
                                                            <x-fields.switcher value="{{$donation->popular}}" name="popular" label="Popular"/>


                                                            <x-fields.select name="status" class="form-control" id="status" title="{{__('Status')}}">
                                                                <option value="{{\App\Enums\StatusEnums::DRAFT}}" {{$donation->status == \App\Enums\StatusEnums::DRAFT ? 'selected' : ''}}>{{__("Draft")}}</option>
                                                                <option value="{{\App\Enums\StatusEnums::PUBLISH}}" {{$donation->status == \App\Enums\StatusEnums::PUBLISH ? 'selected' : ''}}>{{__("Publish")}}</option>
                                                            </x-fields.select>

                                                            <x-landlord-others.edit-media-upload-image :label="'Donation Image'" :name="'image'" :value="$donation->image"/>
                                                            <x-landlord-others.edit-media-upload-gallery :label="'Image Gallery'" :name="'image_gallery'" :value="$donation->image_gallery"/>

                                                            <div class="submit_btn mt-5">
                                                                <button type="submit" class="btn btn-gradient-primary pull-right">{{__('Update Donation')}}</button>
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
    <x-slug.js.edit :module="'donation'"/>


    <script>
        (function($){
            "use strict";
            $(document).ready(function () {

                $(document).on('change','select[name="lang"]',function (e){
                    $(this).closest('form').trigger('submit');
                    $('input[name="lang"]').val($(this).val());
                });
                <x-btn.update/>

                $('.gifts').select2();

                let gift_status = '{{$donation->gift_status}}';

                if(gift_status != 'on'){
                      $('.gifts').prop('disabled',true);
                }
                $(document).on('change','.add_gift_status',function(){
                    $('.gifts').prop('disabled',false);
                    if(this.checked){
                        $('.gift_select_wrapper').removeClass('d-none')
                    }else{
                        $('.gift_select_wrapper').addClass('d-none')
                    }
                });




            });
        })(jQuery)
    </script>

@endsection
