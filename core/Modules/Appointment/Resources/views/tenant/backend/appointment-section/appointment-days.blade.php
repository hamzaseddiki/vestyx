@extends('tenant.admin.admin-master')
@section('title')
    {{__('All Appointment Days')}}
@endsection

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
                        <h4 class="card-title mb-5">{{__('All Appointment Days')}}</h4>
                    <x-bulk-action permissions="donation-category-delete"/>
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
                        <button class="btn btn-info btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#new_testimonial">{{__('Add New Day')}}</button>
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
                        <th>{{__('Schedule Time')}}</th>
                        <th>{{__('Status')}}</th>
                        <th>{{__('Action')}}</th>
                    </x-slot>
                    <x-slot name="tr">
                        @foreach($all_days as $data)
                            <tr>
                                <td>
                                    <x-bulk-delete-checkbox :id="$data->id"/>
                                </td>
                                <td>{{$data->id}}</td>
                                <td>
                                    {{ $data->getTranslation('day',$lang_slug)}}
                                </td>

                                <td>
                                      @foreach($data->times as $time)
                                        <span class="badge badge-info">{{ $time->time }}</span>
                                      @endforeach
                                </td>

                                <td>{{ \App\Enums\StatusEnums::getText($data->status) }}</td>
                                <td>

                                    <a href="#"
                                       data-bs-toggle="modal"
                                       data-bs-target="#testimonial_item_edit_modal"
                                       class="btn btn-primary btn-xs mb-3 mr-1 testimonial_edit_btn"
                                       data-bs-placement="top"
                                       title="{{__('Edit')}}"
                                       data-id="{{$data->id}}"
									   data-key="{{$data->key}}"
                                       data-action="{{route('tenant.admin.appointment.days.update')}}"
                                       data-day="{{$data->getTranslation('day',$default_lang)}}"
                                       data-status="{{$data->status}}"
                                    >
                                        <i class="las la-edit"></i>
                                    </a>

                                    <x-delete-popover permissions="appointment-days" url="{{route('tenant.admin.appointment.days.delete', $data->id)}}"/>
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
                        <h5 class="modal-title" id="staticBackdropLabel">{{__('New Day')}}</h5>
                        <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{route('tenant.admin.appointment.days')}}" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="lang" value="{{$default_lang}}">

                            <x-fields.input name="day" label="{{__('Day')}}" />
							
							<x-fields.select name="key" title="{{__('Day Key')}}" info="{{__('This is they key will use to find availble schedule in database, please select proper key based on your given day title')}}">
                                <option value="Thursday" @if($lang->key === 'Thursday') selected @endif>{{__('Thursday')}}</option>
								<option value="Wednesday" @if($lang->key === 'Wednesday') selected @endif>{{__('Wednesday')}}</option>
								<option value="Saturday" @if($lang->key === 'Saturday') selected @endif>{{__('Saturday')}}</option>
								<option value="Sunday" @if($lang->key === 'Sunday') selected @endif>{{__('Sunday')}}</option>
								<option value="Monday" @if($lang->key === 'Monday') selected @endif>{{__('Monday')}}</option>
								<option value="Tuesday" @if($lang->key === 'Tuesday') selected @endif>{{__('Tuesday')}}</option>
								<option value="Friday" @if($lang->key === 'Friday') selected @endif>{{__('Friday')}}</option>
                            </x-fields.select>

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
                        <h5 class="modal-title" id="staticBackdropLabel">{{__('Edit Day')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="#" id="testimonial_edit_modal_form" method="post"
                          enctype="multipart/form-data">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="lang" value="{{$default_lang}}">
                            <input type="hidden" name="id" class="day_id" value="">
                            <x-fields.input name="day" label="{{__('Day')}}" class="edit_day" />
                            
                            <x-fields.select name="key" title="{{__('Day Key')}}" info="{{__('This is they key will use to find availble schedule in database, please select proper key based on your given day title')}}">
                                <option value="Thursday" @if($lang->key === 'Thursday') selected @endif>{{__('Thursday')}}</option>
								<option value="Wednesday" @if($lang->key === 'Wednesday') selected @endif>{{__('Wednesday')}}</option>
								<option value="Saturday" @if($lang->key === 'Saturday') selected @endif>{{__('Saturday')}}</option>
								<option value="Sunday" @if($lang->key === 'Sunday') selected @endif>{{__('Sunday')}}</option>
								<option value="Monday" @if($lang->key === 'Monday') selected @endif>{{__('Monday')}}</option>
								<option value="Tuesday" @if($lang->key === 'Tuesday') selected @endif>{{__('Tuesday')}}</option>
								<option value="Friday" @if($lang->key === 'Friday') selected @endif>{{__('Friday')}}</option>
                            </x-fields.select>
							
                            <x-fields.select name="status" title="{{__('Status')}}" class="edit_status">
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
    <x-datatable.js/>
    <script>
        $(document).ready(function($){
            "use strict";

            <x-bulk-action-js :url="route( 'tenant.admin.appointment.days.bulk.action')"/>
            $(document).on('change','select[name="lang"]',function (e){
                $(this).closest('form').trigger('submit');
                $('input[name="lang"]').val($(this).val());
            });

            $(document).on('click', '.testimonial_edit_btn', function () {
                var el = $(this);
                var id = el.data('id');
                var day = el.data('day');
                var action = el.data('action');

                var form = $('#testimonial_edit_modal_form');
                form.attr('action', action);
                form.find('.day_id').val(id);
                form.find('.edit_day').val(day);
                form.find('.edit_status option[value="' + el.data('status') + '"]').attr('selected', true);
				form.find('select[name="key"] option').attr('selected', false);
				form.find('select[name="key"] option[value="' + el.data('key') + '"]').attr('selected', true);
            });

        });
    </script>
@endsection
