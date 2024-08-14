
<section class="subscribeArea bottom-padding wow ladeInUp" data-wow-delay="0.0s">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row">
            <div class="col-xxl-12">
                <div class="subscribeCaption sectionBg1 text-center">
                    <div class="row justify-content-center">
                        <div class="col-xl-9 col-lg-8 col-md-9 col-sm-11">
                            {!! get_modified_title_tenant_event($data['title']) !!}
                            <p class="pera wow ladeInUp" data-wow-delay="0.1s">{{ $data['description'] }}</p>
                            <div class="row justify-content-center">
                                <div class="col-lg-6">
                                    <!-- Subscribe Form -->
                                    <form action="#" class="wow ladeInUp" data-wow-delay="0.2s">
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <input type="email" name="email" class="email" placeholder="{{__($data['button_placeholder_text'])}}">
                                        <div class="form-message-show mt-2"></div>
                                        <button class="subscribe-btn newsletter-submit-btn" type="submit">{{$data['button_text']}}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

