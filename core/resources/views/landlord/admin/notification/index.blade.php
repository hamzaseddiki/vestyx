@extends(route_prefix().'admin.admin-master')
@section('title') {{__('All Notifications')}} @endsection

@section('style')
    <x-datatable.css/>
@endsection

@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <x-admin.header-wrapper>
                    <x-slot name="left">
                        <h4 class="card-title mb-5">{{__('All Notifications')}}</h4>
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
                        <th>{{__('Status')}}</th>
                        <th>{{__('Notification Title ')}}</th>
                        <th>{{__('Type')}}</th>
                        <th>{{__('Date')}}</th>
                        <th>{{__('Action')}}</th>
                    </x-slot>
                    <x-slot name="tr">

                        @foreach($all_notifications_date as $data)

                            <tr>
                                <td>
                                    <x-bulk-delete-checkbox :id="$data->id"/>
                                </td>

                                <td>{{$data->id}}</td>
                                <td>
                                    @if($data->status == 0)
                                        <span class="badge badge-success">{{__('New')}}</span>
                                    @else
                                        <span class="badge badge-secondary">{{__('Seen')}}</span>
                                    @endif

                                </td>
                                <td>{{$data->title}}</td>

                                <td>{{ str_replace('_', ' ',ucfirst($data->type)) }}</td>
                                <td>{{ date('d M,Y',strtotime($data->created_at)) }}</td>



                                <td>
                                    <x-link-with-popover url="{{route(route_prefix().'admin.notification.view', $data->id)}}" popover="View">
                                        <i class="las la-eye"></i>
                                    </x-link-with-popover>

                                    <x-delete-popover url="{{route(route_prefix().'admin.notification.delete', $data->id)}}"/>
                                </td>
                            </tr>

                        @endforeach
                    </x-slot>
                </x-datatable.table>

            </div>
        </div>
    </div>



@endsection
@section('scripts')
    <x-datatable.js/>
    <script>
        $(document).ready(function($){
            "use strict";

            <x-bulk-action-js :url="route( route_prefix().'admin.notification.bulk.action')" />
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
