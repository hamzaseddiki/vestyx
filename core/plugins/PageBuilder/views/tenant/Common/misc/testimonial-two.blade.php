<div class="testimonialarea-global section-padding2 ">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row">

            @foreach($data['testimonial'] as $item)
            <div class="col-xl-4 col-md-6">
                <div class="singleTestimonial-global mb-24">
                    <div class="testiPera">
                        <p class="pera">{{$item->getTranslation('description',get_user_lang())}}</p>
                    </div>
                    <!-- Client -->
                    <div class="testimonialClient">
                        <!-- Clients -->
                        <div class="clients">
                            <div class="clientImg">
                                {!! render_image_markup_by_attachment_id($item->image) !!}
                            </div>
                            <div class="clientText">
                                <span class="clientName">{{$item->getTranslation('name',get_user_lang())}}</span>
                                <p class="clinetDisCrip">{{$item->getTranslation('designation',get_user_lang())}}</p>
                            </div>
                        </div>
                        <!-- Client feedback -->
                        <div class="feedback">
                            <ul class="rattingList">
                                <li class="listItems"><i class="las fa-star icon"></i></li>
                                <li class="listItems"><i class="las fa-star icon"></i></li>
                                <li class="listItems"><i class="las fa-star icon"></i></li>
                                <li class="listItems"><i class="las fa-star icon"></i></li>
                                <li class="listItems"><i class="las fa-star icon"></i></li>
                            </ul>
                            <div class="dates">
                                <span class="dateTime">{{date('d M Y',strtotime($item->created_at))}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-lg-12">
                <!-- Pagination -->

                @if(!empty($data['pagination_status']) && !empty($data['items']))
                    <div class="pagination mt-40 mb-30">
                        <ul class="pagination-list">
                            {{$data['testimonial']->links()}}
                        </ul>
                    </div>
                 @endif
            </div>
        </div>
    </div>
</div>
