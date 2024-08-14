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
                <div class="col-xxl-4 col-xl-4 col-lg-5 col-md-6">
                    <x-service::frontend.sidebar-data/>
                </div>
                <div class="col-lg-8">
                    <div class="row">
                        @if(count($all_services) < 1)
                            <div class="col-lg-12">
                                <div class="alert alert-warning text-center">
                                    {{__('No Post Available In ').$category_name.__(' Category')}}
                                </div>
                            </div>
                        @endif

                        @php
                            $user_lang = get_user_lang();
                        @endphp
                            @foreach($all_services as $data)
                                @php
                                    $single_route = route('tenant.frontend.service.single',$data->slug);
                                @endphp
                                <div class="col-lg-6">
                                    <figure class="singleServices-global mb-24">
                                        <div class="event-img imgEffect">
                                            <a href="{{$single_route}}">
                                                {!! render_image_markup_by_attachment_id($data->image) !!}
                                            </a>
                                        </div>
                                        <figcaption class="eventCaption">
                                            <h3><a href="{{$single_route}}" class="tittle">{!! purify_html($data->getTranslation('title',get_user_lang()) )!!}</a></h3>
                                            <p class="pera">{!! \Illuminate\Support\Str::words($data->getTranslation('description',get_user_lang()),20) !!}</p>
                                            <div class="btn-wrapper mb-20">
                                                <a href="{{$single_route}}" class="{{get_static_option('tenant_default_theme')}}_cmn_btn btn_bg_1  cmn-btn-outline3"> {{__('Explore')}} </a>
                                            </div>
                                        </figcaption>
                                    </figure>
                                </div>
                            @endforeach
                    </div>
                    <div class="col-lg-12">
                            {!! $all_services->links() !!}
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
