@extends(route_prefix().'admin.admin-master')
@section('title') {{__('Custom Css')}} @endsection

@section('style')
    <link rel="stylesheet" href="{{global_asset('assets/landlord/common/css/codemirror.css')}}">
    <link rel="stylesheet" href="{{global_asset('assets/landlord/common/css/show-hint.css')}}">
@endsection
@section('site-title')
    {{__('Custom Css')}}
@endsection
@section('content')

    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-5">{{__('Custom Css')}}</h4>
                <x-error-msg/>
                <x-flash-msg/>

                <form action="{{route(route_prefix().'admin.general.custom.css.settings')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <textarea name="custom_css_area" id="custom_css_area" cols="30" rows="10">{{$custom_css}}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                </form>

            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{global_asset('assets/landlord/common/js/codemirror.js')}}"></script>
    <script src="{{global_asset('assets/landlord/common/js/css.js')}}"></script>
    <script src="{{global_asset('assets/landlord/common/js/show-hint.js')}}"></script>
    <script src="{{global_asset('assets/landlord/common/js/css-hint.js')}}"></script>
    <script>
        (function($) {
            "use strict";
            var editor = CodeMirror.fromTextArea(document.getElementById("custom_css_area"), {
                lineNumbers: true,
                mode: "text/css",
                matchBrackets: true
            });
        })(jQuery);
    </script>
@endsection
