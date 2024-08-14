<div class="newspaper_topbar newspaper_border__bottom">
    <div class="container">
        <div class="newspaper_topbar__wrap">
            <div class="newspaper_topbar__flex">
                <div class="newspaper_topbar__item">
                    <div class="newspaper_topbar__item__flex">
                        <div class="newspaper_topbar__item__icon sidebars-item">
                            <i class="las la-bars"></i>
                        </div>
                        <div class="newspaper_topbar__item__icon search-open">
                            <i class="las la-search"></i>
                        </div>
                    </div>
                </div>
                <div class="newspaper_topbar__item">
                    <div class="newspaper_topbar__item__logo">
                        <a href="{{url('/')}}" class="logo">
                            {!! render_image_markup_by_attachment_id(get_static_option('site_logo'),'logo') !!}
                        </a>
                    </div>
                </div>
                <div class="newspaper_topbar__item">
                    <div class="newspaper_topbar__item__flex">
                        @if(get_static_option('landlord_frontend_language_show_hide'))
                            <!-- Select  -->
                            <div class="select-language newspaper_topbar__item__flex">
                            <div class="select-language newspaper_topbar__item__select">
                                <select class="niceSelect tenant_languages_selector">
                                    @foreach(\App\Facades\GlobalLanguage::all_languages(\App\Enums\StatusEnums::PUBLISH) as $lang)
                                        @php
                                            $exploded = explode('(',$lang->name);
                                        @endphp
                                        <option class="lang_item" value="{{$lang->slug}}" >{{current($exploded)}}</option>
                                    @endforeach
                                </select>
                            </div>
                            </div>
                        @endif
                        <div class="newspaper_topbar__item__social">
                            <ul>
                                <li class=""><a href="{{get_static_option('topbar_facessbook_url')}}" class="social"><i class="lab la-facebook-f icon"></i></a></li>
                                <li class=""> <a href="{{get_static_option('topbar_instagram_url')}}" class="social"><i class="lab la-instagram icon"></i></a></li>
                                <li class=""> <a href="{{get_static_option('topbar_linkedin_url')}}" class="social"><i class="lab la-linkedin-in icon"></i></a></li>
                                <li class=""> <a href="{{get_static_option('topbar_twitter_url')}}" class="social"><i class="lab la-twitter icon"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- SearcBar -->
    <div class="search-bar newspaper_searchBar">
        <form class="menu-search-form" action="{{ route('tenant.frontend.blog.search') }}">
            <div class="search-close"> <i class="las la-times"></i> </div>
            <input class="item-search" type="text"  name="search" placeholder="Search Here.....">
            <button type="submit"> {{__('Search')}} </button>
        </form>
    </div>
    @include('tenant.frontend.partials.pages-portion.navbars.newspaper-partial.sidebar')
</div>
