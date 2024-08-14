<div class="col-2">
    <h4 class="mb-4">{{__('Theme (Photography)')}}</h4>

    <x-colorpicker.input value="{{get_static_option($suffix.'_main_color_one')}}" name="{{$suffix}}_main_color_one" label="{{__('Site Main Color One')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_main_color_one_rgb','248, 79, 140')}}" name="{{$suffix}}_main_color_one_rgb" label="{{__('Site Main Color One RGB')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_main_color_two','#ff7a03')}}" name="{{$suffix}}_main_color_two" label="{{__('Site Main Color Two')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_main_color_two_rgb','255, 122, 3')}}" name="{{$suffix}}_main_color_two_rgb" label="{{__('Site Main Color Two RGB')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_secondary_color','#fefa3d')}}" name="{{$suffix}}_secondary_color" label="{{__('Site Secondary Color ')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_secondary_color_rgb','254, 250, 61')}}" name="{{$suffix}}_secondary_color_rgb" label="{{__('Site Secondary Color RGB')}}"/>

    <x-colorpicker.input value="{{get_static_option($suffix.'_section_bg','#353543')}}" name="{{$suffix}}_section_bg" label="{{__('Photography Section Background Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_section_bg_2','#DDDDDD')}}" name="{{$suffix}}_section_bg_2" label="{{__('Photography Section Background Two Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_section_bg_3','#F9F9F9')}}" name="{{$suffix}}_section_bg_3" label="{{__('Photography Section Background Three Color')}}"/>

    <x-colorpicker.input value="{{get_static_option($suffix.'_white','#ffffff')}}" name="{{$suffix}}_white" label="{{__('Site White Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_white_rgb','255, 255, 255')}}" name="{{$suffix}}_white_rgb" label="{{__('Site White RGB Color')}}"/>

    <x-colorpicker.input value="{{get_static_option($suffix.'_black','#000')}}" name="{{$suffix}}_black" label="{{__('Site Black Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_black_rgb','0, 0, 0')}}" name="{{$suffix}}_black_rgb" label="{{__('Site Black RGB Color')}}"/>

    <x-colorpicker.input value="{{get_static_option($suffix.'_border_color','#ebebeb')}}" name="{{$suffix}}_border_color" label="{{__('Border Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_border_color_two','#eff0f1')}}" name="{{$suffix}}_border_color_two" label="{{__('Border Color Two')}}"/>

   <x-colorpicker.input value="{{get_static_option($suffix.'_success_color','#2FAB73')}}" name="{{$suffix}}_success_color" label="{{__('Success Color')}}"/>
   <x-colorpicker.input value="{{get_static_option($suffix.'_delete_color','#e00000')}}" name="{{$suffix}}_delete_color" label="{{__('Delete Color')}}"/>

    <x-colorpicker.input value="{{get_static_option($suffix.'_heading_color','#1D2635')}}" name="{{$suffix}}_heading_color" label="{{__('Site Heading Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_body_color','#8a8f96')}}" name="{{$suffix}}_body_color" label="{{__('Body Color')}}"/>

    <x-colorpicker.input value="{{get_static_option($suffix.'_paragraph_color','#666')}}" name="{{$suffix}}_paragraph_color" label="{{__('Site Paragraph Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_paragraph_color_two','#5e4c47')}}" name="{{$suffix}}_paragraph_color_two" label="{{__('Site Paragraph Color Two')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_light_color','#8a8f96')}}" name="{{$suffix}}_light_color" label="{{__('Site Light Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_review_color','#FABE50')}}" name="{{$suffix}}_review_color" label="{{__('Site Review Color')}}"/>

</div>

