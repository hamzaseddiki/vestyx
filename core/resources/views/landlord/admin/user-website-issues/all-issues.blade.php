@extends(route_prefix().'admin.admin-master')
@section('title') {{__('All User Website Issues')}} @endsection

@section('style')
    <x-media-upload.css/>
    <x-datatable.css/>

    <style>
        #view_details_website_issues ul li{
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
                        <h4 class="card-title mb-5">{{__('All User Website Issues')}}</h4>
                        </br>

                        <small class="text-primary">{{__('If you want to generate database and domain automatically with user given subdomain then generate with auto, unless you are also can create database in your server with given privillege permissions to your user, then you can set that database on the set manaual option..!')}}</small>
                    </x-slot>

                </x-admin.header-wrapper>
                <x-error-msg/>
                <x-flash-msg/>
                <x-datatable.table>
                    <x-slot name="th">
                        <th>{{__('Issue ID')}}</th>
                        <th>{{__('User ID')}}</th>
                        <th>{{__('User Name')}}</th>
                        <th>{{__('Issue Type')}}</th>
                        <th>{{__('Subdomain')}}</th>
                        <th>{{__('Domain Status')}}</th>
                        <th>{{__('Action')}}</th>
                    </x-slot>
                    <x-slot name="tr">
                        @foreach($all_issues as $data)
                            <tr>
                                <td>{{$data->id}}</td>
                                <td>{{$data->tenant?->payment_log?->user?->id}}</td>
                                <td>{{$data->tenant?->payment_log?->user?->name}}</td>
                                <td>{{ ucfirst($data->issue_type) }}</td>
                                <td>{{ $data->tenant_id  }}</td>
                                <td>{{ \App\Enums\DomainCreateStatusEnum::getText($data->domain_create_status)  }}</td>

                                <td>
                                    <a href="" class="btn btn-success btn-sm view_issue_button"
                                        data-bs-toggle="modal"
                                        data-bs-target="#view_details_website_issues"
                                        data-id="{{$data->id}}"
                                        data-user_id="{{$data->tenant?->payment_log?->user?->id}}"
                                        data-user_name="{{$data->tenant?->payment_log?->user?->name}}"
                                        data-issue_type="{{ ucfirst($data->issue_type)}}"
                                        data-domain="{{$data->tenant_id}}"
                                        data-domain_status="{{\App\Enums\DomainCreateStatusEnum::getText($data->domain_create_status)}}"
                                        data-description="{{$data->description}}"
                                    >
                                       <i class="mdi mdi-eye"></i>
                                    </a>

                                    <a href="" class="btn btn-primary btn-sm manual_generate_database_btn"
                                       data-bs-toggle="modal"
                                       data-bs-target="#manual_generate_database_modal"
                                       data-id="{{$data->id}}"
                                       data-user_id="{{$data->tenant?->payment_log?->user?->id}}"
                                       data-user_name="{{$data->tenant?->payment_log?->user?->name}}"
                                       data-issue_type="{{ ucfirst($data->issue_type)}}"
                                       data-domain="{{$data->tenant_id}}"
                                       data-domain_status="{{\App\Enums\DomainCreateStatusEnum::getText($data->domain_create_status)}}"
                                       data-description="{{$data->description}}"
                                    >
                                        {{__('Set Database Manually')}}
                                    </a>

                                    <form action="{{route('landlord.admin.failed.domain.generate')}}" method="post">
                                        <input type="hidden" name="id" value="{{$data->id}}">
                                        @csrf
                                        <button type="submit"class="btn btn-info btn-sm my-2">{{__('Generate Auto')}}</button>
                                    </form>

                                    <x-delete-popover :url="route('landlord.admin.issue.delete',$data->id)"/>

                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-datatable.table>
            </div>
        </div>
    </div>

    <x-media-upload.markup/>

    <div class="modal fade" id="view_details_website_issues" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Issue Details')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                </div>
                    <div class="modal-body">
                        <ul>
                            <li><strong>{{ __('Issue ID') }} </strong>: <span class="issue_id"></span></li>
                            <li><strong>{{ __('User ID') }}</strong> : <span class="user_id"></span></li>
                            <li><strong>{{ __('User Name') }}</strong> : <span class="user_name"></span></li>
                            <li><strong>{{ __('Issue Type') }}</strong> : <span class="issue_type"></span></li>
                            <li><strong>{{ __('Domain') }}</strong> : <span class="domain"></span></li>
                            <li><strong>{{ __('Domain Status') }}</strong> : <span class="domain_status"></span></li>
                            <li><strong>{{ __('Description') }}</strong> : <span class="description"></span></li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                    </div>

            </div>
        </div>
    </div>


    <div class="modal fade" id="manual_generate_database_modal" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Add Database Manually')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                </div>
                   <form action="{{route('landlord.admin.failed.domain.generate.manually')}}" method="post">
                    <div class="modal-body">
                            @csrf

                        <input type="hidden" class="issue_id" name="id">
                        <input type="hidden" class="user_id" name="user_id">
                        <input type="hidden" class="issue_type" name="issue_type">
                        <input type="hidden" class="domain" name="domain">

                            <div class="form-group">
                                <label for="">{{__('Database Name')}}</label>
                                <input type="text" class="form-control" name="manual_database_name" placeholder="Database Name">
                                <small class="text-danger">{{__('Make sure your database name is as same as you created in your server with given privillege permissions to your user, unless it is not going to work..!')}}</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                            <button type="submit" class="btn btn-primary" >{{__('Submit')}}</button>
                        </div>
                  </form>

            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <x-media-upload.js/>
    <x-datatable.js/>

    <script>
        $(document).ready(function(){

            $(document).on('click','.view_issue_button',function(){
                let el = $(this);
                let modal = $('#view_details_website_issues');

                modal.find('.issue_id').text(el.data('id'))
                modal.find('.user_id').text(el.data('user_id'))
                modal.find('.user_name').text(el.data('user_name'))
                modal.find('.issue_type').text(el.data('issue_type'))
                modal.find('.domain').text(el.data('domain'))
                modal.find('.domain_status').text(el.data('domain_status'))
                modal.find('.description').text(el.data('description'))
            });

            $(document).on('click','.manual_generate_database_btn',function(){
                let el = $(this);
                let modal = $('#manual_generate_database_modal');

                modal.find('.issue_id').val(el.data('id'))
                modal.find('.user_id').val(el.data('user_id'))
                modal.find('.domain').val(el.data('domain'))
            });
        });
    </script>

@endsection
