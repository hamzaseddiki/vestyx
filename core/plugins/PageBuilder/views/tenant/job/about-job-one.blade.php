<div class="abutArea sectionBg2 section-padding">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row justify-content-between">
            <div class="col-xxl-6 col-xl-6 col-lg-6">
                <div class="video-area-Component">
                    <div class="video-wrap position-relative">
                        <div class="videoImg">
                            {!! render_image_markup_by_attachment_id($data['left_image']) !!}
                        </div>
                        <div class="video-icon">
                            <a class="popup-video btn-icon" href="{{$data['left_video_url']}}">
                                <i class="fas fa-play"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-5 col-xl-6 col-lg-6">
                <div class="aboutCaption">
                    <div class="mb-40">
                        <h3 class="tittle">{{$data['title']}}</h3>
                        <p class="pera">{{$data['description']}}</p>
                    </div>

                    <ul class="infoWrapper">
                        @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key=> $title)
                            <li class="singleInfo">
                                <div class="singleNumber">
                                    <span class="number">{{ $loop->iteration }}</span>
                                </div>
                                <div class="cap">
                                    <h4 class="tittle">{{$title ?? '' }}</h4>
                                    <p class="pera">{{$data['repeater_data']['repeater_subtitle_'.get_user_lang()][$key] ?? '' }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
