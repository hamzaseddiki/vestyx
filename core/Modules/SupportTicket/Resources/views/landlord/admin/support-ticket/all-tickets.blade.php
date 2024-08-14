@extends(route_prefix().'admin.admin-master')

@section('title') {{__('All Support Ticket')}} @endsection
@section('style')
  <x-datatable.css/>
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
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
                <x-flash-msg/>
                <x-error-msg/>
            </div>
            <div class="col-lg-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <div class="top-wrapp d-flex justify-content-between">
                            <div class="left-part">
                                <h4 class="header-title">{{__('All Tickets')}}</h4>
                                @can('support-ticket-delete')
                                    <div class="bulk-delete-wrapper">
                                    <div class="select-box-wrap">
                                        <select name="bulk_option" id="bulk_option">
                                            <option value="">{{{__('Bulk Action')}}}</option>
                                            <option value="delete">{{{__('Delete')}}}</option>
                                        </select>
                                        <button class="btn btn-primary btn-sm" id="bulk_delete_btn">{{__('Apply')}}</button>
                                    </div>
                                </div>
                                @endcan
                            </div>
                            <div class="btn-wrapper"><a href="{{route(route_prefix().'admin.support.ticket.new')}}" class="btn btn-primary">{{__('New Ticket')}}</a></div>
                        </div>
                        <div class="table-wrap table-responsive">
                            <table class="table table-default table-striped table-bordered">
                                <thead class="text-white">
                                <th class="no-sort">
                                    <div class="mark-all-checkbox">
                                        <input type="checkbox" class="all-checkbox">
                                    </div>
                                </th>
                                <th>{{__('ID')}}</th>
                                <th>{{__('Title')}}</th>
                                <th>{{__('Department')}}</th>
                                <th>{{__('User')}}</th>
                                <th>{{__('Priority')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                @foreach($all_tickets as $data)
                                    <tr>
                                        <td>
                                            <div class="bulk-checkbox-wrapper">
                                                <input type="checkbox" class="bulk-checkbox" name="bulk_delete[]" value="{{$data->id}}">
                                            </div>
                                        </td>
                                        <td>#{{$data->id}}</td>
                                        <td>{{$data->title}}</td>

                                        <td>{{optional($data->department)->getTranslation('name',default_lang()) ?? __('anonymous')}}</td>
                                        <td>
                                            {{optional($data->user)->name ?? __('anonymous')}}
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
                                            <x-delete-popover permissions="support-ticket-delete" :url="route(route_prefix().'admin.support.ticket.delete',$data->id)"/>
                                            <x-view-icon :url="route(route_prefix().'admin.support.ticket.view',$data->id)"/>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <x-datatable.js/>
        <script>
         $(document).ready(function(){

          <x-bulk-action-js :url="route(route_prefix().'admin.support.ticket.bulk.action')"/>
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
                    'url' : "{{route('landlord.admin.support.ticket.priority.change')}}",
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
                    'url' : "{{route('landlord.admin.support.ticket.status.change')}}",
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


        });


    </script>



@endsection
