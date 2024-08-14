@extends('tenant.frontend.frontend-page-master')
@php
    $post_img = null;
    $user_lang = get_user_lang();
@endphp

@section('title')
    {{ $portfolio->getTranslation('title',$user_lang) }}
@endsection

@section('page-title')
    {{ $portfolio->getTranslation('title',$user_lang) }}
@endsection


@section('content')

    <!-- Services Area -->
    <section class="servicesArea section-padding">
        <div class="container">
            <div class="row">

                <div class="col-xxl-8 col-xl-8 col-lg-7 col-md-6">
                    @php
                        $images = explode('|',$portfolio->image_gallery);
                    @endphp
                    <div class="product-view-wrap mb-50" id="myTabContent">
                       @if(!empty($portfolio->image_gallery))
                        <div class="shop-details-gallery-slider global-slick-init slider-inner-margin sliderArrow mb-30"
                             data-asNavFor=".shop-details-gallery-nav" data-infinite="true" data-arrows="false" data-dots="false" data-slidesToShow="1" data-swipeToSlide="true" data-fade="true"  data-autoplay="true" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="las la-angle-left"></i></div>'
                             data-nextArrow='<div class="next-icon"><i class="las la-angle-right"></i></div>' data-responsive='[{"breakpoint": 1800,"settings": {"slidesToShow": 1}},{"breakpoint": 1600,"settings": {"slidesToShow": 1}},{"breakpoint": 1400,"settings": {"slidesToShow": 1}},{"breakpoint": 1200,"settings": {"slidesToShow": 1}},{"breakpoint": 991,"settings": {"slidesToShow": 1}},{"breakpoint": 768, "settings": {"slidesToShow": 1}},{"breakpoint": 576, "settings": {"slidesToShow": 1}}]'>

                            @foreach($images as $img)
                                <div class="single-main-image">
                                    <a href="#" class="long-img">
                                        {!! render_image_markup_by_attachment_id($img,'w-100') !!}
                                    </a>
                                </div>
                            @endforeach

                            @else
                                <div class="single-main-image">
                                    <a href="#" class="long-img">
                                        {!! render_image_markup_by_attachment_id($portfolio->image,'w-100') !!}
                                    </a>
                                </div>
                             @endif
                        </div>

                        <div class="thumb-wrap">
                            <div class="shop-details-gallery-nav global-slick-init slider-inner-margin sliderArrow"
                                 data-asNavFor=".shop-details-gallery-slider" data-focusOnSelect="true"  data-infinite="true" data-arrows="false" data-dots="false" data-slidesToShow="4" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="las la-angle-left"></i></div>'
                                 data-nextArrow='<div class="next-icon"><i class="las la-angle-right"></i></div>' data-responsive='[{"breakpoint": 1800,"settings": {"slidesToShow": 4}},{"breakpoint": 1600,"settings": {"slidesToShow": 4}},{"breakpoint": 1400,"settings": {"slidesToShow": 4}},{"breakpoint": 1200,"settings": {"slidesToShow": 4}},{"breakpoint": 991,"settings": {"slidesToShow": 4}},{"breakpoint": 768, "settings": {"slidesToShow": 4}},{"breakpoint": 576, "settings": {"slidesToShow": 4}}]'>

                                @if(!empty($portfolio->image_gallery))
                                    @foreach($images as $img)
                                    <div class="single-thumb">
                                        <a class="thumb-link" data-toggle="tab" href="#image-0{{$loop->iteration}}">
                                            {!! render_image_markup_by_attachment_id($img,'w-100') !!}
                                        </a>
                                    </div>
                                    @endforeach
                                 @endif

                            </div>
                        </div>
                    </div>
                    <!-- End-of Slider -->

                    <article class="portfolioDiscription mb-30">
                        {!! $portfolio->getTranslation('description',$user_lang) !!}
                    </article>
                </div>
                <div class="col-xxl-4 col-xl-4 col-lg-5 col-md-6">



                    <div class="simplePresentCart mb-24">
                        <div class="small-tittle mb-40">
                            <h3 class="tittle">{{$portfolio->getTranslation('title',$user_lang)}}</h3>
                        </div>

                        @php
                            $url_con = !empty($portfolio->file) && file_exists('assets/uploads/custom-file/'.$portfolio->file) ? url('assets/uploads/custom-file/'.$portfolio->file) : '#';
                        @endphp
                        <!-- btn Preview -->
                        <div class="btn-wrapper btn-rtl mb-40">
                            <a href="{{$portfolio->url}}" class="cmn-btn4 mb-10 mr-10" target="_blank">{{__('Live Preview')}}</a>
                            <a @if(!empty($portfolio->file)) href="{{$url_con}}"  download="" @endif class="cmn-btn3 mb-10">{{__('Download')}}</a>
                        </div>

                        <!-- items discription -->
                        <div class="catListing mb-40">
                            <ul class="listing">
                                <li class="listItem">
                                    <p class="leftCap">{{__('Category')}}: </p>
                                    <p class="rightCap">{{optional($portfolio->category)->getTranslation('title',$user_lang)}}</p>
                                </li>
                                <li class="listItem">
                                    <p class="leftCap">{{__('Date')}}: </p>
                                    <p class="rightCap">{{optional($portfolio->created_at)->format('d M, Y')}}</p>
                                </li>
                                <li class="listItem">
                                    <p class="leftCap">{{__('Project')}}: </p>
                                    <p class="rightCap">{{$portfolio->getTranslation('title',$user_lang)}}</p>
                                </li>
                                <li class="listItem">
                                    <p class="leftCap">{{__('Clients')}} : </p>
                                    <p class="rightCap">{{$portfolio->getTranslation('client',$user_lang)}}</p>
                                </li>
                                <li class="listItem">
                                    <p class="leftCap">{{__('Design')}}: </p>
                                    <p class="rightCap">{{$portfolio->getTranslation('design',$user_lang)}}</p>
                                </li>
                                <li class="listItem">
                                    <p class="leftCap">{{__('Typography')}}: </p>
                                    <p class="rightCap">{{$portfolio->getTranslation('typography',$user_lang)}}</p>
                                </li>
                            </ul>
                        </div>

                        <!-- Social -->
                        <div class="footer-social">
                            <a href="{{get_static_option('topbar_facebook_url')}}" class="wow ladeInUp social animated animated" data-wow-delay="0.0s" ><i class="fab fa-facebook-f icon"></i></a>
                            <a href="{{get_static_option('topbar_instagram_url')}}" class="wow ladeInUp social animated animated" data-wow-delay="0.1s" ><i class="fab fa-instagram icon"></i></a>
                            <a href="{{get_static_option('topbar_linkedin_url')}}" class="wow ladeInUp social animated animated" data-wow-delay="0.2s"><i class="fab fa-linkedin-in icon"></i></a>
                            <a href="{{get_static_option('topbar_twitter_url')}}" class="wow ladeInUp social animated animated" data-wow-delay="0.3s"><i class="fab fa-twitter icon"></i></a>
                        </div>
                    </div>



                    <div class="simplePresentCart mb-24">
                        <!-- Tag -->
                        <div class="tagArea">
                            <div class="small-tittle mb-30">
                                <h3 class="tittle">{{__('Categories')}}</h3>
                            </div>
                            <ul class="selectTag">
                                @foreach($categories as $cat)
                                    @php
                                        $cat_title = $cat->getTranslation('title',$user_lang);
                                    @endphp
                                   <li class="listItem">
                                       <a href="{{route('tenant.frontend.portfolio.category',['id' => $cat->id,'slug'=>\Illuminate\Support\Str::slug($cat_title)])}}">{{$cat_title}}</a>
                                   </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Services Area End -->

    @if(count($more_portfolios) > 0)
        <div class="portfolioSlider bottom-padding2">
            <div class="container">
                <div class="row">
                    <div class="col-xl-10 col-lg-10 col-md-10">
                        <div class="section-tittle mb-40">
                            <h2 class="tittle wow fadeInUp" data-wow-delay="0.0s">{{__('More Project')}} </h2>
                        </div>
                    </div>
                </div>
                <div class="row ">
                    <div class="col-xl-12">
                        <div class="portfolioWrapper global-slick-init slider-inner-margin" data-infinite="true" data-arrows="false" data-dots="false" data-slidesToShow="4" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500"  data-responsive='[{"breakpoint": 1800,"settings": {"slidesToShow": 4}},{"breakpoint": 1600,"settings": {"slidesToShow": 4}},{"breakpoint": 1400,"settings": {"slidesToShow": 4}},{"breakpoint": 1200,"settings": {"slidesToShow": 4}},{"breakpoint": 992,"settings": {"slidesToShow": 4}},{"breakpoint": 768, "settings": {"slidesToShow": 2}},{"breakpoint": 480, "settings": {"slidesToShow": 1}}]'>
                            @foreach($more_portfolios as $more)
                                <div class="portfolio-single  wow fadeInRight" data-wow-delay="0.2s">
                                    <a href="{{route('tenant.frontend.portfolio.single',$more->slug)}}">
                                        {!! render_image_markup_by_attachment_id($more->image) !!}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
  @endif
@endsection
