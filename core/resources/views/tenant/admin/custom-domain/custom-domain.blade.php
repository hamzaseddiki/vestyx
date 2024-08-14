@extends('tenant.admin.admin-master')
@section('title') {{__(' Custom Domain Request')}} @endsection

@section('style')
    <x-summernote.css/>
@endsection

@section('title')
    {{__(' Custom Domain Request')}}
@endsection

@section('content')
    @php
        $central_domain = env('CENTRAL_DOMAIN');

    @endphp
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                <div class="card my-4">
                    <div class="card-header">
                        @php
                            $langlord_default_language_slug = get_static_option_central('landlord_default_language_slug','en_GB');
                        @endphp
                        <h3 class="text-center">{{get_static_option_central('custom_domain_settings_'.$langlord_default_language_slug.'_title')}}</h3>
                    </div>

                    <div class="card-body">

                        <p>{{get_static_option_central('custom_domain_settings_'.$langlord_default_language_slug.'_description')}}</p>

                        <h5>{{get_static_option_central('custom_domain_table_title')}}</h5>
                        <table class="table table-default table-striped table-bordered">
                            <thead class="text-white bg-dark">
                            <tr>
                                <th>{{__('Type')}}</th>
                                <th>{{__('Host')}}</th>
                                <th>{{__('Value')}}</th>
                                <th>{{__('TTL')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>CNAME Record</td>
                                <td>www</td>
                                <td>{{getenv('CENTRAL_DOMAIN')}}</td>
                                <td>Automatic</td>
                            </tr>

                            <tr>
                                <td>CNAME Record</td>
                                <td>@</td>
                                <td>{{getenv('CENTRAL_DOMAIN')}}</td>
                                <td>Automatic</td>
                            </tr>

                        <tr>
                                <td colspan="4">Use this if you are using cloulflare</td>
                            </tr>
                             <tr>
                                <td>A Record</td>
                                <td>@</td>
                                <td>{{$_SERVER['SERVER_ADDR']}}</td>
                                <td>Automatic</td>
                            </tr>

                            </tbody>
                        </table>

                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <x-admin.header-wrapper>
                                        <x-slot name="left">
                                            <h4 class="card-title mb-5">{{__('Custom Domain Request')}}</h4>
                                        </x-slot>
                                        <x-slot name="right" class="d-flex">
                                            <button class="btn btn-info btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#new_custom_domain">{{__('Request Custom Domain')}}</button>
                                        </x-slot>
                                    </x-admin.header-wrapper>
                                    <x-error-msg/>
                                    <x-flash-msg/>
                                    <div class="table-wrap table-responsive">
                                         <table class="table table-default table-striped table-bordered mt-4">
                                    <table class="table table-default table-striped table-bordered">
                                        <thead class="text-white">
                                        <tr>
                                            <th>{{__('Current Domain')}}</th>
                                            <th>{{__('Requested Domain')}}</th>
                                            <th>{{__('Requested Domain Status')}}</th>
                                            <th>{{__('Date')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                                @php
                                                    $tenant = tenant();
                                                @endphp

                                                <tr>
                                                <td>{{$tenant->id . '.'. getenv('CENTRAL_DOMAIN')}}</td>
                                                <td>{{optional($tenant->custom_domain)->custom_domain}}</td>
                                                <td class="py-4">
                                                    @if(optional($tenant->custom_domain)->custom_domain_status == 'pending')
                                                        <span class="alert alert-warning text-capitalize">{{optional($tenant->custom_domain)->custom_domain_status}}</span>
                                                    @elseif(optional($tenant->custom_domain)->custom_domain_status == 'in_progress')
                                                        <span class="alert alert-info text-capitalize">{{ str_replace('_',' ',optional($tenant->custom_domain)->custom_domain_status) }}</span>
                                                    @elseif(optional($tenant->custom_domain)->custom_domain_status == 'connected')
                                                        <span class="alert alert-success text-capitalize">{{optional($tenant->custom_domain)->custom_domain_status}}</span>
                                                    @elseif(optional($tenant->custom_domain)->custom_domain_status == 'rejected')
                                                        <span class="alert alert-info text-capitalize">{{str_replace('_', ' ',ucwords(optional($tenant->custom_domain)->custom_domain_status))}}</span>
                                                    @elseif(optional($tenant->custom_domain)->custom_domain_status == null)

                                                    @else
                                                        <span class="alert alert-danger text-capitalize">{{optional($tenant->custom_domain)->custom_domain_status ?? __('Removed')}}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(!empty($tenant->custom_domain))
                                                        {{date('d-m-Y',strtotime(optional($tenant->custom_domain)->updated_at))}}
                                                    @endif
                                                </td>
                                                </tr>


                                        </tbody>
                                    </table>
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


    <div class="modal fade" id="new_custom_domain" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Request Custom Domain')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                </div>
                <form action="{{route('tenant.admin.custom.domain.requests')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <div class="alert-alert-warning">
                            {{__('You already have a custom domain ('.$central_domain.') connected with your portfolio website.
                                if you request another domain now & if it gets connected with our server, then your current domain ('.$central_domain.') will be removed')
                             }}
                        </div>

                        <input type="hidden" name="user_id" value="{{$user_domain_infos->id}}">
                         <input type="hidden" class="form-control" name="old_domain" value="{{$custom_domain_info->old_domain ?? tenant()->id}}">

                          <div class="form-group mt-3">
                            <label for="name">{{__('Old Domain')}}</label>
                            <input type="text" class="form-control" value="{{$custom_domain_info->old_domain ?? tenant()->id}}" readonly>
                            <div id="subdomain-wrap"></div>
                        </div>

                        <div class="form-group mt-3">
                            <label for="name">{{__('Enter your custom domain')}}</label>
                            <input type="text" class="form-control" name="custom_domain" value="{{tenant()->custom_domain?->custom_domain ?? ''}}">
                            <div id="subdomain-wrap"></div>
                        </div>

                        <div class="form-group">
                            {{sprintf(__('Do not use http:// or https://
                              The valid format will be exactly like this one - domain.tld, www.domain.tld or subdomain.domain.tld, www.subdomain.domain.tld'))}}
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Send Request')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $('.close-bars, .body-overlay').on('click', function() {
            $('.dashboard-close, .dashboard-close-main, .body-overlay').removeClass('active');
        });
        $('.sidebar-icon').on('click', function() {
            $('.dashboard-close, .dashboard-close-main, .body-overlay').addClass('active');
        });
    </script>
@endsection

