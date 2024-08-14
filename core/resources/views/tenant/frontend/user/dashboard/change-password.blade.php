@extends('tenant.frontend.user.dashboard.user-master')

@section('page-title')
{{__('Change Password')}}
@endsection


@section('site-title')
    {{__('Change Password')}}
@endsection

@section('section')

        <div class="parent my-5">
            <h2 class="title">{{__('Change Password')}}</h2>
            <form action="{{route('tenant.user.password.change')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group mt-3">
                    <label for="old_password">{{__('Old Password')}}</label>
                    <input type="password" class="form-control" id="old_password" name="old_password"
                           placeholder="{{__('Old Password')}}">
                </div>
                <div class="form-group my-3">
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

@endsection
