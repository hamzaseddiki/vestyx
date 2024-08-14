<div class="abutArea sectionImg-bg2 section-padding2" data-background="{{global_asset('assets/tenant/frontend/themes/img/gallery/article-listing-sectionBg1.png')}}">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row justify-content-between align-items-center">
            <div class="col-xxl-6 col-xl-6 col-lg-6">

                <div class="video-area-Component">
                    <div class="video-wrap position-relative">
                        <div class="videoImg">
                            {!! render_image_markup_by_attachment_id($data['left_image'],'tilt-effect') !!}
                        </div>

                        <div class="video-icon">
                            <a class="popup-video btn-icon" href="{!! $data['video_url'] !!}">
                                <i class="fas fa-play"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-xxl-5 col-xl-6 col-lg-6">
                <div class="aboutCaption">
                    <div class="mb-40">
                        {!! get_modified_title_knowledgebase($data['title']) !!}
                        <p class="pera">{{$data['description']}}</p>
                    </div>

                    <ul class="listing mb-50">
                        @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key => $title)
                            <li class="listItem">
                                <img src="{{global_asset('assets/tenant/frontend/themes/img/icon/article-listing-check-mark.svg')}}" class="icon" alt="">
                                <blockquote class="articlesTag">{{$title}} </blockquote>
                            </li>
                        @endforeach
                    </ul>

                    <div class="btn-wrapper">
                        <a href="{{$data['button_url']}}" class="cmn-btn1 hero-btn">{{$data['button_text']}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
