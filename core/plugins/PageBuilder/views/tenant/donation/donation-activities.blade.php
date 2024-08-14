@php
    $user_lang = get_user_lang();
@endphp
<section class="exploreActivities section-padding">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10 col-md-10 col-sm-10">
                <div class="section-tittle text-center mb-40">
                    {!! get_modified_title_tenant($data['title']) !!}
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($data['activities'] as $item)
                <div class="col-xl-6 col-lg-12">
                    <div class="singleFlexitem mb-24 wow fadeInUp " data-wow-delay="0.0s" >
                        <a href="{{route('tenant.frontend.donation.activities.single',$item->slug)}}"> <div class="activitiesImg" {!! render_background_image_markup_by_attachment_id($item->image) !!}></div></a>
                        <div class="activitiesCaption">
                            <h5><a href="{{route('tenant.frontend.donation.activities.single',$item->slug)}}" class="featureTittle">{{$item->getTranslation('title',$user_lang)}}</a></h5>
                            <p class="featureCap">{!! Str::words(purify_html_raw($item->getTranslation('description',$user_lang)),27) !!}</p>
                            <div class="btn-wrapper mt-10">
                                <a href="{{route('tenant.frontend.donation.activities.single',$item->slug)}}" class="browseBtn mb-15">{{__('View Story')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>

</section>
