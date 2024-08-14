@extends('tenant.admin.admin-master')

@section('title')
    {{__('All Event Settings')}}
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12">
                <x-flash-msg/>
                <x-error-msg/>
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">{{__("Event Settings")}}</h4>
                        <form action="{{route('tenant.admin.event.settings')}}" method="POST"
                              enctype="multipart/form-data">
                            @csrf

                    <x-lang-tab>
                        @foreach(\App\Facades\GlobalLanguage::all_languages() as $lang)
                            @php $slug = $lang->slug; @endphp
                            <x-slot :name="$slug">

                            <div class="form-group">
                                <label for="event_map_area_title">{{__('Map Area Title')}}</label>
                                <input type="text" name="event_map_area_{{$slug}}_title" class="form-control"
                                       value="{{get_static_option('event_map_area_'.$slug.'_title')}}">
                            </div>
                            <div class="form-group">
                                <label for="event_chart_area_title">{{__('Chart Area Title')}}</label>
                                <input type="text" name="event_chart_area_{{$slug}}_title" class="form-control"
                                        value="{{get_static_option('event_chart_area_'.$slug.'_title')}}">
                            </div>
                            <div class="form-group">
                                <label for="event_chart_area_title">{{__('Social Area Title')}}</label>
                                <input type="text" name="event_social_area_{{$slug}}_title" class="form-control"
                                       value="{{get_static_option('event_social_area_'.$slug.'_title')}}">
                            </div>
                            <div class="form-group">
                                <label for="event_chart_area_title">{{__('Category Area Title')}}</label>
                                <input type="text" name="event_category_area_{{$slug}}_title" class="form-control"
                                       value="{{get_static_option('event_category_area_'.$slug.'_title')}}">
                            </div>
                            <div class="form-group">
                                <label for="event_chart_area_title">{{__('Tab Description Title')}}</label>
                                <input type="text" name="event_tab_description_{{$slug}}_title" class="form-control"
                                       value="{{get_static_option('event_tab_description_'.$slug.'_title')}}">
                            </div>
                            <div class="form-group">
                                <label for="event_chart_area_title">{{__('Tab Comment Title')}}</label>
                                <input type="text" name="event_tab_comment_{{$slug}}_title" class="form-control"
                                       value="{{get_static_option('event_tab_comment_'.$slug.'_title')}}">
                            </div>
                            <div class="form-group">
                                <label for="event_chart_area_title">{{__('Tab Book Title')}}</label>
                                <input type="text" name="event_tab_book_{{$slug}}_title" class="form-control"
                                       value="{{get_static_option('event_tab_book_'.$slug.'_title')}}">
                            </div>

                            </x-slot>
                        @endforeach
                    </x-lang-tab>

                            <div class="form-group">
                                <label for="navbar_button">{{__('Show/Hide Event Map Area')}}</label>
                                <label class="switch">
                                    <input type="checkbox" name="event_map_area_show_hide"
                                           @if(!empty(get_static_option('event_map_area_show_hide'))) checked @endif >
                                    <span class="slider"></span>
                                </label>
                            </div>

                            <div class="form-group">
                                <label for="navbar_button">{{__('Show/Hide Event Chart Area')}}</label>
                                <label class="switch">
                                    <input type="checkbox" name="event_chart_area_show_hide"
                                           @if(!empty(get_static_option('event_chart_area_show_hide'))) checked @endif >
                                    <span class="slider"></span>
                                </label>
                            </div>


                            <div class="form-group">
                                <label for="navbar_button">{{__('Show/Hide Event Social Area')}}</label>
                                <label class="switch">
                                    <input type="checkbox" name="event_social_area_show_hide"
                                           @if(!empty(get_static_option('event_social_area_show_hide'))) checked @endif >
                                    <span class="slider"></span>
                                </label>
                            </div>

                            <div class="form-group">
                                <label for="navbar_button">{{__('Show/Hide Category Area')}}</label>
                                <label class="switch">
                                    <input type="checkbox" name="event_category_area_show_hide"
                                           @if(!empty(get_static_option('event_category_area_show_hide'))) checked @endif >
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="navbar_button">{{__('Show/Hide related Area')}}</label>
                                <label class="switch">
                                    <input type="checkbox" name="event_related_area_show_hide"
                                           @if(!empty(get_static_option('event_related_area_show_hide'))) checked @endif >
                                    <span class="slider"></span>
                                </label>
                            </div>

                            <button id="update" type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
