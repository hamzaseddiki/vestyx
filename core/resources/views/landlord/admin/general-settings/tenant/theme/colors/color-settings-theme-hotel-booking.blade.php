<div class="col-2">
    <h4 class="mb-4">{{__('Theme (Hotel Booking)')}}</h4>

    @php
        $suffix = str_replace('-','_',$suffix);
    @endphp

    <x-colorpicker.input value="{{get_static_option($suffix.'_main_color_one','#1E84FE')}}" name="{{$suffix}}_main_color_one" label="{{__('Site Main Color One')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_main_color_one_rgb','rgb(30, 132, 254)')}}" name="{{$suffix}}_main_color_one_rgb" label="{{__('Site Main Color One RGB')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_secondary_color','#FF8C32')}}" name="{{$suffix}}_secondary_color" label="{{__('Site Secondary Color ')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_secondary_color_rgb','rgb(255, 140, 50)')}}" name="{{$suffix}}_secondary_color_rgb" label="{{__('Site Secondary Color RGB')}}"/>

    <x-colorpicker.input value="{{get_static_option($suffix.'_section_bg_1','#F6F7F8')}}" name="{{$suffix}}_section_bg_1" label="{{__('Section Background Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_section_bg_2','#F2F4F7')}}" name="{{$suffix}}_section_bg_2" label="{{__('Secondary Background Two Color')}}"/>

    <x-colorpicker.input value="{{get_static_option($suffix.'_footer_bg_1','#27282B')}}" name="{{$suffix}}_footer_bg_1" label="{{__('Footer background Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_footer_bg_2','#1E84FE')}}" name="{{$suffix}}_footer_bg_2" label="{{__('Footer background Color Two')}}"/>

    <x-colorpicker.input value="{{get_static_option($suffix.'_copyright_bg_1','#323336')}}" name="{{$suffix}}_copyright_bg_1" label="{{__('Copyright Background Color')}}"/>

    <x-colorpicker.input value="{{get_static_option($suffix.'_gray_color','#f8f8f8')}}" name="{{$suffix}}_gray_color" label="{{__('Gray White Color')}}"/>

    <x-colorpicker.input value="{{get_static_option($suffix.'_input_color','#EAECF0')}}" name="{{$suffix}}_input_color" label="{{__('Input Color')}}"/>

   <x-colorpicker.input value="{{get_static_option($suffix.'_success_color','#00C897')}}" name="{{$suffix}}_success_color" label="{{__('Success Color')}}"/>
   <x-colorpicker.input value="{{get_static_option($suffix.'_success_color_rgb','rgb(0, 200, 151)')}}" name="{{$suffix}}_success_color_rgb" label="{{__('Success Color RGB')}}"/>

    <x-colorpicker.input value="{{get_static_option($suffix.'_heading_color','#1D2635')}}" name="{{$suffix}}_heading_color" label="{{__('Site Heading Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_body_color','#667085')}}" name="{{$suffix}}_body_color" label="{{__('Body Color')}}"/>

    <x-colorpicker.input value="{{get_static_option($suffix.'_paragraph_color','#667085')}}" name="{{$suffix}}_paragraph_color" label="{{__('Site Paragraph Color')}}"/>

    <x-colorpicker.input value="{{get_static_option($suffix.'_light_color','#999')}}" name="{{$suffix}}_light_color" label="{{__('Site Light Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_review_color','#FABE50')}}" name="{{$suffix}}_review_color" label="{{__('Site Review Color')}}"/>

</div>

