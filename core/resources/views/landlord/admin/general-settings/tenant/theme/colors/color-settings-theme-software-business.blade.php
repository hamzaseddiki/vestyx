<div class="col-2">
    <h4 class="mb-4">{{__('Theme (Software)')}}</h4>
    <x-colorpicker.input value="{{get_static_option($suffix.'_main_color_one')}}" name="{{$suffix}}_main_color_one" label="{{__('Site Main Color One')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_main_color_one_rgb','255, 128, 93')}}" name="{{$suffix}}_main_color_one_rgb" label="{{__('Site Main Color One RGB')}}"/>

    <x-colorpicker.input value="{{get_static_option($suffix.'_secondary_color','#452b4e')}}" name="{{$suffix}}_secondary_color" label="{{__('Site Secondary Color ')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_secondary_color_rgb','69, 43, 78')}}" name="{{$suffix}}_secondary_color_rgb" label="{{__('Site Secondary Color RGB')}}"/>

    <x-colorpicker.input value="{{get_static_option($suffix.'_section_bg','#FFBD76')}}" name="{{$suffix}}_section_bg" label="{{__('Software Section Background Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_section_bg_secondary','#452b4e')}}" name="{{$suffix}}_section_bg_secondary" label="{{__('Software Section Background Secondary Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_section_bg_2','#F9F9F9')}}" name="{{$suffix}}_section_bg_2" label="{{__('Software Section Background Two Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_section_bg_3','#f1f1f1')}}" name="{{$suffix}}_section_bg_3" label="{{__('Software Section Background Three Color')}}"/>

    <x-colorpicker.input value="{{get_static_option($suffix.'_white','#ffffff')}}" name="{{$suffix}}_white" label="{{__('Site White Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_white_rgb','255, 255, 255')}}" name="{{$suffix}}_white_rgb" label="{{__('Site White RGB Color')}}"/>

    <x-colorpicker.input value="{{get_static_option($suffix.'_black','#000')}}" name="{{$suffix}}_black" label="{{__('Site Black Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_black_rgb','0, 0, 0')}}" name="{{$suffix}}_black_rgb" label="{{__('Site Black RGB Color')}}"/>

    <x-colorpicker.input value="{{get_static_option($suffix.'_border_color','#ebebeb')}}" name="{{$suffix}}_border_color" label="{{__('Border Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_border_color_two','#eff0f1')}}" name="{{$suffix}}_border_color_two" label="{{__('Border Color Two')}}"/>

   <x-colorpicker.input value="{{get_static_option($suffix.'_success_color','#2FAB73')}}" name="{{$suffix}}_success_color" label="{{__('Success Color')}}"/>

    <x-colorpicker.input value="{{get_static_option($suffix.'_heading_color','#1D2635')}}" name="{{$suffix}}_heading_color" label="{{__('Site Heading Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_body_color','#8a8f96')}}" name="{{$suffix}}_body_color" label="{{__('Body Color')}}"/>

    <x-colorpicker.input value="{{get_static_option($suffix.'_paragraph_color','#666')}}" name="{{$suffix}}_paragraph_color" label="{{__('Site Paragraph Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_paragraph_color_two','#5e4c47')}}" name="{{$suffix}}_paragraph_color_two" label="{{__('Site Paragraph Color Two')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_light_color','#8a8f96')}}" name="{{$suffix}}_light_color" label="{{__('Site Light Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_review_color','#FABE50')}}" name="{{$suffix}}_review_color" label="{{__('Site Review Color')}}"/>
</div>

