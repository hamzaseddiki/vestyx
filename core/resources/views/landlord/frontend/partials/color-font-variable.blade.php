<style>
    :root {

        --main-color-one: {{get_static_option('main_color_one') ?? '#EA6249'}};
        --main-color-one-rgb:{{get_static_option('main_color_one_rgb') ?? '216, 83, 58'}} ;
        --main-color-two: {{get_static_option('main_color_two') ?? '#524EB7'}};
        --main-color-two-rba: {{get_static_option('main_color_two_rba') ?? '82, 78, 183'}};
        --main-color-three: {{get_static_option('main_color_three') ?? '#599a8d'}};
        --heading-color: {{get_static_option('heading_color') ?? '#22211F'}};
        --heading-color-rgb:{{get_static_option('heading_color_rgb','82, 78, 183')}};
        --secondary-color: {{get_static_option('secondary_color','#FBA260')}};
        --bg-light-one: {{get_static_option('bg_light_one','#F5F9FE')}};
        --bg-light-two: {{get_static_option('bg_light_two','#FEF8F3')}};
        --bg-dark-one: {{get_static_option('bg_dark_one','#040A1B')}};
        --bg-dark-two:{{get_static_option('bg_dark_two','#22253F')}};
        --paragraph-color: {{get_static_option('paragraph_color','#555454')}};
        --paragraph-color-two: {{get_static_option('paragraph_color_two','#475467')}};
        --paragraph-color-three: {{get_static_option('paragraph_color_three','#D0D5DD')}};
        --paragraph-color-four: {{get_static_option('paragraph_color_four','#344054')}};

        @if(empty(get_static_option('custom_font')))
            --heading-font: "{{get_static_option('heading_font_family')}}",sans-serif;
            --body-font:"{{get_static_option('body_font_family')}}",sans-serif;
        @endif


    }
</style>
