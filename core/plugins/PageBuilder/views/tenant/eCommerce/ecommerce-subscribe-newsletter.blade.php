<section class="subscribeArea wow fadeInUp" data-wow-delay="0.0s">
    <div class="container" data-padding-bottom="{{$data['padding_bottom']}}" data-padding-top="{{$data['padding_top']}}">
        <div class="row">
            <div class="col-xxl-12">
                <div class="subscribeCaption text-center">
                    <div class="row justify-content-center">
                        <div class="col-xl-6 col-lg-7 col-md-9 col-sm-11">
                            <h3 class="tittle wow fadeInUp" data-wow-delay="0.0s"> {{$data['title']}}</h3>
                            <div class="row justify-content-center">
                                <div class="col-xl-10 col-lg-11 col-md-11">
                                    <p class="pera wow fadeInUp" data-wow-delay="0.1s" >{{$data['description']}}</p>
                                    <!-- Subscribe Form -->
                                    <form action="#" class="wow fadeInUp" data-wow-delay="0.2s">
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <input type="email" name="email" class="email" placeholder="{{$data['button_placeholder_text']}}">
                                        <div class="form-message-show mt-2"></div>
                                        <button type="submit" class="subscribe-btn newsletter-submit-btn">{{$data['button_text']}}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shape 01-->
                    <div class="shapeSubscribe shapeSubscribe1 wow fadeInLeft" data-wow-delay="0.4s">
                        <img src="{{global_asset('assets/tenant/frontend/themes/img/gallery/eCommerce-subsCribeShape1.png')}} " alt="image" class="routedOne">
                    </div>
                    <!-- Shape 03-->
                    <div class="shapeSubscribe shapeSubscribe3">
                        <img src="{{global_asset('assets/tenant/frontend/themes/img/gallery/eCommerce-subsCribeShape3.png')}}" alt="image" class="heartbeat">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
