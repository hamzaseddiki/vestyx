@extends(route_prefix().'admin.admin-master')
@section('title') {{__('All Complete Orders')}} @endsection
@section('style')
    <x-media-upload.css/>
    <x-datatable.css/>
    <x-summernote.css/>
@endsection
@section('title')
    {{__('All Complete orders')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12 mt-5">
                            <div class="card">
                                <div class="card-body">
                                    <x-error-msg/>
                                    <x-flash-msg/>
                                    <h4 class="header-title">{{__('All Complete Orders')}}</h4>
                                    <x-bulk-action permissions="package-order-delete"/>
                                    <div class="table-wrap table-responsive">
                                        <table class="table table-default table-striped table-bordered">
                                            <thead class="text-white">
                                            <tr>
                                                <th class="no-sort">
                                                    <div class="mark-all-checkbox">
                                                        <input type="checkbox" class="all-checkbox">
                                                    </div>
                                                </th>
                                                <th>{{__('ID')}}</th>
                                                <th>{{__('Package Name')}}</th>
                                                <th>{{__('Package Price')}}</th>
                                                <th>{{__('Payment Status')}}</th>
                                                <th>{{__('Order Status')}}</th>
                                                <th>{{__('Date')}}</th>
                                                <th>{{__('Action')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($all_orders as $data)
                                                <tr>
                                                    <td>
                                                        <div class="bulk-checkbox-wrapper">
                                                            <input type="checkbox" class="bulk-checkbox" name="bulk_delete[]" value="{{$data->id}}">
                                                        </div>
                                                    </td>
                                                    <td>{{$data->id}}</td>
                                                    <td>{{$data->package_name}}</td>
                                                    <td>{{amount_with_currency_symbol($data->package_price)}}</td>
                                                    <td>
                                                        @if($data->payment_status == 'complete')
                                                            <span class="alert alert-success text-capitalize">{{$data->payment_status}}</span>
                                                        @else
                                                            <span class="alert alert-warning text-capitalize">{{$data->payment_status ?? __('Pending')}}</span>
                                                        @endif

                                                            @if($data->payment_status == 'pending')
                                                                <a href="{{route('landlord.admin.package.order.payment.status.change',$data->id)}}" class="btn btn-success btn-xs" title="{{__('Make Complete')}}"><i class="las la-check"></i></a>
                                                            @endif

                                                    </td>
                                                    <td>
                                                        @if($data->status == 'pending')
                                                            <span class="alert alert-warning text-capitalize">{{$data->status}}</span>
                                                        @elseif($data->status == 'cancel')
                                                            <span class="alert alert-danger text-capitalize">{{$data->status}}</span>
                                                        @elseif($data->status == 'in_progress')
                                                            <span class="alert alert-info text-capitalize">{{str_replace('_', ' ',ucwords($data->status))}}</span>
                                                        @else
                                                            <span class="alert alert-success text-capitalize">{{$data->status}}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{date_format($data->created_at,'d M Y')}}</td>
                                                    <td>

                                                        <x-delete-popover permissions="package-order-delete" url="{{route(route_prefix().'admin.package.order.manage.delete', $data->id)}}"/>

                                                        @can('package-order-edit')
                                                        <a href="#"
                                                           data-bs-toggle="modal"
                                                           data-bs-target="#user_edit_modal"
                                                           class="btn btn-lg btn-info btn-sm mb-3 mr-1 user_edit_btn"
                                                        >
                                                            <i class="las la-envelope"></i>
                                                        </a>
                                                        <a href="{{route(route_prefix().'admin.package.order.manage.view',$data->id)}}" class="btn btn-lg btn-primary btn-sm mb-3 mr-1 view_order_details_btn">
                                                            <i class="las la-eye"></i>
                                                        </a>

                                                        <a href="#"
                                                           data-id="{{$data->id}}"
                                                           data-status="{{$data->status}}"
                                                           data-bs-toggle="modal"
                                                           data-bs-target="#order_status_change_modal"
                                                           class="btn btn-lg btn-info btn-sm mb-3 mr-1 order_status_change_btn">
                                                            {{__("Update Status")}}
                                                        </a>

                                                        @if(!empty($data->user_id) && $data->payment_status == 'pending' || $data->payment_status == null)
                                                            <form action="{{route(route_prefix().'admin.package.order.reminder')}}"  method="post">
                                                                @csrf
                                                                <input type="hidden" name="id" value="{{$data->id}}">
                                                                <button class="btn btn-secondary mb-2 btn-sm" type="submit"><i class="las la-bell"></i></button>
                                                            </form>
                                                        @endif

                                                        @endcan

                                                        <form action="{{route('landlord.package.invoice.generate')}}" method="post" enctype="multipart/form-data">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{$data->id}}">
                                                            <button class="btn btn-dark btn-sm" type="submit">{{__('Invoice')}}</button>
                                                        </form>

                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('package-order-edit')
        @include('tenant.admin.package-order-manage.portion.status-and-mail-send')
    @endcan

    <x-media-upload.markup/>
@endsection

@section('scripts')
    <x-datatable.js/>
    <x-media-upload.js/>
    <x-summernote.js/>

    <script>
        (function($){
            "use strict";
            $(document).ready(function() {
                <x-bulk-action-js :url="route( route_prefix().'admin.package.order.bulk.action')" />
                    $(document).on('click','.order_status_change_btn',function(e){
                        e.preventDefault();
                        var el = $(this);
                        var form = $('#order_status_change_modal');
                        form.find('#order_id').val(el.data('id'));
                        form.find('#order_status option[value="'+el.data('status')+'"]').attr('selected',true);
                    });
                $('#all_user_table').DataTable( {
                    "order": [[ 1, "desc" ]],
                    'columnDefs' : [{
                        'targets' : 'no-sort',
                        'orderable' : false
                    }]
                } );
                $('.summernote').summernote({
                    height: 250,   //set editable area's height
                    codemirror: { // codemirror options
                        theme: 'monokai'
                    },
                    callbacks: {
                        onChange: function(contents, $editable) {
                            $(this).prev('input').val(contents);
                        }
                    }
                });

            } );

        })(jQuery);
    </script>

@endsection

