<section class="consulting_faq_area padding-top-50 padding-bottom-50" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="body_border">
        <span class="one"></span>
        <span class="two"></span>
        <span class="three"></span>
        <span class="four"></span>
        <span class="five"></span>
        <span class="six"></span>
        <span class="seven"></span>
    </div>
    <div class="container">
        <div class="consulting_sectionTitle">
            <span class="subtitle">{{$data['title']}}</span>
            <h2 class="title">
                <span class="title__top">{{$data['subtitle']}}<span class="title__top__shape">
                        <img src="{{global_asset('assets/tenant/frontend/themes/img/consultancy/banner/consulting_banner_title_shape.svg')}}" alt="">
                    </span>
                </span>
            </h2>
        </div>
        <div class="row g-4 mt-4">
            <div class="col-lg-6">
                <div class="question-faq-wrapper">
                    <div class="consultingFaq__contents">

                        @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key=> $title)
                            @break($loop->index == 4 )
                            @php
                                $open_condition = $data['repeater_data']['open_status_'.get_user_lang()][$key] ?? '';
                            @endphp
                            <div class="consultingFaq__item wow fadeInLeft {{ $open_condition == 'yes' ? 'active open' : '' }}" data-wow-delay=".2s">
                                <div class="consultingFaq__title"> {{$title ?? ''}} </div>
                                <div class="consultingFaq__panel" style="{{ $open_condition == 'yes' ? "style=display:block" : '' }}">
                                    <p class="consultingFaq__para">{{ $data['repeater_data']['repeater_description_'.get_user_lang()][$key] ?? '' }}</p>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="question-faq-wrapper">
                    <div class="consultingFaq__contents">
                        @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key=> $title)
                          @if($loop->index > 3)
                                @php
                                    $open_condition = $data['repeater_data']['open_status_'.get_user_lang()][$key] ?? '';
                                @endphp
                            <div class="consultingFaq__item wow fadeInLeft {{ $open_condition == 'yes' ? 'active open' : '' }}" data-wow-delay=".2s">
                                <div class="consultingFaq__title"> {{$title ?? ''}} </div>
                                <div class="consultingFaq__panel" style="{{ $open_condition == 'yes' ? "style=display:block" : '' }}">
                                    <p class="consultingFaq__para">{{ $data['repeater_data']['repeater_description_'.get_user_lang()][$key] ?? '' }}</p>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
