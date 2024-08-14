@extends('tenant.frontend.user.dashboard.user-master')
@section('title')
   {{__('Appointment Payment Logs')}}
@endsection


@section('section')
    @if(count($all_user_appointments) > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">{{__('Appointment Payment Info')}}</th>
                    <th scope="col">{{__('Additional Order Info')}}</th>
                    <th scope="col">{{__('Payment Status')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($all_user_appointments as $data)
                    <tr>
                        <td>
                            <div class="user-dahsboard-order-info-wrap">
                                <h5 class="title">{{$data->appointment?->getTranslation('title',get_user_lang())}}</h5>
                                <div class="div">
                                    <small class="d-block"><strong>{{__('Order ID')}}</strong>: #{{$data->id}}</small>
                                    <small class="d-block"><strong>{{__('Order Date')}}</strong>: {{date('d-m-Y',strtotime($data->created_at))}}</small>
                                    <small class="d-block"><strong>{{__('Name')}}</strong>: {{$data->name}}</small>
                                    <small class="d-block"><strong>{{__('Email')}}</strong>: {{$data->email}}</small>
                                    <small class="d-block"><strong>{{__('Phone')}}</strong>: {{$data->phone}}</small>
                                    <small class="d-block"><strong>{{__('Appointment Price')}}</strong>: {{ amount_with_currency_symbol($data->appointment_price) }}</small>
                                    <small class="d-block"><strong>{{__('Subtotal')}}</strong>: {{ amount_with_currency_symbol($data->subtotal) }}</small>
                                    <small class="d-block"><strong>{{__('Vat')}}</strong>:
                                        {!! get_appointment_tax_amount_percentage($data->appointment_id) !!}
                                        {{ amount_with_currency_symbol($data->tax_amount)  }}
                                    </small>

                                    <small class="d-block"><strong>{{__('Total Amount')}}</strong>: {{ amount_with_currency_symbol($data->total_amount) }}</small>

                                    @if(!in_array($data->payment_gateway,['manual_payment_','bank_transfer']))
                                     <small class="d-block"><strong>{{__('Transaction Id')}}</strong>: {{$data->transaction_id}}</small>
                                    @endif

                                    <small class="d-block"><strong>{{__('Payment Gateway')}}</strong>: {{ str_replace('_',' ',ucwords($data->payment_gateway)) }}</small>
                                    <small class="d-block"><strong>{{__('Appointment Date')}}</strong>: {{$data->appointment_date}}</small>
                                    <small class="d-block"><strong>{{__('Appointment Time')}}</strong>: {{$data->appointment_time}}</small>

                                    @if(!empty($data->payment_gateway) && $data->payment_status == 'complete')
                                        <form action="{{route('tenant.frontend.appointment.invoice.generate')}}"  method="post">
                                            @csrf
                                            <input type="hidden" name="id" id="invoice_generate_order_field" value="{{$data->id}}">
                                            <button class="btn btn-secondary btn-xs btn-small margin-top-10" type="submit">{{__('Invoice')}}</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </td>


                        <td>
                            @if(count($data->sub_appointment_log_items) > 0)
                                @foreach($data->sub_appointment_log_items ?? [] as $sub_orders)
                                    <small class="d-block"><strong>{{__('SL#')}}</strong>: {{$loop->iteration}}</small>
                                    <small class="d-block"><strong>{{__('Title')}}</strong>: {{$sub_orders->title}}</small>
                                    <small class="d-block"><strong>{{__('Price')}}</strong>: {{ amount_with_currency_symbol($sub_orders->price) }}</small>
                                    <br>
                                @endforeach
                            @endif
                        </td>

                        <td>
                            @if(!empty($data->payment_gateway) && $data->payment_status != 'complete')
                                <span class="alert alert-warning text-capitalize donation_status d-inline-block">{{ ucfirst($data->status )}}</span>
                                <a href="{{route('tenant.frontend.appointment.order.page',$data->appointment?->slug)}}" class="btn btn-success btn-sm my-2" target="_blank">{{__('Pay Now')}}</a>
                            @else
                                <span class="alert alert-success text-capitalize donation_status d-inline-block">{{ucfirst($data->payment_status)}}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="blog-pagination">
            {{ $all_user_appointments->links() }}
        </div>
    @else
        <div class="alert alert-warning">{{__('No Appointment Found')}}</div>
    @endif
@endsection
