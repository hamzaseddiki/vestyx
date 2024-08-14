@extends('tenant.admin.admin-master')
@section('title')
    {{__('New Shipping Method')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="margin-top-40">
                    <x-flash-msg/>
                    <x-error-msg/>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="header-wrapper d-flex justify-content-between">
                            <h4 class="header-title mb-4">{{__('Add New Shipping Method')}}</h4>
                            @can('shipping-method-list')
                            <a href="{{route('tenant.admin.shipping.method.all')}}" class="btn btn-primary">{{__('All Shipping Methods')}}</a>
                            @endcan
                        </div>

                        @can('shipping-method-create')
                        <form action="{{route('tenant.admin.shipping.method.new')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="zone_id">{{__('Zone')}}</label>
                                        <select name="zone_id" id="zone_id" class="form-control">
                                            @foreach ($all_zones as $zone)
                                                <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="title">{{__('Title')}}</label>
                                        <select name="title" id="title" class="form-control">
                                            @foreach ($all_shipping_method_names as $key => $tax_status)
                                                <option value="{{ $key }}">{{ $tax_status }}</option>
                                            @endforeach
                                        </select>
                                        <p class="text-small">{{__('you can chagne this title, in edit of this shipping method')}}</p>
                                    </div>
                                    <div class="form-group">
                                        <label for="tax_status">{{__('Tax Status')}}</label>
                                        <select name="tax_status" id="tax_status" class="form-control">
                                            @foreach ($all_tax_status as $key => $tax_status)
                                                <option value="{{ $key }}">{{ $tax_status }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="status">{{__('Status')}}</label>
                                        <select name="status" id="status" class="form-control">
                                            @foreach ($all_publish_status as $key => $status)
                                                <option value="{{ $key }}">{{ $status }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="setting_preset">{{__('Setting')}}</label>
                                        <select name="setting_preset" id="setting_preset" class="form-control">
                                            @foreach ($all_setting_presets as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group" style="display: none">
                                        <label for="minimum_order_amount">{{__('Minimum Order Amount')}}</label>
                                        <input type="number" id="minimum_order_amount" name="minimum_order_amount" class="form-control" placeholder="{{ __('Minimum Order Amount') }}">
                                    </div>

                                    <div class="form-group" style="display: none">
                                        <label for="coupon_code">{{__('Coupon Code')}}</label>
                                        <select name="coupon_code" id="coupon_code" class="form-control">
                                            <option value="">Select coupon code</option>
                                            @foreach($all_coupons as $coupon)
                                                <option value="{{ $coupon->code }}">{{ $coupon->code }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="cost">{{__('Cost')}}</label>
                                        <input type="number" id="cost" name="cost" class="form-control" placeholder="{{ __('Cost') }}">
                                    </div>
                                    <button type="submit" class="btn btn-primary my-4 px-4">{{__('Create Shipping Method')}}</button>
                                </div>
                            </div>
                        </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        (function ($) {
            $(document).ready(function () {
                $('#setting_preset').on('change', function () {
                    let presets = ['min_order', 'min_order_or_coupon', 'min_order_and_coupon'];
                    let selected_value = $('#setting_preset').val();
                    let allOptions = ['min_order_or_coupon','min_order_and_coupon'];

                    if (presets.includes(selected_value)) {
                        $('#minimum_order_amount').parent().fadeIn();
                        if(allOptions.includes(selected_value)){
                             $('#coupon_code').parent().fadeIn();
                        }else{
                            $('#coupon_code').parent().fadeOut();
                        }
                    } else {
                        $('#minimum_order_amount').parent().fadeOut();
                        $('#coupon_code').parent().fadeOut();
                    }
                });
            });
        })(jQuery)
    </script>
@endsection
