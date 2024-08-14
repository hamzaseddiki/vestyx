@extends('landlord.admin.admin-master')
@section('title')
    {{__('About Area Demo Data (Donation Home)')}}
@endsection

@section('style')
    <x-datatable.css/>
    <x-summernote.css/>
@endsection

@section('content')
    @php
        $lang_slug = request()->get('lang') ?? \App\Facades\GlobalLanguage::default_slug();
    @endphp
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
                <x-error-msg/>
                <x-flash-msg/>
            </div>
            <div class="col-lg-12 mt-5">
                <div class="card">
                    <div class="card-body">

                        <x-admin.header-wrapper>
                            <x-slot name="left">
                                <h4 class="header-title">{{__('About Area Demo Data (Donation Home)')}}</h4>
                            </x-slot>
                            <x-slot name="right" class="d-flex">

                                <form action="" method="get">
                                    <x-fields.select name="lang" title="{{__('Language')}}">
                                        @foreach(\App\Models\Language::all() as $lang)
                                            @continue($lang->slug == 'en_GB')
                                            @php
                                                $slug = $lang->slug;
                                            @endphp
                                            <option value="{{$slug}}" @if($lang->slug === $lang_slug) selected @endif>{{$lang->name}}</option>
                                        @endforeach
                                    </x-fields.select>
                                </form>

                              <p></p>

                                <x-link-with-popover  url="{{route('landlord.admin.seeder.page.builder.index')}}" extraclass="ml-3">
                                    {{__('Go Back')}}
                                </x-link-with-popover>

                            </x-slot>
                        </x-admin.header-wrapper>

                        <div class="table-wrap table-responsive">
                            <table class="table table-default table-striped table-bordered">

                                <thead>
                                <th>{{__('ID#')}}</th>
                                <th>{{__('Title')}}</th>
                                <th>{{__('Description')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>

                                <tbody>

                                <tr>
                                    <td>{{ $all_data_decoded['id']}}</td>
                                    <td>{!! $all_data_decoded['title_'.$lang_slug] ?? '' !!}</td>
                                    <td>{{ \Illuminate\Support\Str::words($all_data_decoded['description_'.$lang_slug] ?? '',20) }}</td>
                                    <td>
{{--                                        <a class="btn btn-success btn-sm mb-3"--}}
{{--                                           href="{{ get_page_builder_addon_preview_image('Donation','header-01.png') }}"--}}
{{--                                           target="_blank"><i class="las la-eye"></i>--}}
{{--                                        </a>--}}

                                        <a href="#"
                                           data-id="{{$all_data_decoded['id']}}"
                                           data-title="{!! $all_data_decoded['title_'.$lang_slug]  ?? '' !!}"
                                           data-description="{{$all_data_decoded['description_'.$lang_slug] ?? ''}}"
                                           data-button_text="{{$all_data_decoded['button_text_'.$lang_slug] ?? ''}}"
                                           data-bs-toggle="modal"
                                           data-bs-target="#donation_category_seeder_edit_modal"
                                           class="btn btn-lg btn-info btn-sm mb-3 mr-1 donation_category_seeder_edit_btn"
                                        >
                                            {{__("Edit Data")}}

                                        </a>
                                    </td>
                                </tr>


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="donation_category_seeder_edit_modal" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Edit Demo Widget Data')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                </div>

                <form action="" method="post" enctype="multipart/form-data" class="donation_category_seeder_edit_form">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" class="category_id">
                        <input type="hidden" name="lang" value="{{$default_lang}}">

                        <div class="form-group">
                            <label for="order_status">{{__('Heading Title')}}</label>
                            <input type="text" name="title" class="form-control title">
                        </div>

                        <div class="form-group">
                            <label for="order_status">{{__('Button Text')}}</label>
                            <input type="text" name="button_text" class="form-control button_text">
                        </div>

                        <x-summernote.textarea label="{{__('Description')}}" name="description" class="description"/>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary btn-sm">{{__('Save Change')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <x-datatable.js/>
    <x-summernote.js/>
    <script>
        $(document).ready(function($){
            "use strict";

            $(document).on('click','.donation_category_seeder_edit_btn',function(){
                let el = $(this);
                let form = $('.donation_category_seeder_edit_form');
                let id = el.data('id');
                let title = el.data('title');
                let button_text = el.data('button_text');
                let button_right_text = el.data('button_right_text');
                let description = el.data('description');

                form.find('.category_id').val(id);
                form.find('.title').val(title);
                form.find('.button_text').val(button_text);
                form.find('.button_right_text').val(button_right_text);
                $('.description').summernote('code',description);

            });

            $(document).on('change','select[name="lang"]',function (e){
                $(this).closest('form').trigger('submit');
                $('input[name="lang"]').val($(this).val());
            });

        });
    </script>
@endsection
