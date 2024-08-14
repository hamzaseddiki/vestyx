@extends('landlord.admin.admin-master')
@section('title')
    {{ __('Add New Theme') }}
@endsection

@section('style')
    <style>
        .padding-30{
            padding: 30px;
        }
        .form-group.plugin-upload-field {
            margin-top: 60px;
        }

        .form-group.plugin-upload-field label {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 35px;
        }

        .form-group.plugin-upload-field small {
            font-size: 12px;
            margin-top: 11px;
        }

    </style>
@endsection
@section('content')
    <div class="dashboard-recent-order">
        <div class="row">
            <x-flash-msg/>
            <div class="col-md-12">
                <div class="recent-order-wrapper dashboard-table bg-white padding-30">
                    <div class="header-wrap">
                        <h4 class="header-title mb-2">{{__("Add New Theme")}}</h4>
                        <p>{{__("upload new theme from here. if you have a theme already but you have uploaded that theme file again, it will override existing theme files")}}</p>
                    </div>
                    <x-error-msg/>

                    <form action="{{route("landlord.admin.add.theme")}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group plugin-upload-field">
                            <label for="#" class="d-block">{{__("Upload Theme File")}}</label>
                            <input type="file" name="theme_file" accept=".zip">
                            <small class="d-block">{{__("only zip file accepted")}}</small>
                        </div>
                        <button type="submit" class="btn btn-gradient-primary me-2 mt-5">{{__("Submit")}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        (function ($){
            "use strict";
        })(jQuery);
    </script>
@endsection
