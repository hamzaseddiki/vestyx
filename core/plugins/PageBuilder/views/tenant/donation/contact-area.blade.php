<section class="simpleContact positioningSection wow fadeInUp" data-wow-delay="0.0s">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row">
            <div class="col-xxl-12">
                <div class="ContactCap text-center">
                    <div class="row justify-content-center">
                        <div class="col-xl-6 col-lg-7 col-md-9 col-sm-11">
                            <h3 class="tittle wow fadeInUp" data-wow-delay="0.0s">{{$data['title']}}</h3>
                            <p class="pera wow fadeInUp" data-wow-delay="0.1s" >{{$data['description']}}</p>
                            <div class="btn-wrapper d-flex align-items-center justify-content-center flex-wrap">
                                <a href="{{$data['left_button_url']}}" class="white-btnBorder mr-20 mb-10 wow fadeInLeft" data-wow-delay="0.4s">{{$data['left_button_text']}}</a>
                                <a href="{{$data['right_button_url']}}" class="white-btnFill mb-10 mr-20 wow fadeInRight" data-wow-delay="0.4s"> {{$data['right_button_text']}}<i class="fas fa-heart icon ZoomTwo"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Shape 01-->
                    <div class="shapeContact shapeContact1" data-animation="fadeInLeft" data-delay="0.8s">
                        <img src="{{global_asset('assets/tenant/frontend/themes/img/gallery/donation-contactShape.png')}}" alt="" class="running ">
                    </div>
                    <!-- Shape 02-->
                    <div class="shapeContact shapeContact2 " data-animation="fadeInDown" data-delay="0.7s">
                        <img src="{{global_asset('assets/tenant/frontend/themes/img/gallery/donation-contactShape2.png')}}" alt="" class=" routedOne ">
                    </div>
                    <!-- Shape 03-->
                    <div class="shapeContact shapeContact3 " data-animation="fadeInDown" data-delay="0.7s">
                        <img src="{{global_asset('assets/tenant/frontend/themes/img/gallery/donation-contactShape3.png')}}" alt="" class="heartbeat2 ">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
