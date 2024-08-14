@php
    $consultancy_home_condition = get_static_option('tenant_default_theme') == 'consultancy' ? 'consulting_counter consulting_section_bg_2' : '';
@endphp



<section class="construction_counter construction_section_bg_2 padding-top-100 padding-bottom-100 {{$consultancy_home_condition}}">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row g-4">
            @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key => $title)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="construction_single_counter desktop-center">
                        <div class="construction_single_counter__contents">
                            <div class="construction_single_counter__count consulting_single_counter__count">
                                <span class="odometer color-heading" data-odometer-final="{{$data['repeater_data']['repeater_number_'.get_user_lang()][$key] ?? ''}}"></span>
                                <span class="construction_single_counter__count__title color-heading"> {{$data['repeater_data']['repeater_extra_alpha_'.get_user_lang()][$key] ?? ''}} </span>
                            </div>
                            <p class="construction_single_counter__para mt-1 mt-md-3">{{$title ?? ''}}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
