@extends(route_prefix().'admin.admin-master')
@section('title') {{__(' Notification Details')}} @endsection

@section('style')
    <style>
        ul li{
            list-style-type: none;
        }
    </style>
@endsection

@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <x-admin.header-wrapper>
                    <x-slot name="left">
                        <h4 class="card-title">{{__('All Notification')}}</h4>
                        <x-link-with-popover url="{{route(route_prefix().'admin.notification.all')}}" extraclass="ml-3">
                            {{__('Go Back')}}
                        </x-link-with-popover>

                    </x-slot>
                </x-admin.header-wrapper>


                    <div class="main-content">
                        <h4 class="text-right mt-5">{{__('Basic Information')}}</h4>
                       <ul>

                           <li>
                               <h5 class="text-primary"><span class="text-dark">#{{__('ID')}} : </span>{{$notification->id ?? ''}}</h5>
                           </li>
                           <li>
                               <h5 class="text-primary"> <span class="text-dark">{{__('Title')}} :</span> {{$notification->title ?? ''}}</h5>
                           </li>
                           <li>
                               <h5 class="text-primary"> <span class="text-dark">{{__('Date')}} :</span> {{ date('d-m-Y',strtotime($notification->created_at)) ?? ''}}</h5>
                           </li>
                       </ul>


                   <h4 class="mt-5">{{__('Details Information')}}</h4>

                            @php
                                $notification_type = $notification->type;
                            @endphp

                            <table class="table table-responsive table-bordered">
                                <thead>
                                    <th>{{__('#SL')}}</th>
                                    <th>{{__('Information')}}</th>
                                    <th>{{__('Notification Type')}}</th>
                                </thead>

                                <tbody>
                                    @include('landlord.admin.notification.details-table-markup')
                                </tbody>
                            </table>

                    </div>


            </div>
        </div>
    </div>
@endsection

