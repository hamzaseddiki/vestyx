@php
    $button_text = $data['button_text'];
@endphp

<section class="productCampaign ">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row mb-40">
            <div class="col-xl-12 col-lg-12 col-md-10 col-sm-10">
                <div class="section-tittle mb-0">
                    <h2 class="title text-center">{{$data['title']}} </h2>
                </div>
            </div>

        </div>
        <div class="row">

            @foreach($data["campaigns"] as $camp)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6  mt-40">
                    <div class="singleProduct text-center mb-24">
                        <div class="productImg imgEffect2">
                            <a href="{{route('tenant.campaign.details',$camp->id)}}">
                                {!! render_image_markup_by_attachment_id($camp->image) !!}
                            </a>
                        </div>

                        <div class="productCap">
                            <a href="" class="title">
                                <h5>{{$camp->getTranslation('title',get_user_lang())}}</h5>
                            </a>
                            <p class="title mt-2">{{ $camp->getTranslation('subtitle',get_user_lang()) }}</p>

                            <div class="dateTimmerGlobal wow fadeInRight campaign-countdown" data-date="{{ $camp->end_date }}"  data-wow-delay="0.0s">
                                <div class="">
                                    <div class="single">
                                        <div class="cap">
                                            <span class="time counter-days"></span>
                                            <p class="cap">{{__('Days')}}</p>
                                        </div>
                                    </div>
                                    <div class="single">
                                        <span class="time counter-hours"></span>
                                        <p class="cap">{{__('Hours')}}</p>
                                    </div>
                                    <div class="single">
                                        <span class="time counter-minutes"></span>
                                        <p class="cap">{{__('Mins')}}</p>
                                    </div>
                                    <div class="single">
                                        <span class="time counter-seconds"></span>
                                        <p class="cap">{{__('Secs')}}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="btn-wrapper mt-3">
                                <a href="{{route('tenant.campaign.details',$camp->id)}}" class="cmn-btn-outline3 w-100 ">{{$button_text}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
