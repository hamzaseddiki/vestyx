@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('Payment Settings')}}
@endsection
@section('style')
<x-summernote.css/>
    <x-media-upload.css/>
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                <x-flash-msg/>
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__("Payment Gateway Settings")}}</h4>
                        <x-error-msg/>
                        <form action="{{route(route_prefix().'admin.general.payment.settings')}}" method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12">
                                    @include('landlord.admin.general-settings.settings-portion.payment-common-settings')
                                    @include('landlord.admin.general-settings.settings-portion.payment-credential-settings')
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

   <x-media-upload.markup/>

@endsection
@section('scripts')
  <x-summernote.js/>
  <x-media-upload.js/>
    <script>
        (function($){
            "use strict";
            $(document).ready(function ($) {
                $('.summernote').summernote({
                    height: 200,   //set editable area's height
                    codemirror: { // codemirror options
                        theme: 'monokai'
                    },
                    callbacks: {
                        onChange: function(contents, $editable) {
                            $(this).prev('input').val(contents);
                        }
                    }
                });
                if($('.summernote').length > 0){
                    $('.summernote').each(function(index,value){
                        $(this).summernote('code', $(this).data('content'));
                    });
                }
            });
        })(jQuery);


    </script>
@endsection
