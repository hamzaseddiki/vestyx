@extends('landlord.admin.admin-master')
@section('title')
    {{__('Contact Info Widget Demo Data')}}
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
                                <h4 class="header-title">{{__('Contact Info  Widget Demo Data')}}</h4>
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

                                <x-link-with-popover  url="{{route('landlord.admin.seeder.widget.index')}}" extraclass="ml-3">
                                    {{__('Go Back')}}
                                </x-link-with-popover>

                            </x-slot>
                        </x-admin.header-wrapper>

                        <div class="table-wrap table-responsive">
                            <table class="table table-default table-striped table-bordered">

                                <thead>
                                <th>{{__('ID#')}}</th>
                                <th>{{__('Widget Title')}}</th>
                                <th>{{__('Location')}}</th>
                                <th>{{__('Email')}}</th>
                                <th>{{__('Phone')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>

                                <tbody>

                                <tr>
                                    <td>{{ $all_data_decoded['id']}}</td>
                                    <td>{!! $all_data_decoded['widget_title_'.$lang_slug] ?? '' !!}</td>
                                    <td>{!! $all_data_decoded['location_'.$lang_slug] ?? '' !!}</td>
                                    <td>{!! $all_data_decoded['email_'.$lang_slug] ?? '' !!}</td>
                                    <td>{!! $all_data_decoded['phone_'.$lang_slug] ?? '' !!}</td>
                                    <td>
                                        <a href="#"
                                           data-id="{{$all_data_decoded['id']}}"
                                           data-title="{!! $all_data_decoded['widget_title_'.$lang_slug]  ?? '' !!}"
                                           data-location="{!! $all_data_decoded['location_'.$lang_slug]  ?? '' !!}"
                                           data-email="{!! $all_data_decoded['email_'.$lang_slug]  ?? '' !!}"
                                           data-phone="{!! $all_data_decoded['phone_'.$lang_slug]  ?? '' !!}"
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
                            <label for="order_status">{{__('Widget Title')}}</label>
                            <input type="text" name="title" class="form-control title">
                        </div>

                        <div class="form-group">
                            <label for="order_status">{{__('Address')}}</label>
                            <input type="text" name="location" class="form-control location">
                        </div>

                        <div class="form-group">
                            <label for="order_status">{{__('Email')}}</label>
                            <input type="text" name="email" class="form-control email">
                        </div>

                        <div class="form-group">
                            <label for="order_status">{{__('Phone')}}</label>
                            <input type="text" name="phone" class="form-control phone">
                        </div>

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
                let location = el.data('location');
                let email = el.data('email');
                let phone = el.data('phone');

                form.find('.category_id').val(id);
                form.find('.title').val(title);
                form.find('.location').val(location);
                form.find('.email').val(email);
                form.find('.phone').val(phone);

            });

            $(document).on('change','select[name="lang"]',function (e){
                $(this).closest('form').trigger('submit');
                $('input[name="lang"]').val($(this).val());
            });

        });
    </script>
@endsection
