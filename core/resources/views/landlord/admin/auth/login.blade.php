@extends('layouts.app')

@section('title')
    {{__('Login')}}
@endsection

@section('content')
    @php
    $username = "";
    $password = "";

   if(preg_match("/multipurposesass|picajobfinder/",url('/'))){
        $username = get_static_option_central('landlord_default_tenant_admin_username_set') ?? "super_admin";
        $password = get_static_option_central('landlord_default_tenant_admin_password_set') ?? "12345678";
    }
    @endphp

    <div class="row flex-grow">
        <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left p-5">
                <div class="brand-logo">
                        {!! render_image_markup_by_attachment_id(get_static_option('site_logo')) !!}
                </div>
                <h4>{{__('Hello! let us get started')}}</h4>
                <h6 class="font-weight-light">{{__('Sign in to continue')}}</h6>
                <div id="msg-wrapper"></div>
                <x-error-msg/>
                <x-flash-msg/>
                <form class="pt-3" action="#" method="post">
                    <div class="form-group">
                        <input type="email" name="email" value="{{$username}}" class="form-control form-control-lg" placeholder="{{__('Email or Username')}}">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control form-control-lg" value="{{$password}}" placeholder="{{__('Password')}}">
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn" id="login_submit_btn">{{__('SIGN IN')}}</button>
                    </div>
                    <div class="my-2 d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <label class="form-check-label text-muted">
                                <input type="checkbox" name="remember" class="form-check-input"> {{__('Keep me signed in')}}
                                <i class="input-helper"></i>
                            </label>
                        </div>
                        <a href="{{route(route_prefix().'forget.password')}}" class="auth-link text-black">{{__('Forgot password?')}}</a>
                    </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        var loginFormButton =  document.getElementById('login_submit_btn');
        loginFormButton.addEventListener('click',function (event){
            event.preventDefault();

            var msgWrap = document.getElementById('msg-wrapper');
            msgWrap.innerHTML= '';

            axios({
                url : "{{route(route_prefix().'admin.login')}}",
                method : 'post',
                responseType: 'json',
                data : {
                    email: document.querySelector('input[name="email"]').value,
                    password: document.querySelector('input[name="password"]').value,
                    remember: document.querySelector('input[name="remember"]').value,
                }
            }).then(function(res){
                loginFormButton.innerText = "{{__('Login Success')}}"
                loginFormButton.innerText = "{{__('Redirecting..')}}"
                window.location.reload();
            }).catch(function (error){
                var responseData = error.response.data.errors;
                if(responseData === undefined){
                    msgWrap.innerHTML = '<div class="alert alert-danger">'+error.response?.data?.message+'</div>';
                }
                var child = '<ul class="alert alert-danger">';
                Object.entries(responseData).forEach(function (value){
                    child += '<li>'+value[1] ?? value+'</li>';
                });
                child += '</ul>';

                msgWrap.innerHTML = child;
            });
        })

    </script>
@endsection
