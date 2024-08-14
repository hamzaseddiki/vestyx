@extends(route_prefix().'admin.admin-master')
@section('title') {{__('Basic Settings')}} @endsection
@section('style')
    <x-media-upload.css/>
@endsection
@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-5">{{__('Basic Settings')}}</h4>
                <x-error-msg/>
                <x-flash-msg/>
                <form class="forms-sample" method="post" action="{{route(route_prefix().'admin.general.basic.settings')}}">
                    @csrf
                    <x-lang-tab>
                        @foreach(\App\Facades\GlobalLanguage::all_languages(1) as $lang)
                        @php $slug = $lang->slug; @endphp
                        <x-slot :name="$slug">
                            <x-fields.input type="text" value="{{get_static_option('site_'.$lang->slug.'_title')}}" name="site_{{ $lang->slug}}_title" label="{{__('Site Title')}}"/>
                            <x-fields.input type="text" value="{{get_static_option('site_'.$lang->slug.'_tag_line')}}" name="site_{{ $lang->slug}}_tag_line" label="{{__('Site Tag Line')}}"/>
                            <x-fields.textarea type="text" value="{{get_static_option('site_'.$lang->slug.'_footer_copyright_text')}}" name="site_{{ $lang->slug}}_footer_copyright_text" label="{{__('Footer Copyright Text')}}" info="{{__('{copy} Will replace by & and {year} will be replaced by current year.')}}"/>
                        </x-slot>
                        @endforeach
                    </x-lang-tab>

                    @if(!tenant())
                        <x-fields.select name="mysql_database_engine" title="{{__('Mysql Database Engine')}}" info="{{__('By default it is null, if you have mysql database engine you can set here, unless leave this as (Null)')}}">
                            <option value="null" {{ get_static_option('mysql_database_engine') == 'null' ? 'selected' : '' }}>{{__('NULL')}}</option>
                            <option value="InnoDB" {{ get_static_option('mysql_database_engine') == 'InnoDB' ? 'selected' : '' }}>{{__('InnoDB')}}</option>
                        </x-fields.select>

                        <x-fields.select name="date_display_style" class="date_display_style" title="{{__('Date Display Style')}}">
                            <option value="style_one" {{ get_static_option('date_display_style') == 'style_one' ? 'selected' : ' ' }}>{{__('01-02-2000') }}</option>
                            <option value="style_two" {{ get_static_option('date_display_style') == 'style_two' ? 'selected' : ' ' }}>{{__('01 Feb, 2000')}}</option>
                            <option value="style_three" {{ get_static_option('date_display_style') == 'style_three' ? 'selected' : ' ' }}>{{__('2000/02/01')}}</option>
                            <option value="style_four" {{ get_static_option('date_display_style') == 'style_four' ? 'selected' : ' ' }}>{{__('2000-02-01')}}</option>
                        </x-fields.select>
                    @endif


                    <x-fields.switcher value="{{get_static_option('dark_mode_for_admin_panel')}}" name="dark_mode_for_admin_panel" label="{{__('Enable/Disable Dark Mode For Admin Panel')}}"/>
                    @if(!tenant())
                    <x-fields.switcher value="{{get_static_option('mouse_cursor_effect_status')}}" name="mouse_cursor_effect_status" label="{{__('Enable/Disable Mouse Cursor Effect')}}"/>
                    <x-fields.switcher value="{{get_static_option('section_title_extra_design_status')}}" name="section_title_extra_design_status" label="{{__('Enable/Disable Section Title Extra Design')}}" link="https://prnt.sc/cUcSkxzzFCHl"/>
                    @endif
                    <x-fields.switcher value="{{get_static_option('maintenance_mode')}}" name="maintenance_mode" label="{{__('Enable/Disable Maintenance Mode')}}"/>
                    <x-fields.switcher value="{{get_static_option('site_force_ssl_redirection')}}" name="site_force_ssl_redirection" label="{{__('Enable/Disable Site SSL Redirection')}}" info="{{__('This means, you want your website ssl will be Https or Http')}}"/>
                    <x-fields.switcher value="{{get_static_option('user_email_verify_status')}}" name="user_email_verify_status" label="{{__('Disable/Enable User Email Verify')}}" info="{{__('if you keep it no, it will allow user to register without being ask for email verify.')}}"/>
                    <x-fields.switcher value="{{get_static_option('table_list_data_orderable_status')}}" name="table_list_data_orderable_status" label="{{__('Enable/Disable of Sorting Table List Data')}}" info="{{__('Enable means you can sort table data by clicking on the table header')}}" link="https://prnt.sc/CIBN4wxnAFtx"/>

                    <button type="submit" class="btn btn-gradient-primary me-2">{{__('Save Changes')}}</button>
                </form>
            </div>
        </div>
    </div>
    <x-media-upload.markup/>
@endsection
@section('scripts')
    <x-media-upload.js/>
@endsection
