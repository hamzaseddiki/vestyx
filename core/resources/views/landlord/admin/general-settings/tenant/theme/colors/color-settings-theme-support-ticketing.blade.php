<div class="col-2">
    <h4 class="mb-4">{{__('Theme (Support Ticket)')}}</h4>
    <x-colorpicker.input value="{{get_static_option($suffix.'_main_color_one','#F7EA78')}}" name="{{$suffix}}_main_color_one" label="{{__('Site Main Color One')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_main_color_one_rgb','247, 234, 120')}}" name="{{$suffix}}_main_color_one_rgb" label="{{__('Site Main Color One RGB')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_main_color_two','#B4E0C5')}}" name="{{$suffix}}_main_color_two" label="{{__('Site Main Color Two')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_main_color_two_rba','180, 224, 197')}}" name="{{$suffix}}_main_color_two_rba" label="{{__('Site Main Color Two RBA')}}"/>

    <x-colorpicker.input value="{{get_static_option($suffix.'_heading_color','#030403')}}" name="{{$suffix}}_heading_color" label="{{__('Site Heading Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_heading_color_rgb','3, 4, 3')}}" name="{{$suffix}}_heading_color_rgb" label="{{__('Site Heading Color RGB')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_heading_color_two','#353836')}}" name="{{$suffix}}_heading_color_two" label="{{__('Site Heading Color Two')}}"/>

    <x-colorpicker.input value="{{get_static_option($suffix.'_btn_color_one','#F7EA78')}}" name="{{$suffix}}_btn_color_one" label="{{__('Site Button Color One')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_btn_color_two','#B4E0C5')}}" name="{{$suffix}}_btn_color_two" label="{{__('Site Button Color Two')}}"/>

    <x-colorpicker.input value="{{get_static_option($suffix.'_section_bg_one','#B4E0C5')}}" name="{{$suffix}}_section_bg_one" label="{{__('Site Section Background One')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_scroll_bar_bg','#F0F0F0')}}" name="{{$suffix}}_scroll_bar_bg" label="{{__('Scroll Bar Background')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_scroll_bar_color','#c5c5c5')}}" name="{{$suffix}}_scroll_bar_color" label="{{__('Scrollbar Color')}}"/>

    <x-colorpicker.input value="{{get_static_option($suffix.'_paragraph_color','#72787B')}}" name="{{$suffix}}_paragraph_color" label="{{__('Site Paragraph Color')}}"/>
    <x-colorpicker.input value="{{get_static_option($suffix.'_paragraph_color_two','#475467')}}" name="{{$suffix}}_paragraph_color_two" label="{{__('Site Paragraph Color')}}"/>

</div>

