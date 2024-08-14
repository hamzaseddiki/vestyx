<div class="col-2">
    <h4 class="mb-4">{{__('Theme (Knowledgebase or Article)')}}</h4>

    <x-colorpicker.input value="{{get_static_option($suffix.'_main_color_one','#5459E8')}}" name="{{$suffix}}_main_color_one" label="{{__('Site Main Color One')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_main_color_one_rgb','84, 89, 232')}}" name="{{$suffix}}_main_color_one_rgb" label="{{__('Site Main Color One RGB')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_main_color_two','#FF8339')}}" name="{{$suffix}}_main_color_two" label="{{__('Site Main Color Two')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_main_color_two_rba','255, 131, 57')}}" name="{{$suffix}}_main_color_two_rba" label="{{__('Site Main Color Two RBA')}}"/>

    <x-colorpicker.input value="{{get_static_option($suffix.'_heading_color','#12244C')}}" name="{{$suffix}}_heading_color" label="{{__('Site Heading Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_heading_color_rgb','18, 36, 76')}}" name="{{$suffix}}_heading_color_rgb" label="{{__('Site Heading Color RGB')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_heading_color_two','#07061A')}}" name="{{$suffix}}_heading_color_two" label="{{__('Site Heading Color Two')}}"/>

    <x-colorpicker.input value="{{get_static_option($suffix.'_btn_color_one','##C62F6')}}" name="{{$suffix}}_btn_color_one" label="{{__('Site Button Color One')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_btn_color_two','#FF5229')}}" name="{{$suffix}}_btn_color_two" label="{{__('Site Button Color Two')}}"/>

    <x-colorpicker.input value="{{get_static_option($suffix.'_section_bg_one','#5459E8')}}" name="{{$suffix}}_section_bg_one" label="{{__('Site Section Background One')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_section_bg_two','#F9F9F9')}}" name="{{$suffix}}_section_bg_two" label="{{__('Site Section Background Two')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_scroll_bar_bg','#F0F0F0')}}" name="{{$suffix}}_scroll_bar_bg" label="{{__('Scroll Bar Background')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_scroll_bar_color','#c5c5c5')}}" name="{{$suffix}}_scroll_bar_color" label="{{__('Scrollbar Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_paragraph_color','#717171')}}" name="{{$suffix}}_paragraph_color" label="{{__('Site Paragraph Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_paragraph_color_two','#475467')}}" name="{{$suffix}}_paragraph_color_two" label="{{__('Site Paragraph Color')}}"/>

</div>

