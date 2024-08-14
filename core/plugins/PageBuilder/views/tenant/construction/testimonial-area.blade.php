
<section class="construction_testimonial_area padding-top-50 padding-bottom-50">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="constructionTestimonial construction-section-bg-2 radius-10">
            <div class="constructionTestimonial_wrapper__shapes">
                <img src="{{global_asset('assets/tenant/frontend/themes/img/construction/testimonial/construction_shape.png')}}" alt="">
            </div>
            <div class="constructionTestimonial__shapes">
                <img src="{{global_asset('assets/tenant/frontend/themes/img/construction/testimonial/construction_feedback.png')}}" alt="feedback">
                <img src="{{global_asset('assets/tenant/frontend/themes/img/construction/testimonial/construction_review.png')}}" alt="review">
            </div>
            @php
                $heading_type_con = $data['heading_style'] == 'consultancy' ? 'consulting_sectionTitle'  : 'construction_sectionTitle__two'
            @endphp

            <div class="{{$heading_type_con}}">

                @if($data['heading_style'] == 'consultancy')
                    <span class="subtitle">{{$data['title']}}</span>
                    {!! get_consultancy_subtitle_line_breaker($data['subtitle']) !!}
                @else
                    <h2 class="title">{{$data['title']}}</h2>
                @endif
            </div>
            <div class="row g-4 mt-4 justify-content-between align-items-center">
                <div class="col-lg-12">
                    <div class="hoverCustom_tab constructionTestimonial_wrapper">
                        <div class="hoverCustom_tab__menu">
                            <ul class="hoverTab__menu constructionTestimonial_wrapper__images list-style-none">
                                @foreach($data['testimonial'] ?? [] as $item)
                                    <li class="constructionTestimonial_wrapper__images__single {{ $loop->index == 0 ? 'active' : '' }}" data-id="{{$item->id}}">
                                        {!! render_image_markup_by_attachment_id($item->image) !!}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-xxl-5 col-lg-6 col-md-8">
                                <div class="hoverTab_area testimonial_inner_single">
                                    @foreach($data['testimonial'] ?? [] as $item_bottom)
                                        @break($loop->index == 1)
                                        <div class="construction__singleTestimonial hoverTab_item active center-text">
                                            <div class="construction__singleTestimonial__thumb">
                                                {!! render_image_markup_by_attachment_id($item_bottom->image) !!}
                                                <span class="construction__singleTestimonial__thumb__quote"><i class="fa-solid fa-quote-left"></i></span>
                                            </div>
                                            <div class="construction__singleTestimonial__contents mt-5">
                                                <div class="construction__singleTestimonial__contents__details">
                                                    <h4 class="construction__singleTestimonial__contents__title">{{$item_bottom->name}}</h4>
                                                    <span class="construction__singleTestimonial__contents__subtitle mt-1">{{$item_bottom->designation}}</span>
                                                    <div class="construction__singleTestimonial__contents__star mt-2">
                                                        <span><i class="fa-solid fa-star"></i></span>
                                                        <span><i class="fa-solid fa-star"></i></span>
                                                        <span><i class="fa-solid fa-star"></i></span>
                                                        <span><i class="fa-solid fa-star"></i></span>
                                                        <span><i class="fa-solid fa-star"></i></span>
                                                    </div>
                                                </div>
                                                <p class="construction__singleTestimonial__contents__para mt-4 mt-lg-5">{!! \Illuminate\Support\Str::words($item_bottom->getTranslation('description',get_user_lang()),30) !!}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@section('scripts')
    <script>
        $(function (){

            $(document).on('mouseover', '.constructionTestimonial_wrapper__images__single', function (e){
                e.preventDefault();

                let el = $(this);
                let testimonial_id = el.data('id');

                $.ajax({
                    type: 'GET',
                    url: "{{route('tenant.frontend.home.construction.testimonial.by.ajax')}}",
                    data: {
                        id : testimonial_id,
                    },
                    success: function (data){
                        $('.testimonial_inner_single').html(data);
                    },
                    error: function (data){
                    }
                });

            });

        });
    </script>
@endsection


