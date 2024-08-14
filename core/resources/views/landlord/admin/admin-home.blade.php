@extends('landlord.admin.admin-master')

@section('title')
    {{__('Dashboard')}}
@endsection

@section('style')
    <style>
        .landlord_recent_orders .card .card-body {
            overflow: hidden;
        }
    </style>
@endsection

@section('content')
    <div class="col-12 grid-margin stretch-card landlord_recent_orders">
        <div class="card">
            <div class="card-body landlord_card_body">
                <h4 class="card-title mb-5">{{__('Dashboard content')}}</h4>
                <div class="row">
                    <div class="col-md-6 mb-5">
                        <div class="chart-wrapper margin-top-40">
                            <h2 class="chart-title">{{__("Amount Per Month In")}} {{date('Y')}}</h2>
                            <canvas id="monthlyRaised"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6 mb-5">
                        <div class="chart-wrapper margin-top-40">
                            <h2 class="chart-title">{{__("Amount Per Day In Last 30Days")}}</h2>
                            <div>
                                <canvas id="monthlyRaisedPerDay"></canvas>
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
                                <h4 class="font-weight-normal mb-3">{{__('Total Testimonial')}} <i class="mdi mdi-diamond mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">{{$total_testimonial}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-primary card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{global_asset('assets/landlord/admin/images/circle.png')}}" class="card-img-absolute" alt="circle-image">
                                <h4 class="font-weight-normal mb-3">{{__('Total Price Plan')}}<i class="mdi mdi-diamond mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">{{$total_price_plan}}</h2>
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

                         <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body landlord_recent_table">
                                <h3 class=" text-center mb-4">{{__('Recent Order Logs')}}</h3>

                                <table class="table table-responsive table-bordered">
                                    <thead class="text-white bg-purple">
                                    <tr>
                                        <th> {{__('ID')}} </th>
                                        <th> {{__('Order ID')}}</th>
                                        <th> {{__('User Name ')}}</th>
                                        <th> {{__('Package Name')}}</th>
                                        <th> {{__('Price')}} </th>
                                        <th> {{__('Created At')}} </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($recent_order_logs as $key => $data)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $data->package_id ?? '' }}</td>
                                            <td> {{$data->name}} </td>
                                            <td> {{$data->package_name}} </td>
                                            <td>{{amount_with_currency_symbol($data->package_price)}}</td>
                                            <td>{{$data->created_at?$data->created_at->diffForHumans(): ""}}</td>
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
@endsection

@section('scripts')
    <script src="{{asset('assets/common/js/chart.js')}}"></script>
    <script>
        $.ajax({
            url: '{{route('landlord.admin.home.chart.data.month')}}',
            type: 'POST',
            async: false,
            data: {
                _token : "{{csrf_token()}}"
            },
            success: function (data) {
                labels = data.labels;
                chartdata = data.data;
                new Chart(
                    document.getElementById('monthlyRaised'),
                    {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: '{{__('Amount Received')}}',
                                backgroundColor:  '#039cda',
                                borderColor: '#039cda',
                                data: chartdata,
                                barThickness: 15,
                                hoverBackgroundColor: '#fc3c68',
                                borderRadius: 5,
                                hoverBorderColor: '#fc3c68',
                                minBarLength: 50,
                                indexAxis: "x",
                                pointStyle: 'star',
                            }],
                        }
                    }
                );
            }
        });
        $.ajax({
            url: '{{route('landlord.admin.home.chart.data.by.day')}}',
            type: 'POST',
            async: false,
            data: {
                _token : "{{csrf_token()}}"
            },
            success: function (data) {
                labels = data.labels;
                chartdata = data.data;
                new Chart(
                    document.getElementById('monthlyRaisedPerDay'),
                    {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: '{{__('Amount Received')}}',
                                backgroundColor: '#F86048',
                                borderColor: '#fd861d',
                                data: data.data,
                            }]
                        }
                    }
                );
            }
        });
    </script>
@endsection
