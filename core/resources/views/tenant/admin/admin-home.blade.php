@extends('tenant.admin.admin-master')

@section('title')
    {{__('Dashboard')}}
@endsection

@section('style')
    <style>
        .tenant_recent_orders .card .card-body {
            overflow: hidden;
        }

    </style>
@endsection

@section('content')

    <div class="col-12 grid-margin stretch-card tenant_recent_orders">
        <div class="card">

            @if(!empty($current_package))
                <div class="main">
                    <div class="alert border-left border-primary text-white text-center bg-gradient-info">
                        <strong>{{__('Current Package :')}} </strong> {{$current_package->package_name}}
                        <span class="badge badge-warning text-dark">
                        {{ \App\Enums\PricePlanTypEnums::getText(optional($current_package->package)->type ) }}
                    </span>

                        @if(optional(tenant()->payment_log)->status == 'trial')
                            @php
                                $days = get_trial_days_left(tenant());
                            @endphp

                            <strong class="text-capitalize"> ( {{optional(tenant()->user?->payment_single_log)->status}} : {{$days ?? ''}} {{__('Days Left')}})</strong>
                        @else
                            <strong> ( {{__('Expire Date :')}} {{$current_package->expire_date ?? ''}})</strong>
                        @endif

                        <a class="btn btn-dark btn-sm pull-right" href="{{route('landlord.homepage') .'#price_plan_section'}}" target="_blank">{{__('Buy a Plan')}}</a>
                    </div>

                </div>
            @endif



            @if(tenant()->instruction_status == 1)
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-center">{{__('Set up your website in few easy steps')}}</h4>
                </div>
                <div class="card-body">
                    <div class="row">

                        @php
                            $all_website_instructions = \App\Models\WebsiteInstruction::where('status',1)->get();
                        @endphp

                        @foreach($all_website_instructions as $instruction)

                            <div class="col-md-4">
                                <div class="card mb-3">

                                    @php
                                        $img = get_lnadlord_attachment_image_by_id_without_query($instruction->imageDetails,$instruction->image);
                                    @endphp

                                    <div class="card-body">
                                        <img class="card-img-top" src="{{$img['img_url'] ?? ''}}" alt="Website Logo" style="max-width: 200px;">
                                        <h5 class="card-title mt-3">{{$instruction->getTranslation('title',default_lang())}}</h5>
                                        <p class="card-text">{{$instruction->getTranslation('description',default_lang())}}</p>

                                        @php
                                            $all_repeater_data =  !empty($instruction->repeater_data) ? unserialize($instruction->repeater_data,['class' => false]) : ['title' => ['']];
                                        @endphp


                                        @foreach($all_repeater_data['button_text'] ?? [] as $key=> $button_text)
                                            <a href="{{ replace_instruction_url($all_repeater_data['button_url'][$key] ?? '') }}" class="btn btn-primary mt-3 mr-3">{{__($button_text) ?? ''}}</a>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            <div class="card-body ">
                <div class="row">
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-danger card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{global_asset('assets/landlord/admin/images/circle.png')}}" class="card-img-absolute" alt="circle-image">
                                <h4 class="font-weight-normal mb-3">{{__('Total Page')}}<i class="las la-user-shield mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">{{$total_page}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-danger card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{global_asset('assets/landlord/admin/images/circle.png')}}" class="card-img-absolute" alt="circle-image">
                                <h4 class="font-weight-normal mb-3">{{__('Total Admins')}}<i class="las la-user-shield mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">{{$total_admin}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{global_asset('assets/landlord/admin/images/circle.png')}}" class="card-img-absolute" alt="circle-image">
                                <h4 class="font-weight-normal mb-3">{{__('Total Users')}}<i class="las la-user-shield mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">{{$total_user}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-success card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{global_asset('assets/landlord/admin/images/circle.png')}}" class="card-img-absolute" alt="circle-image">
                                <h4 class="font-weight-normal mb-3">{{__('Total Blogs')}}<i class="mdi mdi-diamond mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">{{$all_blogs}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{global_asset('assets/landlord/admin/images/circle.png')}}" class="card-img-absolute" alt="circle-image">
                                <h4 class="font-weight-normal mb-3">{{__('Total Services')}} <i class="mdi mdi-diamond mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">{{$total_services}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-primary card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{global_asset('assets/landlord/admin/images/circle.png')}}" class="card-img-absolute" alt="circle-image">
                                <h4 class="font-weight-normal mb-3">{{__('Total Language')}}<i class="mdi mdi-diamond mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">{{$total_language}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-warning card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{global_asset('assets/landlord/admin/images/circle.png')}}" class="card-img-absolute" alt="circle-image">
                                <h4 class="font-weight-normal mb-3">{{__('Total Brand')}} <i class="mdi mdi-diamond mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">{{$total_brand}}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-success card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{global_asset('assets/landlord/admin/images/circle.png')}}" class="card-img-absolute" alt="circle-image">
                                <h4 class="font-weight-normal mb-3">{{__('Total Products')}} <i class="mdi mdi-diamond mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">{{$total_product}}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{global_asset('assets/landlord/admin/images/circle.png')}}" class="card-img-absolute" alt="circle-image">
                                <h4 class="font-weight-normal mb-3">{{__('Total Campaign')}} <i class="mdi mdi-diamond mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">{{$total_campaign}}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-danger card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{global_asset('assets/landlord/admin/images/circle.png')}}" class="card-img-absolute" alt="circle-image">
                                <h4 class="font-weight-normal mb-3">{{__('Total Event')}} <i class="mdi mdi-diamond mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">{{$total_event}}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-primary card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{global_asset('assets/landlord/admin/images/circle.png')}}" class="card-img-absolute" alt="circle-image">
                                <h4 class="font-weight-normal mb-3">{{__('Total Job')}} <i class="mdi mdi-diamond mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">{{$total_job}}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-warning card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{global_asset('assets/landlord/admin/images/circle.png')}}" class="card-img-absolute" alt="circle-image">
                                <h4 class="font-weight-normal mb-3">{{__('Total Article')}} <i class="mdi mdi-diamond mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">{{$total_article}}</h2>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-6 grid-margin landlord_recent_table">
                        <h3 class=" text-center mb-4">{{__('Recent Product Order Logs')}}</h3>

                        <table class="table table-responsive table-bordered">
                            <thead class="text-white bg-purple">
                            <tr>
                                <th> {{__('ID')}} </th>
                                <th> {{__('Order ID')}}</th>
                                <th> {{__('User Name ')}}</th>
                                <th> {{__('Order Amount')}} </th>
                                <th> {{__('Payment Status')}} </th>
                                <th> {{__('Created At')}} </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($recent_product_order_logs ?? [] as $key => $data)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $data->id ?? '' }}</td>
                                    <td> {{$data->name}} </td>
                                    <td>{{amount_with_currency_symbol($data->total_amount)}}</td>
                                    <td> {{$data->payment_status}} </td>
                                    <td>{{$data->created_at->diffForHumans()}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-6 grid-margin landlord_recent_table" >
                        <h3 class=" text-center mb-4">{{__('Recent Donation Logs')}}</h3>

                        <table class="table table-responsive table-bordered">
                            <thead class="text-white bg-purple">
                            <tr>
                                <th> {{__('ID')}} </th>
                                <th> {{__('Order ID')}}</th>
                                <th> {{__('User Name ')}}</th>
                                <th> {{__('Amount')}} </th>
                                <th> {{__('Payment Status')}}</th>
                                <th> {{__('Created At')}} </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($recent_donation_logs ?? [] as $key => $data)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $data->id ?? '' }}</td>
                                    <td> {{$data->name}} </td>
                                    <td>{{amount_with_currency_symbol($data->amount)}}</td>
                                    <td> {{$data->status  == 1 ? __('Complete') : __('Pending')}} </td>
                                    <td>{{$data->created_at->diffForHumans()}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-6 grid-margin landlord_recent_table">
                        <h3 class=" text-center mb-4">{{__('Recent Event Logs')}}</h3>

                        <table class="table table-responsive table-bordered">
                            <thead class="text-white bg-purple">
                            <tr>
                                <th> {{__('ID')}} </th>
                                <th> {{__('Order ID')}}</th>
                                <th> {{__('User Name ')}}</th>
                                <th> {{__('Amount')}} </th>
                                <th> {{__('Payment Status')}}</th>
                                <th> {{__('Created At')}} </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($recent_event_logs ?? [] as $key => $data)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $data->id ?? '' }}</td>
                                    <td> {{$data->name}} </td>
                                    <td>{{amount_with_currency_symbol($data->amount)}}</td>
                                    <td> {{$data->status == 1 ? __('Complete') : __('Pending')}} </td>
                                    <td>{{$data->created_at->diffForHumans()}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-6 grid-margin landlord_recent_table">
                        <h3 class=" text-center mb-4">{{__('Recent Job Post Logs')}}</h3>

                        <table class="table table-responsive table-bordered">
                            <thead class="text-white bg-purple">
                            <tr>
                                <th> {{__('ID')}} </th>
                                <th> {{__('Order ID')}}</th>
                                <th> {{__('User Name ')}}</th>
                                <th> {{__('Price')}} </th>
                                <th> {{__('Payment Status')}}</th>
                                <th> {{__('Created At')}} </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($recent_job_logs ?? [] as $key => $data)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $data->id ?? '' }}</td>
                                    <td> {{$data->name}} </td>
                                    <td>{{amount_with_currency_symbol($data->amount)}}</td>
                                    <td> {{$data->status == 1 ? __('Complete') : __('Pending')}} </td>
                                    <td>{{$data->created_at->diffForHumans()}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
