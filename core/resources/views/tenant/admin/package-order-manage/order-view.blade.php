@extends(route_prefix().'admin.admin-master')
@section('title') {{__('Order Details')}} @endsection
@section('style')
   <x-media-upload.css/>
@endsection
@section('title')
    {{__('Order Details')}}
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
                                    <h4 class="header-title">{{__('Order Details')}}</h4>
                                    <x-link-with-popover url="{{route(route_prefix().'admin.package.order.manage.all')}}" class="info">{{__('All Orders')}}</x-link-with-popover>
                                    <div class="table-wrap table-responsive">
                                        <table class="table table-default table-striped table-bordered">
                                            <thead class="text-white">
                                            <tr>
                                                <th>{{__('ID')}}</th>
                                                <th>{{__('Details')}}</th>
                                                <th>{{__('Payment Status')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{$order->id}}</td>
                                                    <td>
                                                        <div class="parent d-flex justify-content-start">
                                                            <strong class="text-dark ">{{__('Package Name : ')}}</strong>
                                                            <span class="text-primary">{{$order->package_name}}</span><br><br>
                                                        </div>

                                                      <div class="parent d-flex justify-content-start">
                                                        <strong class="text-dark ">{{__('Package Price : ')}}</strong>
                                                        <span class="text-primary ">{{amount_with_currency_symbol($order->package_price)}}</span><br><br>
                                                      </div>

                                                        <div class="parent d-flex justify-content-start">
                                                            <strong class="text-dark ">{{__('Payment Gateway : ')}}</strong>
                                                            <span class="text-primary">{{$order->package_gateway}}</span><br><br>
                                                        </div>

                                                        <div class="parent d-flex justify-content-start">
                                                            <strong class="text-dark ">{{__('Order User Name : ')}}</strong>
                                                            <span class="text-primary"> {{$order->name}}</span><br><br>
                                                        </div>

                                                        <div class="parent d-flex justify-content-start">
                                                            <strong class="text-dark ">{{__('Order User Email : ')}}</strong>
                                                            <span class="text-primary">{{$order->email}}</span><br><br>
                                                        </div>

                                                        <div class="parent d-flex justify-content-start">
                                                            <strong class="text-dark ">{{__('Order Date : ')}}</strong>
                                                            <span class="text-primary">{{date_format($order->created_at,'d M Y')}}</span><br><br>
                                                        </div>

                                                        @php
                                                            $attachments = $order->attachments;
                                                            $attachment_item = json_decode($attachments) ?? [];
                                                            $all_custom_fields = json_decode($order->custom_fields) ?? [];
                                                        @endphp


                                                    @if(!empty($attachments))
                                                            <strong class="mb-2 text-danger mt-3">{{__('(Files) ')}}</strong>
                                                        @foreach($attachment_item ?? [] as $attach)
                                                           <div class="att mb-2 mt-2">
                                                               <strong class="text-dark ">{{__('Attachment : ')}}</strong>
                                                               <a download="" href="{{url($attach)}}">{{$attach}}</a><br>
                                                           </div>
                                                        @endforeach
                                                     @endif

                                                        @if(!empty($all_custom_fields))
                                                            <strong class="mb-2 text-danger mt-4">{{__('(Custom Fields) ')}}</strong>
                                                            @foreach($all_custom_fields ?? [] as $key=> $field)
                                                                <div class="att mb-2 mt-2">
                                                                    <strong class="text-dark ">{{ ucfirst($key) . ' : ' }}</strong>
                                                                    <span>{{$field}}</span><br>
                                                                </div>
                                                            @endforeach
                                                        @endif

                                                    </td>

                                                    <td>
                                                        @if($order->payment_status == 'complete')
                                                            <span class="alert alert-success text-capitalize">{{$order->payment_status}}</span>
                                                        @else
                                                            <span class="alert alert-warning text-capitalize">{{$order->payment_status ?? __('Pending')}}</span>
                                                        @endif

                                                    </td>

                                                </tr>

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
    <x-media-upload.markup/>
@endsection

@section('scripts')
    <x-media-upload.js/>
@endsection

