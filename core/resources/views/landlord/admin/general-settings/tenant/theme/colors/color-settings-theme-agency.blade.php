<div class="col-2">
    <h4 class="mb-4">{{__('Theme (Agency)')}}</h4>

    <x-colorpicker.input value="{{get_static_option($suffix.'_main_color_one','#ffd338')}}" name="{{$suffix}}_main_color_one" label="{{__('Site Main Color One')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_main_color_one_rgb','255, 211, 56')}}" name="{{$suffix}}_main_color_one_rgb" label="{{__('Site Main Color One RGB')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_agency_section_bg','#FFFCF4')}}" name="{{$suffix}}_agency_section_bg" label="{{__('Site Section Background')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_agency_section_bg_2','#6368E5')}}" name="{{$suffix}}_agency_section_bg_2" label="{{__('Site Section Background Two')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_agency_section_bg_3','#141414')}}" name="{{$suffix}}_agency_section_bg_3" label="{{__('Site Section Background Three')}}"/>

    <x-colorpicker.input value="{{get_static_option($suffix.'_heading_color','#1D2635')}}" name="{{$suffix}}_heading_color" label="{{__('Site Heading Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_body_color','#777D86')}}" name="{{$suffix}}_body_color" label="{{__('Body Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_light_color','#777D86')}}" name="{{$suffix}}_light_color" label="{{__('Site Light Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_review_color','#FABE50')}}" name="{{$suffix}}_review_color" label="{{__('Site Review Color')}}"/>

</div>

