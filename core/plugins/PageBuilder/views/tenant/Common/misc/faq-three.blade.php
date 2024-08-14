<section class="faqArea section-padding fix sectionBg2">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-7 col-md-10 col-sm-10">
                <div class="section-tittle text-center mb-50">
                    {!! get_modified_title_ticket($data['heading_title']) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="collapse-wrapper">
                    @php
                        $parent_number = \Illuminate\Support\Str::random(20);
                    @endphp
                    <div class="accordion" id="accordionExample-{{$parent_number}}">
                        <div class="row">
                          @foreach($data['faq'] ?? [] as $item)
                            <div class="col-lg-6">
                                    @php
                                        $child_number = \Illuminate\Support\Str::random(10);
                                        $area_expanded = $loop->iteration == 0 ? 'true' : 'false';
                                    @endphp
                                    <div class="accordion-item wow fadeInLeft" data-wow-delay="0.0s">
                                    <h2 class="accordion-header" id="headingOne-{{$child_number}}">
                                        <button class="accordion-button {{in_array($loop->index,[0,1])  ? '' : 'collapsed'}}" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne-{{$child_number}}" aria-expanded="{{$area_expanded}}" aria-controls="collapseOne-{{$child_number}}">
                                            {{$item->getTranslation('title',get_user_lang())}}
                                        </button>
                                    </h2>
                                    <div id="collapseOne-{{$child_number}}" class="accordion-collapse collapse {{ in_array($loop->index,[0,1])  ? 'show' : ''}}" aria-labelledby="headingOne" data-bs-parent="#accordionExample-{{$parent_number}}">
                                        <div class="accordion-body">
                                            <p>{!!  Str::words($item->getTranslation('description',get_user_lang()),80) !!}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                          @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
