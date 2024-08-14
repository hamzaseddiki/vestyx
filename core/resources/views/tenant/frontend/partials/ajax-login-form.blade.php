@php $title = $title ?? __('Login To Leave Review'); @endphp
<div class="login-form">
        <h2 class="tittle text-center my-3">{{__('Login to create a ticket')}}</h2>
    <div class="login-form">
        <form action="{{route('tenant.user.ajax.login')}}" method="post" enctype="multipart/form-data" class="account-form" id="login_form_order_page">
            @csrf
            <div class="error-wrap"></div>
            <div class="col-lg-12 col-md-12">
                <label class="catTittle"> {{__('Username')}}</label>
                <div class="input-form input-form2">
                    <input type="text" name="username" class="form-control" placeholder="{{__('Username')}}">
                </div>
            </div>

            <div class="col-lg-12 col-md-12">
                <label class="catTittle"> {{__('Password')}}</label>
                <div class="input-form input-form2">
                    <input type="password" name="password" class="form-control" placeholder="{{__('Password')}}">
                </div>
            </div>

            <div class="form-group btn-wrapper">
                <button type="submit" id="login_btn" class="submit-btn">{{__('Login')}}</button>
            </div>
            <div class="row mb-4 rmber-area">
                <div class="col-6">
                    <div class="custom-control custom-checkbox mr-sm-2">
                        <input type="checkbox" name="remember" class="custom-control-input" id="remember">
                        <label class="custom-control-label" for="remember">{{__('Remember Me')}}</label>
                    </div>
                </div>
                <div class="col-6 text-right mt-3">
                    <a class="d-block" href="{{route('tenant.user.register')}}">{{__('Create New account?')}}</a>
                    <a href="{{route('tenant.user.forget.password')}}">{{__('Forgot Password?')}}</a>
                </div>
            </div>
        </form>
    </div>
</div>
