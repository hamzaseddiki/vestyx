
<div class="login-form">
    <h4 class="title text-center mb-4">{{__('Login to Leave a Comment')}}</h4>

    <div class="login-form">
        <form action="#" method="post" enctype="multipart/form-data" class="account-form" id="login_form_order_page">
            @csrf
            <div class="error-wrap"></div>
            <div class="input-form input-form2">
                <input type="text" name="username" class="form-control" placeholder="{{__('Username')}}">
            </div>
            <div class="input-form input-form2">
                <input type="password" name="password" class="form-control" placeholder="{{__('Password')}}">
            </div>
            <div class=" btn-wrapper d-flex justify-content-center">
                <button type="submit" id="login_btn" class="cmn-btn1 w-100 mb-40">{{ __('Login') }}</button>
            </div>

            <div class="row mb-4 rmber-area ajax-partial-login-form  d-flex justify-content-between">
                <div class="col-6">
                    <div class="custom-control custom-checkbox mr-sm-2 text-left">
                        <input type="checkbox" name="remember" class="custom-control-input" id="remember">
                        <label class="custom-control-label int" for="remember">{{__('Remember Me')}}</label>
                    </div>
                </div>
                <div class="col-6">
                    <a class="d-block int" href="{{route('tenant.user.register')}}">{{__('Create New account?')}}</a>
                    <a href="{{route('tenant.user.forget.password')}}" class="int">{{__('Forgot Password?')}}</a>
                </div>

            </div>
        </form>
    </div>
</div>

@section('custom-ajax-scripts')
    <x-custom-js.ajax-login/>,
@endsection
