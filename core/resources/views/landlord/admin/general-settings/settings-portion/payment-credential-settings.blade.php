<div class="accordion-wrapper">
    <div id="accordion-payment">
        @foreach($all_gateway as $gateway)
            <div class="card">
                <div class="card-header" id="{{$loop->index}}_settings">
                    <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#settings_content_{{$loop->index}}" aria-expanded="false" >
                            <span class="page-title"> {{ str_replace('_',' ', ucfirst($gateway->name)) }}</span>
                        </button>
                    </h5>
                </div>
                <div id="settings_content_{{$loop->index}}" class="collapse"  data-parent="#accordion-payment">
                    <div class="card-body">

                        @if(!empty($gateway->description))
                            <div class="payment-notice alert alert-warning">
                                <p>{{$gateway->description}}</p>
                            </div>
                        @endif

                            @if($gateway->name === "paystack")
                                <p>{{__('Do not forget to put below url to "Settings > API Key & Webhook > Callback URL" in your paystack admin panel')}}</p>
                                <input type="text" class="form-control mb-2" readonly value="{{route('landlord.frontend.paystack.ipn')}}"/>
                            @endif

                        <div class="form-group">
                            <label for="instamojo_gateway"><strong>{{__('Enable/Disable '. ucfirst($gateway->name))}}</strong></label>
                            <input type="hidden" name="{{$gateway->name}}_gateway">
                            <label class="switch">
                                <input type="checkbox" name="{{$gateway->name}}_gateway"  @if($gateway->status === 1 )) checked @endif >
                                <span class="slider onff"></span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="instamojo_test_mode"><strong>{{sprintf(__('Enable Test Mode %s'),ucfirst($gateway->name))}}</strong></label>

                            <label class="switch">
                                <input type="checkbox" name="{{$gateway->name}}_test_mode" @if($gateway->test_mode === 1) checked @endif>
                                <span class="slider onff"></span>
                            </label>
                        </div>

                        <x-landlord-others.edit-media-upload-image label="{{ sprintf(__('%s Logo'),__(ucfirst($gateway->name)))}}" name="{{$gateway->name.'_logo'}}" :value="$gateway->image" size="100*100"/>

                        @php
                            $credentials = !empty($gateway->credentials) ? json_decode($gateway->credentials) : [];
                        @endphp

                        @foreach($credentials as $cre_name =>  $cre_value)
                            <div class="form-group">
                                <label >{{ str_replace('_', ' ' , ucfirst($cre_name)) }}</label>
                                <input type="text" name="{{$gateway->name.'_'.$cre_name}}" value="{{$cre_value}}" class="form-control">
                                @if($gateway->name == 'paytabs')
                                    @if($cre_name == 'region')
                                        <small class="text-secondary" >{{__('GLOBAL, ARE, EGY, SAU, OMN, JOR')}}</small>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
