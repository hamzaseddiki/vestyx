@extends(route_prefix().'admin.admin-master')
@section('title') {{__('All Newsletter')}} @endsection

@section('style')
    <x-summernote.css/>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">

        <div class="card">
            <div class="card-body">
                <x-error-msg/>
                <x-flash-msg/>
                <h4 class="header-title">{{__('Send Mail To All Newsletter Subscriber')}}</h4>
                <form action="{{route('landlord.admin.newsletter.mail')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="edit_icon">{{__('Subject')}}</label>
                        <input type="text" class="form-control"  id="subject" name="subject" placeholder="{{__('Subject')}}">
                    </div>
                    <div class="form-group">
                        <label for="message">{{__('Message')}}</label>
                        <input type="hidden" name="message" >
                        <div class="summernote"></div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-lg btn-primary">{{__('Send Mail')}}</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>


@endsection

@section('scripts')
    <x-summernote.js/>
    <script>
        (function ($){
            "use strict";
            $(document).ready(function () {

                $('.summernote').summernote({
                    height: 300,   //set editable area's height
                    codemirror: { // codemirror options
                        theme: 'monokai'
                    },
                    callbacks: {
                        onChange: function(contents, $editable) {
                            $(this).prev('input').val(contents);
                        }
                    }
                });
            });

        })(jQuery)
    </script>
@endsection
