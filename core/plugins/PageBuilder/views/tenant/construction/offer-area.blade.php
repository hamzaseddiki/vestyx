
<section class="construction_offer_area construction_bg_black padding-top-100 padding-bottom-100">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="construction_sectionTitle white">
            <h2 class="title">{{$data['title']}}</h2>
            <p class="section_para">{{$data['description']}}</p>
        </div>
        <div class="row g-5 mt-1 justify-content-center">
            <div class="col-lg-6 col-md-9">
                <div class="construction_offer__faq">
                    <div class="constructionFaq__contents white">

                    @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key=> $title)
                        <div class="constructionFaq__item wow fadeInLeft {{ $loop->index == 2 ? 'active' : '' }}" data-wow-delay=".2s">
                            <div class="constructionFaq__title">{{$title ?? ''}}</div>
                            <div class="constructionFaq__panel">
                                <p class="constructionFaq__para">{{ $data['repeater_data']['repeater_description_'.get_user_lang()][$key] ?? '' }}</p>
                            </div>
                        </div>
                    @endforeach

                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-9">
                <div class="construction_offer__wrapper">
                    <div class="construction_offer__thumb">
                        {!! render_image_markup_by_attachment_id($data['right_image']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

