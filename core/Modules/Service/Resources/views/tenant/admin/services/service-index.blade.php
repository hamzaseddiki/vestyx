@extends(route_prefix().'admin.admin-master')
@section('title') {{__('All Services')}} @endsection

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
                        <h4 class="card-title mb-5">{{__('All Services')}}</h4>
                    <x-bulk-action permissions="service-delete"/>
                    </x-slot>
                    <x-slot name="right" class="d-flex">
                        <form action="" method="get">
                            <x-fields.select name="lang" title="{{__('Language')}}">
                                @foreach(\App\Facades\GlobalLanguage::all_languages() as $lang)
                                    <option value="{{$lang->slug}}" @if($lang->slug === $default_lang) selected @endif>{{$lang->name}}</option>
                                @endforeach
                            </x-fields.select>
                        </form>
                        <p></p>
                        <a class="btn btn-info btn-sm mb-3" href="{{route('tenant.admin.service.add')}}">{{__('Add New Services')}}</a>
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
                        <th>{{__('Category')}}</th>
                        <th>{{__('Status')}}</th>
                        <th>{{__('Action')}}</th>
                    </x-slot>
                    <x-slot name="tr">
                        @foreach($all_services as $data)
                            <tr>
                                <td>
                                    <x-bulk-delete-checkbox :id="$data->id"/>
                                </td>
                                <td>{{$data->id}}</td>
                                <td>
                                    {!! render_attachment_preview_for_admin($data->image ?? '') !!}
                                </td>
                                <td>
                                    {{ $data->getTranslation('title',$default_lang)}}
                                </td>
                                <td>
                                    <span class="badge badge-info">{{optional($data->category)->getTranslation('title',$default_lang)}}</span>
                                </td>
                                <td>{{ \App\Enums\StatusEnums::getText($data->status)  }}</td>
                                <td>
                                    @can('service-edit')
                                    <a href="{{route('tenant.admin.service.edit',$data->id)}}"
                                       class="btn btn-primary btn-xs mb-3 mr-1 service_edit_btn"
                                       data-bs-placement="top"
                                       title="{{__('Edit')}}"
                                    >
                                        <i class="las la-edit"></i>
                                    </a>
                                    @endcan
                                    <x-delete-popover permissions="service-delete" url="{{route(route_prefix().'admin.service.delete', $data->id)}}"/>

                                    <a href="{{ route(route_prefix().'frontend.service.single',$data->slug) }}" class="btn btn-success btn-xs mb-3 mr-1" target="_blank">
                                        <i class="las la-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-datatable.table>

            </div>
        </div>
    </div>

    @can('service-create')
        <div class="modal fade" id="new_service" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">{{__('New Services')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{route(route_prefix().'admin.service')}}" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="lang" value="{{$default_lang}}">
                            <x-fields.input name="title" label="{{__('Title')}}" />
                            <x-fields.textarea name="description" label="{{__('Description')}}" />
                            <x-fields.select name="status" title="{{__('Status')}}">
                                <option value="{{\App\Enums\StatusEnums::PUBLISH}}">{{__('Publish')}}</option>
                                <option value="{{\App\Enums\StatusEnums::DRAFT}}">{{__('Draft')}}</option>
                            </x-fields.select>
                            <x-fields.media-upload name="image" title="{{__('Image')}}" dimentions="{{__('360x360 px image recommended')}}"/>
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

        @can('service-edit')
        <div class="modal fade" id="service_item_edit_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">{{__('Edit Services Item')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="#" id="service_edit_modal_form" method="post"
                          enctype="multipart/form-data">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="lang" value="{{$default_lang}}">
                            <input type="hidden" name="id" class="service_id" value="">
                            <x-fields.input name="title" label="{{__('Title')}}" class="edit_title"/>
                            <x-fields.textarea name="description" label="{{__('Description')}}" class="edit_description" />
                            <x-fields.select name="status" title="{{__('Status')}}" class="edit_status">
                                <option value="{{\App\Enums\StatusEnums::PUBLISH}}">{{__('Publish')}}</option>
                                <option value="{{\App\Enums\StatusEnums::DRAFT}}">{{__('Draft')}}</option>
                            </x-fields.select>
                            <x-fields.media-upload name="image" title="{{__('Image')}}" dimentions="{{__('1920 X 1080 px image recommended')}}" />
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

            <x-bulk-action-js :url="route( route_prefix().'admin.service.bulk.action')" />
            $(document).on('change','select[name="lang"]',function (e){
                $(this).closest('form').trigger('submit');
                $('input[name="lang"]').val($(this).val());
            });

            $(document).on('click', '.service_edit_btn', function () {
                var el = $(this);
                var id = el.data('id');
                var title = el.data('title');
                var action = el.data('action');
                var description = el.data('description');
                var image = el.data('image');
                var imageid = el.data('imageid');

                var form = $('#service_edit_modal_form');
                form.attr('action', action);
                form.find('.service_id').val(id);
                form.find('.edit_title').val(title);
                form.find('.edit_description').val(description);
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
