@extends('landlord.admin.admin-master')
@section('title')
    {{__('Database Upgrade')}}
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                <x-error-msg/>
                <x-flash-msg/>
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__("Database Upgrade")}}</h4>

                        <form action="{{route('landlord.admin.general.database.upgrade.settings')}}" method="POST" id="cache_settings_form" enctype="multipart/form-data">
                            @csrf
                            <button class="btn btn-primary mt-4 pr-4 pl-4 clear-cache-submit-btn" data-value="cache">{{__('Database Upgrade')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        (function($){
            "use strict";

            $(document).ready(function(){
                $(document).on('click','.clear-cache-submit-btn',function(e){
                    $(this).html('<i class="las la-spinner la-spin"></i> {{__("Processing..")}}')
                });
            });

        })(jQuery);
    </script>
@endsection
