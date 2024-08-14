@extends('tenant.frontend.user.dashboard.user-master')
@section('title')
    {{__('All Reservation')}}
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
    @if(count($user_reservations) > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>{{__('Reservation ID')}}</th>
                    <th>{{__('Hotel')}}</th>
                    <th>{{__('Created')}}</th>
                    <th>{{__('Status')}}</th>
                    <th>{{__('Action')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($user_reservations as $data)
                    <tr>
                        <td>{{$data->reservation_id}}</td>
                        <td>{{\Modules\HotelBooking\Entities\Hotel::find($data->hotel_id)->name}}</td>
                        <td>{{$data->title}}
                            <p> <small>{{$data->created_at->format('D, d M Y')}}</small></p>
                        </td>
                        <td>
                            @if($data->payment_status == 0)
                                <button class="btn btn-warning btn-sm">
                                    pending
                                </button>
                            @elseif($data->payment_status == 1)
                                <button class="btn btn-success btn-sm">
                                    complete
                                </button>
                            @elseif($data->payment_status == 2)
                                <button class="btn btn-info btn-sm">
                                    in-progress
                                </button>
                            @elseif($data->payment_status == 3)
                                <button class="btn btn-danger btn-sm">
                                    cancled
                                </button>
                            @elseif($data->payment_status == 4)
                                <button class="btn btn-danger btn-sm">
                                    cancel requested
                                </button>
                            @endif
                        </td>
                        <td>
                            <a href="{{route('tenant.user.dashboard.view.reservation',$data->id)}}"  class="btn btn-info btn-sm mb-3" target="_blank"><i class="fas fa-eye"></i></a>
                            @if($data->payment_status != 4 && $data->payment_status != 3)
                                <x-table.btn.swal.delete :route="route('tenant.user.dashboard.reservation.cancel.request',$data->id)" type="'cancel'" />
                            @endif

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="blog-pagination">
            {{ $user_reservations->links() }}
        </div>
    @else
        <div class="alert alert-warning">{{__('Nothing Found')}}</div>
    @endif

@endsection

@section('scripts')
    <x-table.btn.swal.js :message="'Do you want to cancel this reservation?'" :type="'Cancel'"/>
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
