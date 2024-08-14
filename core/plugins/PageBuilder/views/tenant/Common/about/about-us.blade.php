@php
    $user_lang = get_user_lang();
@endphp

<div class="abutArea-global1 section-padding2">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row justify-content-between align-items-center">

            @if($data['image_alignment'] == 'left')
                <div class="col-xxl-6 col-xl-6 col-lg-6">
                    <div class="aboutImg-global imgEffect">
                        {!! render_image_markup_by_attachment_id($data['right_image']) !!}
                    </div>
                </div>
            @endif

            <div class="col-xxl-5 col-xl-6 col-lg-6">
                <div class="aboutCaption-global mb-30">
                    <div class="mb-40">
                        <h3 class="tittle">{{$data['title']}}</h3>
                        <p class="pera"> {{$data['description']}}</p>
                    </div>
                    <ul class="listingStyle listing2 mb-50">

                        @foreach($data['repeater_data']['repeater_title_'.$user_lang] ?? [] as $key => $title)
                            <li class="listItem ">
                                <a href="{{$data['repeater_data']['repeater_url_'.$user_lang][$key] ?? ''}}"> {{$title ?? ''}}</a>
                            </li>
                        @endforeach
                    </ul>

                </div>
            </div>

            @if($data['image_alignment'] == 'right')
                <div class="col-xxl-6 col-xl-6 col-lg-6">
                    <div class="aboutImg-global imgEffect">
                        {!! render_image_markup_by_attachment_id($data['right_image']) !!}
                    </div>
                </div>
             @endif
        </div>
    </div>
</div>
