@
<section class="wedding_make_area position-relative padding-top-100 padding-bottom-100" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="gradient_bg">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    <div class="wedding_make__shapes">
        <img src="{{global_asset('assets/tenant/frontend/themes/img/wedding/make/wedding_flower2.png')}}" alt="makeFlower">
        <img src="{{global_asset('assets/tenant/frontend/themes/img/wedding/make/wedding_heart.png')}}" alt="makeHeart">
    </div>
    <div class="container">
        <div class="row gy-5 justify-content-between align-items-center">
            <div class="col-lg-6">
                <div class="wedding_make">
                    <div class="wedding_sectionTitle text-left">
                        <h2 class="title">{{$data['title']}}</h2>
                        <p class="section_para mt-4">{{$data['description']}}</p>
                    </div>
                    <div class="wedding_make__quality mt-4 mt-lg-5">
                        @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key => $title)
                            <div class="wedding_make__quality__item">
                                <div class="wedding_make__quality__flex">
                                    <div class="wedding_make__quality__icon">
                                        {!! render_image_markup_by_attachment_id($data['repeater_data']['repeater_image_'.get_user_lang()][$key] ?? '') !!}
                                    </div>
                                    <div class="wedding_make__quality__content">
                                        <h4 class="wedding_make__quality__title"><a href="#!">{{$title ?? ''}}</a></h4>
                                        <p class="wedding_make__quality__para mt-2">{{$data['repeater_data']['repeater_description_'.get_user_lang()][$key] ?? ''}}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-9">
                <div class="wedding_make__wrapper">
                    <div class="wedding_make__counter radius-10">
                        <div class="wedding_make__counter__flex">
                            @foreach($data['repeater_data_two']['repeater_title_'.get_user_lang()] ?? [] as $key => $title)
                              <div class="wedding_make__counter__item">
                                <div class="wedding__counter desktop-center">
                                    <div class="wedding__counter__contents">
                                        <div class="wedding__counter__count">
                                            <span class="odometer" data-odometer-final="{{$data['repeater_data_two']['repeater_number_'.get_user_lang()][$key] ?? '' }}"></span>
                                            <span class="wedding__counter__count__title"> {{$data['repeater_data_two']['symbol_'.get_user_lang()][$key] ?? '' }} </span>
                                        </div>
                                        <p class="wedding__counter__para">{{$title ?? ''}}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="wedding_make__thumb">
                        <div class="wedding_make__thumb__main">
                            {!! render_image_markup_by_attachment_id($data['right_image']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
