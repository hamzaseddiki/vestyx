
<section class="subscribeArea wow fadeInUp section-padding2" data-wow-delay="0.0s">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row">
            <div class="col-xxl-12 ">
                <div class="subscribeWrapper sectionBg2">
                    <div class="subscribeCaption  text-center">
                        <h3 class="tittle wow fadeInUp" data-wow-delay="0.0s">{{$data['title']}}</span></h3>
                        <div class="subscribePera">
                            <p class="pera wow fadeInUp" data-wow-delay="0.1s" >{{$data['description']}} </p>
                            <!-- Subscribe Form -->
                            <form action="#" class="wow fadeInUp" data-wow-delay="0.2s">
                                <input type="email" name="email" class="email" placeholder="{{__($data['button_placeholder_text'])}}">
                                <div class="form-message-show mt-2"></div>
                                <button class="subscribe-btn  newsletter-submit-btn" type="submit">{{$data['button_text']}}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@section('scripts')
    <x-custom-js.landlord-newsletter-store/>
@endsection
