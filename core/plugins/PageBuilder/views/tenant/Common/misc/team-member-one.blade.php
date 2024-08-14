<section class="teamArea-global section-padding">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row justify-content-center">
            <div class="col-xxl-7 col-xl-7 col-lg-8 col-md-9 col-sm-10">
                <div class="section-tittle text-center mb-50">
                    <h2 class="tittle">{{$data['title']}}</h2>
                </div>
            </div>
        </div>
        @php
            $rtl_con = get_user_lang_direction() == 1 ? 'true' : 'false';
        @endphp
        <div class="row">
            <div class="col-lg-12">
                <div class="global-slick-init slider-inner-margin DotStyleTwo" data-rtl="{{$rtl_con}}" data-arrows="false" data-dots="true" data-slidesToShow="4" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="las fa-angle-left"></i></div>'
                     data-nextArrow='<div class="next-icon"><i class="las fa-angle-right"></i></div>' data-responsive='[{"breakpoint": 1800,"settings": {"slidesToShow": 3}},{"breakpoint": 1600,"settings": {"slidesToShow": 3}},{"breakpoint": 1400,"settings": {"slidesToShow": 3}},{"breakpoint": 1200,"settings": {"slidesToShow": 3}},{"breakpoint": 991,"settings": {"slidesToShow": 2}},{"breakpoint": 768, "settings": {"slidesToShow": 2}},{"breakpoint": 576, "settings": {"slidesToShow": 1}}]'>

                      @foreach($data['repeater_data']['repeater_name_'.get_user_lang()]  ?? [] as $key => $name)
                        <div class="singleTeam-global tilt-effect mb-15 mt-15">
                            <div class="team-img">
                                {!! render_image_markup_by_attachment_id($data['repeater_data']['repeater_image_'.get_user_lang()][$key] ?? '' ) !!}
                                <!-- Blog Social -->
                                <ul class="teamSocial">
                                    <li class="single"><a href="{{$data['repeater_data']['repeater_facebook_url_'.get_user_lang()][$key] ?? '' }}" class="social"><i class="fab fa-facebook-f"></i></a></li>
                                    <li class="single"><a href="{{$data['repeater_data']['repeater_twitter_url_'.get_user_lang()][$key] ?? '' }}" class="social"><i class="fab fa-twitter"></i></a></li>
                                    <li class="single"><a href="{{$data['repeater_data']['repeater_instagram_url_'.get_user_lang()][$key] ?? '' }}" class="social"><i class="fa-brands fa-instagram"></i></a></li>
                                </ul>
                            </div>
                            <div class="teamCaption">
                                <h3><a href="#" class="title">{{$name ?? ''}}</a></h3>
                                <p class="pera">{{$data['repeater_data']['repeater_designation_'.get_user_lang()][$key] ?? '' }}</p>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</section>
