@extends('landlord.frontend.frontend-page-master')

@section('content')

    <div class="container">
    <div class="body-overlay"></div>
    <div class="dashboard-area landlord dashboard-padding" data-padding-top="100" data-padding-bottom="100">
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
                                <h4 class="title"> {{Auth::guard('web')->user()->name ?? __('Not Given')}} </h4>
                            </div>
                        </div>
                        <div class="dashboard-bottom margin-top-35 margin-bottom-50">
                            <ul class="dashboard-list ">
                                <li class="list @if(request()->routeIs('landlord.user.home')) active @endif">
                                    <a href="{{route('landlord.user.home')}}"> <i class="las la-th"></i> {{__('Dashboard')}} </a>
                                </li>

                                @if(!empty(get_static_option('user_wallet')))
                                    @php
                                        $condition = request()->routeIs('landlord.user.wallet.history') || request()->routeIs('landlord.user.wallet.settings')   ? 'active show' : '';
                                    @endphp

                                    <li class="list has-children {{$condition}}">
                                        <a href="javascript:void(0)"> <i class="las la-wallet"></i> {{__('My Wallet')}} </a>
                                        <ul class="sub-menu">
                                            <li class="list {{request()->routeIs('landlord.user.wallet.history') ? 'selected' : '' }}">
                                                <a class="@if(request()->routeIs('landlord.user.wallet.history')) active-submenu @endif" href="{{route('landlord.user.wallet.history')}}">{{__('My Wallet')}}</a>
                                            </li>
                                            <li class="list {{request()->routeIs('landlord.user.wallet.settings') ? 'selected' : '' }}">
                                                <a class="@if(request()->routeIs('landlord.user.wallet.settings')) active-submenu @endif" href="{{route('landlord.user.wallet.settings')}}">{{__('Settings')}}</a>
                                            </li>
                                        </ul>
                                    </li>
                                @endif

                                <li class="list @if(request()->routeIs('landlord.user.dashboard.package.order')) active @endif">
                                    <a href="{{route('landlord.user.dashboard.package.order')}}"> <i class="las la-tasks"></i> {{__('Payment Logs')}} </a>
                                </li>

                                @php
                                    $auth_user = auth()->guard('web')->user();
                                    $user = \App\Models\User::where('id',$auth_user->id)->first();
                                @endphp

                                @if(count($user->tenant_details) > 0)
                                    <li class="list @if(request()->routeIs('landlord.user.dashboard.custom.domain')) active @endif">
                                        <a href="{{route('landlord.user.dashboard.custom.domain')}}"> <i class="las la-tasks"></i> {{__('Custom Domain')}} </a>
                                    </li>
                                @endif

                                <li class="list @if(request()->routeIs('landlord.user.home.support.tickets')) active @endif">
                                    <a href="{{route('landlord.user.home.support.tickets')}}"> <i class="las la-tasks"></i> {{__('Support Tickets')}} </a>
                                </li>


                                <li class="list @if(request()->routeIs('landlord.user.enable2fa')) active @endif">
                                    <a href="{{ route('landlord.user.enable2fa') }}"> <i class="las la-user"></i> {{__('2FA Settings')}} </a>
                                </li>


                                <li class="list @if(request()->routeIs('landlord.user.home.edit.profile')) active @endif">
                                    <a href="{{route('landlord.user.home.edit.profile')}}"> <i class="las la-user"></i> {{__('Edit Profile')}} </a>
                                </li>
                                <li class="list @if(request()->routeIs('landlord.user.home.change.password')) active @endif ">
                                    <a href="{{route('landlord.user.home.change.password')}}"> <i class="las la-lock"></i> {{__('Change Password')}} </a>
                                </li>

                                <li class="list">
                                    <a href="{{ route('landlord.user.logout') }}" ><i class="las la-sign-out-alt"></i>{{ __('Logout') }}</a>
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


