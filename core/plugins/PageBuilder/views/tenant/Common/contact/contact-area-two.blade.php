@php
    $userlang = get_user_lang();
    dd(33);
@endphp

    <div id="what" class="contact-area bg-image" {!! render_background_image_markup_by_attachment_id($data['bg_image']) !!} data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="container">
            <div class="row">

                @foreach($data['repeater_data']['repeater_title_'] ?? [] as $key => $title)
                <div class="col-lg-4 col-md-6">
                    <div class="single-icon-box-05">
                        <div class="icon">
                            <i class="{{$data['repeater_data']['repeater_icon_'][$key] ?? ''}}"></i>
                        </div>
                        <div class="content">
                            <h3 class="title">{{$title ?? ''}}</h3>
                            <p>{{$data['repeater_data']['repeater_info_one_'][$key] ?? ''}} <br> {{$data['repeater_data']['repeater_info_two_'][$key] ?? ''}}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
