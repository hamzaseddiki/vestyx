@extends(route_prefix().'admin.admin-master')
@section('title') {{__('All Testimonial')}} @endsection

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
                        <h4 class="card-title mb-5">{{__('All Testimonial')}}</h4>
                    <x-bulk-action permissions="testimonial-delete"/>
                    </x-slot>
                    <x-slot name="right" class="d-flex">
                        <form action="" method="get">
                            <x-fields.select name="lang" title="{{__('Language')}}">
                                @foreach(\App\Facades\GlobalLanguage::all_languages(1) as $lang)
                                    <option value="{{$lang->slug}}" @if($lang->slug === $lang_slug) selected @endif>{{$lang->name}}</option>
                                @endforeach
                            </x-fields.select>
                        </form>
                        <p></p>
                        <button class="btn btn-info btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#new_testimonial">{{__('Add New Testimonial')}}</button>
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
                        <th>{{__('Name')}}</th>
                        <th>{{__('Designation')}}</th>
                        <th>{{__('Company')}}</th>
                        <th>{{__('Status')}}</th>
                        <th>{{__('Action')}}</th>
                    </x-slot>
                    <x-slot name="tr">
                        @foreach($all_testimonials as $data)
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
                                    {{ $data->getTranslation('name',$lang_slug)}}
                                </td>
                                <td>{{$data->getTranslation('designation',$lang_slug)}}</td>
                                <td>{{$data->getTranslation('company',$lang_slug)}}</td>
                                <td>{{ \App\Enums\StatusEnums::getText($data->status)  }}</td>
                                <td>
                                @can('testimonial-edit')
                                    <a href="#"
                                       data-bs-toggle="modal"
                                       data-bs-target="#testimonial_item_edit_modal"
                                       class="btn btn-primary btn-xs mb-3 mr-1 testimonial_edit_btn"
                                       data-bs-placement="top"
                                       title="{{__('Edit')}}"
                                       data-id="{{$data->id}}"
                                       data-action="{{route(route_prefix().'admin.testimonial.update')}}"
                                       data-name="{{$data->getTranslation('name',$default_lang)}}"
                                       data-status="{{$data->status}}"
                                       data-description="{{$data->getTranslation('description',$default_lang)}}"
                                       data-designation="{{$data->getTranslation('designation',$default_lang)}}"
                                       data-company="{{$data->getTranslation('company',$default_lang)}}"
                                       data-imageid="{{$data->image}}"
                                       data-image="{{$img_url}}"
                                    >
                                        <i class="las la-edit"></i>
                                    </a>

                                    <x-clone-icon :action="route(route_prefix().'admin.testimonial.clone')" :id="$data->id"/>
                                    @endcan
                                    <x-delete-popover permissions="testimonial-delete" url="{{route(route_prefix().'admin.testimonial.delete', $data->id)}}"/>
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-datatable.table>

            </div>
        </div>
    </div>

    @can('testimonial-create')
        <div class="modal fade" id="new_testimonial" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">{{__('New Testimonial')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{route(route_prefix().'admin.testimonial')}}" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="lang" value="{{$default_lang}}">
                            <x-fields.input name="name" label="{{__('Name')}}" />
                            <x-fields.input name="designation" label="{{__('Designation')}}" />
                            <x-fields.input name="company" label="{{__('Company')}}" />
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

    @can('testimonial-edit')
        <div class="modal fade" id="testimonial_item_edit_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">{{__('Edit Testimonial Item')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="#" id="testimonial_edit_modal_form" method="post"
                          enctype="multipart/form-data">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="lang" value="{{$default_lang}}">
                            <input type="hidden" name="id" class="testimonial_id" value="">
                            <x-fields.input name="name" label="{{__('Name')}}" class="edit_name" />
                            <x-fields.input name="designation" label="{{__('Designation')}}" class="edit_designation" />
                            <x-fields.input name="company" label="{{__('Company')}}" class="edit_company" />
                            <x-fields.textarea name="description" label="{{__('Description')}}" class="edit_description" />
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
    <x-media-upload.js/>
    <x-datatable.js/>
    <script>
        $(document).ready(function($){
            "use strict";

            <x-bulk-action-js :url="route( route_prefix().'admin.testimonial.bulk.action')" />
            $(document).on('change','select[name="lang"]',function (e){
                $(this).closest('form').trigger('submit');
                $('input[name="lang"]').val($(this).val());
            });

            $(document).on('click', '.testimonial_edit_btn', function () {
                var el = $(this);
                var id = el.data('id');
                var name = el.data('name');
                var designation = el.data('designation');
                var company = el.data('company');
                var action = el.data('action');
                var description = el.data('description');


                var form = $('#testimonial_edit_modal_form');
                form.attr('action', action);
                form.find('.testimonial_id').val(id);
                form.find('.edit_name').val(name);
                form.find('.edit_description').val(description);
                form.find('.edit_designation').val(designation);
                form.find('.edit_company').val(company);
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
