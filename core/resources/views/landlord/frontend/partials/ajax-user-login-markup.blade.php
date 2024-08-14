
<div class="login-form">
    <p class="mb-4 text-center">{{__('Login to Leave a Comment')}}</p>

    <div class="login-form">
        <form action="#" method="post" enctype="multipart/form-data" class="account-form" id="login_form_order_page">
            @csrf
            <div class="error-wrap"></div>
            <div class="form-group">
                <input type="text" name="username" class="form-control" placeholder="{{__('Username')}}">
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="{{__('Password')}}">
            </div>
            <div class="form-group btn-wrapper">
                <button type="submit" id="login_btn" class="submit-btn btn-default boxed-btn utility-btn style-04">{{ __('Login') }}</button>
            </div>

            <div class="row mb-4 rmber-area ajax-partial-login-form">
                <div class="col-6">
                    <div class="custom-control custom-checkbox mr-sm-2 text-left">
                        <input type="checkbox" name="remember" class="custom-control-input" id="remember">
                        <label class="custom-control-label int" for="remember">{{__('Remember Me')}}</label>
                    </div>
                </div>
                <div class="col-6 text-right">
                    <a class="d-block int" href="{{route('tenant.user.register')}}">{{__('Create New account?')}}</a>
                    <a href="{{route('tenant.user.forget.password')}}" class="int">{{__('Forgot Password?')}}</a>
                </div>

            </div>
        </form>
    </div>
</div>

@section('scripts')
    <x-custom-js.ajax-login/>
@endsection
