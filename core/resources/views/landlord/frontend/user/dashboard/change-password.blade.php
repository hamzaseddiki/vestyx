@extends('landlord.frontend.user.dashboard.user-master')

@section('title')
    {{__('Change Password')}}
@endsection

@section('page-title')
    {{__('Change Password')}}
@endsection


@section('section')

        <div class="parent my-5">
            <h2 class="title">{{__('Change Password')}}</h2>
            <form action="{{route('landlord.user.password.change')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="old_password">{{__('Old Password')}}</label>
                    <input type="password" class="form-control" id="old_password" name="old_password"
                           placeholder="{{__('Old Password')}}">
                </div>
                <div class="form-group">
                    <label for="password">{{__('New Password')}}</label>
                    <input type="password" class="form-control" id="password" name="password"
                           placeholder="{{__('New Password')}}">
                </div>
                <div class="form-group">
                    <label for="password_confirmation">{{__('Confirm Password')}}</label>
                    <input type="password" class="form-control" id="password_confirmation"
                           name="password_confirmation" placeholder="{{__('Confirm Password')}}">
                </div>
                <div class="btn-wrapper">
                    <button id="save" type="submit" class="btn-default boxed-btn">{{__('Save changes')}}</button>
                </div>
            </form>
        </div>


@endsection

@section('scripts')
    <script>
        <x-btn.save/>
    </script>

    <script>
        $('.close-bars, .body-overlay').on('click', function() {
            $('.dashboard-close, .dashboard-close-main, .body-overlay').removeClass('active');
        });
        $('.sidebar-icon').on('click', function() {
            $('.dashboard-close, .dashboard-close-main, .body-overlay').addClass('active');
        });
    </script>

@endsection
