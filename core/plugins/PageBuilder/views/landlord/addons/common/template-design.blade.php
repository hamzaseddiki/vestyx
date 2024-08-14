@php
    $current_lang = \App\Facades\GlobalLanguage::user_lang_slug();
@endphp
<div class="exploreTemplates" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row align-items-center mb-40">

            <div class="col-md-8 col-sm-6">
                <div class="section-tittle mb-0">
                    {!! get_landlord_modified_title($data['title']) !!}
                </div>
            </div>
            @if(!empty($data['right_text']))
                <div class="col-md-4 col-sm-6">
                    <div class="btn-wrapper mb-20 f-right">
                        <a href="{{$data['right_text_url']}}" target="_blank" class="cmn-btn2">{{$data['right_text']}}
                            <i class="las la-long-arrow-alt-right icon"></i>
                        </a>
                    </div>
                </div>
              @endif
        </div>
        <div class="row">
            <!-- Left -->

            @php
                $all_themes = getAllThemeData();
                $items = $data['items'];
            @endphp

            @foreach($all_themes as $index => $theme)


                @break($loop->index == $items)

                @php
                    $theme_slug = $theme->slug;
                    $theme_data = getIndividualThemeDetails($theme_slug);
                    $theme_image = loadScreenshot($theme_slug);
                    $lang_suffix = '_'.get_user_lang();
                    $theme_name = get_static_option_central($theme_data['slug'].'_theme_name'.$lang_suffix) ;
                    $theme_description = get_static_option_central($theme_data['slug'].'_theme_description'.$lang_suffix);
                    $theme_url = get_static_option_central($theme_data['slug'].'_theme_url');
                    $custom_theme_image = get_static_option_central($theme_data['slug'].'_theme_image');
                     $is_available = get_static_option_central($theme_data['slug'].'_theme_is_available');
                @endphp

                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                        <div class="singleTemplates mb-24  wow fadeInUp" data-wow-delay="0.0s">
                            <div class="templateImg Effect">
                               <img src="{{ !empty($custom_theme_image) ? $custom_theme_image : $theme_image}}" alt="">
                            </div>
                            <div class="templateDetails">
                                <div class="cap">
                                    <h4>
                                        <a class="templateTittle">{{$theme_name}}</a>
                                    </h4>
                                    <p class="templateCap">{{$theme_description}}</p>
                                </div>

                                 @if($is_available == 'on')
                                    <div class="btn-wrapper mb-20">
                                        <a href="{{ $theme_url}}" target="_blank" class="cmn-btn cmn-btns btn__livePreview" >{{$data['bottom_text']}}</a>
                                    </div>
                                   @else
                                    <div class="btn-wrapper mb-20">
                                        <a href="#!" class="cmn-btn cmn-btns coming_soon__btn" >{{__('Not Available')}}</a>
                                    </div>
                                  @endif

                            </div>
                        </div>
                    </div>
             @endforeach
        </div>
    </div>
</div>
