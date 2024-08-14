<!DOCTYPE html>

@php
     use App\Facades\GlobalLanguage;
@endphp

<html lang="{{GlobalLanguage::default_slug()}}" dir="{{GlobalLanguage::default_dir()}}">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>

        {{get_static_option('site_'. GlobalLanguage::user_lang_slug().'_title')}}

            - {{get_static_option('site_'. GlobalLanguage::user_lang_slug().'_tag_line')}}

    </title>

    <link rel="stylesheet" href="{{asset('assets/frontend/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/fontawesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/dynamic-style.css')}}">
    <style>
        :root {
            --main-color-one: {{get_static_option('site_color')}};
            --secondary-color: {{get_static_option('site_main_color_two')}};
            --heading-color: {{get_static_option('site_heading_color')}};
            --paragraph-color: {{get_static_option('site_paragraph_color')}};
            @php $heading_font_family = !empty(get_static_option('heading_font')) ? get_static_option('heading_font_family') :  get_static_option('body_font_family') @endphp
             --heading-font: "{{$heading_font_family}}", sans-serif;
            --body-font: "{{get_static_option('body_font_family')}}", sans-serif;
        }
    </style>
    <style>
        .maintenance-page-content-area {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            z-index: 0;
            background-size: cover;
            background-position: center;
            overflow-y: auto;

        }

        .maintenance-page-content-area:after {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: -1;
            content: '';
        }

        .page-content-wrap {
            text-align: center;
        }

        .page-content-wrap .logo-wrap {
            margin-bottom: 30px;
        }

        .page-content-wrap .maintain-title {
            font-size: 45px;
            font-weight: 700;
            color: #fff;
            line-height: 50px;
            margin-bottom: 20px;
        }

        .page-content-wrap p {
            font-size: 16px;
            line-height: 28px;
            color: rgba(255, 255, 255, .7);
            font-weight: 400;
        }

        .page-content-wrap .subscriber-form {
            position: relative;
            z-index: 0;
            max-width: 500px;
            margin: 0 auto;
            margin-top: 40px;
        }

        .page-content-wrap .subscriber-form .submit-btn {
            position: absolute;
            right: 0;
            bottom: 0;
            width: 60px;
            height: 50px;
            text-align: center;
            border: none;
            background-color: var(--main-color-one);
            color: #fff;
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
        }

        .page-content-wrap .subscriber-form .form-group .form-control {
            height: 50px;
            padding: 0 20px;
            padding-right: 80px;
        }
        .counterdown-wrap.event-page #event_countdown .wrapper{
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 120px;
        }
        .counterdown-wrap.event-page #event_countdown {
            display: flex;
            margin-bottom: 30px
        }

        .counterdown-wrap.event-page #event_countdown > div {
            width: calc(100% / 4);
            margin: 5px;
            text-align: center;
            padding: 10px 10px;
            border: 2px dashed rgba(255,255,255,.5);
        }

        .counterdown-wrap.event-page #event_countdown > div .label {
            display: block;
            text-transform: capitalize;
            font-size: 14px;
            color: rgba(255, 255, 255, .8);
            font-weight: 500;
            line-height: 20px
        }

        .counterdown-wrap.event-page #event_countdown > div .time {
            font-size: 30px;
            font-weight: 700;
            color: #fff
        }
        @media screen and (max-width: 991px) {
            .maintenance-page-content-area {
                width: 100%;
                height: auto;
                display: block;
            }
            .page-content-wrap .maintain-title {
                font-size: 36px;
                line-height: 42px;
            }
            .page-content-wrap {
                text-align: center;
                padding: 40px 10px;
            }
            .counterdown-wrap.event-page #event_countdown {
                flex-wrap: wrap;
            }
        }
    </style>
    @yield('style')
    @if( GlobalLanguage::default_dir() === 'rtl')
        <link rel="stylesheet" href="{{asset('assets/frontend/css/rtl.css')}}">
    @endif
</head>
<body>
@php

    $user_select_lang_slug = GlobalLanguage::default_slug();
@endphp
<div class="maintenance-page-content-area"
    {!! render_background_image_markup_by_attachment_id(get_static_option('maintenance_bg_image')) !!}
>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="maintenance-page-inner-content">
                    <div class="page-content-wrap">
                        <div class="logo-wrap">
                            {!! render_image_markup_by_attachment_id(get_static_option('maintenance_logo')) !!}
                        </div>
                        <h2 class="maintain-title">{{get_static_option('maintains_page_'.$user_select_lang_slug.'_title')}}</h2>

                        <p>{{get_static_option('maintains_page_'.$user_select_lang_slug.'_description')}}</p>

                        <div class="counterdown-wrap event-page">
                            <div id="event_countdown"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{global_asset('assets/tenant/frontend/themes/js/jquery-3.6.1.min.js')}}"></script>
<script src="{{global_asset('assets/common/js/countdown.jquery.js')}}"></script>

<script>
    var ev_offerTime = "{{get_static_option('mentenance_back_date')}}";

    var ev_year = ev_offerTime.substr(0, 4);
    var ev_month = ev_offerTime.substr(5, 2);
    var ev_day = ev_offerTime.substr(8, 2);

    if (ev_offerTime) {
        $('#event_countdown').countdown({
            year: ev_year,
            month: ev_month,
            day: ev_day,
            labels: true,
            labelText: {
                'days': "{{__('days')}}",
                'hours': "{{__('hours')}}",
                'minutes': "{{__('min')}}",
                'seconds': "{{__('sec')}}",
            }
        });
    }
</script>

</body>

</html>
