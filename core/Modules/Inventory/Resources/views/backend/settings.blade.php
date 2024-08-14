@extends('tenant.admin.admin-master')

@section('title')
    {{__('Inventory Settings')}}
@endsection

@section('style')
    <x-datatable.css/>
    <x-bulk-action.css/>
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="margin-top-40">
                    <x-error-msg/>
                    <x-flash-msg/>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">{{__('Inventory Settings')}}</h4>
                        <form action="{{route('tenant.admin.product.inventory.settings')}}" method="POST" class="mt-4">
                            @csrf
                            <div class="form-group">
                                <label for="">{{__('Product Warning threshold')}}</label>
                                <input type="number" class="form-control" placeholder="example: 5" name="stock_threshold_amount" value="{{get_static_option('stock_threshold_amount')}}">
                                <sub>{{__('You will get alert notifications when any individual product stock reach to this amount')}}</sub>
                            </div>

                            <div class="form-group mt-4">
                                <label for="">{{__('Stock Warning Message')}}</label>
                                <input type="text" class="form-control" placeholder="Following products stock are running low" name="stock_warning_message" value="{{get_static_option('stock_warning_message')}}">
                                <sub>{{__('Your custom email notification message for stock warning')}}</sub>
                            </div>

                            <div class="form-group text-end">
                                <button class="btn btn-info">{{__('Update')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

