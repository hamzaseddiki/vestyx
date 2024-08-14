
<section class="sliderArea sectionImg-bg2" {!! render_background_image_markup_by_attachment_id($data['bg_image']) !!}>
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row justify-content-between align-items-center">
            <div class="col-xxl-6 col-xl-6 col-lg-6 ">
                <div class="heroCaption heroPadding">
                        {!! get_modified_title_ticket($data['title']) !!}
                    <div class="row">
                        <div class="col-xl-10">
                            <p class="pera wow fadeInUp" data-wow-delay="0.1s">{{$data['description']}}</p>
                            <div class="searchBox-wrapper mb-50">
                                <form action="#" class="search-box wow fadeInUp" data-wow-delay="0.3s">
                                    <div class="input-form">
                                        <input type="text" name="search" class=" keyup-input" placeholder="Search">
                                        <!-- button -->
                                        <button type="submit"> {{$data['button_text']}} </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <ul class="notice-listing">

                        @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key=> $title)
                            <li class="listItem wow fadeInLeft" data-wow-delay="0.4s">
                                <div class="icon"><i class="lar la-check-circle"></i></div>
                               {{ $title ?? '' }}
                            </li>
                        @endforeach

                        <!-- Scrolling -->
                        <li class="listItem wow fadeInLeft" data-wow-delay="0.6s">
                            <a href="#NexSection">
                                <div class="icon"><i class="las la-arrow-circle-down"></i></div>
                                {{__('Scroll Down')}}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-xxl-6 col-xl-6 col-lg-6">
                <div class="hero-man tilt-effect d-none d-lg-block f-right wow fadeInRight" data-wow-delay="0.0s" >
                    {!! render_image_markup_by_attachment_id($data['right_image']) !!}
                </div>
            </div>
        </div>
    </div>
</section>
