@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('Page Settings')}}
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                <x-error-msg/>
                <x-flash-msg/>
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title my-2">{{__("Page Settings")}}</h4>
                        <form action="{{route(route_prefix().'admin.general.page.settings')}}" method="POST" enctype="multipart/form-data">
                          @csrf
                               <div class="form-group  mt-3">
                                   <label for="site_logo">{{__('Home Page Display')}}</label>
                                   <select name="home_page" class="form-control">
                                       @foreach($all_home_pages as $page)
                                           <option value="{{$page->id}}" @if($page->id == get_static_option('home_page'))  selected @endif >{!! $page->getTranslation('title',get_user_lang()) !!}</option>
                                       @endforeach
                                   </select>
                                   <small class="text-primary">{{__('You can set any page as home page from this section')}}</small>
                               </div>

                            @if(!tenant())
                            <div class="form-group  mt-3">
                                <label for="site_logo">{{__('Price Plan Page Display')}}</label>
                                <select name="pricing_plan" class="form-control">
                                    @foreach($all_home_pages as $page)
                                        <option value="{{$page->id}}" @if($page->id == get_static_option('pricing_plan'))  selected @endif >{!! $page->getTranslation('title',get_user_lang()) !!}</option>
                                    @endforeach
                                </select>
                                <small class="text-primary">{{__('You can set any page as price plan page from this section')}}</small>
                            </div>
                            @endif

                            @if(tenant())
                                @php
                                    $package = tenant()->payment_log()->first()?->package()->first() ?? [];
                                    $all_features = $package->plan_features ?? [];
                                    $check_feature_name = $all_features->pluck('feature_name')->toArray();
                                @endphp
                              @if(in_array('eCommerce',$check_feature_name))
                                    <div class="form-group  mt-3">
                                        <label for="site_logo">{{__('Shop Page Display')}}</label>
                                        <select name="shop_page" class="form-control">
                                            @foreach($all_home_pages as $page)
                                                <option value="{{$page->id}}" @if($page->id == get_static_option('shop_page'))  selected @endif >{!! $page->getTranslation('title',get_user_lang()) !!}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-primary">{{__('You can set any page as shop page from this section')}}</small>
                                    </div>
                                @endif

                            <div class="form-group  mt-3">
                                <label for="site_logo">{{__('Donation Page Display')}}</label>
                                <select name="donation_page" class="form-control">
                                    @foreach($all_home_pages as $page)
                                        <option value="{{$page->id}}" @if($page->id == get_static_option('donation_page'))  selected @endif >{!! $page->getTranslation('title',get_user_lang()) !!}</option>
                                    @endforeach
                                </select>
                                <small class="text-primary">{{__('You can set any page as donation page from this section')}}</small>
                            </div>

                            <div class="form-group  mt-3">
                                <label for="site_logo">{{__('Event Page Display')}}</label>
                                <select name="event_page" class="form-control">
                                    @foreach($all_home_pages as $page)
                                        <option value="{{$page->id}}" @if($page->id == get_static_option('event_page'))  selected @endif >{!! $page->getTranslation('title',get_user_lang()) !!}</option>
                                    @endforeach
                                </select>
                                <small class="text-primary">{{__('You can set any page as event page from this section')}}</small>
                            </div>

                            <div class="form-group  mt-3">
                                <label for="site_logo">{{__('Job Page Display')}}</label>
                                <select name="job_page" class="form-control">
                                    @foreach($all_home_pages as $page)
                                        <option value="{{$page->id}}" @if($page->id == get_static_option('job_page'))  selected @endif >{!! purify_html($page->getTranslation('title',get_user_lang())) !!}</option>
                                    @endforeach
                                </select>
                                <small class="text-primary">{{__('You can set any page as job page from this section')}}</small>
                            </div>

                            <div class="form-group  mt-3">
                                <label for="site_logo">{{__('Knowledgebase Page Display')}}</label>
                                <select name="knowledgebase_page" class="form-control">
                                    @foreach($all_home_pages as $page)
                                        <option value="{{$page->id}}" @if($page->id == get_static_option('knowledgebase_page'))  selected @endif >{!! purify_html($page->getTranslation('title',get_user_lang())) !!}</option>
                                    @endforeach
                                </select>
                                <small class="text-primary">{{__('You can set any page as knowledgebase/article page from this section')}}</small>
                            </div>

                                <div class="form-group  mt-3">
                                    <label for="site_logo">{{__('Terms Condition Page Display')}}</label>
                                    <select name="terms_condition_page" class="form-control">
                                        @foreach($all_home_pages as $page)
                                            @if($page->page_builder != 1 )
                                              <option value="{{$page->id}}" @if($page->id == get_static_option('terms_condition_page'))  selected @endif >{!! purify_html($page->getTranslation('title',get_user_lang())) !!}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <small class="text-primary">{{__('Static Page will show here only')}}</small>
                                </div>

                                <div class="form-group  mt-3">
                                    <label for="site_logo">{{__('Privacy Policy Page Display')}}</label>
                                    <select name="privacy_policy_page" class="form-control">
                                        @foreach($all_home_pages as $page)
                                            @if($page->page_builder != 1 )
                                               <option value="{{$page->id}}" @if($page->id == get_static_option('privacy_policy_page'))  selected @endif >{!! purify_html($page->getTranslation('title',get_user_lang())) !!}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <small class="text-primary">{{__('Static Page will show here only')}}</small>
                                </div>

                            @endif



                            <button type="submit" id="update" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
