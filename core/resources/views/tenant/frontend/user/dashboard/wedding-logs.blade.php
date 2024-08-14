@extends('tenant.frontend.user.dashboard.user-master')
@section('title')
   {{__('Donation Payment Logs')}}
@endsection


@section('section')

    @if(count($all_user_wedding) > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">{{__('Wedding Order Info')}}</th>
                    <th scope="col">{{__('Payment Status')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($all_user_wedding as $data)
                    <tr>
                        <td>
                            <div class="user-dahsboard-order-info-wrap">
                                <h5 class="title">{{$data->package_name}}</h5>
                                <div class="div">
                                    <small class="d-block"><strong>{{__('Order ID')}}</strong> : #{{$data->id}}</small>
                                    <small class="d-block"><strong>{{__('Amount')}}</strong> : {{amount_with_currency_symbol($data->package_price)}}</small>
                                    <small class="d-block"><strong>{{__('Payment Gateway')}} : </strong> {{ $data->package_gateway }}</small>
                                    <small class="d-block"><strong>{{__('Date')}}</strong> : {{date_format($data->created_at,'D m Y')}}</small>
                                    @if($data->status == 'complete')
                                        <form action="{{route('tenant.frontend.wedding.invoice.generate')}}"  method="post">
                                            @csrf
                                            <input type="hidden" name="id" id="invoice_generate_order_field" value="{{$data->id}}">
                                            <button class="btn btn-secondary btn-xs btn-small margin-top-10" type="submit">{{__('Invoice')}}</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td>
                            @if($data->payment_status != 'complete')
                                @if($data->package_gateway != 'manual_payment')
                                    <span class="alert alert-warning text-capitalize donation_status d-inline-block" >{{ $data->payment_status }}</span>
                                    <a href="{{route('tenant.frontend.wedding.price.plan.order',$data->package_id)}}" class="btn btn-success btn-sm my-2" target="_blank">{{__('Pay Now')}}</a>
                                    @else
                                    <span class="alert alert-warning text-capitalize donation_status d-inline-block" >{{ __('Waiting for admin approval')}}</span>
                                 @endif
                            @else
                                <span class="alert alert-success text-capitalize donation_status d-inline-block" >{{ $data->payment_status }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="blog-pagination">
            {{ $all_user_wedding->links() }}
        </div>
    @else
        <div class="alert alert-warning">{{__('No Order Found')}}</div>
    @endif
@endsection
