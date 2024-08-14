@php
    $user_lang = get_user_lang();
@endphp

<section class="aboutArea section-padding2 sectionBg1">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row justify-content-between align-items-center">
            <div class="col-xl-5 col-lg-5">
                <!-- video start -->
                <div class="video-area">
                    <div class="video-wrap position-relative">
                        <div class="videoImg">
                            {!! render_image_markup_by_attachment_id($data['left_image'],'tilt-effects') !!}
                        </div>
                        <!-- Video icon -->

                        <div class="video-icon">
                            <a class="popup-video btn-icon" href="{{$data['left_video_url']}}">
                                <i class="fas fa-play"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- video end -->
            </div>
            <div class="col-xl-7 col-lg-7 col-md-10">
                <div class="about-caption">
                    <!-- Section Tittle -->
                    <div class="section-tittle mb-15">
                        {!! get_modified_title_tenant($data['title']) !!}
                    </div>
                    <p class="about-cap-top">{!! $data['description'] !!}</p>
                    <div class="row">
                        @foreach($data['repeater_data']['repeater_number_'.$user_lang] ?? [] as $key => $number)
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <!-- Counter Up -->
                                <div class="single mb-30">
                                    <div class="single-counter">
                                        <span class="counter odometer" data-odometer-final="{{$number ?? 0}}"></span>
                                    </div>
                                    <div class="pera-count">
                                        <p class="pera">{{ $data['repeater_data']['repeater_title_'.$user_lang][$key] ?? '' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                    <div class="btn-wrapper mt-10">
                        <a href="{{$data['button_url']}}" class="cmn-btn2 hero-btn">{{$data['button_text']}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

