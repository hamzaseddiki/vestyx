@extends('tenant.frontend.user.dashboard.user-master')

@section('title')
    {{__('Payment Logs')}}
@endsection



@section('section')
    <link rel="stylesheet" href="{{global_asset('assets/landlord/admin/css/module-fix-style.css')}}">
    @if(count($order_list) > 0)
        <div class="table-responsive">
            <!-- Order history start-->
            <div class="order-history-inner">
                <table class="table-responsive">
                    <thead class="bg-dark ">
                    <tr>
                        <th class="text-white">
                            {{__('Order ID')}}
                        </th>
                        <th class="text-white">
                            {{__('Date')}}
                        </th>
                        <th class="text-white">
                            {{__('Status')}}
                        </th>

                        <th class="text-white">
                            {{__('Amount')}}
                        </th>
                        <th class="text-white">
                            {{__('Action')}}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($order_list as $data)
                        <tr class="completed">
                            <td class="order-numb">
                                #{{ $data->id ?? 0 }}
                            </td>
                            <td class="date">
                                {{ $data->created_at->format("d-m-Y") }}
                            </td>
                            <td class="status">
                                <p>
                                    <span>{{__('Order Status')}} :</span>
                                    <span class="text-dark">{{ $data->status ?? ""}}</span>
                                </p>
                                <p>
                                    <span>{{__('Payment Status')}} : </span>
                                    <span class="text-dark">{{$data->payment_status ?? ""}}</span>
                                </p>
                            </td>

                            <td class="amount">
                                {{ amount_with_currency_symbol($data->total_amount) }}
                            </td>
                            <td class="table-btn">
                                <div class="btn-wrapper">
                                    <a href="{{ route('tenant.user.dashboard.product.order', $data->id) }}" class="btn-default rounded-btn"> view details</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Order history end-->
        </div>
        <div class="blog-pagination">
            {{ $order_list->links() }}
        </div>
    @else
        <div class="alert alert-warning">{{__('No Order Found')}}</div>
    @endif
@endsection
