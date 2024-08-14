<div class="col-2">
    <h4 class="mb-4">{{__('Theme (Newspaper)')}}</h4>

    <x-colorpicker.input value="{{get_static_option($suffix.'_main_color_one','#f65050')}}" name="{{$suffix}}_main_color_one" label="{{__('Site Main Color One')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_main_color_one_rgb','246, 80, 80')}}" name="{{$suffix}}_main_color_one_rgb" label="{{__('Site Main Color One RGB')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_secondary_color','#FFD203')}}" name="{{$suffix}}_secondary_color" label="{{__('Site Secondary Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_secondary_color_rgb','255, 210, 3')}}" name="{{$suffix}}_secondary_color_rgb" label="{{__('Site Secondary Color RGB')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_newspaper_section_bg','#141414')}}" name="{{$suffix}}_newspaper_section_bg" label="{{__('Site Section Background')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_newspaper_section_bg_2','#F9F9F9')}}" name="{{$suffix}}_newspaper_section_bg_2" label="{{__('Site Section Background Two')}}"/>

    <x-colorpicker.input value="{{get_static_option($suffix.'_border_color','#e9e9e9')}}" name="{{$suffix}}_border_color" label="{{__('Site Border Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_border_color_2','#f3f3f3')}}" name="{{$suffix}}_border_color_2" label="{{__('Site Border Color Two')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_heading_color','#1D2635')}}" name="{{$suffix}}_heading_color" label="{{__('Heading Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_body_color','#777D86')}}" name="{{$suffix}}_body_color" label="{{__('Body Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_light_color','#777D86')}}" name="{{$suffix}}_light_color" label="{{__('Site Light Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_review_color','#FABE50')}}" name="{{$suffix}}_review_color" label="{{__('Site Review Color')}}"/>

</div>

