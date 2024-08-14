
<div class="portfolioArea section-padding">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row">
         @foreach($data['portfolio'] ?? [] as $port)
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <figure class="singlePortfolio mb-24  wow fadeInUp" data-wow-delay="0.0s">
                    <div class="portfolioImg imgEffect">
                        <a href="{{route('tenant.frontend.portfolio.single',$port->slug)}}" target="_blank">
                            {!! render_image_markup_by_attachment_id($port->image) !!}
                        </a>
                    </div>

                    <figcaption class="portfolioDetails">
                        <div class="cap">
                            <h4><a href="{{route('tenant.frontend.portfolio.single',$port->slug)}}" class="templateTittle">{{$port->getTranslation('title',get_user_lang())}}</a></h4>
                            <p class="templateCap">{!! Str::words(\App\Helpers\SanitizeInput::esc_html($port->getTranslation('description',get_user_lang())),13) !!}</p>
                        </div>
                        <div class="btn-wrapper mb-20">
                            <a href="{{$port->url}}" target="_blank" class="cmn-btn cmn-btns">{{__('Live preview')}}</a>
                        </div>
                    </figcaption>
                </figure>
            </div>
            @endforeach
            <!-- Right -->

        </div>
        <!-- Pagination -->
        <div class="row">
            <div class="col-lg-12">
                <div class="pagination mt-40 mb-30">
                    <ul class="pagination-list">
                          {!! $data['portfolio']->links() !!}
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

