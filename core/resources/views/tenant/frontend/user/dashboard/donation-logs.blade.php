@extends('tenant.frontend.user.dashboard.user-master')
@section('title')
   {{__('Donation Payment Logs')}}
@endsection


@section('section')

    @if(count($all_user_donation) > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">{{__('Donation Order Info')}}</th>
                    <th scope="col">{{__('Payment Status')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($all_user_donation as $data)
                    <tr>
                        <td>
                            <div class="user-dahsboard-order-info-wrap">
                                <h5 class="title">{{$data->donation?->getTranslation('title',get_user_lang())}}</h5>
                                <div class="div">
                                    <small class="d-block"><strong>{{__('Order ID:')}}</strong> #{{$data->id}}</small>
                                    <small class="d-block"><strong>{{__('Donation Price:')}}</strong> {{amount_with_currency_symbol($data->amount)}}</small>
                                    <small class="d-block"><strong>{{__('Payment Gateway')}} : </strong> {{ $data->payment_gateway }}</small>
                                    <small class="d-block"><strong>{{__('Date:')}}</strong> {{date_format($data->created_at,'D m Y')}}</small>
                                    @if($data->status == 1)
                                        <form action="{{route('tenant.frontend.donation.invoice.generate')}}"  method="post">
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
                                <a href="{{route('tenant.frontend.donation.payment',$data->donation_id)}}" class="btn btn-success btn-sm my-2" target="_blank">{{__('Pay Now')}}</a>

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
            {{ $all_user_donation->links() }}
        </div>
    @else
        <div class="alert alert-warning">{{__('No Order Found')}}</div>
    @endif
@endsection
