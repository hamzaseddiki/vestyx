@extends('tenant.frontend.frontend-page-master')
@section('title')
    {{ $category_name}}
@endsection
@section('page-title')
    {{__('Category: ').$category_name}}
@endsection
@section('content')

    <section class="blog-content-area" data-padding-top="110" data-padding-bottom="110">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    @forelse($all_activities as $item)
                        <div class="singleFlexitem mb-24 wow fadeInUp " data-wow-delay="0.0s" >
                            <a href="{{route('tenant.frontend.donation.activities.single',$item->slug)}}"> <div class="activitiesImg" {!! render_background_image_markup_by_attachment_id($item->image) !!}></div></a>
                            <div class="activitiesCaption">

                                <h5>
                                    <a href="{{route('tenant.frontend.donation.activities.single',$item->slug)}}" class="featureTittle">{{$item->getTranslation('title',get_user_lang())}}</a>
                                </h5>
                                <p class="featureCap">{!! Str::limit(purify_html($item->getTranslation('description',get_user_lang())),160) !!}</p>
                                <div class="btn-wrapper mt-10">
                                    <a href="{{route('tenant.frontend.donation.activities.single',$item->slug)}}" class="browseBtn mb-15">{{__('View Story')}}</a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="alert alert-warning">
                            {{__('No Post Available In ') .' : ' .$category_name.__(' Category')}}
                        </div>
                    @endforelse
                </div>

                <div class="col-lg-4">
                    {!! render_frontend_sidebar('donation_acitvities_sidebar',['column' => false]) !!}
                </div>
            </div>
        </div>
    </section>
@endsection
