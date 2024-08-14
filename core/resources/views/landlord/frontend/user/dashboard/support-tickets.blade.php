@extends('landlord.frontend.user.dashboard.user-master')
@section('title')
    {{__('Support Tickets')}}
@endsection
@section('page-title')
    {{__('Support Tickets')}}
@endsection

@section('style')
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

        .dashboard-right .parent .table-responsive {
            overflow-x: unset;
        }
    </style>
@endsection
@section('section')

        <a href="{{route('landlord.frontend.support.ticket')}}" class="btn btn-info margin-bottom-30">{{__('New Ticket')}}</a>

        @if(count($all_tickets) > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>{{__('ID')}}</th>
                        <th>{{__('Title')}}</th>
                        <th>{{__('Priority')}}</th>
                        <th>{{__('Status')}}</th>
                        <th>{{__('Action')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($all_tickets as $data)
                        <tr>
                            <td>#{{$data->id}}</td>
                            <td>{{$data->title}}
                            <p>{{__('created at:')}} <small>{{$data->created_at->format('D, d M Y')}}</small></p>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="{{$data->priority}} dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{$data->priority}}
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item change_priority" data-id="{{$data->id}}" data-val="low" href="#">{{__('Low')}}</a>
                                        <a class="dropdown-item change_priority" data-id="{{$data->id}}" data-val="high" href="#">{{__('High')}}</a>
                                        <a class="dropdown-item change_priority" data-id="{{$data->id}}" data-val="medium" href="#">{{__('Medium')}}</a>
                                        <a class="dropdown-item change_priority" data-id="{{$data->id}}" data-val="urgent" href="#">{{__('Urgent')}}</a>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="status-{{$data->status}} dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{$data->status}}
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item status_change" data-id="{{$data->id}}" data-val="open" href="#">{{__('Open')}}</a>
                                        <a class="dropdown-item status_change" data-id="{{$data->id}}" data-val="close" href="#">{{__('Close')}}</a>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="{{route('landlord.user.dashboard.support.ticket.view',$data->id)}}"  class="btn btn-info btn-xs" target="_blank"><i class="las la-eye"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="blog-pagination">
                {{ $all_tickets->links() }}
            </div>
        @else
            <div class="alert alert-warning">{{__('Nothing Found')}}</div>
        @endif

@endsection

@section('scripts')

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
                    'url' : "{{route('landlord.user.dashboard.support.ticket.priority.change')}}",
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
                    'url' : "{{route('landlord.user.dashboard.support.ticket.status.change')}}",
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
    <script>
        $('.close-bars, .body-overlay').on('click', function() {
            $('.dashboard-close, .dashboard-close-main, .body-overlay').removeClass('active');
        });
        $('.sidebar-icon').on('click', function() {
            $('.dashboard-close, .dashboard-close-main, .body-overlay').addClass('active');
        });
    </script>
@endsection
