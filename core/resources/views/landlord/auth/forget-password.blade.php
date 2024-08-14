@extends('layouts.app')
@section('title')
    {{__('Forget Password')}}
@endsection

@section('page-title')
   {{__('Forget Password')}}
@endsection

@section('content')

    <div class="row flex-grow">
        <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left p-5">
                <div class="brand-logo text-center">
                    {!! render_image_markup_by_attachment_id(get_static_option('site_logo')) !!}
                </div>
                <h2 class="text-center my-4">{{__('Forget Password ?')}}</h2>
                <x-error-msg/>
                <x-flash-msg/>
                <form action="{{route('landlord.forget.password')}}" method="post" enctype="multipart/form-data" class="contact-page-form style-01">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="username" class="form-control" placeholder="{{__('Username')}}">
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn" id="login_submit_btn">{{__('Send Reset Mail')}}</button>
                    </div>
                </form>
        </div>
    </div>
  </div>


@endsection
@section('scripts')
    <script>
        (function($){
        "use strict";
        $(document).ready(function () {
            <x-btn.custom :id="'send'" :title="__('Sending')"/>
        });
        })(jQuery);
    </script>
@endsection
