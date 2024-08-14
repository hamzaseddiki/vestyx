<section class="portfolio_specialities_area padding-top-100 padding-bottom-50" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="portfolio_sectionTitle">
         {!! get_modified_title_portfolio($data['title']) !!}
        </div>

        <div class="row g-4 mt-4">
           @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key=> $ti)
               @php
                   $title = $ti ?? '';
                   $title_url = $data['repeater_data']['repeater_title_url_'.get_user_lang()][$key] ?? '';
                   $description = $data['repeater_data']['repeater_description_'.get_user_lang()][$key] ?? '';
                   $image = $data['repeater_data']['repeater_image_'.get_user_lang()][$key] ?? '';
               @endphp
                <div class="col-lg-4 col-md-6">
                    <div class="portfolio_specialities__single radius-10">
                        <div class="portfolio_specialities__single__icon">
                           {!! render_image_markup_by_attachment_id($image) !!}
                        </div>
                        <div class="portfolio_specialities__single__contents mt-4">
                            <h4 class="portfolio_specialities__single__title">{{$title}}</h4>
                            <p class="portfolio_specialities__single__para mt-3">{{$description}}</p>
                        </div>
                    </div>
                </div>
           @endforeach
        </div>
    </div>
</section>
