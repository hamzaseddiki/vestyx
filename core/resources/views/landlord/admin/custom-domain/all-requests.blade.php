@extends('landlord.admin.admin-master')
@section('title') {{__('All Custom Domain Requests')}} @endsection

@section('style')
    <x-datatable.css/>
@endsection

@section('title')
    {{__('All Custom Domain Requests')}}
@endsection

@section('content')

    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12 mt-5">
                            <div class="card">
                                <div class="card-body">
                                    <x-error-msg/>
                                    <x-flash-msg/>
                                    <h4 class="header-title"> {{__('All Custom Domain Requests')}}</h4>

                                    <div class="table-wrap table-responsive">
                                        <table class="table table-default table-striped table-bordered">
                                            <thead class="text-white">
                                            <tr>
                                                <th>{{__('ID')}}</th>
                                                <th>{{__('Username')}}</th>
                                                <th>{{__('Old Domain')}}</th>
                                                <th>{{__('New Domain')}}</th>
                                                <th>{{__('Custom Domain Status')}}</th>
                                                <th>{{__('Date')}}</th>

                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($domain_infos as $data)
                                                <tr>
                                                    <td>{{$data->id}}</td>
                                                    <td>{{optional($data->user)->username}}</td>
                                                    <td>{{$data->old_domain .'.' . env('CENTRAL_DOMAIN')}}</td>
                                                    <td>{{$data->custom_domain}}</td>
                                                    <td class="py-4">
                                                        @if($data->custom_domain_status == 'pending')
                                                            <span class="alert alert-warning text-capitalize">{{$data->custom_domain_status}}</span>
                                                        @elseif($data->custom_domain_status == 'in_progress')
                                                            <span class="alert alert-info text-capitalize">{{$data->custom_domain_status}}</span>
                                                        @elseif($data->custom_domain_status == 'connected')
                                                            <span class="alert alert-success text-capitalize">{{$data->custom_domain_status}}</span>
                                                        @elseif($data->custom_domain_status == 'removed')
                                                            <span class="alert alert-danger text-capitalize">{{str_replace('_', ' ',ucwords($data->custom_domain_status))}}</span>
                                                        @elseif($data->custom_domain_status == 'rejected')
                                                            <span class="alert alert-info text-capitalize">{{str_replace('_', ' ',ucwords($data->custom_domain_status))}}</span>
                                                        @endif
                                                    </td>
                                                         <td>{{date('d-m-Y',strtotime($data->updated_at))}}</td>
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
            </div>
        </div>
    </div>



@endsection

@section('scripts')
    <x-datatable.js/>
@endsection

