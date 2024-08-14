@extends(route_prefix().'admin.admin-master')
@section('title') {{__('Typography Settings')}} @endsection

@section('style')
    <style>
        .typo_admin .nice-select {
            line-height: 25px !important;
        }
    </style>
    <link rel="stylesheet" href="{{global_asset('assets/landlord/admin/css/nice-select.css')}}">
@endsection

@section('content')
    <div class="col-12 grid-margin stretch-card typo_admin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-5">{{__('Typography Identity')}}</h4>
                <x-error-msg/>
                <x-flash-msg/>

                @php
                    $scanned_directory = [];
                    if(tenant()){

                        if(is_dir('assets/tenant/frontend/custom-fonts/'.tenant()->id)){
                            $scanned_directory =  array_diff(scandir('assets/tenant/frontend/custom-fonts/'.tenant()->id), array('..', '.'));
                        }

                        }else{
                            $scanned_directory =  array_diff(scandir('assets/landlord/frontend/custom-fonts'), array('..', '.'));
                        }

                        foreach ($scanned_directory as $item) {
                                $body_fonts[] = $item;
                        }
                @endphp

                <div class="custom_font_parent mb-3">
                    <div class="row">
                        <div class="col-lg-6">
                            <h4 class="header-title mb-2">{{ __('Set Heading and Body Font') }}</h4>

                            <form action="{{ route(route_prefix().'admin.set.custom.font') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group mt-2">
                                    <label for="">{{__('Set Heading Font')}}</label>
                                    <select name="custom_heading_font" id="" class="form-control" >
                                        @foreach($body_fonts ?? [] as $font)
                                            @php
                                                $explode = explode('.',$font);
                                                $font_name = current($explode);
                                            @endphp
                                            <option value="{{$font_name}}" {{ get_static_option('custom_heading_font') == $font_name ? 'selected' : '' }}>{{ $font_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="">{{__('Set Body Font')}}</label>
                                    <select name="custom_body_font" id="" class="form-control" {{ get_static_option('custom_body_font') }}>
                                        @foreach($body_fonts ?? [] as $font)
                                            @php
                                                $explode = explode('.',$font);
                                                $font_name = current($explode);
                                            @endphp
                                            <option value="{{$font_name}}" {{ get_static_option('custom_body_font') == $font_name ? 'selected' : '' }}>{{ $font_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-info btn-md">{{__('Set Custom Font ')}}</button>
                            </form>

                        </div>
                        <div class="col-lg-6">
                            <form action="{{ route(route_prefix().'admin.add.custom.font') }}" method="POST" enctype="multipart/form-data">

                                <!-- custom font -->
                                <div class="custom_font_upload">
                                    <h4 class="header-title mb-2">{{ __('Upload New Font (single or multiple)') }}</h4>
                                    @csrf
                                    <input type="file" name="files[]" placeholder="Choose single or multiple files" class="btn btn-primary" multiple><br>

                                    <small class="text-danger">{{ __(' allowed file format: ttf, woff, woff2, eot')  }}</small><br><br>
                                    <small class="text-dark">{{ __('Please convert your zip file from this link for supporting extensions')  }}</small> :
                                    <small><a href="https://transfonter.org/" target="_blank">{{ __('https://transfonter.org/') }}</a></small><br>
                                    <button type="submit"  class="btn btn-info mt-4 pr-4 pl-4">{{__('Upload')}}</button>
                                </div>
                            </form>
                        </div>

                        <div class="col-lg-12">
                            @if(count($scanned_directory) > 0)
                                <div class="table-responsive mt-5 mb-3">
                                    <h4 class="header-title mb-2">{{ __('All Custom Fonts') }}</h4>
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th scope="col">{{__('SL#')}}</th>
                                            <th scope="col">{{__('Name')}}</th>
                                            <th scope="col">{{__('Action')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($scanned_directory ?? [] as $data)
                                            @php
                                                $explode = explode('.',$data);
                                                $name_with_extension = count($explode) > 1 ? current($explode). '.'.end($explode) : current($explode);
                                            @endphp

                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$explode[0]}}</td>
                                                <td>
                                                    <a href="{{route(route_prefix().'admin.custom.font.delete',$name_with_extension)}}" class="btn btn-danger btn-sm">{{__('Delete')}}</a>
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                           @endif

                        </div>
                    </div>
                </div>


                    <form class="forms-sample" method="post" action="{{route(route_prefix().'admin.general.typography.settings')}}">
                        @csrf

                        <div class="form-group custom_font_title_button">
                            <label for="custom_font">{{__('Use Custom Font')}}</label>
                            <label class="switch">
                                <input type="checkbox" name="custom_font" id="custom_font_switcher" @if(!empty(get_static_option('custom_font'))) checked @endif>
                                <span class="slider"></span>
                            </label>
                        </div>


                        <div class="google_font_container">
                        @if(!tenant())

                            <div class="form-group">
                                <label for="body_font_family">{{__('Font Family')}}</label>
                                <select class="form-control nice-select wide" name="body_font_family" id="body_font_family">
                                    @foreach($google_fonts as $font_family => $font_variant)
                                        <option value="{{$font_family}}" @if($font_family == get_static_option('body_font_family')) selected @endif>{{$font_family}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <br><br>

                            <div class="form-group">
                                <label for="body_font_variant">{{__('Font Variant')}}</label>
                                @php
                                    $font_family_selected = get_static_option('body_font_family') ?? get_static_option('body_font_family') ;
                                    $get_font_family_variants = property_exists($google_fonts,$font_family_selected) ? (array) $google_fonts->$font_family_selected : ['variants' => array('regular')];
                                @endphp
                                <select class="form-control nice-select wide" multiple id="body_font_variant" name="body_font_variant[]">
                                    @foreach($get_font_family_variants['variants'] ?? [] as $variant)
                                        @php
                                            $selected_variant = !empty(get_static_option('body_font_variant')) ? unserialize(get_static_option('body_font_variant')) : [];
                                        @endphp
                                        <option value="{{$variant}}" @if(in_array($variant,$selected_variant)) selected @endif>{{str_replace(['0,','1,'],['','i'],$variant)}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <br><br>

                            <h4 class="header-title margin-top-80">{{__("Heading Typography Settings")}}</h4>
                            <div class="form-group">
                                <label for="heading_font">{{__('Heading Font')}}</label>
                                <label class="switch">
                                    <input type="checkbox" name="heading_font"  @if(!empty(get_static_option('heading_font'))) checked @endif id="heading_font">
                                    <span class="slider"></span>
                                </label>
                                <small>{{__('Use different font family for heading tags ( h1,h2,h3,h4,h5,h6)')}}</small>
                            </div>
                            <br>


                            <div class="form-group">
                                <label for="heading_font_family">{{__('Font Family')}}</label>
                                <select class="form-control nice-select wide" name="heading_font_family" id="heading_font_family">
                                    @foreach($google_fonts as $font_family => $font_variant)
                                        <option value="{{$font_family}}" @if($font_family == get_static_option('heading_font_family')) selected @endif>{{$font_family}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br><br>
                            <div class="form-group margin-top-50">
                                <label for="heading_font_variant">{{__('Font Variant')}}</label>
                                @php
                                    $font_family_selected = get_static_option('heading_font_family') ?? '';
                                    $get_font_family_variants = property_exists($google_fonts,$font_family_selected) ? (array) $google_fonts->$font_family_selected : ['variants' => array('regular')];
                                @endphp
                                <select class="form-control nice-select wide" multiple name="heading_font_variant[]" id="heading_font_variant">
                                    @foreach($get_font_family_variants['variants'] as $variant)
                                        @php
                                            $selected_variant = !empty(get_static_option('heading_font_variant')) ? unserialize(get_static_option('heading_font_variant')) : [];
                                        @endphp
                                        <option value="{{$variant}}"
                                                @if(in_array($variant,$selected_variant)) selected @endif>{{str_replace(['0,','1,'],['','i'],$variant)}}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif


                        @if(tenant())
                            <div class="row">
                                @php
                                        $all_theme = [
                                                  'theme_donation', 'theme_job', 'theme_event','theme_support_ticket', 'theme_ecommerce',
                                                  'theme_knowledgebase','theme_agency','theme_newspaper','theme_construction','theme_consultancy',
                                                  'theme_wedding','theme_photography','theme_portfolio','theme_software','theme_barber_shop', 'theme_hotel_booking'
                                              ];
                                @endphp

                                @foreach($all_theme as $theme)
                                    @include('landlord.admin.general-settings.tenant.theme.typography.'. str_replace('theme_','',$theme), ['suffix' => $theme])
                                @endforeach
                            </div>
                        @endif

                        </div>
                        <br><br><br>
                        <button type="submit" class="btn btn-gradient-primary me-2 typo_submit_button">{{__('Save Changes')}}</button>
                    </form>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{global_asset('assets/landlord/admin/js/jquery.nice-select.min.js')}}"></script>
    <script>
        (function($){
            "use strict";
            $(document).ready(function(){

                @php
                    $typo_suffix = get_static_option('tenant_default_theme');

                    if($typo_suffix == 'eCommerce'){
                        $typo_suffix = 'ecommerce';
                    }elseif ($typo_suffix == 'article-listing'){
                        $typo_suffix = 'knowledgebase';
                     }elseif ($typo_suffix == 'support-ticketing'){
                        $typo_suffix = 'support_ticket';
                    }elseif ($typo_suffix == 'job-find'){
                        $typo_suffix = 'job';

                    }elseif ($typo_suffix == 'software-business'){
                        $typo_suffix = 'software';
                    } elseif ($typo_suffix == 'barber-shop'){
                        $typo_suffix = 'barber_shop';
                    }
                @endphp

                //Heading container code
                let heading_font_family = '{{ get_static_option('heading_font_'.'theme_'.$typo_suffix) }}';

                if(heading_font_family == 'on'){
                    $('.heading_container').removeClass('d-none')
                }else{
                    $('.heading_container').addClass('d-none')
                }

                //Custom Font
                  $('.custom_font_parent').hide();
                 let custom_font_status = '{{get_static_option('custom_font')}}';


                if(custom_font_status == 'on'){
                    $('.custom_font_parent').show();
                    $('.google_font_container').hide();
                }else{
                    $('.custom_font_parent').hide();
                    $('.google_font_container').show();

                }

                $(document).on('change','#custom_font_switcher',function(){
                    let el = $(this);
                    if(el.attr('checked',true)){
                        $('.google_font_container').fadeToggle();
                        $('.custom_font_parent').fadeToggle();
                    }
                })

                $(document).on('change','.body_font_family',function (e) {
                    e.preventDefault();
                    var themeNum = $(this).data('theme');
                    var fontFamily =  $(this).val();

                    $.ajax({
                        url: "{{route(route_prefix().'admin.general.typography.single')}}",
                        type: "POST",
                        data:{
                            _token: "{{csrf_token()}}",
                            font_family : fontFamily,
                            theme : themeNum
                        },
                        success:function (data) {
                            var theme = data.theme;
                            var variantSelector = $('.body_font_variant_'+theme);
                            variantSelector.html('');

                            $.each(data.decoded_fonts.variants,function (index,value) {
                                var nameval = value.replace('0,','');
                                nameval = nameval.replace('1,','i');

                                variantSelector.append('<option value="'+value+'">'+nameval+'</option>');
                            });
                            variantSelector.niceSelect('update');
                        }
                    });
                });
                $(document).on('change','.heading_font_family',function (e) {
                    e.preventDefault();
                    var themeNum = $(this).data('theme');
                    var fontFamily =  $(this).val();

                    $.ajax({
                        url: "{{route(route_prefix().'admin.general.typography.single')}}",
                        type: "POST",
                        data:{
                            _token: "{{csrf_token()}}",
                            font_family : fontFamily,
                            theme : themeNum
                        },
                        success:function (data) {
                            var theme = data.theme;
                            var variantSelector = $('.heading_font_variant_'+theme);
                            console.log(variantSelector)
                            variantSelector.html('');
                            $.each(data.decoded_fonts.variants,function (index,value) {
                                var nameval = value.replace('0,','');
                                nameval = nameval.replace('1,','i');

                                variantSelector.append('<option value="'+value+'">'+nameval+'</option>');
                            });

                            variantSelector.niceSelect('update');
                        }
                    });

                });

                if($('.nice-select').length > 0){
                    $('.nice-select').niceSelect();
                }


                $(document).on('change','input.heading_font',function (e) {

                    if(!$(this).prop('checked')){
                        $('.heading_container').addClass('d-none');
                    }else{
                        $('.heading_container').removeClass('d-none');
                    }
                });

                $(document).on('click','#typography_submit_btn',function (e) {
                    e.preventDefault();
                    $(this).text('Updating...').prop('disabled',true);
                    $(this).parent().trigger("submit");
                })
            });
        }(jQuery));
    </script>
@endsection
