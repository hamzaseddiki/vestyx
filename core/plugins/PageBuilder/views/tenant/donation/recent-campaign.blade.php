@php
    $current_lang = \App\Facades\GlobalLanguage::user_lang_slug();
@endphp

<section class="OurCampaign sectionBgHalf section-padding campaign-padding-top">
    <div class="container" data-padding-top="{{$data['padding_top']}}"
         data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row">
            <div class="col-xl-8 col-lg-10 col-md-10 col-sm-10">
                <div class="section-tittle mb-40">
                    {!! get_modified_title_tenant($data['title']) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="global-slick-init slider-inner-margin sliderArrow" data-infinite="false" data-arrows="true"
                     data-dots="false" data-slidesToShow="3" data-swipeToSlide="true" data-autoplay="true"
                     data-autoplaySpeed="2500"
                     data-prevArrow='<div class="prev-icon"><i class="fa-solid fa-arrow-left-long"></i></div>'
                     data-nextArrow='<div class="next-icon"><i class="fa-solid fa-arrow-right-long"></i></div>'
                     data-responsive='[{"breakpoint": 1800,"settings": {"slidesToShow": 4}},{"breakpoint": 1600,"settings": {"slidesToShow": 4}},{"breakpoint": 1400,"settings": {"slidesToShow": 3}},{"breakpoint": 1200,"settings": {"slidesToShow": 3}},{"breakpoint": 991,"settings": {"slidesToShow": 2}},{"breakpoint": 768, "settings": {"slidesToShow": 1}},{"breakpoint": 576, "settings": {"slidesToShow": 1}}]'>
                    @foreach($data['donation'] as $item)
                        <div class="singleCases mb-40">
                            <div class="cases-img">
                                <a href="{{route('tenant.frontend.donation.single',$item->slug)}}">
                                    {!! render_image_markup_by_attachment_id($item->image) !!}
                                </a>
                            </div>
                            <div class="casesCaption">
                                <h3><a href="{{route('tenant.frontend.donation.single',$item->slug)}}"
                                       class="tittle">{{ $item->getTranslation('title',$current_lang) }} </a></h3>
                                <p class="pera">{!! \Illuminate\Support\Str::words(purify_html_raw($item->getTranslation('description',$current_lang)),18) !!}</p>

                                @php
                                    $deadline = $item->deadline;
                                    $remaining_days = \Illuminate\Support\Carbon::parse($deadline)->diffInDays(now());
                                        $count = \Modules\Donation\Entities\DonationPaymentLog::where('donation_id',$item->id)->count();
                                @endphp

                                <div class="prices mb-10 mt-10">
                                    <span class="price">{{__('Goal')}}: {{amount_with_currency_symbol($item->amount)}}</span>
                                    <span class="dates"> {{$remaining_days}} {{__('days left')}}</span>
                                </div>
                                <!-- Progress Bar -->
                                <div class="singleProgres mb-15 donation-progress"
                                     data-percentage="{{get_percentage($item->amount,$item->raised)}}">
                                    <div class="bar-1"></div>
                                </div>
                                <!-- /End progress -->
                                <div class="donateMember">

                                    @if($data['donors_count_type'] == 'with_image')
                                        <div class="donateMemberList mb-10">
                                            <ul class="listing">
                                                <li class="listItem">
                                                    <a href="#!"> <img
                                                                src="{{global_asset('assets/tenant/frontend/themes/img/gallery/donation-donatePepols1.jpg')}}"
                                                                alt="images"></a>
                                                </li>
                                                <li class="listItem">
                                                    <a href="#!"> <img
                                                                src="{{global_asset('assets/tenant/frontend/themes/img/gallery/donation-donatePepols2.jpg')}}"
                                                                alt="images"></a>
                                                </li>
                                                <li class="listItem">
                                                    <a href="#!"> <img
                                                                src="{{global_asset('assets/tenant/frontend/themes/img/gallery/donation-donatePepols3.jpg')}}"
                                                                alt="images"></a>
                                                </li>
                                                <li class="listItem">
                                                    <a href="#!" class="memberCounter">326+</a>
                                                </li>
                                            </ul>
                                        </div>
                                    @else
                                        <div class="donateMemberList mb-10">
                                            <span class="memberCounter2">{{$count}} {{__('Donated')}}</span>
                                        </div>
                                    @endif

                                    <div class="btn-wrapper mb-10">
                                        <a href="{{route('tenant.frontend.donation.payment',$item->id)}}"
                                           class="cmn-btn4">{{__('Donate Now')}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
