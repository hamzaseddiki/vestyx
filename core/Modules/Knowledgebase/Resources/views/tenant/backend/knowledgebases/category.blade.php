@extends('tenant.admin.admin-master')
@section('title') {{__('All Knowledgebase Category')}} @endsection

@section('style')
    <x-media-upload.css/>
    <x-datatable.css/>
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
                        <h4 class="card-title mb-5">{{__('All Knowledgebase Category')}}</h4>
                    <x-bulk-action permissions="knowledgebase-category-delete"/>
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
                        <button class="btn btn-info btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#new_testimonial">{{__('Add New Category')}}</button>
                    </x-slot>
                </x-admin.header-wrapper>
                <x-error-msg/>
                <x-flash-msg/>
                <x-datatable.table>
                    <x-slot name="th">
                        <th class="no-sort">
                            <div class="mark-all-checkbox">
                                <input type="checkbox" class="all-checkbox">
                            </div>
                        </th>
                        <th>{{__('ID')}}</th>
                        <th>{{__('Image')}}</th>
                        <th>{{__('Title')}}</th>
                        <th>{{__('Status')}}</th>
                        <th>{{__('Action')}}</th>
                    </x-slot>
                    <x-slot name="tr">
                        @foreach($all_categories as $data)
                            <tr>
                                <td>
                                    <x-bulk-delete-checkbox :id="$data->id"/>
                                </td>
                                <td>{{$data->id}}</td>
                                <td>
                                    @php
                                        $testimonial_img = get_attachment_image_by_id($data->image,null,true);
                                    @endphp
                                    {!! render_attachment_preview_for_admin($data->image ?? '') !!}
                                    @php  $img_url = $testimonial_img['img_url']; @endphp
                                </td>

                                <td>
                                    {!! $data->getTranslation('title',$lang_slug) !!}
                                </td>
                                <td>{{ \App\Enums\StatusEnums::getText($data->status) }}</td>
                                <td>
                                @can('knowledgebase-category-edit')
                                    <a href="#"
                                       data-bs-toggle="modal"
                                       data-bs-target="#testimonial_item_edit_modal"
                                       class="btn btn-primary btn-xs mb-3 mr-1 testimonial_edit_btn"
                                       data-bs-placement="top"
                                       title="{{__('Edit')}}"
                                       data-id="{{$data->id}}"
                                       data-action="{{route('tenant.admin.knowledgebase.category.update')}}"
                                       data-title="{{$data->getTranslation('title',$default_lang)}}"
                                       data-status="{{$data->status}}"
                                       data-imageid="{{$data->image}}"
                                       data-image="{{$img_url}}"
                                    >
                                        <i class="las la-edit"></i>
                                    </a>
                                    @endcan
                                    <x-delete-popover permissions="knowledgebase-category-delete" url="{{route('tenant.admin.knowledgebase.category.delete', $data->id)}}"/>
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-datatable.table>

            </div>
        </div>
    </div>

    @can('knowledgebase-category-create')
        <div class="modal fade" id="new_testimonial" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">{{__('New Category')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{route('tenant.admin.knowledgebase.category')}}" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="lang" value="{{$default_lang}}">
                            <x-fields.input name="title" label="{{__('Title')}}" />
                            <x-fields.media-upload name="image" title="{{__('Image')}}"/>

                            <x-fields.select name="status" title="{{__('Status')}}">
                                <option value="{{\App\Enums\StatusEnums::PUBLISH}}">{{__('Publish')}}</option>
                                <option value="{{\App\Enums\StatusEnums::DRAFT}}">{{__('Draft')}}</option>
                            </x-fields.select>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                            <button type="submit" class="btn btn-primary">{{__('Save Changes')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    @can('knowledgebase-category-edit')
        <div class="modal fade" id="testimonial_item_edit_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">{{__('Edit Category Item')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="#" id="testimonial_edit_modal_form" method="post"
                          enctype="multipart/form-data">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="lang" value="{{$default_lang}}">
                            <input type="hidden" name="id" class="knowledgebase_category_id" value="">
                            <x-fields.input name="title" label="{{__('Title')}}" class="edit_title" />
                            <x-fields.select name="status" title="{{__('Status')}}" class="edit_status">
                                <option value="{{\App\Enums\StatusEnums::PUBLISH}}">{{__('Publish')}}</option>
                                <option value="{{\App\Enums\StatusEnums::DRAFT}}">{{__('Draft')}}</option>
                            </x-fields.select>
                            <x-fields.media-upload name="image" title="{{__('Image')}}" dimentions="{{__('360x360 px image recommended')}}" />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                            <button type="submit" class="btn btn-primary">{{__('Save Changes')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endcan
    <x-media-upload.markup/>
@endsection

@section('scripts')
    <x-datatable.js/>
    <x-media-upload.js/>
    <script>
        $(document).ready(function($){
            "use strict";

            <x-bulk-action-js :url="route( 'tenant.admin.knowledgebase.category.bulk.action')"/>
            $(document).on('change','select[name="lang"]',function (e){
                $(this).closest('form').trigger('submit');
                $('input[name="lang"]').val($(this).val());
            });

            $(document).on('click', '.testimonial_edit_btn', function () {
                var el = $(this);
                var id = el.data('id');
                var title = el.data('title');
                var slug = el.data('slug');
                var action = el.data('action');

                var form = $('#testimonial_edit_modal_form');
                form.attr('action', action);
                form.find('.knowledgebase_category_id').val(id);
                form.find('.edit_title').val(title);
                form.find('.edit_slug').val(slug);
                form.find('.edit_status option[value="' + el.data('status') + '"]').attr('selected', true);

                var image = el.data('image');
                var imageid = el.data('imageid');

                if (imageid != '') {
                    form.find('.media-upload-btn-wrapper .img-wrap').html('<div class="attachment-preview"><div class="thumbnail"><div class="centered">' +
                        '<img class="avatar user-thumb" src="' + image + '" > </div></div></div>');
                    form.find('.media-upload-btn-wrapper input').val(imageid);
                    form.find('.media-upload-btn-wrapper .media_upload_form_btn').text('Change Image');
                }
            });

        });
    </script>
@endsection
