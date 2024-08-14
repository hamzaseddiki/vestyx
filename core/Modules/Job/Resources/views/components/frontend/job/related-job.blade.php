@php
    $user_lang = get_user_lang();
@endphp

<section class="relatedJob bottom-padding">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-7 col-md-10 col-sm-10">
                <div class="section-tittle mb-50">
                    <h2 class="tittle">{{__('Related Jobs')}}</h2>
                </div>
            </div>
        </div>
        <div class="global-slick-init slider-inner-margin arrowStyleThree" data-infinite="true" data-arrows="true" data-dots="false" data-slidesToShow="3" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="las la-angle-left"></i></div>'
             data-nextArrow='<div class="next-icon"><i class="las la-angle-right"></i></div>' data-responsive='[{"breakpoint": 1800,"settings": {"slidesToShow": 4}},{"breakpoint": 1600,"settings": {"slidesToShow": 4}},{"breakpoint": 1400,"settings": {"slidesToShow": 3}},{"breakpoint": 1200,"settings": {"slidesToShow": 3}},{"breakpoint": 991,"settings": {"slidesToShow": 2}},{"breakpoint": 768, "settings": {"slidesToShow": 1}},{"breakpoint": 576, "settings": {"slidesToShow": 1}}]'>

            @foreach($allRelatedJobs as $data)
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="singleJob mb-24">
                        <div class="company">
                            <!-- Clients -->
                            <div class="companyCap">
                                <div class="companyLogo">
                                    {!! render_image_markup_by_attachment_id($data->image) !!}
                                </div>
                                <div class="companyTitle">
                                    <span class="title">{{ \Modules\Job\Enums\EmployeeTypeEnum::getText($data->employee_type) }}</span>
                                    <p class="pera">{{$data->getTranslation('job_location',$user_lang)}}</p>
                                </div>
                            </div>
                            <div class="jobCat">
                                <a href="{{ route('tenant.frontend.job.category',['id' => $data->category_id, 'any'=> \Illuminate\Support\Str::slug($data->category?->getTranslation('title',get_user_lang()))]) }}">
                                    <span class="title">{!! $data->category?->getTranslation('title',$user_lang) !!}</span>
                                </a>
                            </div>
                        </div>
                        <div class="productCap">
                            <h5><a href="{{ route('tenant.frontend.job.single',$data->slug) }}" class="title">{{$data->getTranslation('title',$user_lang)}}</a></h5>
                            <p class="pera">{!! \Illuminate\Support\Str::words(purify_html($data->getTranslation('description',$user_lang)),12) !!}</p>
                        </div>
                        <div class="d-flex align-items-center flex-wrap justify-content-between">
                            <div class="productPrice">
                                <strong class="regularPrice">{{ amount_with_currency_symbol($data->salary_offer) }}</strong>
                                <span class="offerPrice"> /{{__('Month')}}</span>
                            </div>
                            <div class="btn-wrapper mb-10">
                                <a href="{{route('tenant.frontend.job.payment',$data->slug)}}" class="cmn-btn3">{{__('Apply Now')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
