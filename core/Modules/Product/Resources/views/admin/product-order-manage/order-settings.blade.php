@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('Order Settings')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row g-4">
            <div class="col-12">
                <x-error-msg/>
                <x-flash-msg/>
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">{{__("Order Settings")}}</h4>
                        <form action="{{route(route_prefix().'admin.product.order.settings')}}" method="POST" enctype="multipart/form-data" id="report_generate_form">
                            @csrf
                            <input type="hidden" name="page" value="1">
                            <div class="form-group mt-4">
                                <label for="receiving_email">{{__('Order Receiving Email')}}</label>
                                <input id="receiving_email" name="receiving_email" type="email" class="form-control" placeholder="admin@gmail.com" value="{{get_static_option('order_receiving_email') ?? ''}}">
                                <br>
                                <small>{{__('Email address on which admin will receive order notification email')}}</small>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success">{{__('Update')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
