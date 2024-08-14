@php
    $current_lang = \App\Facades\GlobalLanguage::user_lang_slug();
@endphp
<section class="subscribeArea bottom-padding wow fadeInUp" data-wow-delay="0.0s" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row">
            <div class="col-xxl-12">
                <div class="subscribeCaption text-center">
                    <div class="row justify-content-center">
                        <div class="col-xl-6 col-lg-7 col-md-9 col-sm-11">
                            <h3 class="tittle wow fadeInUp" data-wow-delay="0.0s"> {{$data['title']}}</h3>
                            <p class="pera wow fadeInUp" data-wow-delay="0.1s" >{{$data['subtitle']}}</p>
                            <!-- Subscribe Form -->
                            <form action="#" class="wow fadeInUp" data-wow-delay="0.2s" id="landlord-newsletter-form" >
                                <input type="email" name="email" placeholder="{{$data['input_text'] ?? __('Your Email Here')}}">
                                <button class="subscribe-btn">{{$data['button_text'] ?? __('Sign Up')}}</button>
                            </form>
                            <div class="form-message-show mt-4"></div>
                        </div>
                    </div>

                    <!-- Shape 01-->
                    <div class="shapeSubscribe shapeSubscribe1" data-animation="fadeInLeft" data-delay="0.8s">
                        <img src="{{asset('assets/landlord/frontend/img/icon/subsCribeShape1.svg')}}" alt="image" class="bouncingAnimation">
                    </div>
                    <!-- Shape 02-->
                    <div class="shapeSubscribe shapeSubscribe2 " data-animation="fadeInDown" data-delay="0.7s">
                        <img src="{{asset('assets/landlord/frontend/img/icon/subsCribeShape2.svg')}}" alt="image" class=" rotateme">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
