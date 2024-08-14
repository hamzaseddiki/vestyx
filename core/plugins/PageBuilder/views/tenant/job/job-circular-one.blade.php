@php
    $static = $data;
@endphp
<section class="featureCirculars section-padding2">
    <div class="container" data-padding-top="{{$static['padding_top']}}" data-padding-bottom="{{$static['padding_bottom']}}">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-7 col-md-10 col-sm-10">
                <div class="section-tittle text-center mb-50">
                    <h2 class="tittle">{{ $data['title'] }}</h2>
                </div>
            </div>
        </div>

        <div class="row">
            @foreach($data['jobs'] as $data)
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="singleJob mb-24">
                        <div class="company">
                            <!-- Clients -->
                            <div class="companyCap">
                                <div class="companyLogo">
                                    <a href="{{route('tenant.frontend.job.single',$data->slug)}}">
                                          {!! render_image_markup_by_attachment_id($data->image) !!}
                                    </a>
                                </div>
                                <div class="companyTitle">
                                    <span class="title">{{$data->getTranslation('company_name',get_user_lang())}}</span>
                                    <p class="pera">{{ $data->getTranslation('job_location',get_user_lang()) }}</p>
                                </div>
                            </div>
                            <div class="jobCat">
                                <span class="title">{{ \Modules\Job\Enums\WorkingTypeEnum::getText($data->job_type) }}</span>
                            </div>
                        </div>
                        <div class="productCap">
                            <h5><a href="{{route('tenant.frontend.job.single',$data->slug)}}" class="title">{{ $data->getTranslation('title',get_user_lang()) }}</a></h5>
                            <p class="pera">{!! \Illuminate\Support\Str::words(purify_html($data->getTranslation('description',get_user_lang())),10) !!} </p>
                        </div>
                        <div class="d-flex align-items-center flex-wrap justify-content-between">
                            <div class="productPrice">
                                <strong class="regularPrice">{{ amount_with_currency_symbol($data->salary_offer) }}</strong>
                                <span class="offerPrice"> /{{ __('Month') }}</span>
                            </div>
                            <div class="btn-wrapper mb-10">
                                <a href="{{route('tenant.frontend.job.payment',$data->slug)}}" class="cmn-btn3">{{__('Apply Now')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col-log-12">
                <div class="btn-wrapper text-center mt-40">
                    <a href="{{$static['button_url']}}" class="cmn-btn4 hero-btn">{{$static['button_text']}}</a>
                </div>
            </div>
        </div>
    </div>
</section>
