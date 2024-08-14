@extends('tenant.admin.admin-master')
@section('title') {{__('All Donations Category')}} @endsection

@section('style')
    <x-media-upload.css/>
    <x-datatable.css/>
@endsection

@section('content')
    @php
        $lang_slug = request()->get('lang') ?? \App\Facades\GlobalLanguage::default_slug();
    @endphp
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12 mt-5">
                <div class="card">

                    <div class="card-body">
                        <x-error-msg/>
                        <x-flash-msg/>
                        <x-admin.header-wrapper>
                            <x-slot name="left">
                                <h4 class="card-title mb-5">{{__('All Donation Updates')}} : <span class="text-primary">{{$donation->getTranslation('title',$default_lang)}}</span> </h4>
                                <x-bulk-action/>
                            </x-slot>
                            <x-slot name="right" class="d-flex">
                                <form action="{{route('tenant.admin.single.donation.update',$donation->id)}}" method="get">
                                    <x-fields.select name="lang" title="{{__('Language')}}">
                                        @foreach(\App\Facades\GlobalLanguage::all_languages() as $lang)
                                            <option value="{{$lang->slug}}" @if($lang->slug === $lang_slug) selected @endif>{{$lang->name}}</option>
                                        @endforeach
                                    </x-fields.select>
                                </form>
                                <p></p>
                                <button class="btn btn-info btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#cause_update_add_modal">{{__('Add New')}}</button>
                                <a class="btn btn-dark btn-sm mb-3" href="{{route('tenant.admin.donation')}}">{{__('Go Back')}}</a>
                            </x-slot>
                        </x-admin.header-wrapper>

                        <x-datatable.table>
                            <x-slot name="th">
                                <th class="no-sort">
                                    <div class="mark-all-checkbox">
                                        <input type="checkbox" class="all-checkbox">
                                    </div>
                                </th>
                                <th>{{__('ID')}}</th>
                                <th>{{__('Title')}}</th>
                                <th>{{__('Image')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                            </x-slot>
                            <x-slot name="tr">
                                @foreach($all_updates as $data)
                                    <tr>
                                        <td>
                                            <x-bulk-delete-checkbox :id="$data->id"/>
                                        </td>
                                        <td>{{$data->id}}</td>
                                        <td>
                                            {{ $data->getTranslation('title',$lang_slug)}}
                                        </td>
                                        <td>
                                            @php
                                                $testimonial_img = get_attachment_image_by_id($data->image,null,true);
                                            @endphp
                                            {!! render_attachment_preview_for_admin($data->image ?? '') !!}
                                            @php  $img_url = $testimonial_img['img_url']; @endphp
                                        </td>
                                        <td>
                                            {{ \App\Enums\StatusEnums::getText($data->status) }}
                                        </td>
                                        <td>
                                            @can('donation-category-edit')
                                                <a href="#"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#testimonial_item_edit_modal"
                                                   class="btn btn-primary btn-xs mb-3 mr-1 testimonial_edit_btn"
                                                   data-bs-placement="top"
                                                   title="{{__('Edit')}}"
                                                   data-id="{{$data->id}}"
                                                   data-title="{{$data->getTranslation('title',$lang_slug)}}"
                                                   data-description="{{$data->getTranslation('description',$lang_slug)}}"
                                                   data-imageid="{{$data->image}}"
                                                   data-image="{{$img_url}}"
                                                   data-status="{{$data->status}}"
                                                >
                                                    <i class="las la-edit"></i>
                                                </a>
                                            @endcan
                                            <x-delete-popover url="{{route('tenant.admin.single.donation.update.delete', $data->id)}}"/>
                                        </td>
                                    </tr>
                                @endforeach
                            </x-slot>
                        </x-datatable.table>
                        </div>

                    </div>
                </div>
            </div>
            </div>


    <div class="modal fade" id="cause_update_add_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Add New Update')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                </div>
                <form action="{{route('tenant.admin.single.donation.update.store')}}" method="post" id="addCauseUpdateForm">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="donation_id" value="{{$donation->id}}">
                        <input type="hidden" name="lang" value="{{$default_lang}}">

                        <div class="form-group">
                            <label for="name">{{__('Title')}}</label>
                            <input type="text" class="form-control" name="title" placeholder="{{__('title')}}">
                        </div>
                        <div class="form-group">
                            <label for="description">{{__('Description')}}</label>
                            <textarea name="description" class="form-control" cols="30" rows="5"
                                      placeholder="{{__('Description')}}"></textarea>
                        </div>

                        <x-fields.select name="status" title="{{__('Status')}}">
                            <option value="{{\App\Enums\StatusEnums::PUBLISH}}">{{__('Publish')}}</option>
                            <option value="{{\App\Enums\StatusEnums::DRAFT}}">{{__('Draft')}}</option>
                        </x-fields.select>

                        <x-fields.media-upload name="image" title="Image"/>
                    </div>
                    <div class="modal-footer">
                        <button id="submit" type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="testimonial_item_edit_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Edit Item Update')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                </div>
                <form action="{{route('tenant.admin.single.donation.update.update')}}" method="post">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="id" value="" id="donation_update_id">
                        <input type="hidden" name="donation_id" value="{{$donation->id}}">
                        <input type="hidden" name="lang" value="{{$default_lang}}">
                        <div class="form-group">
                            <label for="edit_name">{{__('Title')}}</label>
                            <input type="text" class="form-control" name="title" placeholder="{{__('title')}}">
                        </div>
                        <div class="form-group">
                            <label for="description">{{__('Description')}}</label>
                            <textarea name="description" class="form-control description" cols="30" rows="5"
                                      placeholder="{{__('Description')}}"></textarea>
                        </div>

                        <x-fields.select name="status" title="{{__('Status')}}" class="edit_status">
                            <option value="{{\App\Enums\StatusEnums::PUBLISH}}">{{__('Publish')}}</option>
                            <option value="{{\App\Enums\StatusEnums::DRAFT}}">{{__('Draft')}}</option>
                        </x-fields.select>

                        <x-fields.media-upload name="image" title="{{__('Image')}}" />

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button id="update" type="submit" class="btn btn-primary">{{__('Save Change')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <x-media-upload.markup/>
@endsection
@section('scripts')
      <x-datatable.js/>
      <x-media-upload.js/>
    <script src="{{asset('assets/backend/js/dropzone.js')}}"></script>
    <script>
        <x-btn.submit/>
        <x-btn.update/>
        (function ($) {
            "use strict";
            $(document).ready(function () {
                <x-bulk-action-js :url="route('tenant.admin.single.donation.update.bulk.action')"/>

                    $(document).on('change','select[name="lang"]',function (e){
                        $(this).closest('form').trigger('submit');
                        $('input[name="lang"]').val($(this).val());
                    });

                $(document).on('click', '.testimonial_edit_btn', function () {
                    var modal = $('#testimonial_item_edit_modal');
                    var el = $(this);
                    var id = el.data('id');
                    var title = el.data('title');
                    var description = el.data('description');
                    modal.find('input[name="title"]').val(title);
                    modal.find('textarea[name="description"]').val(description);
                    $('#donation_update_id').val(id);
                    modal.find('.edit_status option[value="' + el.data('status') + '"]').attr('selected', true);

                    var image = el.data('image');
                    var imageid = el.data('imageid');

                    if (imageid != '') {
                        modal.find('.media-upload-btn-wrapper .img-wrap').html('<div class="attachment-preview"><div class="thumbnail"><div class="centered">' +
                            '<img class="avatar user-thumb" src="' + image + '" > </div></div></div>');
                        modal.find('.media-upload-btn-wrapper input').val(imageid);
                        modal.find('.media-upload-btn-wrapper .media_upload_form_btn').text('Change Image');
                    }
                });
            });
        })(jQuery)
    </script>
@endsection
