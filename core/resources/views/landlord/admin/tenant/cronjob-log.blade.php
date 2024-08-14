@php
    $route_name ='landlord';
@endphp

@extends($route_name.'.admin.admin-master')

@section('title')
    {{__('Cronjob Logs')}}
@endsection

@section('style')
    <x-datatable.css/>
    <x-summernote.css/>
@endsection

@section('content')

    <div class="col-12 stretch-card">

        <div class="card">

            <div class="card-header bg-dark text-white">

                <div class="header_con d-flex justify-content-between">
                    <div class="left">
                        <h4> {{__('Cronjob Logs')}}</h4>
                    </div>

                    <div class="right">
                        <x-confirm-popover-all :url="route('landlord.admin.tenant.cronjob.log.all.delete')" title="{{__('Clear All')}}"/>
                    </div>

                </div>

            </div>

            <div class="card-body activity_log_table">

                <x-error-msg/>
                <x-flash-msg/>

                <x-datatable.table>
                    <x-slot name="th">
                        <th>{{__('#SL')}}</th>
                        <th>{{__('Title')}}</th>
                        <th>{{__('Type')}}</th>
                        <th>{{__('Running Qty')}}</th>
                        <th>{{__('Date & Time')}}</th>
                        <th>{{__('Action')}}</th>
                    </x-slot>
                    <x-slot name="tr">
                        @foreach($cronjobs as $data)

                            <tr>
                                <td>{{ $loop->iteration  }}</td>
                                <td>{{$data->title}}</td>
                                <td>{{ str_replace('_', ' ',ucwords($data->type)) }}</td>
                                <td>{{$data->running_qty}}</td>
                                <td>{{$data->created_at}}</td>
                                <td><x-delete-popover :url="route('landlord.admin.tenant.cronjob.log.delete',$data->id)"/></td>
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
    <x-summernote.js/>

    <script>
        $(document).on('click','.swal_change_confirm_button_all',function(e){
            e.preventDefault();
            Swal.fire({
                title: '{{__("Are you sure to delete all cronjob logs?")}}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "{{__('Yes, Delete it!')}}",
                cancelButtonText : "{{__('No')}}"
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).next().find('.swal_form_submit_btn').trigger('click');
                }
            });
        });
    </script>
@endsection

