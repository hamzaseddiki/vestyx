@extends(route_prefix().'admin.admin-master')
@section('title') {{__('All Brands')}} @endsection

@section('style')
    <x-media-upload.css/>
    <x-datatable.css/>
@endsection

@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <x-admin.header-wrapper>
                    <x-slot name="left">
                        <h4 class="card-title mb-5">{{__('All Brands')}}</h4>
                    <x-bulk-action permissions="brand-delete"/>
                    </x-slot>
                    <x-slot name="right" class="d-flex">
                        <button class="btn btn-info btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#new_brands">{{__('Add New Brand')}}</button>
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
                        <th>{{__('URL')}}</th>
                        <th>{{__('Status')}}</th>
                        <th>{{__('Action')}}</th>
                    </x-slot>
                    <x-slot name="tr">
                        @foreach($all_brands as $data)
                            <tr>
                                <td>
                                    <x-bulk-delete-checkbox :id="$data->id"/>
                                </td>
                                <td>{{$data->id}}</td>
                                <td>
                                    @php
                                        $brands_img = get_attachment_image_by_id($data->image,null,true);
                                    @endphp
                                    {!! render_attachment_preview_for_admin($data->image ?? '') !!}
                                    @php  $img_url = $brands_img['img_url']; @endphp
                                </td>
                                <td>{{$data->url ?? ''}}</td>

                                <td>{{ \App\Enums\StatusEnums::getText($data->status)  }}</td>
                                <td>
                                    @can('brand-edit')
                                    <a href="#"
                                       data-bs-toggle="modal"
                                       data-bs-target="#brands_item_edit_modal"
                                       class="btn btn-primary btn-xs mb-3 mr-1 brands_edit_btn"
                                       data-bs-placement="top"
                                       title="{{__('Edit')}}"
                                       data-id="{{$data->id}}"
                                       data-action="{{route(route_prefix().'admin.brands.update')}}"
                                       data-url="{{$data->url}}"
                                       data-status="{{$data->status}}"
                                       data-imageid="{{$data->image}}"
                                       data-image="{{$img_url}}"
                                    >
                                        <i class="las la-edit"></i>
                                    </a>
                                    @endcan
                                    <x-delete-popover permissions="brand-delete" url="{{route(route_prefix().'admin.brands.delete', $data->id)}}"/>
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-datatable.table>

            </div>
        </div>
    </div>

    @can('brand-create')
        <div class="modal fade" id="new_brands" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">{{__('New Brand')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{route(route_prefix().'admin.brands')}}" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            @csrf

                            <x-fields.input name="url" label="{{__('URL')}}" />

                            <x-fields.select name="status" title="{{__('Status')}}">
                                <option value="{{\App\Enums\StatusEnums::PUBLISH}}">{{__('Publish')}}</option>
                                <option value="{{\App\Enums\StatusEnums::DRAFT}}">{{__('Draft')}}</option>
                            </x-fields.select>

                            <x-fields.media-upload name="image" title="{{__('Image')}}" dimentions="{{__('151 X 46 px image recommended')}}"/>

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

    @can('brand-edit')

        <div class="modal fade" id="brands_item_edit_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">{{__('Edit Brand Item')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="#" id="brands_edit_modal_form" method="post"
                          enctype="multipart/form-data">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="id" class="brands_id" value="">
                            <x-fields.input name="url" label="{{__('Name')}}" class="edit_url" />

                            <x-fields.select name="status" title="{{__('Status')}}" class="edit_status">
                                <option value="{{\App\Enums\StatusEnums::PUBLISH}}">{{__('Publish')}}</option>
                                <option value="{{\App\Enums\StatusEnums::DRAFT}}">{{__('Draft')}}</option>
                            </x-fields.select>

                            <x-fields.media-upload name="image" title="{{__('Image')}}" dimentions="{{__('151 X 46 px image recommended')}}"/>
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
    <x-media-upload.js/>
    <x-datatable.js/>
    <script>
        $(document).ready(function($){
            "use strict";

            <x-bulk-action-js :url="route( route_prefix().'admin.brands.bulk.action')" />
            $(document).on('change','select[name="lang"]',function (e){
                $(this).closest('form').trigger('submit');
                $('input[name="lang"]').val($(this).val());
            });
            //
            $(document).on('click', '.brands_edit_btn', function () {
                var el = $(this);
                var id = el.data('id');
                var url = el.data('url');
                var action = el.data('action');
                var image = el.data('image');
                var imageid = el.data('imageid');

                var form = $('#brands_edit_modal_form');
                form.attr('action', action);
                form.find('.brands_id').val(id);
                form.find('.edit_url').val(url);
                form.find('.edit_status option[value="' + el.data('status') + '"]').attr('selected', true);
                if (imageid != '') {
                    form.find('.media-upload-btn-wrapper .img-wrap').html('<div class="attachment-preview"><div class="thumbnail"><div class="centered"><img class="avatar user-thumb" src="' + image + '" > </div></div></div>');
                    form.find('.media-upload-btn-wrapper input').val(imageid);
                    form.find('.media-upload-btn-wrapper .media_upload_form_btn').text('Change Image');
                }
            });

        });
    </script>
@endsection
