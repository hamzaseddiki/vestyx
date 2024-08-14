@extends(route_prefix().'admin.admin-master')
@section('title') {{__('All Payment  Details')}} @endsection
@section('style')
    <x-media-upload.css/>
@endsection
@section('title')
    {{__('Order Payment Details')}}
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
                                    <div class="alert alert-info">
                                        <h3 class="header-title text-center">{{__('Payment History of')}} : {{$domain_name}}</h3>
                                    </div>
                                    <x-link-with-popover url="{{route(route_prefix().'admin.package.order.manage.all')}}" class="info">{{__('All Orders')}}</x-link-with-popover>
                                    <div class="table-wrap table-responsive">
                                        <table class="table table-default table-striped table-bordered">
                                            <thead class="text-white">
                                            <tr>
                                                <th>{{__('ID')}}</th>
                                                <th>{{__('Details')}}</th>
                                                <th>{{__('Payment Status')}}</th>
                                                <th>{{__('Payment Attachment')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                        @foreach($payment_history as $order)
                                            @php
                                                $tenantHelper = \App\Helpers\TenantHelper\TenantHelpers::init()->setTenantId($order->tenant_id);
                                            @endphp
                                            <tr>
                                                <td>{{$order->id}}</td>
                                                <td>
                                                    <div class="parent d-flex justify-content-start">
                                                        <strong class="text-dark ">{{__('Package Name')}} : </strong>
                                                        <span class="text-primary"> {{$order->package_name}}</span><br><br>
                                                    </div>

                                                    <div class="parent d-flex justify-content-start">
                                                        <strong class="text-dark ">{{__('Package Price')}} : </strong>
                                                        <span class="text-primary "> {{amount_with_currency_symbol($order->package?->price)}}</span><br><br>
                                                    </div>


                                                    @if(!empty($order->coupon_discount))
                                                        <div class="parent d-flex justify-content-start">
                                                            <strong class="text-dark ">{{__('Coupon Discount')}} : </strong>
                                                            <span class="text-primary "> {{amount_with_currency_symbol($order->coupon_discount)}}</span><br><br>
                                                        </div>
                                                    @endif

                                                    <div class="parent d-flex justify-content-start">
                                                        <strong class="text-dark ">{{__('Paid Amount')}} : </strong>
                                                        <span class="text-primary "> {{amount_with_currency_symbol($order->package_price)}}</span><br><br>
                                                    </div>

                                                    <div class="parent d-flex justify-content-start">
                                                        <strong class="text-dark ">{{__('Transaction ID')}} : </strong>
                                                        <span class="text-primary"> {{$order->transaction_id}}</span><br><br>
                                                    </div>

                                                    <div class="parent d-flex justify-content-start">
                                                        <strong class="text-dark ml-2">{{__('Payment Gateway')}} : </strong>
                                                        <span class="text-primary"> {{ str_replace('_',' ',ucwords($order->package_gateway)) }}</span><br><br>
                                                    </div>

                                                    <div class="parent d-flex justify-content-start">
                                                        <strong class="text-dark ">{{__('Order User Name')}} : </strong>
                                                        <span class="text-primary"> {{$order->name}}</span><br><br>
                                                    </div>

                                                    <div class="parent d-flex justify-content-start">
                                                        <strong class="text-dark ">{{__('Order User Email')}} : </strong>
                                                        <span class="text-primary"> {{$order->email}}</span><br><br>
                                                    </div>

                                                    <div class="parent d-flex justify-content-start">
                                                        <strong class="text-dark ">{{__('Order Date')}} :</strong>
                                                        <span class="text-primary"> {{date_format($order->created_at,'d M Y')}}</span><br><br>
                                                    </div>

                                                    <div class="parent d-flex justify-content-start">
                                                        <strong class="text-dark ">{{__('Package Start Date')}} :</strong>
                                                        <span class="text-primary"> {{ $tenantHelper->getTenantStartDate() }}</span><br><br>
                                                    </div>

                                                    <div class="parent d-flex justify-content-start">
                                                        <strong class="text-dark ">{{__('Package Expire Date')}} :</strong>
                                                        <span class="text-primary"> {{$tenantHelper->getTenantExpiredDate() }}</span><br><br>
                                                    </div>


                                                    @php
                                                        $attachments = $order->attachments ?? [];
                                                        $attachment_item = !empty($attachments) ? json_decode($attachments) : [];
                                                        $all_custom_fields = json_decode($order->custom_fields) ?? [];
                                                    @endphp


                                                    @if(!empty($attachments))
                                                        <strong class="mb-2 text-warning mt-3">{{__('(Custom Files) ')}}</strong>
                                                        @foreach($attachment_item ?? [] as $attach)
                                                            <div class="att mb-2 mt-2">
                                                                <strong class="text-dark ">{{__('Attachment')}} : </strong>
                                                                <a download="" href="{{url($attach)}}">{{$attach}}</a><br>
                                                            </div>
                                                        @endforeach
                                                    @endif

                                                    @if(!empty($all_custom_fields))
                                                        <strong class="mb-2 text-secondary mt-4">{{__('(Custom Fields) ')}}</strong>
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
                                                        @if($order->is_renew == 1)
                                                            <span class="alert alert-primary text-capitalize">{{__('Renewed')}}</span>
                                                       @endif
                                                    @else
                                                        <span class="alert alert-warning text-capitalize">{{$order->payment_status ?? __('Pending')}}</span>
                                                    @endif

                                                </td>
                                                <td>
                                                    @if(!empty($order->manual_payment_attachment))
                                                        <a href="{{url('assets/uploads/attachment/',$order->manual_payment_attachment)}}" target="_blank">{{ $order->manual_payment_attachment }}</a>
                                                    @endif
                                                </td>

                                            </tr>
                                        @endforeach

                                            </tbody>
                                        </table>

                                        <div class="pagination justify-content-center mt-4">
                                            {{$payment_history->links() ?? []}}
                                        </div>
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

