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
                        <x-error-msg/>
                        <h4 class="header-title">{{__("Payment Gateway Settings")}}</h4>
                        <form action="{{route(route_prefix().'admin.payment.gateway.update')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="gateway" value="{{$gateway->name ?? ''}}">

                            <div class="row">
                                <div class="col-lg-12">
                                        <div class="card">

                                        <h3 class="mt-3"> {{ str_replace('_',' ', ucfirst($gateway->name ?? '')) }}</h3>

                                                <div class="card-body">

                                                    @if(!empty($gateway->description))
                                                        <div class="payment-notice alert alert-warning">
                                                            <p>{{$gateway->description}}</p>
                                                        </div>
                                                    @endif

                                                    @if(@$gateway->name === "paystack")
                                                        <p>{{__('Do not forget to put below url to "Settings > API Key & Webhook > Callback URL" in your paystack admin panel')}}</p>
                                                        <input type="text" class="form-control mb-2" readonly value="{{route('landlord.frontend.paystack.ipn')}}"/>
                                                    @endif

                                                    <div class="form-group">
                                                        <label for="instamojo_gateway"><strong>{{__('Enable/Disable')}}  {{ str_replace('_',' ',ucfirst(@$gateway->name)) }}</strong></label>
                                                        <input type="hidden" name="{{@$gateway->name}}_gateway">
                                                        <label class="switch">
                                                            <input type="checkbox" name="{{@$gateway->name}}_gateway"  @if(@$gateway->status === 1 )) checked @endif >
                                                            <span class="slider onff"></span>
                                                        </label>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="instamojo_test_mode"><strong>{{__('Enable Test Mode')}} {{ str_replace('_',' ',ucfirst(@$gateway->name)) }}</strong></label>

                                                        <label class="switch">
                                                            <input type="checkbox" name="{{@$gateway->name}}_test_mode" @if(@$gateway->test_mode === 1) checked @endif>
                                                            <span class="slider onff"></span>
                                                        </label>
                                                    </div>

                                                    <x-landlord-others.edit-media-upload-image label="{{__('Logo')}} {{ str_replace('_',' ',ucfirst(@$gateway->name)) }}" name="{{@$gateway->name.'_logo'}}" :value="@$gateway->image" size="100*100"/>


                                                    @php
                                                        $credentials = !empty(@$gateway->credentials) ? json_decode(@$gateway->credentials) : [];
                                                    @endphp

                                                    @foreach($credentials as $cre_name =>  $cre_value)
                                                      @if(!in_array(@$gateway->name,['manual_payment_','bank_transfer','kinetic']) )
                                                        <div class="form-group">
                                                            <label >{{ str_replace('_', ' ' , ucfirst($cre_name)) }}</label>
                                                            <input type="text" name="{{@$gateway->name.'_'.$cre_name}}" value="{{$cre_value}}" class="form-control">

                                                            @if(@$gateway->name == 'paytabs')
                                                                @if($cre_name == 'region')
                                                                    <small class="text-secondary" >{{__('GLOBAL, ARE, EGY, SAU, OMN, JOR')}}</small>
                                                                @endif
                                                            @endif
                                                        </div>
                                                        @endif

                                                          @if(in_array(@$gateway->name,['manual_payment_','bank_transfer']))
                                                              @if($loop->index == 0)
                                                                <div class="form-group">
                                                                    <label >{{ str_replace('_', ' ' , ucfirst($cre_name)) }}</label>
                                                                    <input type="text" name="{{@$gateway->name.'_'.$cre_name}}" value="{{$cre_value}}" class="form-control">
                                                                </div>
                                                                @elseif($loop->index == 1)
                                                                  <div class="form-group">
                                                                      <label >{{ str_replace('_', ' ' , ucfirst($cre_name)) }}</label>
                                                                      <textarea name="{{@$gateway->name.'_'.$cre_name}}" class="form-control" rows="4">{!! $cre_value !!}</textarea>
                                                                  </div>

                                                                  @if(@$gateway->name == 'manual_payment_')
                                                                      <small class="text-primary" >{{__('It will ask user for transaction id..!')}}</small>
                                                                  @endif

                                                                  @if(@$gateway->name == 'bank_transfer')
                                                                      <small class="text-primary" >{{__('It will ask user for bank attachment..!')}}</small>
                                                                  @endif
                                                              @endif

                                                          @endif
                                                          @if(@$gateway->name == 'kinetic')
                                                              <div class="form-group">
                                                                  <label> {{__('Merchant Key')}}</label>
                                                                  <input type="text" name="{{@$gateway->name.'_'.$cre_name}}" value="{{$cre_value}}"  class="form-control">
                                                              </div>
                                                          @endif
                                                    @endforeach

                                                </div>

                                        </div>
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
