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
   <x-hotelbooking::frontent.user-home-navbar />

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
