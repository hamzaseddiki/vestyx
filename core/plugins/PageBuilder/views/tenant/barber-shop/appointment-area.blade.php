<section class="barberShop_service_area" data-padding-top="{{ $data['padding_top'] }}" data-padding-bottom="{{$data['padding_bottom']}}">

    <div class="barberShop_service__shapes">
        {!! render_image_markup_by_attachment_id($data['left_small_image']) !!}
        {!! render_image_markup_by_attachment_id($data['right_small_image']) !!}
    </div>

    <div class="container">
        <div class="barberShop_sectionTitle">
            {!! get_modified_title_barber_two($data['title']) !!}
        </div>
        <div class="row g-4 mt-4">
            @foreach($data['appointments'] ?? [] as $app)
                <div class="col-lg-4 col-md-6">
                <div class="barberShop_service__single barberShop-section-bg">
                    <div class="barberShop_service__single__icon">
                        <a href="{{ route('tenant.frontend.appointment.order.page',$app->slug) }}">
                            {!! render_image_markup_by_attachment_id($app->image) !!}
                        </a>

                    </div>
                    <div class="barberShop_service__single__contents mt-3">
                        <a href="{{ route('tenant.frontend.appointment.order.page',$app->slug) }}">
                            <h4 class="barberShop_service__single__title">{{$app->title}} <span class="price">{{__('From')}} {{amount_with_currency_symbol($app->price)}}</span></h4>
                        </a>

                        <p class="barberShop_service__single__para mt-3">{!! \Illuminate\Support\Str::words(strip_tags($app->description),25) !!}</p>
                        <div class="btn-wrapper mt-4">
                            <a href="{{ route('tenant.frontend.appointment.order.page',$app->slug) }}" class="barberShop_cmn_btn btn_outline_1 color_one btn_small">{{$data['button_text']}}</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @if(!empty($data['bottom_button_url']) || !empty($data['bottom_button_text']))
        <div class="barberShop_service__btn mt-4 center-text">
            <div class="btn-wrapper">
                <a href="{{$data['bottom_button_url']}}" class="barberShop_cmn_btn btn_bg_1">{{$data['bottom_button_text']}}</a>
            </div>
        </div>
		@endif
    </div>
</section>
