@extends(route_prefix().'admin.admin-master')
@section('title') {{__('All Contact Message')}} @endsection

@section('style')
    <x-datatable.css/>
@endsection

@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <x-admin.header-wrapper>
                    <x-slot name="left">
                        <h4 class="card-title mb-5">{{__('All Contact Message')}}</h4>
                    <x-bulk-action/>
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
                        <th>{{__('Form Name ')}}</th>
                        <th>{{__('Data ')}}</th>
                        <th>{{__('Date')}}</th>
                        <th>{{__('Status')}}</th>
                        <th>{{__('Action')}}</th>
                    </x-slot>
                    <x-slot name="tr">
                        @foreach($all_contact_messages as $data)
                        @php
                            $fields = json_decode($data->fields,true);
                         @endphp
                            <tr>
                                <td>
                                    <x-bulk-delete-checkbox :id="$data->id"/>
                                </td>

                                <td>
                                    {{$data->id}}

                                </td>
                                <td>
                                    {{optional($data->form)->title}}

                                </td>
                                <td>
                                        <ul>
                                    @foreach($fields as $k => $vl)
                                            <li style="list-style-type: none"><strong>{{$k}}:</strong> {{$vl}}</li>
                                     @endforeach
                                        </ul>


                                </td>

                                <td>{{ date('d M,Y',strtotime($data->created_at)) }}</td>

                                <td>
                                    @if($data->status == 1)
                                        <span class="badge badge-success">{{__('New')}}</span>
                                   @else
                                        <span class="badge badge-secondary">{{__('Read Already')}}</span>
                                    @endif

                                </td>

                                <td>
                                    <x-link-with-popover url="{{route(route_prefix().'admin.contact.message.view', $data->id)}}">
                                        <i class="las la-eye"></i>
                                    </x-link-with-popover>

                                    <x-delete-popover url="{{route(route_prefix().'admin.contact.message.delete', $data->id)}}"/>
                                </td>
                            </tr>

                        @endforeach
                    </x-slot>
                </x-datatable.table>

            </div>
        </div>
    </div>


        <div class="modal fade" id="contact_view_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">{{__('View Contact Message Details')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <ul class="d-inline-block">
                            <li><strong>{{__('ID : ')}}</strong><span class="id"></span></li>
                            <li><strong>{{__('Name : ')}}</strong><span class="name"></span></li>
                            <li><strong>{{__('Email : ')}}</strong><span class="email"></span></li>
                            <li><strong>{{__('Phone : ')}}</strong><span class="phone"></span></li>
                            <li><strong>{{__('Subject : ')}}</strong><span class="subject"></span></li>
                            <li><strong>{{__('Message : ')}}</strong><span class="message"></span></li>
                            <li><strong>{{__('Attachement : ')}}</strong><a class=" btn btn-info btn-sm attachement">{{__('Show')}}</a>
                            <li><strong>{{__('Others : ')}}</strong><span class="others"></span></li>
                        </ul>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                    </div>
                </div>
            </div>
        </div>

@endsection
@section('scripts')
    <x-datatable.js/>
    <script>
        $(document).ready(function($){
            "use strict";

            <x-bulk-action-js :url="route( route_prefix().'admin.contact.message.bulk.action')" />
            $(document).on('change','select[name="lang"]',function (e){
                $(this).closest('form').trigger('submit');
                $('input[name="lang"]').val($(this).val());
            });

            $(document).on('click', '.contact_view_btn', function () {

                let modal = $('#contact_view_modal');
                var el = $(this);
                var id = el.data('id');
                var name = el.data('name');
                var email = el.data('email');
                var phone = el.data('phone');
                var subject = el.data('subject');
                var message = el.data('message');
                var others = el.data('others');
                var attachment = el.data('attachment');

                modal.find('.id').text(id);
                modal.find('.name').text(name);
                modal.find('.email').text(email);
                modal.find('.phone').text(phone);
                modal.find('.subject').text(subject);
                modal.find('.message').text(message);
                modal.find('.others').text(others);
                modal.find('.attachment').attr('href',attachment);

            });

        });
    </script>
@endsection
