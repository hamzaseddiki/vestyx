@extends('landlord.admin.admin-master')
@section('title')
    {{__('Product Demo Data')}}
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
                                <h4 class="header-title">{{__('Product Demo Data')}}</h4>
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

                                <x-link-with-popover  url="{{route('landlord.admin.seeder.product.index')}}" extraclass="ml-3">
                                    {{__('Go Back')}}
                                </x-link-with-popover>

                            </x-slot>
                        </x-admin.header-wrapper>
                        <div class="table-wrap table-responsive">
                            <table class="table table-default table-striped table-bordered">

                                <thead>
                                <th>{{__('SL#')}}</th>
                                <th>{{__('Title')}}</th>
                                <th>{{__('Description')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>

                                <tbody>

                                @foreach($all_data_decoded->data ?? [] as $data)
                                    @php

                                        $name_decoded = (array) json_decode($data->name) ?? [];
                                        $summary_decoded = (array) json_decode(get_data_without_extra_space_or_new_line($data->summary)) ?? [];
                                        $description_decoded = (array) json_decode(get_data_without_extra_space_or_new_line($data->description)) ?? [];
                                    @endphp
                                    <tr>

                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $name_decoded[$lang_slug] ?? '' }}</td>
                                        <td>{!! \Illuminate\Support\Str::words($description_decoded[$lang_slug] ?? '',20) !!}</td>
                                        <td>
                                            <a href="#"
                                               data-id="{{$data->id}}"
                                               data-name="{{$name_decoded[$lang_slug] ?? ''}}"
                                               data-summary="{!! $summary_decoded[$lang_slug] ?? '' !!}"
                                               data-description="{!! $description_decoded[$lang_slug] ?? '' !!}"
                                               data-bs-toggle="modal"
                                               data-bs-target="#donation_category_seeder_edit_modal"
                                               class="btn btn-lg btn-info btn-sm mb-3 mr-1 donation_category_seeder_edit_btn"
                                            >
                                                {{__("Edit Data")}}

                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="donation_category_seeder_edit_modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Edit Demo Data')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                </div>

                <form action="" method="post" enctype="multipart/form-data" class="donation_category_seeder_edit_form">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" class="donation_id">
                        <input type="hidden" name="lang" value="{{$default_lang}}">

                        <div class="form-group">
                            <label for="order_status">{{__('Name')}}</label>
                            <input type="text" name="name" class="form-control name">
                        </div>

                        <x-summernote.textarea label="{{__('Summary')}}" name="summary" class="summary"/>
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
                let name = el.data('name');
                let summary = el.data('summary');
                let description = el.data('description');

                form.find('.donation_id').val(id);
                form.find('.name').val(name);
                $('.summary').summernote('code',summary);
                $('.description').summernote('code',description);

            });

            $(document).on('change','select[name="lang"]',function (e){
                $(this).closest('form').trigger('submit');
                $('input[name="lang"]').val($(this).val());
            });

        });
    </script>
@endsection
