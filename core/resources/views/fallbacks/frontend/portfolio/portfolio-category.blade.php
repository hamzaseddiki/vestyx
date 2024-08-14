@extends('tenant.frontend.frontend-page-master')

@php
    $post_img = null;
    $user_lang = get_user_lang();
@endphp

@section('title')
    {{ ucwords(str_replace('-',' ',$slug))}}
@endsection

@section('page-title')
    {{ ucwords(str_replace('-',' ',$slug))}}
@endsection

@section('content')

    <section class="servicesArea section-padding">
        <div class="container">
            <div class="row">
                <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                 <div class="row">
                  @if(count($portfolio) < 1)
                        <div class="col-lg-12">
                            <div class="alert alert-warning">
                                <span>{{__('There is no category in ')}} : {{$slug}}</span>
                            </div>
                        </div>
                    @else
                         @foreach($portfolio as $port)
                            <div class="col-lg-4">
                                <figure class="singleServices-global mb-24">
                                    <div class="event-img imgEffect">
                                        <a href="{{route('tenant.frontend.portfolio.single',$port->slug)}}">
                                            {!! render_image_markup_by_attachment_id($port->image) !!}
                                        </a>
                                    </div>
                                    <figcaption class="eventCaption">
                                        <h3><a href="{{route('tenant.frontend.portfolio.single',$port->slug)}}" class="tittle">{{ $port->getTranslation('title',get_user_lang()) }}</a></h3>
                                        <p class="pera">{!! Str::words(\App\Helpers\SanitizeInput::esc_html($port->getTranslation('description',get_user_lang())),20) !!}</p>
                                        <div class="btn-wrapper mb-20">
                                            <a href="{{route('tenant.frontend.portfolio.single',$port->slug)}}" class="cmn-btn-outline3"> {{__('Show')}} </a>
                                        </div>
                                    </figcaption>
                                </figure>
                            </div>
                         @endforeach
                     @endif
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="pagination mt-60">
                                      {!! $portfolio->links() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
