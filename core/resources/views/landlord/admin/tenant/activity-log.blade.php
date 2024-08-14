@php
    $route_name ='landlord';
@endphp

@extends($route_name.'.admin.admin-master')

@section('title')
    {{__('User Activity Logs')}}
@endsection

@section('style')
    <x-datatable.css/>
    <x-summernote.css/>
@endsection

@section('content')

    <div class="col-12 stretch-card">

        <div class="card">

            <div class="card-header bg-dark text-white">

                <div class="header_con d-flex justify-content-between">
                    <div class="left">
                        <h4>{{__('User Activity Logs')}}</h4>
                    </div>

                    <div class="right">
                        <a class="btn btn-danger btn-sm pull-right" href="{{route('landlord.admin.tenant.activity.log.all.delete')}}">{{__('Clear all Logs')}}</a>
                    </div>

                </div>

            </div>

            <div class="card-body activity_log_table">

                <x-error-msg/>
                <x-flash-msg/>

                <x-datatable.table>
                    <x-slot name="th">
                        <th>{{__('#SL')}}</th>
                        <th>{{__('Content')}}</th>
                        <th>{{__('Action')}}</th>
                    </x-slot>
                    <x-slot name="tr">
                        @foreach($activities as $activity)
                            @php
                                $ex = explode("App\Models\\",$activity->subject_type) ?? [];
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration  }}</td>
                                <td>
                                    <i class="las la-user mr-1 text-dark"></i> <strong>  {{ optional($activity->causer)->name ?? optional(Auth::guard('web')->user())->name}}</strong>
                                    <span class="text-primary">{{$activity->description  }} </a> <span class="text-danger">{{$ex[1] ?? '' }} <span class="text-dark">{{__('section')}}</span></span></span>
                                    <br>

                                    @if(!empty($activity->user_ip) && !empty($activity->user_agent))
                                        <br>
                                        <span>{{__('User IP')}} : <span class="text-primary">{{ $activity->user_ip}}</span></span><br><br>
                                        <span>{{__('User Agent')}} : <span class="text-primary">{{$activity->user_agent}}</span></span>
                                    @endif
                                </td>

                                <td>
                                    <x-delete-popover :url="route('landlord.admin.tenant.activity.log.delete',$activity->id)"/>
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-datatable.table>

            </div>
        </div>
    </div>



@endsection

@section('scripts')
    <x-datatable.js/>
    <x-summernote.js/>
@endsection

