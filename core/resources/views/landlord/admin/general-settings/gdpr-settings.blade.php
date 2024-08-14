@extends(route_prefix().'admin.admin-master')
@section('title') {{__('GDPR Complain Settings')}} @endsection
@section('style')
    <x-media-upload.css/>
@endsection
@section('content')
@php
 $all_languages = \App\Facades\GlobalLanguage::all_languages(1);
@endphp
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-5">{{__('GDPR Complain Settings')}}</h4>
                <x-error-msg/>
                <x-flash-msg/>

                <form class="forms-sample" method="post" action="{{route(route_prefix().'admin.general.gdpr.settings')}}">
                    @csrf
                   <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            @foreach($all_languages as $key => $lang)
                                <a class="nav-item nav-link @if($key == 0) active @endif" id="nav-home-tab" data-bs-toggle="tab" href="#nav-home-{{$lang->slug}}" role="tab" aria-controls="nav-home" aria-selected="true">{{$lang->name}}</a>
                            @endforeach
                        </div>
                            </nav>
                            <div class="tab-content margin-top-30" id="nav-tabContent">
                                @foreach($all_languages as $key => $lang)
                                    <div class="tab-pane fade @if($key == 0) show active @endif" id="nav-home-{{$lang->slug}}" role="tabpanel" aria-labelledby="nav-home-tab">
                                        <div class="form-group">
                                            <label for="site_gdpr_cookie_{{$lang->slug}}_title">{{__('GDPR Title')}}</label>
                                            <input type="text" name="site_gdpr_cookie_{{$lang->slug}}_title"  class="form-control" value="{{get_static_option('site_gdpr_cookie_'.$lang->slug.'_title')}}" id="site_gdpr_cookie_{{$lang->slug}}_title">
                                        </div>
                                        <div class="form-group">
                                            <label for="site_gdpr_cookie_{{$lang->slug}}_message">{{__('GDPR Message')}}</label>
                                            <textarea name="site_gdpr_cookie_{{$lang->slug}}_message"  class="form-control" rows="5" id="site_gdpr_cookie_{{$lang->slug}}_message">{{get_static_option('site_gdpr_cookie_'.$lang->slug.'_message')}}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="site_gdpr_cookie_{{$lang->slug}}_more_info_label">{{__('GDPR More Info Link Label')}}</label>
                                            <input type="text" name="site_gdpr_cookie_{{$lang->slug}}_more_info_label"  class="form-control" value="{{get_static_option('site_gdpr_cookie_'.$lang->slug.'_more_info_label')}}" id="site_gdpr_cookie_{{$lang->slug}}_more_info_label">
                                        </div>
                                        <div class="form-group">
                                            <label for="site_gdpr_cookie_{{$lang->slug}}_more_info_link">{{__('GDPR More Info Link')}}</label>
                                            <input type="text" name="site_gdpr_cookie_{{$lang->slug}}_more_info_link"  class="form-control" value="{{get_static_option('site_gdpr_cookie_'.$lang->slug.'_more_info_link')}}" id="site_gdpr_cookie_{{$lang->slug}}_more_info_link">
                                            <small class="form-text text-muted">{{__('enter more info link user {url} to point the site address, example: {url}/about , it will be converted to www.yoursite.com/about')}}</small>
                                        </div>
                                        <div class="form-group">
                                            <label for="site_gdpr_cookie_{{$lang->slug}}_accept_button_label">{{__('GDPR Cookie Accept Button Label')}}</label>
                                            <input type="text" name="site_gdpr_cookie_{{$lang->slug}}_accept_button_label"  class="form-control" value="{{get_static_option('site_gdpr_cookie_'.$lang->slug.'_accept_button_label')}}" id="site_gdpr_cookie_{{$lang->slug}}_accept_button_label">
                                        </div>
                                        <div class="form-group">
                                            <label for="site_gdpr_cookie_{{$lang->slug}}_decline_button_label">{{__('GDPR Cookie Decline Button Label')}}</label>
                                            <input type="text" name="site_gdpr_cookie_{{$lang->slug}}_decline_button_label"  class="form-control" value="{{get_static_option('site_gdpr_cookie_'.$lang->slug.'_decline_button_label')}}" id="site_gdpr_cookie_{{$lang->slug}}_decline_button_label">
                                        </div>
                                        <div class="form-group">
                                            <label for="site_gdpr_cookie_{{$lang->slug}}_manage_button_label">{{__('GDPR Cookie Manage Button Label')}}</label>
                                            <input type="text" name="site_gdpr_cookie_{{$lang->slug}}_manage_button_label"  class="form-control" value="{{get_static_option('site_gdpr_cookie_'.$lang->slug.'_manage_button_label')}}" >
                                        </div>
                                        <div class="form-group">
                                            <label for="site_gdpr_cookie_{{$lang->slug}}_manage_title">{{__('GDPR Cookie Manage Title')}}</label>
                                            <input type="text" name="site_gdpr_cookie_{{$lang->slug}}_manage_title"  class="form-control" value="{{get_static_option('site_gdpr_cookie_'.$lang->slug.'_manage_title')}}" >
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-group">
                                <label for="site_gdpr_cookie_enabled"><strong>{{__('GDPR Cookie Enable/Disable')}}</strong></label>
                                <label class="switch yes">
                                    <input type="checkbox" name="site_gdpr_cookie_enabled"  @if(!empty(get_static_option('site_gdpr_cookie_enabled'))) checked @endif id="site_gdpr_cookie_enabled">
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="site_gdpr_cookie_expire">{{__('Cookie Expire')}}</label>
                                <input type="text" name="site_gdpr_cookie_expire"  class="form-control" value="{{get_static_option('site_gdpr_cookie_expire')}}" id="site_gdpr_cookie_expire">
                                <small class="form-text text-muted">{{__('set cookie expire time, eg: 30, means 30days')}}</small>
                            </div>
                            <div class="form-group">
                                <label for="site_gdpr_cookie_delay">{{__('Show Delay')}}</label>
                                <input type="text" name="site_gdpr_cookie_delay"  class="form-control" value="{{get_static_option('site_gdpr_cookie_delay')}}" id="site_gdpr_cookie_delay">
                                <small class="form-text text-muted">{{__('set GDPR cookie delay time, it mean the notification will show after this time. number count as mili seconds. eg: 5000, means 5seconds')}}</small>
                            </div>
                            @php
                                $all_title_fields = get_static_option('site_gdpr_cookie_'.get_user_lang().'_manage_item_title');
                                $all_title_fields = !empty($all_title_fields) ? unserialize($all_title_fields,['class' => false]) : [''];
                            @endphp
                            @foreach($all_title_fields as $index => $title)
                                <div class="iconbox-repeater-wrapper">
                                    <div class="all-field-wrap">
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            @foreach($all_languages as $key => $lang)
                                                <li class="nav-item">
                                                    <a class="nav-link @if($key == 0) active @endif" data-bs-toggle="tab" href="#tab_{{$lang->slug}}_{{$key + $index}}" role="tab"  aria-selected="true">{{$lang->name}}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="tab-content margin-top-30" id="myTabContent">
                                            @foreach($all_languages as $key => $lang)
                                                @php
                                                  $all_title_fields = get_static_option('site_gdpr_cookie_'.$lang->slug.'_manage_item_title');
                                                  $all_title_fields = !empty($all_title_fields) ? unserialize($all_title_fields,['class' => false]) : [''];
                                                  $all_description_fields = get_static_option('site_gdpr_cookie_'.$lang->slug.'_manage_item_description');
                                                  $all_description_fields = !empty($all_description_fields) ? unserialize($all_description_fields,['class' => false]) : [''];
                                                @endphp

                                                <div class="tab-pane fade @if($key == 0) show active @endif" id="tab_{{$lang->slug}}_{{$key + $index}}" role="tabpanel" >
                                                    <div class="form-group">
                                                        <label for="site_gdpr_cookie_{{$lang->slug}}_manage_item_title">{{__('Title')}}</label>
                                                        <input type="text" name="site_gdpr_cookie_{{$lang->slug}}_manage_item_title[]" class="form-control" value="{{$all_title_fields[$index] ?? ''}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="site_gdpr_cookie_{{$lang->slug}}_manage_item_description">{{__('Description')}}</label>
                                                        <textarea name="site_gdpr_cookie_{{$lang->slug}}_manage_item_description[]" class="form-control max-height-120" cols="30" rows="5">{{$all_description_fields[$index] ?? ''}}</textarea>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="action-wrap">
                                            <span class="add"><i class="las la-plus"></i></span>
                                            <span class="remove"><i class="las la-trash"></i></span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach



                    <button type="submit" class="btn btn-gradient-primary me-2">{{__('Save Changes')}}</button>
                </form>
            </div>
        </div>
    </div>
    <x-media-upload.markup/>
