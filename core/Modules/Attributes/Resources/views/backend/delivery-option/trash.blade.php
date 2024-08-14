@extends(route_prefix().'admin.admin-master')
@section('site-title')
    {{__('All Trashed Delivery Manage')}}
@endsection
@section('style')
    <x-datatable.css />
    <x-bulk-action.css />
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="">
                    <x-error-msg/>
                    <x-flash-msg/>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="header-wrap d-flex flex-wrap justify-content-between">
                            <h4 class="header-title mb-4">{{__('All Trashed Delivery Manages')}}</h4>
                        </div>
                        @can('product-delivery-manage-delete')
                            <x-bulk-action.dropdown />
                            <a class="btn btn-info btn-sm" href="{{route('tenant.admin.product.delivery.option.all')}}">{{__('Back')}}</a>
                        @endcan
                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                <x-bulk-action.th />
                                <th>{{__('ID')}}</th>
                                <th>{{__('Icon')}}</th>
                                <th>{{__('Title')}}</th>
                                <th>{{__('Sub Title')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                @foreach($delivery_manages as $item)
                                    <tr>
                                        <x-bulk-action.td :id="$item->id" />
                                        <td>{{$loop->iteration}}</td>
                                        <td>
                                            <i class="{{$item->icon}}"></i>
                                        </td>
                                        <td>{{$item->title}}</td>
                                        <td>{{$item->sub_title}}</td>
                                        <td>
                                            @can('product-delivery_manage-delete')
                                                <a class="btn btn-success btn-sm" href="{{route('tenant.admin.product.delivery.option.trash.restore', $item->id)}}">{{__('Restore')}}</a>
                                                <x-table.btn.swal.delete :route="route('tenant.admin.product.delivery.option.trash.delete', $item->id)" />
                                            @endcan
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
@endsection
@section('scripts')
    <x-datatable.js />
    <x-table.btn.swal.js />

    @can('product-delivery_manage-delete')
        <x-bulk-action.js :route="route('tenant.admin.product.delivery.option.trash.bulk.action')" />
    @endcan
@endsection
