<section class="categoriesArea section-padding" id="NexSection">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-7 col-md-10 col-sm-10">
                <div class="section-tittle text-center mb-50 wow fadeInUp" data-wow-delay="0.0s">
                    {!! get_modified_title_ticket($data['title']) !!}
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key=> $title)
                <div class="col-lg-4 col-md-6">
                    <div class="singleCategories mb-24 ">
                        <div class="icon-wrap">
                            <div class="icon catBg1">
                                {!! render_image_markup_by_attachment_id($data['repeater_data']['repeater_image_'.get_user_lang()][$key] ?? null) !!}
                            </div>
                        </div>
                        <div class="cap">
                            <h4><a href="{{ $data['repeater_data']['repeater_title_url_'.get_user_lang()][$key] ?? '' }}" class="title">{{$title ?? ''}}</a></h4>
                            <p class="pera">{{ $data['repeater_data']['repeater_description_'.get_user_lang()][$key] ?? '' }}</p>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</section>
