@extends('landlord.admin.admin-master')

@section('title')
    {{__('Coupon Manage')}}
@endsection

@section('style')
    <x-datatable.css />
    <x-bulk-action.css />
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row g-4">
            <div class="col-xl-8 col-lg-12">
                <div class="card">

                    <div class="card-body">

                        <x-error-msg/>
                        <x-flash-msg/>

                        <h4 class="header-title mb-4">{{__('All Coupon manage')}}</h4>
                        @can('coupon-delete')
                            <x-bulk-action.dropdown />
                        @endcan

                        <div class="table-wrap table-responsive">
                            <table class="table table-default table-striped table-bordered">
                                <thead>
                                <x-bulk-action.th />
                                <th>{{__('ID')}}</th>
                                <th>{{__('Title')}}</th>
                                <th>{{__('Code')}}</th>
                                <th>{{__('Discount')}}</th>
                                <th>{{__('Expire Date')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                @foreach($all_coupons as $data)
                                    <tr>
                                        <x-bulk-action.td :id="$data->id" />
                                        <td>{{$data->id}}</td>
                                        <td>{{$data->title}}</td>
                                        <td>{{$data->code}}</td>
                                        <td>
                                            @if($data->discount_type == 'percentage')
                                                {{$data->discount_amount}}%
                                            @else
                                                {{amount_with_currency_symbol($data->discount_amount)}}
                                            @endif
                                        </td>
                                        <td>{{ date('d M Y', strtotime($data->expire_date)) }}</td>
                                        <td>
                                            <x-status-span :status="$data->status"/>
                                        </td>
                                        <td>

                                            @can('coupon-edit')
                                                <a href="#"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#category_edit_modal"
                                                   class="btn btn-sm btn-primary btn-xs mb-3 mr-1 category_edit_btn"
                                                   data-id="{{$data->id}}"
                                                   data-title="{{$data->title}}"
                                                   data-code="{{$data->code}}"
                                                   data-discount_amount="{{$data->discount_amount}}"
                                                   data-discount_type="{{$data->discount_type}}"
                                                   data-max_use_qty="{{$data->max_use_qty}}"
                                                   data-expire_date="{{$data->expire_date}}"
                                                   data-status="{{$data->status}}"
                                                >
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                            @endcan

                                                @can('product-coupon-delete')
                                                    <x-delete-popover :url="route('landlord.admin.coupons.delete', $data->id)"/>
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
            @can('coupon-create')
                <div class="col-xl-4 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mb-4">{{__('Add New Coupon')}}</h4>
                            <form action="{{route('landlord.admin.coupons')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="title">{{__('Coupon Title')}}</label>
                                    <input type="text" class="form-control"  id="title" name="title" placeholder="{{__('Title')}}">
                                </div>
                                <div class="form-group">
                                    <label for="code">{{__('Coupon Code')}}</label>
                                    <input type="text" class="form-control"  id="code" name="code" placeholder="{{__('Code')}}">
                                    <span id="status_text" class="text-danger" style="display: none"></span>
                                </div>

                                <div class="form-group">
                                    <label for="discount">{{__('Discount Amount')}}</label>
                                    <input type="number" class="form-control"  id="discount" name="discount_amount" placeholder="{{__('Discount')}}">
                                </div>

                                <div class="form-group">
                                    <label for="discount_type">{{__('Coupon Type')}}</label>
                                    <select name="discount_type" class="form-control" id="discount_type">
                                        <option value="percentage">{{__("Percentage")}}</option>
                                        <option value="amount">{{__("Amount")}}</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="discount">{{__('Maximum Use Quantity')}}</label>
                                    <input type="number" class="form-control"  id="discount" name="max_use_qty" placeholder="{{__('Maximum Use Quantity')}}">
                                    <small class="text-primary">{{__('That means how many time a user can able to use this coupon ? ')}}</small>
                                </div>

                                <div class="form-group">
                                    <label for="expire_date">{{__('Expire Date')}}</label>
                                    <input type="date" class="form-control flatpickr"  id="expire_date" name="expire_date" placeholder="{{__('Expire Date')}}">
                                </div>
                                <div class="form-group">
                                    <label for="status">{{__('Status')}}</label>
                                    <select name="status" class="form-control" id="status" >
                                        <option value="1">{{__("Active")}}</option>
                                        <option value="0">{{__("Inactive")}}</option>
                                    </select>
                                </div>
                                <button type="submit" id="coupon_create_btn" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Add New Coupon')}}</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>
    @can('coupon-edit')
        <div class="modal fade" id="category_edit_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('Update Coupon')}}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="{{route('landlord.admin.coupons.update')}}"  method="post">
                        <input type="hidden" name="id" id="coupon_id">
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <label for="title">{{__('Coupon Title')}}</label>
                                <input type="text" class="form-control"  id="edit_title" name="title" placeholder="{{__('Title')}}">
                            </div>
                            <div class="form-group">
                                <label for="edit_code">{{__('Coupon Code')}}</label>
                                <input type="text" class="form-control"  id="edit_code" name="code" placeholder="{{__('Code')}}">
                                <span id="status_text" class="text-danger" style="display: none"></span>
                            </div>

                            <div class="form-group">
                                <label for="edit_discount">{{__('Discount Amount')}}</label>
                                <input type="number" class="form-control"  id="edit_discount_amount" name="discount_amount" placeholder="{{__('Discount Amount')}}">
                            </div>

                            <div class="form-group">
                                <label for="edit_discount_type">{{__('Coupon Type')}}</label>
                                <select name="discount_type" class="form-control" id="edit_discount_type">
                                    <option value="percentage">{{__("Percentage")}}</option>
                                    <option value="amount">{{__("Amount")}}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="discount">{{__('Maximum Use Quantity')}}</label>
                                <input type="number" class="form-control"  id="edit_max_use_qty" name="max_use_qty" placeholder="{{__('Maximum Use Quantity')}}">
                                <small class="text-primary">{{__('That means how many time a user can able to use this coupon ? ')}}</small>
                            </div>

                            <div class="form-group">
                                <label for="edit_expire_date">{{__('Expire Date')}}</label>
                                <input type="date" class="form-control flatpickr"  id="edit_expire_date" name="expire_date" placeholder="{{__('Expire Date')}}">
                                <small>{{__('Current Date')}} : </small> <small id="edit_expire_date_bottom" class="text-primary"></small>
                            </div>
                            <div class="form-group">
                                <label for="edit_status">{{__('Status')}}</label>
                                <select name="status" class="form-control" id="edit_status">
                                    <option value="0">{{__("Inactive")}}</option>
                                    <option value="1">{{__("Active")}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                            <button type="submit" class="btn btn-primary">{{__('Save Change')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

@endsection
@section('scripts')
    <x-datatable.js />
    <x-table.btn.swal.js />
    <x-bulk-action.js :route="route('tenant.admin.product.coupon.bulk.action')" />

    <script>
        $(document).ready(function () {
            flatpickr(".flatpickr", {
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
            });


            $(document).on('click','.category_edit_btn',function(){
                let el = $(this);
                let id = el.data('id');
                let status = el.data('status');
                let modal = $('#category_edit_modal');
                let discount_type = el.data('discount_type');
                console.log(discount_type)

                modal.find('#coupon_id').val(id);
                modal.find('#edit_code').val(el.data('code'));
                modal.find('#edit_discount_amount').val(el.data('discount_amount'));
                modal.find('#edit_max_use_qty').val(el.data('max_use_qty'));
                modal.find('#edit_expire_date').val(el.data('expire_date'));
                modal.find('#edit_expire_date_bottom').text(el.data('expire_date'));
                modal.find('#edit_discount_type option[value="'+discount_type+'"]').attr('selected',true);
                modal.find('#edit_status option[value="'+status+'"]').attr('selected',true);
                modal.find('#edit_title').val(el.data('title'));

            });

        });
    </script>
@endsection
