@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('Color Settings')}}
@endsection

@section('style')
    <x-colorpicker.css/>
@endsection

@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-5">{{__('Color Settings')}}</h4>
                <x-error-msg/>
                <x-flash-msg/>
                <form class="forms-sample" method="post"
                      action="{{route(route_prefix().'admin.general.color.settings')}}">
                    @csrf

                    @if(!tenant())
                        <x-colorpicker.input value="{{get_static_option('main_color_one','#EA6249')}}"
                                             name="main_color_one" label="{{__('Site Main Color One')}}"/>

                        <x-colorpicker.input value="{{get_static_option('main_color_one_rgb','216, 83, 58')}}"
                                             name="main_color_one_rgb" label="{{__('Site Main Color One RGB')}}"/>

                        <x-colorpicker.input value="{{get_static_option('main_color_two','#524EB7')}}"
                                             name="main_color_two" label="{{__('Site Main Color Two')}}"/>

                        <x-colorpicker.input value="{{get_static_option('main_color_two_rba','82, 78, 183')}}"
                                             name="main_color_two_rba" label="{{__('Site Main Color Two RBA')}}"/>

                        <x-colorpicker.input value="{{get_static_option('main_color_three','#599a8d')}}"
                                             name="main_color_three" label="{{__('Site Main Color Three')}}"/>

                        <x-colorpicker.input value="{{get_static_option('heading_color','#22211F')}}"
                                             name="heading_color" label="{{__('Site Heading Color')}}"/>

                        <x-colorpicker.input value="{{get_static_option('heading_color_rgb','82, 78, 183')}}"
                                             name="heading_color_rgb" label="{{__('Site Heading Color Rgb')}}"/>

                        <x-colorpicker.input value="{{get_static_option('secondary_color','#FBA260')}}"
                                             name="secondary_color" label="{{__('Site Secondary Color')}}"/>

                        <x-colorpicker.input value="{{get_static_option('bg_light_one','#F5F9FE')}}" name="bg_light_one"
                                             label="{{__('Site Background Light Color One')}}"/>

                        <x-colorpicker.input value="{{get_static_option('bg_light_two','#FEF8F3')}}" name="bg_light_two"
                                             label="{{__('Site Background Light Color Two')}}"/>

                        <x-colorpicker.input value="{{get_static_option('bg_dark_one','#040A1B')}}" name="bg_dark_one"
                                             label="{{__('Site Background Dark Color One')}}"/>

                        <x-colorpicker.input value="{{get_static_option('bg_dark_two','#22253F')}}" name="bg_dark_two"
                                             label="{{__('Site Background Dark Color Two')}}"/>

                        <x-colorpicker.input value="{{get_static_option('paragraph_color','#555454')}}"
                                             name="paragraph_color" label="{{__('Site Paragraph Color One')}}"/>

                        <x-colorpicker.input value="{{get_static_option('paragraph_color_two','#475467')}}"
                                             name="paragraph_color_two" label="{{__('Site Paragraph Color Two')}}"/>

                        <x-colorpicker.input value="{{get_static_option('paragraph_color_three','#D0D5DD')}}"
                                             name="paragraph_color_three" label="{{__('Site Paragraph Color Three')}}"/>

                        <x-colorpicker.input value="{{get_static_option('paragraph_color_four','#344054')}}"
                                             name="paragraph_color_four" label="{{__('Site Paragraph Color Four')}}"/>

                    @endif

                    @if(tenant())
                        <div class="row">
                            @php
                                $tenant_default_theme = get_static_option('tenant_default_theme');
                            @endphp

                            @if(!empty($tenant_default_theme))
                                @php
                                    $newTenantTheme = strtolower($tenant_default_theme);
                                    if($newTenantTheme == "article-listing"){
                                        $newTenantTheme = "knowledgebase";
                                    }
                                @endphp
                                @include("landlord.admin.general-settings.tenant.theme.colors.color-settings-theme-{$newTenantTheme}",['suffix'=> $newTenantTheme])
                            @endif

                            {{--                              @if($tenant_default_theme == 'donation')--}}
                            {{--                                 @include('landlord.admin.general-settings.tenant.theme.colors.color-settings-theme-donnation',['suffix'=> 'donation'])--}}
                            {{--                              @endif--}}

                            {{--                              @if($tenant_default_theme == 'job-find')--}}
                            {{--                                 @include('landlord.admin.general-settings.tenant.theme.colors.color-settings-theme-job',['suffix'=> 'job'])--}}
                            {{--                              @endif--}}

                            {{--                              @if($tenant_default_theme == 'event')--}}
                            {{--                                 @include('landlord.admin.general-settings.tenant.theme.colors.color-settings-theme-event',['suffix'=> 'event'])--}}
                            {{--                              @endif--}}

                            {{--                              @if($tenant_default_theme == 'support-ticketing')--}}
                            {{--                                 @include('landlord.admin.general-settings.tenant.theme.colors.color-settings-theme-support-ticket',['suffix'=> 'support_ticket'])--}}
                            {{--                              @endif--}}

                            {{--                              @if($tenant_default_theme == 'eCommerce')--}}
                            {{--                                @include('landlord.admin.general-settings.tenant.theme.colors.color-settings-theme-ecommerce',['suffix'=> 'ecommerce'])--}}
                            {{--                              @endif--}}

                            {{--                              @if($tenant_default_theme == 'article-listing')--}}
                            {{--                                 @include('landlord.admin.general-settings.tenant.theme.colors.color-settings-theme-knowledgebase',['suffix'=> 'knowledgebase'])--}}
                            {{--                              @endif--}}

                            {{--                              @if($tenant_default_theme == 'agency')--}}
                            {{--                                @include('landlord.admin.general-settings.tenant.theme.colors.color-settings-theme-agency',['suffix'=> 'agency'])--}}
                            {{--                              @endif--}}

                            {{--                            @if($tenant_default_theme == 'newspaper')--}}
                            {{--                                @include('landlord.admin.general-settings.tenant.theme.colors.color-settings-theme-newspaper',['suffix'=> 'newspaper'])--}}
                            {{--                            @endif--}}

                            {{--                            @if($tenant_default_theme == 'construction')--}}
                            {{--                                @include('landlord.admin.general-settings.tenant.theme.colors.color-settings-theme-construction',['suffix'=> 'construction'])--}}
                            {{--                            @endif--}}

                            {{--                            @if($tenant_default_theme == 'consultancy')--}}
                            {{--                                @include('landlord.admin.general-settings.tenant.theme.colors.color-settings-theme-consultancy',['suffix'=> 'consultancy'])--}}
                            {{--                            @endif--}}

                            {{--                            @if($tenant_default_theme == 'wedding')--}}
                            {{--                                @include('landlord.admin.general-settings.tenant.theme.colors.color-settings-theme-wedding',['suffix'=> 'wedding'])--}}
                            {{--                            @endif--}}

                            {{--                            @if($tenant_default_theme == 'photography')--}}
                            {{--                                @include('landlord.admin.general-settings.tenant.theme.colors.color-settings-theme-photography',['suffix'=> 'photography'])--}}
                            {{--                            @endif--}}

                            {{--                            @if($tenant_default_theme == 'portfolio')--}}
                            {{--                                @include('landlord.admin.general-settings.tenant.theme.colors.color-settings-theme-portfolio',['suffix'=> 'portfolio'])--}}
                            {{--                            @endif--}}

                            {{--                            @if($tenant_default_theme == 'software-business')--}}
                            {{--                                @include('landlord.admin.general-settings.tenant.theme.colors.color-settings-theme-software-business',['suffix'=> 'software'])--}}
                            {{--                            @endif--}}

                            {{--                            @if($tenant_default_theme == 'barber-shop')--}}
                            {{--                                @include('landlord.admin.general-settings.tenant.theme.colors.color-settings-theme-barber-shop',['suffix'=> 'barber_shop'])--}}
                            {{--                            @endif--}}

                            {{--                            @if($tenant_default_theme == 'hotel-booking')--}}
                            {{--                                @include('landlord.admin.general-settings.tenant.theme.colors.color-settings-theme-hotel-booking',['suffix'=> 'barber_shop'])--}}
                            {{--                            @endif--}}

                        </div>
                    @endif

                    <button type="submit" class="btn btn-gradient-primary me-2">{{__('Save Changes')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <x-colorpicker.js/>
@endsection

