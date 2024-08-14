@extends('tenant.frontend.user.dashboard.user-master')
@section('title')
   {{__('Event Payment Logs')}}
@endsection


@section('section')
    @if(count($all_user_event) > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">{{__('Event Order Info')}}</th>
                    <th scope="col">{{__('Payment Status')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($all_user_event as $data)
                    <tr>
                        <td>
                            <div class="user-dahsboard-order-info-wrap">
                                <h5 class="title">{{$data->event?->getTranslation('title',get_user_lang())}}</h5>
                                <div class="div">
                                    <small class="d-block"><strong>{{__('Event')}}</strong>: #{{$data->id}}</small>
                                    <small class="d-block"><strong>{{__('Tickets')}}</strong>: {{$data->ticket_qty}}</small>
                                    <small class="d-block"><strong>{{__('Event Price')}}</strong>: {{amount_with_currency_symbol($data->amount)}}</small>

                                    <small class="d-block"><strong>{{__('Event Location')}}</strong>: {{$data->event?->venue_location}}</small>
                                    <small class="d-block"><strong>{{__('Date')}}</strong>: {{$data->event?->date}}</small>
                                    <small class="d-block"><strong>{{__('Time')}}</strong>: {{$data->event?->time}}</small>

                                    @if($data->status == 1)
                                        <form action="{{route('tenant.frontend.event.invoice.generate')}}"  method="post">
                                            @csrf
                                            <input type="hidden" name="id" id="invoice_generate_order_field" value="{{$data->id}}">
                                            <button class="btn btn-secondary btn-xs btn-small margin-top-10" type="submit">{{__('Invoice')}}</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td>
                            @if($data->status != 1)
                                <span class="text-capitalize alert-sm">{{$data->payment_status}}</span>
                                <span class="alert alert-warning text-capitalize donation_status d-inline-block" >{{ \App\Enums\DonationPaymentStatusEnum::getText($data->status) }}</span>
                                <a href="{{route('tenant.frontend.event.payment',$data->event?->slug)}}" class="btn btn-success btn-sm my-2" target="_blank">{{__('Pay Now')}}</a>
                            @else
                                <span class="alert alert-success text-capitalize donation_status d-inline-block" >{{ \App\Enums\DonationPaymentStatusEnum::getText($data->status) }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="blog-pagination">
            {{ $all_user_event->links() }}
        </div>
    @else
        <div class="alert alert-warning">{{__('No Event Attendance Found')}}</div>
    @endif
@endsection
