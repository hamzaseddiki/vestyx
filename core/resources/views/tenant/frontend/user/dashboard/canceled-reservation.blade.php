@extends('tenant.frontend.user.dashboard.user-master')
@section('title')
    {{__('Canceled Reservation')}}
@endsection
@section('style')
    <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/custom-dashboard.css')}}">

    <style>
        button.low,
        button.status-open{
            display: inline-block;
            background-color: #6bb17b;
            padding: 3px 10px;
            border-radius: 4px;
            color: #fff;
            text-transform: capitalize;
            border: none;
            font-weight: 600;
        }
        button.high,
        button.status-close{
            display: inline-block;
            background-color: #c66060;
            padding: 3px 10px;
            border-radius: 4px;
            color: #fff;
            text-transform: capitalize;
            border: none;
            font-weight: 600;
        }
        button.medium {
            display: inline-block;
            background-color: #70b9ae;
            padding: 3px 10px;
            border-radius: 4px;
            color: #fff;
            text-transform: capitalize;
            border: none;
            font-weight: 600;
        }
        button.urgent {
            display: inline-block;
            background-color: #bfb55a;
            padding: 3px 10px;
            border-radius: 4px;
            color: #fff;
            text-transform: capitalize;
            border: none;
            font-weight: 600;
        }
    </style>
@endsection
@section('section')
    <div class="mb-4">
        <x-hotelbooking::frontent.user-home-navbar />
    </div>
    @if(count($user_canceled_reservations) > 0)
        <div class="dashboard-reservation">
            @foreach($user_canceled_reservations as $item)
                <div class="single-reservation shadow p-3 mb-5 bg-body rounded base-padding">
                    <div class="single-reservation-expandIcon"> <i class="las la-angle-down"></i> </div>
                    <div class="single-reservation-head">
                        <div class="single-reservation-flex">
                            <div class="single-reservation-content">
                                <h5 class="single-reservation-content-title"> Reservation ID </h5>
                                <span class="single-reservation-content-id"> {{$item->reservation_id}}  </span>
                            </div>
                            @if($item->payment_status == 3)
                                <div class="single-reservation-btn">
                                    <a href="javascript:void(0)" class="btn btn-danger"> Canceled </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="single-reservation-inner">

                        <div class="single-reservation-item">
                            <div class="single-reservation-name">
                                <h5 class="single-reservation-name-title"> {{\Modules\HotelBooking\Entities\Hotel::find($item->hotel_id)->name}} </h5>
{{--                                <p class="single-reservation-name-para"> 4140 Parker Rd. Allentown, New Mexico 31134 </p>--}}
                            </div>
                        </div>
                        <div class="single-reservation-item">
                            <div class="single-reservation-details">
                                <div class="single-reservation-details-item">
                                    <span class="single-reservation-details-subtitle"> Check in </span>
                                    <h5 class="single-reservation-details-title">  {{$item->booking_date}} </h5>
                                </div>
                                <div class="single-reservation-details-item">
                                    <span class="single-reservation-details-subtitle"> Check Out </span>
                                    <h5 class="single-reservation-details-title"> {{$item->booking_expiry_date}} </h5>
                                </div>
                                <div class="single-reservation-details-item">
                                    <span class="single-reservation-details-subtitle"> Guests & Rooms </span>
                                    <h5 class="single-reservation-details-title"> ( {{$item->room_type->max_adult}} Person,  {{$item->room_type->max_child}} Children,  {{$item->room_type->no_bedroom}} BedRoom, {{$item->room_type->no_living_room}} LivingRoom,  {{$item->room_type->no_bathrooms}} BathRoom) </h5>
                                </div>
                                <div class="single-reservation-details-item">
                                    <span class="single-reservation-details-subtitle"> Booked date</span>
                                    <h5 class="single-reservation-details-title"> {{$item->created_at}} </h5>
                                </div>
                            </div>
                        </div>
                        <div class="single-reservation-item">
                            <div class="single-reservation-request">
                                <ul class="single-reservation-request-list list-style-none">
                                    <li class="single-reservation-request-list-item success">
                                        <span class="single-reservation-request-list-item-number"> 1 </span>
                                        <div class="single-reservation-request-list-item-contents">
                                            <h5 class="single-reservation-name-title"> Cancelled and Refund Requested </h5>
                                            <p class="single-reservation-name-para"> Thu 23 Jun, 2022 </p>
                                        </div>
                                    </li>
                                    <li class="single-reservation-request-list-item success">
                                        <span class="single-reservation-request-list-item-number"> 2 </span>
                                        <div class="single-reservation-request-list-item-contents">
                                            <h5 class="single-reservation-name-title"> Review </h5>
                                            <p class="single-reservation-name-para"> Our review team is reviewing your refund request and it may take upto 2-3 business days to complete </p>
                                        </div>
                                    </li>
                                    <li class="single-reservation-request-list-item current">
                                        <span class="single-reservation-request-list-item-number"> 3 </span>
                                        <div class="single-reservation-request-list-item-contents">
                                            <h5 class="single-reservation-name-title"> Refund policy </h5>
                                            <p class="single-reservation-name-para"> After review Refund Request your refund will be refunded manually through discussion </p>
                                        </div>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-warning">{{__('Nothing Found')}}</div>
    @endif
@endsection

@section('scripts')

    <script src="{{global_asset('assets/common/js/bootstrap.bundle.min.js')}}"></script>

    <script>
        (function (){
            "use strict";

            $(document).on('click','.change_priority',function (e){
                e.preventDefault();
                //get value
                var priority = $(this).data('val');
                var id = $(this).data('id');
                var currentPriority =  $(this).parent().prev('button').text();
                currentPriority = currentPriority.trim();
                $(this).parent().prev('button').removeClass(currentPriority).addClass(priority).text(priority);
                //ajax call
                $.ajax({
                    'type': 'post',
                    'url' : "{{route('tenant.user.dashboard.support.ticket.priority.change')}}",
                    'data' : {
                        _token : "{{csrf_token()}}",
                        priority : priority,
                        id : id,
                    },
                    success: function (data){
                        $(this).parent().find('button.'+currentPriority).removeClass(currentPriority).addClass(priority).text(priority);
                    }
                })
            });
            $(document).on('click','.status_change',function (e){
                e.preventDefault();
                //get value
                var status = $(this).data('val');
                var id = $(this).data('id');
                var currentStatus =  $(this).parent().prev('button').text();
                currentStatus = currentStatus.trim();
                $(this).parent().prev('button').removeClass('status-'+currentStatus).addClass('status-'+status).text(status);
                //ajax call
                $.ajax({
                    'type': 'post',
                    'url' : "{{route('tenant.user.dashboard.support.ticket.status.change')}}",
                    'data' : {
                        _token : "{{csrf_token()}}",
                        status : status,
                        id : id,
                    },
                    success: function (data){
                        $(this).parent().prev('button').removeClass(currentStatus).addClass(status).text(status);
                    }
                })
            });


        })(jQuery);
    </script>
@endsection
