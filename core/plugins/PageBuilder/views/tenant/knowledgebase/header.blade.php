<section class="sliderArea sectionBg1">
    <div class="container position-relative" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row justify-content-center">
            <div class="col-xxl-7 col-xl-8 col-lg-7 ">
                <div class="heroCaption heroPadding text-center">

                    {!! get_modified_title_knowledgebase($data['title']) !!}

                    <div class="row justify-content-center">
                        <div class="col-xxl-9 col-xl-9 col-lg-10">
                            <p class="pera wow fadeInUp" data-wow-delay="0.1s">{{ $data['description'] }}</p>
                            <div class="searchBox-wrapper mb-50">
                                <!-- Search input Box -->
                                <x-error-msg/>
                                <form action="{{route('tenant.frontend.knowledgebase.search.page')}}" class="search-box wow fadeInUp" data-wow-delay="0.3s">
                                    <div class="input-form">
                                        <input type="text" name="search" class="keyup-input" placeholder="Search">
                                        <!-- button -->
                                        <button type="submit"> {{ $data['button_text'] }} </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- line -->
        <div class="lindeDrow d-none d-lg-block  wow fadeInUp" data-wow-delay="0.3s" >
            <!-- Shape 03-->
            <div class="shapeHero shapeHero3  wow  fadeInUp" data-wow-delay="0.4s">

                {!! render_image_markup_by_attachment_id($data['left_image'],'routedTwo') !!}
            </div>
            <!-- Shape 04-->
            <div class="shapeHero shapeHero4 wow  fadeInUp" data-wow-delay="0.4s">
                {!! render_image_markup_by_attachment_id($data['right_image'],'routedTwo') !!}
            </div>

            <!-- Shape 05-->
            <div class="shapeHero shapeHero5 bounce-animate ">
                <a href="#Down"><img src="{{global_asset('assets/tenant/frontend/themes/img/icon/article-listing-downArrow.svg')}}" alt="image" ></a>
            </div>
        </div>
    </div>
    <!-- Shape 01-->
    <div class="shapeHero shapeHero1">
        <img src="{{global_asset('assets/tenant/frontend/themes/img/hero/article-listing-shape.png')}}" alt="image" >
    </div>
</section>
