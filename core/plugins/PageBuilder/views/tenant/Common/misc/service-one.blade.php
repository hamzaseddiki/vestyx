<section class="servicesArea section-padding">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row">
            <div class="col-xxl-4 col-xl-4 col-lg-5 col-md-6">
                <x-service::frontend.sidebar-data/>
            </div>
            <div class="col-xxl-8 col-xl-8 col-lg-7 col-md-6">
                <div class="row">
                    @php
                        $all_services = $data['service'];
                    @endphp
                    @foreach($data['service'] ?? [] as $data)
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
                                    <p class="pera">{!! \Illuminate\Support\Str::words(purify_html_raw($data->getTranslation('description',get_user_lang())),20) !!}</p>
                                    <div class="btn-wrapper mb-20 mt-20">
                                        @php
                                            $d_theme = get_static_option('tenant_default_theme');
                                            $theme_con = $d_theme == 'software-business' ? 'softwareFirm_cmn_btn btn_bg_1' : $d_theme.'_cmn_btn btn_bg_1';
                                        @endphp
                                        <a href="{{$single_route}}" class="{{$theme_con}} cmn-btn-outline3"> {{__('Explore')}} </a>
                                    </div>
                                </figcaption>
                            </figure>
                        </div>
                    @endforeach
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="pagination mt-40 mb-30">
                                   {!! $all_services->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
