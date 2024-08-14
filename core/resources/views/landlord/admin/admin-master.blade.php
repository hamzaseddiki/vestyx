@include('landlord.admin.partials.header')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> @yield('title') </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('landlord.admin.home')}}">{{__('Dashboard')}}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
                </ol>
            </nav>
        </div>
        @yield('content')
    </div>

 @include('landlord.admin.partials.footer')