@endsection
@section('scripts')
    <x-media-upload.js/>
    <script>
        $(document).on('click','.all-field-wrap .action-wrap .add',function (e){
            e.preventDefault();
            var el = $(this);
            var parent = el.parent().parent();
            var container = $('.all-field-wrap');
            var clonedData = parent.clone();
            var containerLength = container.length;
            clonedData.find('#myTab').attr('id','mytab_'+containerLength);
            clonedData.find('#myTabContent').attr('id','myTabContent_'+containerLength);
            var allTab =  clonedData.find('.tab-pane');
            allTab.each(function (index,value){
                var el = $(this);
                var oldId = el.attr('id');
                el.attr('id',oldId+containerLength);
            });
            var allTabNav =  clonedData.find('.nav-link');
            allTabNav.each(function (index,value){
                var el = $(this);
                var oldId = el.attr('href');
                el.attr('href',oldId+containerLength);
            });

            parent.parent().append(clonedData);

            if (containerLength > 0){
                parent.parent().find('.remove').show(300);
            }
            parent.parent().find('.iconpicker-popover').remove();
            parent.parent().find('.icp-dd').iconpicker();

        });

        $(document).on('click','.all-field-wrap .action-wrap .remove',function (e){
            e.preventDefault();
            var el = $(this);
            var parent = el.parent().parent();
            var container = $('.all-field-wrap');

            if (container.length > 1){
                el.show(300);
                parent.hide(300);
                parent.remove();
            }else{
                el.hide(300);
            }
        });
        $('.icp-dd').iconpicker();
        $('body').on('iconpickerSelected','.icp-dd', function (e) {
            var selectedIcon = e.iconpickerValue;
            $(this).parent().parent().children('input').val(selectedIcon);
            $('body .dropdown-menu.iconpicker-container').removeClass('show');
        });
    </script>
@endsection
