<div class="offerCat">
    <div class="container" data-padding-bottom="{{$data['padding_bottom']}}" data-padding-top="{{$data['padding_top']}}">
        <div class="row">
            @php
                $colors = ['bgColorOne','bgColorTwo'];
            @endphp
            <div class="col-xxl-6 col-xl-5 col-lg-5">

                @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key=> $title)

                    @break($loop->index == 2)
                    <div class="singleCart mb-24 {{ $colors[ $key % count($colors)] }}">
                        <div class="itemsCaption">
                            <p class="itemsCap">{{$title ?? '' }}</p>
                            <h5>
                                <a href="{{$data['repeater_data']['repeater_button_url_'.get_user_lang()][$key] ?? '' }}" class="itemsTittle">{!! get_string_line_breaker($data['repeater_data']['repeater_subtitle_'.get_user_lang()][$key],$data['repeater_data']['repeater_subtitle_line_breaking_number_'.get_user_lang()][$key] ?? '' ) !!}</a>
                            </h5>

                            @if(!empty($data['repeater_data']['repeater_extra_note_'.get_user_lang()][$key]))
                            <span class="offerStyle">{{ $data['repeater_data']['repeater_extra_note_'.get_user_lang()][$key] ?? '' }}</span>
                            @endif

                            <div class="btn-wrapper">
                                <a href="{{$data['repeater_data']['repeater_button_url_'.get_user_lang()][$key] ?? '' }}" class="cmn-btn-outline3" tabindex="0">{{$data['repeater_data']['repeater_button_text_'.get_user_lang()][$key] ?? '' }}</a>
                            </div>
                        </div>
                        <div class="itemsImg wow fadeInUp" data-wow-delay="0.1s">
                            {!! render_image_markup_by_attachment_id($data['repeater_data']['repeater_right_image_'.get_user_lang()][$key] ?? '') !!}
                        </div>
                    </div>
                @endforeach


            </div>
            <div class="col-xxl-6 col-xl-7 col-lg-7">
                <!-- Single Cart -->
                @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key=> $title)
                 @if($loop->index == 2)

                    <div class="singleCart singleCartBig mb-24 bgColorThree tilt-effect">
                        <div class="itemsCaption">
                            <p class="itemsCap">{{$title ?? '' }}</p>
                            <h5>
                                <a href="{{$data['repeater_data']['repeater_button_url_'.get_user_lang()][$key] ?? '' }}" class="itemsTittle">
                                    {!!
                                        get_string_line_breaker($data['repeater_data']['repeater_subtitle_'.get_user_lang()][$key],
                                        $data['repeater_data']['repeater_subtitle_line_breaking_number_'.get_user_lang()][$key]  ?? '' )
                                    !!}
                                </a>
                            </h5>

                            @if(!empty($data['repeater_data']['repeater_extra_note_'.get_user_lang()][$key]))
                                <span class="offerStyle">{{ $data['repeater_data']['repeater_extra_note_'.get_user_lang()][$key] ?? '' }}</span>
                            @endif
                            <div class="btn-wrapper">
                                <a href="{{$data['repeater_data']['repeater_button_url_'.get_user_lang()][$key] ?? '' }}" class="cmn-btn-outline3" tabindex="0">{{$data['repeater_data']['repeater_button_text_'.get_user_lang()][$key] ?? '' }}</a>
                            </div>
                        </div>
                        <div class="itemsImg wow fadeInLeft" data-wow-delay="0.1s">
                            {!! render_image_markup_by_attachment_id($data['repeater_data']['repeater_right_image_'.get_user_lang()][$key] ?? '') !!}
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
