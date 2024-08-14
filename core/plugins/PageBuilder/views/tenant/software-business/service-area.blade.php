<section class="softwareFirm_service_area padding-top-100 padding-bottom-100">
    <div class="container">
        <div class="softwareFirm_sectionTitle">
            <h2 class="title">{{$data['title']}}</h2>
        </div>
        <div class="row g-4 mt-4">
           @foreach($data['service'] ?? [] as $item)
                <div class="col-md-6">
                    <div class="softwareFirm__service center-text radius-10">
                        <div class="softwareFirm__service__icon">
                            {!! render_image_markup_by_attachment_id($item->image) !!}
                        </div>
                        <div class="softwareFirm__service__contents mt-3">
                            <h4 class="softwareFirm__service__title">{{ $item->title }}</h4>
                            <p class="softwareFirm__service__para mt-3">{!! \Illuminate\Support\Str::words($item->description,40) !!}</p>
                            <div class="btn-wrapper mt-3">
                                <a href="{{ route('tenant.frontend.service.single',$item->slug) }}" class="softwareFirm__service__learnMore">{{$data['extra_text']}}</a>
                            </div>
                        </div>
                    </div>
                </div>
           @endforeach
        </div>
        <div class="row">
            <div class="col-12">
                <div class="btn-wraper center-text mt-4 mt-lg-5">
                    <a href="{{$data['button_url']}}" class="softwareFirm_cmn_btn btn_bg_1 radius-10">{{$data['button_text']}}</a>
                </div>
            </div>
        </div>
    </div>
</section>
