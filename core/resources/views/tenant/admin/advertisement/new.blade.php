@extends(route_prefix().'admin.admin-master')

@section('title')
    {{__('New Advertisement')}}
@endsection

@section('style')
    <x-media-upload.css/>
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
                <x-error-msg/>
                <x-flash-msg/>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="header-wrap d-flex justify-content-between">
                            <div class="left-content">
                                <h4 class="header-title">{{__('Add New Advertisement')}}   </h4>
                            </div>
                            <div class="right-content">
                                <a class="btn btn-info btn-sm" href="{{route('tenant.admin.advertisement')}}">{{__('All Advertisements')}}</a>
                            </div>
                        </div>
                        <form action="{{route('tenant.admin.advertisement.store')}}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="tab-content margin-top-40">

                             <div class="row">
                                 <div class="form-group col-md-12" id="title" >
                                     <label for="title">{{__(' Title')}}</label>
                                     <input type="text" class="form-control" name="title" id="title">
                                 </div>
                                 <div class="form-group  col-md-12">
                                     <label for="title">{{__('Advertisement Type')}}</label>
                                     <select class="form-control" name="type" id="type">
                                         <option selected disabled>{{__('Select a Type')}}</option>
                                         <option value="image">{{__('Image')}}</option>
                                         <option value="google_adsense">{{__('Google Adsense')}}</option>
                                         <option value="scripts">{{__('Scripts')}}</option>
                                     </select>
                                 </div>

                                 <div class="form-group col-md-12">
                                     <label for="title">{{__('Advertisement Size')}}</label>
                                     <select class="form-control" name="size" id="size">
                                         <option selected disabled>{{__('Select a Size')}}</option>
                                         <option value="350*250">{{__('350 x 250')}}</option>
                                         <option value="320*50">{{__('320 x 50')}}</option>
                                         <option value="160*600">{{__('160 x 600')}}</option>
                                         <option value="300*600">{{__('300 x 600')}}</option>
                                         <option value="336*280">{{__('336 x 280')}}</option>
                                         <option value="728*90">{{__('728 x 90')}}</option>
                                         <option value="730*180">{{__('730 x 180')}}</option>
                                         <option value="730*210">{{__('730 x 210')}}</option>
                                         <option value="300*1050">{{__('300 X 1050')}}</option>
                                         <option value="950*160">{{__('950 X 160')}}</option>
                                         <option value="950*200">{{__('950 X 200')}}</option>
                                         <option value="250*1110">{{__('250 X 1110')}}</option>
                                     </select>
                                 </div>

                                 <div class="form-group col-md-12" id="slot" style="display: none">
                                     <label for="title">{{__('Advertisement Slot')}}</label>
                                     <input type="text" class="form-control" name="slot" >
                                 </div>

                                 <div class="form-group col-md-12" style="display: none" id="embed_code">
                                     <label for="title">{{__('Embed Code')}}</label>
                                     <textarea class="form-control" name="embed_code"></textarea>
                                 </div>

                                 <div class="form-group col-md-12" style="display: none" id="redirect_url">
                                     <label for="title">{{__('Redirect URL')}}</label>
                                     <input type="text" class="form-control" name="redirect_url" >
                                 </div>

                                 <div class="form-group col-md-12">
                                     <label for="title">{{__('Status')}}</label>
                                     <select class="form-control" name="status">
                                         <option value="0">{{__('Inactive')}}</option>
                                         <option value="1">{{__('Active')}}</option>
                                     </select>
                                 </div>

                                </div>

                                <x-fields.media-upload name="image" title="{{__('Image')}}"/>

                                <button id="submit" type="submit" class="btn btn-primary mt-5 submit_btn">{{__('Submit Advertise ')}}</button>
                              </div>
                        </form>
                   </div>
                </div>
            </div>
        </div>
    </div>
    <x-media-upload.markup/>
@endsection

@section('scripts')
    <x-media-upload.js/>
    <script>
        (function ($) {
            "use strict";
            $(document).ready(function () {
                <x-btn.submit/>
                $('.image').hide();
                $(document).on('change','#type',function(e){
                    e.preventDefault();
                    let el = $(this).val();
                    if(el === 'image'){
                        $('.image').show();
                        $('#redirect_url').show();
                        $('#slot').hide();
                        $('#embed_code').hide();

                    }else if(el === 'google_adsense'){
                        $('#slot').show();
                        $('#redirect_url').hide();
                        $('#embed_code').hide();
                        $('.image').hide();

                    }else if(el === 'scripts'){
                        $('#embed_code').show();
                        $('#slot').hide();
                        $('#redirect_url').hide();
                        $('.image').hide();

                    }else{
                        $('#redirect_url').hide();
                    }
                });
            });
        })(jQuery);
    </script>
@endsection

