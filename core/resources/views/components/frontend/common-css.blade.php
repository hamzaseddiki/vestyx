
@php
    $default_theme = get_static_option('tenant_default_theme');
    $themes = ['event','donation','job-find','support-ticketing','eCommerce','article-listing','agency',
        'newspaper','construction','consultancy','wedding','photography','portfolio','software-business','barber-shop', 'hotel-booking'];
@endphp

@foreach($themes as $theme)
    @if($theme != $default_theme)
        <link rel="stylesheet" href="{{global_asset('assets/tenant/frontend/themes/css/'.$theme.'-main-style.css')}}">
   @endif
@endforeach


<style>

    /*Video area css*/
    .aboutArea .aboutImg {
        margin-bottom: 40px;
    }

    .logistic-video-wrap {
        position: relative;
        border: 10px solid #fff;
        box-shadow: 0 0 5px 0 rgba(0, 0, 0, .08);
        z-index: 1;
    }

    .aboutArea .aboutImg img {
        width: 100%;
    }

    .logistic-video-wrap .video-play-btn {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }

    .video-play-btn {
        position: relative;
        z-index: 1;
        display: inline-block;
        width: 100px;
        height: 100px;
        line-height: 100px;
        text-align: center;
        font-size: 16px;
        background-color: #fff;
        border-radius: 50%;
        color: var(--main-color-one);
    }

    .aboutImg::after {
        background: none !important;
    }

    /*Video area css*/

    .payment-gateway-list .single-gateway-item img {
        width: 100%;
        height: auto !important;
        padding: 0;
        margin: 0;
    }

    .payment-gateway-list .single-gateway-item {
        display: flex;
        align-items: center;
        justify-content: center;
    }

</style>
