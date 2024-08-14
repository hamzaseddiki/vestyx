@extends(route_prefix().'admin.admin-master')
@section('title') {{__('All Service Category')}} @endsection

@section('style')
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
                        <h4 class="card-title mb-5">{{__('All Service Category')}}</h4>
                    <x-bulk-action permissions="service-category-list"/>
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
                        <button class="btn btn-info btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#new_service_category">{{__('Add New')}}</button>
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
                        <th>{{__('Title')}}</th>
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
                                    {{ $data->getTranslation('title',$lang_slug)}}
                                </td>
                                <td>{{ \App\Enums\StatusEnums::getText($data->status)  }}</td>
                                <td>
                                    @can('service-category-delete')
                                        <a href="#"
                                           data-bs-toggle="modal"
                                           data-bs-target="#service_category_item_edit_modal"
                                           class="btn btn-primary btn-xs mb-3 mr-1 service_category_edit_btn"
                                           data-bs-placement="top"
                                           title="{{__('Edit')}}"
                                           data-id="{{$data->id}}"
                                           data-action="{{route(route_prefix().'admin.service.category.update')}}"
                                           data-title="{{$data->getTranslation('title',$default_lang)}}"
                                           data-status="{{$data->status}}">
                                            <i class="las la-edit"></i>
                                        </a>
                                    @endcan
                                    <x-delete-popover permissions="service-category-delete" url="{{route(route_prefix().'admin.service.category.delete', $data->id)}}"/>
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-datatable.table>

            </div>
        </div>
    </div>

        <div class="modal fade" id="new_service_category" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">{{__('New Service Category')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{route(route_prefix().'admin.service.category')}}" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="lang" value="{{$default_lang}}">
                            <x-fields.input name="title" label="{{__('Title')}}" value="{{old('title')}}"/>
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

        <div class="modal fade" id="service_category_item_edit_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">{{__('Edit Services Category Item')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="#" id="service_categoryy_edit_modal_form" method="post"
                          enctype="multipart/form-data">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="lang" value="{{$default_lang}}">
                            <input type="hidden" name="id" class="service_category_id" value="">
                            <x-fields.input name="title" label="{{__('Title')}}" class="edit_title"/>
                            <x-fields.select name="status" title="{{__('Status')}}" class="edit_status">
                                <option value="1">{{__('Publish')}}</option>
                                <option value="0">{{__('Draft')}}</option>
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

@endsection
@section('scripts')

    <x-datatable.js/>
    <script>
        $(document).ready(function($){
            "use strict";

            <x-bulk-action-js :url="route( route_prefix().'admin.service.category.bulk.action')" />
            $(document).on('change','select[name="lang"]',function (e){
                $(this).closest('form').trigger('submit');
                $('input[name="lang"]').val($(this).val());
            });

            $(document).on('click', '.service_category_edit_btn', function () {
                var el = $(this);
                var id = el.data('id');
                var title = el.data('title');
                var action = el.data('action');

                var form = $('#service_categoryy_edit_modal_form');
                form.attr('action', action);
                form.find('.service_category_id').val(id);
                form.find('.edit_title').val(title);
                form.find('.edit_status option[value="' + el.data('status') + '"]').attr('selected', true);

            });

        });
    </script>
@endsection
