@extends('tenant.frontend.user.dashboard.user-master')
@section('page-title')
 {{__('User Dashboard')}}
@endsection

@section('title')
    {{__('User Dashboard')}}
@endsection


@section('section')
    <div class="row">
{{--        @if($total_donation)--}}
        <div class="col-xl-6 col-md-6 orders-child">
            <div class="single-orders">
                <div class="orders-flex-content">
                    <div class="icon">
                        <i class="las la-tasks"></i>
                    </div>
                    <div class="contents">
                        <h2 class="order-titles"> {{$total_donation ?? ''}} </h2>
                        <span class="order-para">{{__('Total Donation')}} </span>
                    </div>
                </div>
            </div>
        </div>
{{--        @endif--}}
{{--        @if($total_product)--}}
        <div class="col-xl-6 col-md-6 orders-child">
            <div class="single-orders">
                <div class="orders-flex-content">
                    <div class="icon">
                        <i class="las la-tasks"></i>
                    </div>
                    <div class="contents">
                        <h2 class="order-titles"> {{$total_product ?? ''}} </h2>
                        <span class="order-para">{{__('Total Product')}} </span>
                    </div>
                </div>
            </div>
        </div>
{{--        @endif--}}
{{--        @if($total_event)--}}
        <div class="col-xl-6 col-md-6 orders-child mt-4">
            <div class="single-orders">

                <div class="orders-flex-content">
                    <div class="icon">
                        <i class="las la-tasks"></i>
                    </div>
                    <div class="contents">
                        <h2 class="order-titles"> {{ $total_event}} </h2>
                        <span class="order-para">{{__('Total Events')}} </span>
                    </div>
                </div>
            </div>
        </div>
{{--        @endif--}}
{{--        @if($support_tickets)--}}
        <div class="col-xl-6 col-md-6 orders-child mt-4">
            <div class="single-orders">

                <div class="orders-flex-content">
                    <div class="icon">
                        <i class="las la-tasks"></i>
                    </div>
                    <div class="contents">
                        <h2 class="order-titles"> {{$support_tickets ?? ''}} </h2>
                        <span class="order-para">{{__('Support Tickets')}} </span>
                    </div>
                </div>
            </div>
        </div>
{{--        @endif--}}
        @if($job_applications)
        <div class="col-xl-6 col-md-6 orders-child mt-4">
            <div class="single-orders">
                <div class="orders-flex-content">
                    <div class="icon">
                        <i class="las la-tasks"></i>
                    </div>
                    <div class="contents">
                        <h2 class="order-titles"> {{$job_applications ?? ''}} </h2>
                        <span class="order-para">{{__('Applied Jobs')}} </span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($wedding_plans)
        <div class="col-xl-6 col-md-6 orders-child mt-4">
            <div class="single-orders">

                <div class="orders-flex-content">
                    <div class="icon">
                        <i class="las la-tasks"></i>
                    </div>
                    <div class="contents">
                        <h2 class="order-titles"> {{$wedding_plans ?? ''}} </h2>
                        <span class="order-para">{{__('Wedding Orders')}} </span>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if($total_appointment)
        <div class="col-xl-12 col-md-6 orders-child mt-4">
            <div class="single-orders">

                <div class="orders-flex-content">
                    <div class="icon">
                        <i class="las la-tasks"></i>
                    </div>
                    <div class="contents">
                        <h2 class="order-titles"> {{$total_appointment ?? ''}} </h2>
                        <span class="order-para">{{__('Total Appointment')}} </span>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="col-xl-12 col-md-12 mt-4">
            <div class="dashboard-promo">
                <div class="row gy-4 justify-content-center">
                    @if($hotel_bookings['pending_reservations'])
                    <div class="col-xxl-3 col-xl-4 col-sm-6 orders-child">
                        <div class="single-order">
                            <div class="single-order-flex">
                                <div class="single-order-contents">
                                    <span class="single-order-contents-subtitle"> Pending Reservation </span>
                                    <h2 class="single-order-contents-title"> {{$hotel_bookings['pending_reservations'] ?? ''}}</h2>
                                </div>
                                <div class="single-order-icon">
                                    <i class="las la-history"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($hotel_bookings['accepted_reservations'])
                    <div class="col-xxl-3 col-xl-4 col-sm-6 orders-child">
                        <div class="single-order">
                            <div class="single-order-flex">
                                <div class="single-order-contents">
                                    <span class="single-order-contents-subtitle"> Accepted Reservation </span>
                                    <h2 class="single-order-contents-title"> {{$hotel_bookings['accepted_reservations'] ?? ''}} </h2>
                                </div>
                                <div class="single-order-icon">
                                    <i class="las la-check-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($hotel_bookings['cancled_reservations'])
                    <div class="col-xxl-3 col-xl-4 col-sm-6 orders-child">
                        <div class="single-order">
                            <div class="single-order-flex">
                                <div class="single-order-contents">
                                    <span class="single-order-contents-subtitle"> Cancelled Reservation </span>
                                    <h2 class="single-order-contents-title"> {{$hotel_bookings['cancled_reservations'] ?? ''}} </h2>
                                </div>
                                <div class="single-order-icon">
                                    <i class="las la-times-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($hotel_bookings['accepted_reservations'])
                    <div class="col-xxl-3 col-xl-4 col-sm-6 orders-child">
                        <div class="single-order">
                            <div class="single-order-contents">
                                <span class="single-order-contents-subtitle"> Completed Reservation </span>
                                <h2 class="single-order-contents-title">{{$hotel_bookings['accepted_reservations'] ?? ''}} </h2>
                            </div>
                            <div class="single-order-icon">
                                <i class="las la-clipboard-check"></i>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            {!! $hotel_bookings['total_reservations'] ? $hotel_bookings['total_reservations']->withQueryString()->links('pagination::bootstrap-5') : '' !!}
        </div>

        @if(count($recent_logs) > 0)
            <div class="col-md-12 mt-5">
                <h4 class="mb-3 text-uppercase text-center">{{__('Recent Product Orders')}}</h4>
                    <div class="payment">
                        <table class="table table-responsive table-bordered recent_payment_table">
                            <thead>
                            <th>{{__('ID')}}</th>
                            <th>{{__('Product Name')}}</th>
                            <th>{{__('Qty')}}</th>
                            <th>{{__('Amount')}}</th>
                            <th>{{__('Date')}}</th>
                            </thead>
                            <tbody class="w-100">
                            @foreach($recent_logs as $key=> $data)
                                <tr>
                                    <td>{{$key +1}}</td>
                                    <td>{{$data->package_name}}</td>
                                    <td>{{$data->package_name}}</td>
                                    <td>{{ amount_with_currency_symbol($data->package_price) }}</td>
                                    <td>{{date('d-m-Y', strtotime($data->created_at))}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
              </div>
            @endif
        </div>
@endsection





