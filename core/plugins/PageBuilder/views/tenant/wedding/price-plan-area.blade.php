<section class="wedding_package_area position-relative padding-top-50 padding-bottom-50" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="gradient_bg">
        <span></span>
        <span></span>
    </div>

    <div class="container">
        <div class="wedding_sectionTitle">
            <h2 class="title">{{$data['title']}}</h2>
        </div>
        <div class="row g-4 mt-4">
            @foreach($data['price_plan'] ?? [] as $item)
                @php
                    $popular_condition_text = $item->is_popular == 'on' ? __('Popular') : '';
                    $popular_condition = $item->is_popular == 'on' ? 'popular' : '';
                @endphp
                <div class="col-lg-4 col-md-6">
                    <div class="wedding_package {{$popular_condition}} radius-10">
                        <div class="wedding_package__header">
                            <h4 class="wedding_package__name">{{$item->title}}<span class="popular__title">{{$popular_condition_text}}</span></h4>
                            <h5 class="wedding_package__price"><sup>{{ site_currency_symbol() }}</sup>{{$item->price}}</h5>
                        </div>
                        <div class="wedding_package__body mt-4">

                            <ul class="wedding_package__list list-none">
                                @php
                                    $fall_back_features =
                                    'This is demo
                                     feature please
                                      change this';

                                    $features_condition = !empty($item->features) ? explode(",",$item->features) : explode("\n",$fall_back_features);
                                    $not_available_features_condition = !empty($item->not_available_features) ? explode(",",$item->not_available_features) : explode("\n",$fall_back_features);
                                @endphp

                                @foreach($features_condition as  $feature)
                                        <li class="check_icon">{!! $feature !!}</li>
                                @endforeach

                                @foreach($not_available_features_condition as $not_feature)
                                        <li class="close_icon">{!! $not_feature !!}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="wedding_package__btn btn-wraper mt-4">
                            <a href="{{route('tenant.frontend.wedding.price.plan.order',$item->id)}}" class="wedding_cmn_btn btn_gradient_main radius-30 w-100">{{$data['button_text']}}</a>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</section>
