@php
    $current_lang = \App\Facades\GlobalLanguage::user_lang_slug();
@endphp

<section class="faqArea section-padding fix section-bg2" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-xl-6 col-lg-6 col-md-11">
                <!-- faq-img -->
                <div class="faq-img wow fadeInLeft" data-wow-delay="0.0s">
                    <div class="faq-back-img">
                        {!! render_image_markup_by_attachment_id($data['left_image']) !!}

                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-12">
                <div class="row justify-content-center">
                    <div class="col-xl-12">
                        <div class="section-tittle section-tittle2 mb-25">
                            {!! get_landlord_modified_title($data['title']) !!}
                        </div>
                    </div>
                </div>

                @php
                    $parent_rand_number = \Illuminate\Support\Str::random(20);
                @endphp

                <div class="collapse-wrapper">
                    <div class="accordion" id="accordionExample-{{$parent_rand_number}}">
                        @foreach($data['repeater_data']['repeater_title_'.$current_lang] ?? [] as $key => $info)
                            @php
                                $title = $info ?? '';
                                $subtitle = $data['repeater_data']['repeater_description_'.$current_lang][$key] ?? '';
                                $child_rand = \Illuminate\Support\Str::random(15);
                            @endphp
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button {{ $key == 0 ? "" : "collapsed" }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne-{{$child_rand}}" aria-expanded="{{ $key == 0 ? "true" : "false" }}"  aria-controls="collapseOne-{{$child_rand}}">
                                   {{$title}}
                                </button>
                            </h2>
                            <div id="collapseOne-{{$child_rand}}" class="accordion-collapse collapse {{$loop->first ? 'show' : ''}}" aria-labelledby="{{$child_rand}}" data-bs-parent="#accordionExample-{{$parent_rand_number}}">
                                <div class="accordion-body">
                                    <p> {{$subtitle}}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
