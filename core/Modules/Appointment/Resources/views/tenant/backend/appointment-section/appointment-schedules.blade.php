@extends('tenant.admin.admin-master')
@section('title')
    {{__('All Appointment Schedules')}}
@endsection

@section('style')
    <x-media-upload.css/>
    <x-datatable.css/>
    <link rel="stylesheet" href="{{global_asset('assets/common/css/jquery.timepicker.min2.css')}}">

    <style>
        .ui-timepicker-container.ui-timepicker-standard {
            z-index: 1059 !important;
        }
    </style>
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
                        <h4 class="card-title mb-5">{{__('All Appointment Schedules')}}</h4>
                        <x-bulk-action permissions="donation-category-delete"/>
                    </x-slot>
                    <x-slot name="right" class="d-flex">
                        <button class="btn btn-info btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#new_testimonial">{{__('Add New Schedule')}}</button>
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
                        <th>{{__('Day')}}</th>
                        <th>{{__('Day Type')}}</th>
                        <th>{{__('Schedule Time')}}</th>
                        <th>{{__('Allow Multiple')}}</th>
                        <th>{{__('Status')}}</th>
                        <th>{{__('Action')}}</th>
                    </x-slot>
                    <x-slot name="tr">
                        @foreach($all_times as $data)
                            <tr>
                                <td>
                                    <x-bulk-delete-checkbox :id="$data->id"/>
                                </td>
                                <td>{{$data->id}}</td>
                                <td>
                                    {{ $data->day?->day }}
                                </td>

                                <td>
                                    {{ $data->type?->getTranslation('title',default_lang()) }}
                                </td>

                                <td> <span class="badge badge-info">{{ $data->time}}</span></td>
                                <td> {{ !empty($data->allow_multiple) ? __('Yes') : __('No') }}</td>
                                <td>{{ \App\Enums\StatusEnums::getText($data->status) }}</td>
                                <td>

                                    @php
                                        $time_explode = explode('-',$data->time) ?? [];
                                        $start_time = current($time_explode) ?? '';
                                        $end_time = end($time_explode) ?? '';
                                    @endphp

                                    <a href="#"
                                       data-bs-toggle="modal"
                                       data-bs-target="#testimonial_item_edit_modal"
                                       class="btn btn-primary btn-xs mb-3 mr-1 testimonial_edit_btn"
                                       data-bs-placement="top"
                                       title="{{__('Edit')}}"
                                       data-id="{{$data->id}}"
                                       data-day_id="{{$data->day_id}}"
                                       data-action="{{route('tenant.admin.appointment.schedule.update')}}"
                                       data-start_time="{{$start_time}}"
                                       data-end_time="{{$end_time}}"
                                       data-status="{{$data->status}}"
                                       data-allow_multiple="{{$data->allow_multiple}}"
                                       data-day_type="{{$data->day_type}}"
                                    >
                                        <i class="las la-edit"></i>
                                    </a>

                                    <x-delete-popover permissions="donation-category-delete" url="{{route('tenant.admin.appointment.schedule.delete', $data->id)}}"/>
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-datatable.table>

            </div>
        </div>
    </div>


    <div class="modal fade" id="new_testimonial" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{__('New Schedule')}}</h5>
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('tenant.admin.appointment.schedule')}}" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf

                        <x-fields.select name="day_id" title="{{__('Select Day')}}">
                            @foreach($days as $day)
                                <option value="{{$day->id}}">{{ $day->getTranslation('day',default_lang()) }}</option>
                            @endforeach
                        </x-fields.select>


                        <x-fields.select name="day_type" title="{{__('Select Day Type')}}">
                            @foreach($day_types as $type)
                                <option value="{{$type->id}}">{{ $type->getTranslation('title',default_lang()) }}</option>
                            @endforeach
                        </x-fields.select>

                        <x-fields.input type="text" name="start_time" label="Start Time" class="timepicker"/>
                        <x-fields.input type="text" name="end_time" label="End Time" class="timepicker_end"/>

                        <x-fields.switcher name="allow_multiple" extra="allow_multiple" label="{{__('Allow Multiple')}}"/>

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



    <div class="modal fade" id="testimonial_item_edit_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{__('Edit Schedule')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" id="testimonial_edit_modal_form" method="post"
                      enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="id" class="time_id" value="">
                        <x-fields.select name="day_id" title="{{__('Select Day')}}" class="day_id">
                            @foreach($days as $day)
                                <option value="{{$day->id}}">{{ $day->getTranslation('day',default_lang()) }}</option>
                            @endforeach
                        </x-fields.select>

                        <x-fields.select name="day_type" title="{{__('Select Day Type')}}" class="day_type">
                            @foreach($day_types as $type)
                                <option value="{{$type->id}}">{{ $type->getTranslation('title',default_lang()) }}</option>
                            @endforeach
                        </x-fields.select>

                        <x-fields.input type="text" name="start_time" label="Start Time" class="timepicker start_time"/>
                        <x-fields.input type="text" name="end_time" label="End Time" class="timepicker_end end_time"/>

                        <x-fields.switcher name="allow_multiple" extra="allow_multiple" label="{{__('Allow Multiple')}}"/>

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

    <x-media-upload.markup/>
@endsection
@section('scripts')
    <script src="{{global_asset('assets/common/js/jquery.timepicker.min2.js')}}"></script>

    <x-datatable.js/>
    <script>
        $(document).ready(function($){
            "use strict";

            $('.timepicker').timepicker({
                timeFormat: 'h:mm p',
                interval: 10,
                minTime: '8',
                maxTime: '11:00pm',
                defaultTime: '11',
                startTime: '8:00',
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });

            $('.timepicker_end').timepicker({
                timeFormat: 'h:mm p',
                interval: 10,
                minTime: '8',
                maxTime: '11:00pm',
                defaultTime: '11',
                startTime: '8:10',
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });

            <x-bulk-action-js :url="route( 'tenant.admin.appointment.schedule.bulk.action')"/>
                $(document).on('change','select[name="lang"]',function (e){
                    $(this).closest('form').trigger('submit');
                    $('input[name="lang"]').val($(this).val());
                });

            $(document).on('click', '.testimonial_edit_btn', function () {
                var el = $(this);
                var id = el.data('id');
                var start_time = el.data('start_time');
                var end_time = el.data('end_time');
                var allow_multiple = el.data('allow_multiple');

                var day_id = el.data('day_id');
                var day_type = el.data('day_type');
                var action = el.data('action');

                var form = $('#testimonial_edit_modal_form');
                form.attr('action', action);
                form.find('.time_id').val(id);
                form.find('.start_time').val(start_time);
                form.find('.end_time').val(end_time);
                form.find('.edit_status option[value="' + el.data('status') + '"]').attr('selected', true);
                form.find('.day_id option[value="' + day_id + '"]').attr('selected', true);
                form.find('.day_type option[value="' + day_type + '"]').attr('selected', true);

                if(allow_multiple == 'on'){
                    form.find('.allow_multiple').attr('checked',true);
                }else{
                    form.find('.allow_multiple').attr('checked',false);
                }
            });

        });
    </script>
@endsection
