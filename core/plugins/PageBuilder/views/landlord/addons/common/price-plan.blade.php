@php
    $selected_lang = \App\Facades\GlobalLanguage::user_lang_slug();
    $price_plan_type_key = array_keys($data['all_price_plan']->toArray());;
@endphp

<section class="pricingCard section-padding" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}" id="price_plan_section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-7 col-md-10 col-sm-10">
                <div class="section-tittle text-center mb-30">
                    {!! get_landlord_modified_title($data['title']) !!}
                </div>
                <!-- Tab Menu -->

                @if(!empty($data['plan_type_status']))
                    @if(count($price_plan_type_key) > 0)
                    <div class="tab-menu tabStyleOne text-center mb-40  wow fadeInUp" data-wow-delay="0.1s">
                        <ul>
                           @foreach($price_plan_type_key as $key => $price_plan)
                               <li><a href="#" data-rel="tab-{{$price_plan}}" class="{{$key == 0 ? 'active' : ''}}" >{{ \App\Enums\PricePlanTypEnums::getText($price_plan) }}</a></li>
                           @endforeach
                        </ul>
                    </div>
                    @endif
                @endif

            </div>
        </div>


      @foreach($data['all_price_plan'] as $plan_type => $plan_items)
        <div class="singleTab-items" id="tab-{{$plan_type}}" style="{{ $loop->iteration == 1 ? 'display:block;' : "" }}">
            <div class="row">

                @php
                   $item = !empty($data['item']) ? $data['item'] : 100;
                @endphp

                @foreach($plan_items->take($item) as $key => $price_plan_item)
                    @php
                        $active = '';
                        if($key == 1){
                            $active = 'active';
                        }
                    @endphp
                <div class="col-lg-4 col-md-6">
                    <div class="singlePrice {{$active}} mb-24">
                        <h4 class="priceTittle">{{$price_plan_item->getTranslation('title',$selected_lang)}}</h4>
                        <span class="price">{{amount_with_currency_symbol($price_plan_item->price)}} <span class="subTittle"> /{{\App\Enums\PricePlanTypEnums::getText($price_plan_item->type)}}</span></span>
                        <p class="pricePera">{{$price_plan_item->getTranslation('subtitle',$selected_lang)}}</p>
                        <ul class="listing">

                            @if(!empty($price_plan_item->page_permission_feature))
                                <li class="listItem">
                                    <img src="{{asset('assets/landlord/frontend/img/icon/grenIcon.svg')}}" class="icon" alt="icon">
                                    <blockquote class="priceTag">{{ __('Page')}} {{$price_plan_item->page_permission_feature }} </blockquote>
                                </li>
                            @endif

                                @if(!empty($price_plan_item->appointment_permission_feature))
                                    <li class="listItem">
                                        <img src="{{asset('assets/landlord/frontend/img/icon/grenIcon.svg')}}" class="icon" alt="icon">
                                        <blockquote class="priceTag">{{ sprintf(__('Appointment').' '.'%s',$price_plan_item->appointment_permission_feature )}} </blockquote>
                                    </li>
                                @endif

                            @if(!empty($price_plan_item->blog_permission_feature))
                                <li class="listItem">
                                    <img src="{{asset('assets/landlord/frontend/img/icon/grenIcon.svg')}}" class="icon" alt="icon">
                                    <blockquote class="priceTag">{{ sprintf(__('Blog').' '.'%s',$price_plan_item->blog_permission_feature )}} </blockquote>
                                </li>
                            @endif

                            @if(!empty($price_plan_item->product_create_permission))
                                <li class="listItem">
                                    <img src="{{asset('assets/landlord/frontend/img/icon/grenIcon.svg')}}" class="icon" alt="icon">
                                    <blockquote class="priceTag">{{ sprintf(__('Product').' '.'%s',$price_plan_item->product_create_permission )}} </blockquote>
                                </li>
                            @endif

                            @if(!empty($price_plan_item->donation_permission_feature))
                                <li class="listItem">
                                    <img src="{{asset('assets/landlord/frontend/img/icon/grenIcon.svg')}}" class="icon" alt="icon">
                                    <blockquote class="priceTag">{{ sprintf(__('Donation').' '.'%s',$price_plan_item->donation_permission_feature )}} </blockquote>
                                </li>
                            @endif

                            @if(!empty($price_plan_item->job_permission_feature))
                                <li class="listItem">
                                    <img src="{{asset('assets/landlord/frontend/img/icon/grenIcon.svg')}}" class="icon" alt="icon">
                                    <blockquote class="priceTag">{{ sprintf(__('Job').' '.' %d',$price_plan_item->job_permission_feature )}} </blockquote>
                                </li>
                            @endif

                            @if(!empty($price_plan_item->event_permission_feature))
                                <li class="listItem">
                                    <img src="{{asset('assets/landlord/frontend/img/icon/grenIcon.svg')}}" class="icon" alt="icon">
                                    <blockquote class="priceTag">{{ sprintf(__('Event').' '.'%s',$price_plan_item->event_permission_feature )}} </blockquote>
                                </li>
                            @endif

                            @if(!empty($price_plan_item->knowledgebase_permission_feature))
                                <li class="listItem">
                                    <img src="{{asset('assets/landlord/frontend/img/icon/grenIcon.svg')}}" class="icon" alt="icon">
                                    <blockquote class="priceTag">{{ sprintf(__('Article').' '.'%s',$price_plan_item->knowledgebase_permission_feature )}} </blockquote>
                                </li>
                            @endif

                            @if(!empty($price_plan_item->portfolio_permission_feature))
                                <li class="listItem">
                                    <img src="{{asset('assets/landlord/frontend/img/icon/grenIcon.svg')}}" class="icon" alt="icon">
                                    <blockquote class="priceTag">{{ sprintf(__('Portfolio').' '.'%d',$price_plan_item->portfolio_permission_feature )}} </blockquote>
                                </li>
                            @endif

                            @foreach($price_plan_item->plan_features as $feature)
                                <li class="listItem">
                                    <img src="{{asset('assets/landlord/frontend/img/icon/grenIcon.svg')}}" class="icon" alt="icon">
                                    <blockquote class="priceTag">{!! __(str_replace('_', ' ', ucwords($feature->feature_name)))  !!}</blockquote>
                                </li>
                             @endforeach

                        </ul>
                        <div class="price-all-feature">
                            <a href="{{route('landlord.frontend.plan.view',$price_plan_item->id)}}" class="btn-feature-view">{{__('View All Features')}}</a>
                        </div>
                        <div class="btn-wrapper mt-40">
                            @if($price_plan_item->has_trial == true)
                                <div class="d-flex justify-content-center">
                                    <a href="{{route('landlord.frontend.plan.order',$price_plan_item->id)}}" class="cmn-btn cmn-btn-outline-one color-one w-100 mx-1">
                                        {{  $data['button_text']}} </a>

                                    <a href="{{route('landlord.frontend.plan.view',[$price_plan_item->id, 'trial'])}}" class="cmn-btn cmn-btn-outline-one color-one w-100 mx-1">
                                       {{$data['trial_text']}}</a>
                                </div>
                            @else
                                <a href="{{route('landlord.frontend.plan.order',$price_plan_item->id)}}" class="cmn-btn1 w-100">{{$price_plan_item->price == 0 ? __('Free Package') : $data['button_text']}}</a>
                            @endif

                        </div>
                    </div>
                </div>
                @endforeach
                </div>
            </div>
        @endforeach
    </div>
</section>




