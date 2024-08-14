@extends('tenant.frontend.frontend-page-master')
@section('title')
    {{__('User Dashboard')}}
@endsection

@section('page-title')
    {{__('User Dashboard')}}
@endsection

@section('style')
    <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/css/custom-dashboard.css')}}">
@endsection

@section('content')
    <div class="container">
    <div class="body-overlay"></div>
    <div class="dashboard-area dashboard-padding my-5 py-5" data-padding-bottom="100">
        <div class="container-fluid">
            <div class="dashboard-contents-wrapper">
                <div class="dashboard-icon">
                    <div class="sidebar-icon">
                        <i class="las la-bars"></i>
                    </div>
                </div>
                <div class="dashboard-left-content">
                    <div class="dashboard-close-main">
                        <div class="close-bars"> <i class="las la-times"></i> </div>
                        <div class="dashboard-top padding-top-40">
                            <div class="author-content">
                                {!! render_image_markup_by_attachment_id(Auth::guard('web')->user()->image, 'thumb' ?? NULL) !!}
                                <h4 class="title"> {{Auth::guard('web')->user()->name ?? __('Not Given')}} </h4>
                            </div>
                        </div>
                        <div class="dashboard-bottom margin-top-35 margin-bottom-50">
                            <ul class="dashboard-list ">
                                <li class="list @if(request()->routeIs('tenant.user.home')) active @endif">
                                    <a href="{{route('tenant.user.home')}}"> <i class="las la-th"></i> {{__('Dashboard')}} </a>
                                </li>
                                @if(moduleExists('HotelBooking'))
                                <li class="list @if(request()->routeIs('tenant.user.dashboard.hotel-booking') || request()->routeIs('tenant.user.dashboard.user.reservation') || request()->routeIs('tenant.user.dashboard.canceled.reservation') || request()->routeIs('tenant.user.dashboard.pending.reservation') || request()->routeIs('tenant.user.dashboard.approved.reservation')) active @endif">
                                    <a href="{{route('tenant.user.dashboard.hotel-booking')}}"> <i class="las la-th"></i> {{__('Hotel Bookings')}} </a>
                                </li>
                                @endif
                                <li class="list @if(request()->routeIs('tenant.user.dashboard.product.order')) active @endif">
                                    <a href="{{route('tenant.user.dashboard.product.order')}}"> <i class="las la-tasks"></i> {{__('Product Logs')}} </a>
                                </li>

                                <li class="list @if(request()->routeIs('tenant.user.dashboard.donations')) active @endif">
                                    <a href="{{route('tenant.user.dashboard.donations')}}"> <i class="las la-tasks"></i> {{__('Donation Logs')}} </a>
                                </li>

                                <li class="list @if(request()->routeIs('tenant.user.dashboard.weddings')) active @endif">
                                    <a href="{{route('tenant.user.dashboard.weddings')}}"> <i class="las la-tasks"></i> {{__('Plan Logs')}} </a>
                                </li>

                                <li class="list @if(request()->routeIs('tenant.user.dashboard.events')) active @endif">
                                    <a href="{{route('tenant.user.dashboard.events')}}"> <i class="las la-tasks"></i> {{__('Event Logs')}} </a>
                                </li>

                                <li class="list @if(request()->routeIs('tenant.user.dashboard.jobs')) active @endif">
                                    <a href="{{route('tenant.user.dashboard.jobs')}}"> <i class="las la-tasks"></i> {{__('Applied Jobs')}} </a>
                                </li>

                                <li class="list @if(request()->routeIs('tenant.user.dashboard.appointments')) active @endif">
                                    <a href="{{route('tenant.user.dashboard.appointments')}}"> <i class="las la-tasks"></i> {{__('Appointments')}} </a>
                                </li>

                                <li class="list @if(request()->routeIs('tenant.user.home.support.tickets')) active @endif">
                                    <a href="{{route('tenant.user.home.support.tickets')}}"> <i class="las la-tasks"></i> {{__('Support Tickets')}} </a>
                                </li>
                                <li class="list @if(request()->routeIs('tenant.user.home.edit.profile')) active @endif">
                                    <a href="{{route('tenant.user.home.edit.profile')}}"> <i class="las la-tasks"></i> {{__('Edit Profile')}} </a>
                                </li>
                                <li class="list @if(request()->routeIs('tenant.user.home.change.password')) active @endif ">
                                    <a href="{{route('tenant.user.home.change.password')}}"> <i class="las la-tasks"></i> {{__('Change Password')}} </a>
                                </li>

                                <li class="list">
                                    <a href="{{ route('tenant.user.logout') }}" ><i class="las la-sign-out-alt"></i>{{ __('Logout') }}</a>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
                <div class="dashboard-right">

                    <div class="parent">
                        <div class="col-xl-12">
                            <x-error-msg/>
                            <x-flash-msg/>
                            @yield('section')
                        </div>
                    </div>

                </div>

            </div>
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


