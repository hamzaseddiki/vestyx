
<section class="portfolio_work_area portfolio_section_bg padding-top-100 padding-bottom-100"
        data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="portfolio_sectionTitle white">
        {!! get_modified_title_portfolio($data['title']) !!}
    </div>

    <div class="container">
        <div class="row g-4 mt-4">
            @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key=> $ti)
                @php
                    $title = $ti ?? '';
                    $url = $data['repeater_data']['repeater_url_'.get_user_lang()][$key] ?? '';
                    $button_text = $data['repeater_data']['repeater_button_text_'.get_user_lang()][$key] ?? '';
                    $image = $data['repeater_data']['repeater_image_'.get_user_lang()][$key] ?? '';
                @endphp
                <div class="col-md-6">
                    <div class="portfolio_work radius-10">
                        <div class="portfolio_work__thumb">
                            <a href="{{$url}}">
                                {!! render_image_markup_by_attachment_id($image) !!}
                            </a>
                        </div>
                        <div class="portfolio_work__contents">
                            <h4 class="portfolio_work__title"><a href="{{$url}}">{{$title}}</a></h4>
                            <div class="btn-wrapper">
                                <a href="{{$url}}" class="portfolio_work__learnMore">{{$button_text}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
