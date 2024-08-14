<section class="barberShop_handsome_area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row gx-5 g-4">
            <div class="col-lg-7">
                <div class="barberShop_handsome">
                    <div class="barberShop_handsome__left">
                        <div class="barberShop_handsome__thumb">
                            <div class="barberShop_handsome__thumb__top">
                                <div class="barberShop_handsome__thumb__author">
                                    <div class="barberShop_handsome__thumb__author__flex">
                                        <div class="barberShop_handsome__thumb__author__thumb">

                                            {!! render_image_markup_by_attachment_id($data['author_image']) !!}
                                        </div>
                                        <div class="barberShop_handsome__thumb__author__contents">
                                            <h5 class="barberShop_handsome__thumb__author__title">{{$data['author_name']}}</h5>
                                            <div class="barberShop_handsome__thumb__author__review">
                                                <span><i class="fa-regular fa-star"></i></span>
                                                <span><i class="fa-regular fa-star"></i></span>
                                                <span><i class="fa-regular fa-star"></i></span>
                                                <span><i class="fa-regular fa-star"></i></span>
                                                <span><i class="fa-regular fa-star"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="barberShop_handsome__thumb__top__thumb">

                                    {!! render_image_markup_by_attachment_id($data['left_image_one']) !!}

                                </div>
                            </div>
                            <div class="barberShop_handsome__thumb__bottom">
                                <div class="barberShop_handsome__thumb__bottom__flex">
                                    <div class="barberShop_handsome__thumb__bottom__textShape">

                                        {!! render_image_markup_by_attachment_id($data['shape_image']) !!}
                                    </div>
                                    <div class="barberShop_handsome__thumb__bottom__thumb">

                                        {!! render_image_markup_by_attachment_id($data['left_image_two']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="barberShop_handsome__wrapper">
                    <div class="barberShop_sectionTitle text-left">
                        {!! get_modified_title_barber_two($data['title']) !!}
                    </div>
                    <div class="barberShop_handsome__contents mt-4">
                        <p class="barberShop_handsome__contents__para">{{$data['description']}}</p>
                        <div class="btn-wrapper mt-4">
                            <a href="{{$data['button_url']}}" class="barberShop_cmn_btn btn_bg_1">{{$data['button_text']}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
