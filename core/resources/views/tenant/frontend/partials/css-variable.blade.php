@php
        $prefix = tenant()->theme_slug;
        $suffix = 'theme_'.$prefix;
@endphp

<style>
    @if($prefix == 'donation')
    :root {
        --main-color-one: {{get_static_option($prefix.'_main_color_one','#14BA85')}};
        --main-color-one-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_main_color_one_rgb','20, 186, 133')) }};
        --main-color-two: {{get_static_option($prefix.'_main_color_two','#524EB7')}};
        --main-color-two-rba: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_main_color_two_rba','82, 78, 183')) }};
        --heading-color: {{get_static_option($prefix.'_heading_color','#333333')}};
        --heading-color-tow: {{get_static_option($prefix.'_heading_color_two','#586178')}};
        --heading-color-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_heading_color_rb','51, 51, 51')) }};
        --secondary-color: {{get_static_option($prefix.'_secondary_color','#FBA260')}};
        --bg-light-one: {{get_static_option($prefix.'_bg_light_one','#F5F9FE')}};
        --bg-light-two: {{get_static_option($prefix.'_bg_light_two','#FEF8F3')}};
        --bg-dark-one: {{get_static_option($prefix.'_bg_dark_one','#040A1B')}};
        --bg-dark-two: {{get_static_option($prefix.'_bg_dark_two','#22253F')}};
        --paragraph-color: {{get_static_option($prefix.'_paragraph_color','#9A9C9F')}};
        --paragraph-color-two: {{get_static_option($prefix.'_paragraph_color_two','#475467')}};
        --paragraph-color-three: {{get_static_option($prefix.'_paragraph_color_three','#D0D5DD')}};
        --paragraph-color-four: {{get_static_option($prefix.'_paragraph_color_four','#344054')}};

        @if(empty(get_static_option('custom_font')))
         --heading-font: {{get_static_option('heading_font_family_'.$suffix,'sans-serif')}} ;
        --body-font: {{get_static_option('body_font_family_'.$suffix,'sans-serif')}};
        font-family: var(--body-font);
    @endif

    }

    @endif

    @if($prefix == 'event')
    :root {
        --main-color-one: {{get_static_option($prefix.'_main_color_one','#FF5229')}};
        --main-color-one-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_main_color_one_rgb','255, 82, 41')) }};
        --main-color-two: {{get_static_option($prefix.'_main_color_two','#524EB7')}};
        --main-color-two-rba: {{replace_rgb_from_css_variable(get_static_option($prefix.'_main_color_two_rba','82, 78, 183'))}};
        --heading-color: {{get_static_option($prefix.'_heading_color','#28272C')}};
        --heading-color-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_heading_color_rgb','73, 77, 87')) }};
        --btn-color-one: {{get_static_option($prefix.'_btn_color_one','#FF5229')}};
        --btn-color-two: {{get_static_option($prefix.'_btn_color_two','#FF5229')}};
        --heading-color-tow: {{get_static_option($prefix.'_heading_color_two','#494D57')}};
        --bg-light-one: {{get_static_option($prefix.'_bg_light_one','#F5F9FE')}};
        --bg-light-two: {{get_static_option($prefix.'_bg_light_two','#FEF8F3')}};
        --bg-dark-one: {{get_static_option($prefix.'_bg_dark_one','#040A1B')}};
        --bg-dark-two: {{get_static_option($prefix.'_bg_dark_two','#22253F')}};
        --paragraph-color: {{get_static_option($prefix.'_paragraph_color','#919191')}};
        --paragraph-color-two: {{get_static_option($prefix.'_paragraph_color_two','#D0D5DD')}};
        --paragraph-color-three: {{get_static_option($prefix.'_paragraph_color_three','#D0D5DD')}};
        --paragraph-color-four: {{get_static_option($prefix.'_paragraph_color_four','#D0D5DD')}};

        @if(empty(get_static_option('custom_font')))
            --heading-font: {{get_static_option('heading_font_family_'.$suffix,'sans-serif')}};
        --body-font: {{get_static_option('body_font_family_'.$suffix,'sans-serif')}};
        --font-family: var(--body-font);
    @endif

    }

    @endif


   @if($prefix == 'job-find')
       @php
            $suffix = 'job';
       @endphp
    :root {
        --main-color-one: {{get_static_option($suffix.'_main_color_one','#2C62F6')}};
        --main-color-one-rgb: {{ replace_rgb_from_css_variable(get_static_option($suffix.'_main_color_one_rgb','44, 98, 246')) }};
        --main-color-two: {{get_static_option($suffix.'_main_color_two','#FF8339')}};
        --main-color-two-rba: {{ replace_rgb_from_css_variable(get_static_option($suffix.'_main_color_two_rba','255, 131, 57')) }};
        --heading-color: {{get_static_option($suffix.'_heading_color','#12244C')}};
        --heading-color-rgb: {{ replace_rgb_from_css_variable(get_static_option($suffix.'_heading_color_rgb','18, 36, 76')) }};
        --heading-color-tow: {{get_static_option($suffix.'_heading_color_two','#07061A')}};
        --btn-color-one: {{get_static_option($suffix.'_btn_color_one','#2C62F6')}};
        --btn-color-two: {{get_static_option($suffix.'_btn_color_two','#FF5229')}};
        --sectionBg-one: {{get_static_option($suffix.'_section_bg_one','#F9F9F9')}};
        --scrollbar-bg: {{get_static_option($suffix.'_scroll_bar_bg','#F0F0F0')}};
        --scrollbar-color: {{get_static_option($suffix.'_scroll_bar_color','#c5c5c5')}};
        --paragraph-color: {{get_static_option($suffix.'_paragraph_color','#17171')}};
        --paragraph-color-two: {{get_static_option($suffix.'_paragraph_color_two','#475467')}};
        @php
            $suffix = 'theme_job';
        @endphp
        @if(empty(get_static_option('custom_font')))
 --heading-font: {{get_static_option('heading_font_family_'.$suffix,'sans-serif')}};
        --body-font: {{get_static_option('body_font_family_'.$suffix,'sans-serif')}};
        font-size: 16px;
        font-weight: 400;
        font-family: var(--body-font);
    @endif

    }

    @endif

   @if($prefix == 'support-ticketing')
       @php
        $suffix = 'support_ticket';
       @endphp
    :root {
        --main-color-one: {{get_static_option($suffix.'_main_color_one','#F7EA78')}};
        --main-color-one-rgb: {{ replace_rgb_from_css_variable(get_static_option($suffix.'_main_color_one_rgb','247, 234, 120')) }};
        --main-color-two: {{get_static_option($suffix.'_main_color_two','#B4E0C5')}};
        --main-color-two-rba: {{ replace_rgb_from_css_variable(get_static_option($suffix.'_main_color_two_rba','180, 224, 197')) }};
        --heading-color: {{get_static_option($suffix.'_heading_color','#030403')}};
        --heading-color-rgb: {{ replace_rgb_from_css_variable(get_static_option($suffix.'_heading_color_rgb','3, 4, 3')) }};
        --heading-color-tow: {{get_static_option($suffix.'_heading_color_two','#353836')}};
        --btn-color-one: {{get_static_option($suffix.'_btn_color_one','#F7EA78')}};
        --btn-color-two: {{get_static_option($suffix.'_btn_color_two','#B4E0C5')}};
        --sectionBg-one: {{get_static_option($suffix.'_section_bg_one','#B4E0C5')}};
        --scrollbar-bg: {{get_static_option($suffix.'_scroll_bar_bg','#F0F0F0')}};
        --scrollbar-color: {{get_static_option($suffix.'_scroll_bar_color','#c5c5c5')}};
        --paragraph-color: {{get_static_option($suffix.'_paragraph_color','#72787B')}};
        --paragraph-color-two: {{get_static_option($suffix.'_paragraph_color_two','#475467')}};
        @php
            $suffix = 'theme_support_ticket';
        @endphp
             @if(empty(get_static_option('custom_font')))
              --heading-font: {{get_static_option('heading_font_family_'.$suffix,'sans-serif')}};
        --body-font: {{get_static_option('body_font_family_'.$suffix,'sans-serif')}};
        font-size: 16px;
        font-weight: 400;
        font-family: var(--body-font);
    @endif

    }

    @endif

   @if($prefix == 'article-listing')

        @php
            $suffix = 'knowledgebase';
        @endphp

    :root {
        --main-color-one: {{get_static_option($suffix.'_main_color_one','#5459E8')}};
        --main-color-one-rgb: {{ replace_rgb_from_css_variable(get_static_option($suffix.'_main_color_one_rgb','84, 89, 232')) }};
        --main-color-two: {{get_static_option($suffix.'_main_color_two','#FF8339')}};
        --main-color-two-rba: {{ replace_rgb_from_css_variable(get_static_option($suffix.'_main_color_two_rba','255, 131, 57')) }};
        --heading-color: {{get_static_option($suffix.'_heading_color','#12244C')}};
        --heading-color-rgb: {{ replace_rgb_from_css_variable(get_static_option($suffix.'_heading_color_rgb','18, 36, 76')) }};
        --heading-color-tow: {{get_static_option($suffix.'_heading_color_two','#07061A')}};
        --btn-color-one: {{get_static_option($suffix.'_btn_color_one','#2C62F6')}};
        --btn-color-two: {{get_static_option($suffix.'_btn_color_two','#FF5229')}};
        --sectionBg-one: {{get_static_option($suffix.'_section_bg_one','#5459E8')}};
        --sectionBg-two: {{get_static_option($suffix.'_section_bg_two','#F9F9F9')}};
        --scrollbar-bg: {{get_static_option($suffix.'_scroll_bar_bg','#F0F0F0')}};
        --scrollbar-color: {{get_static_option($suffix.'_scroll_bar_color','#c5c5c5')}};
        --paragraph-color: {{get_static_option($suffix.'_paragraph_color','#717171')}};
        --paragraph-color-two: {{get_static_option($suffix.'_paragraph_color_two','#475467')}};

        @php
          $suffix = 'theme_knowledgebase';
       @endphp
           @if(empty(get_static_option('custom_font')))
 --heading-font: {{get_static_option('heading_font_family_'.$suffix,'sans-serif')}};
        --body-font: {{get_static_option('body_font_family_'.$suffix,'sans-serif')}};
        font-size: 16px;
        font-weight: 400;
        font-family: var(--body-font);
    @endif

    }

    @endif

    @if($prefix == 'eCommerce')
        @php
           $suffix = 'ecommerce';
        @endphp
    :root {
        --main-color-one: {{get_static_option($suffix.'_main_color_one','#FF7465')}};
        --main-color-one-rgb: {{ replace_rgb_from_css_variable(get_static_option($suffix.'_main_color_one_rgb','255, 116, 101')) }};
        --main-color-two: {{get_static_option($suffix.'_main_color_two','#FF8339')}};
        --main-color-two-rba: {{ replace_rgb_from_css_variable(get_static_option($suffix.'_main_color_two_rba','255, 131, 57')) }};
        --heading-color: {{get_static_option($suffix.'_heading_color','#12244C')}};
        --heading-color-rgb: {{ replace_rgb_from_css_variable(get_static_option($suffix.'_heading_color_rgb','81, 96, 114')) }};
        --btn-color-one: {{get_static_option($suffix.'_btn_color_one','#FF7465')}};
        --btn-color-two: {{get_static_option($suffix.'_btn_color_two','#FF5229')}};
        --heading-color-tow: {{get_static_option($suffix.'_heading_color_two','#516072')}};
        --scrollbar-bg: {{get_static_option($suffix.'_scroll_bar_bg','#F0F0F0')}};
        --scrollbar-color: {{get_static_option($suffix.'_scroll_bar_color','#c5c5c5')}};
        --bg-light-one: {{get_static_option($suffix.'_bg_light_one','#F5F9FE')}};
        --bg-light-two: {{get_static_option($suffix.'_bg_light_two','#FEF8F3')}};
        --bg-dark-one: {{get_static_option($suffix.'_bg_dark_one','#040A1B')}};
        --bg-dark-two: {{get_static_option($suffix.'_bg_dark_two','#22253F')}};
        --paragraph-color: {{get_static_option($suffix.'_paragraph_color','#919191')}};
        --paragraph-color-two: {{get_static_option($suffix.'_paragraph_color_two','#475467')}};
        --paragraph-color-three: {{get_static_option($suffix.'_paragraph_color_three','#D0D5DD')}};
        --paragraph-color-four: {{get_static_option($suffix.'_paragraph_color_four','#344054')}};
        --stock-color: {{get_static_option($suffix.'_stock_color','#5AB27E')}};


        @php
           $suffix = 'theme_eCommerce';
        @endphp
         @if(empty(get_static_option('custom_font')))
 --heading-font: {{get_static_option('heading_font_family_'.$suffix,'sans-serif')}}  !important;
        --body-font: {{get_static_option('body_font_family_'.$suffix,'sans-serif')}};
        font-size: 16px;
        font-weight: 400;
        font-family: var(--body-font);
    @endif

    }

    @endif


    @if($prefix == 'agency')

    :root {
        --main-color-one: {{get_static_option($prefix.'_main_color_one','#ffd338')}};
        --main-color-one-rgb: {{replace_rgb_from_css_variable(get_static_option($prefix.'_main_color_one_rgb','255, 211, 56'))}};
        --agency-section-bg: {{get_static_option($prefix.'_agency_section_bg','#FFFCF4')}};
        --agency-section-bg-2: {{get_static_option($prefix.'_agency_section_bg_2','#6368E5')}};
        --agency-section-bg-3: {{get_static_option($prefix.'_agency_section_bg_3','#141414')}};
        --heading-color: {{get_static_option($prefix.'_heading_color','#1D2635')}};
        --heading-body-color: {{get_static_option($prefix.'_body_color','#777D86')}};
        --light-color: {{get_static_option($prefix.'_light_color','#777D86')}};
        --review-color: {{get_static_option($prefix.'_review_color','#FABE50')}};

        @if(empty(get_static_option('custom_font')))
         --heading-font: {{get_static_option('heading_font_family_'.$suffix,'sans-serif')}};
        --body-font: {{get_static_option('body_font_family_'.$suffix,'sans-serif')}};
        font-size: 16px;
        font-weight: 400;
        font-family: var(--body-font) !important;
    @endif

    }

    @endif


    @if($prefix == 'newspaper')

    :root {
        --main-color-one: {{get_static_option($prefix.'_main_color_one','#f65050')}};
        --main-color-one-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_main_color_one_rgb','246, 80, 80')) }};
        --secondary-color: {{get_static_option($prefix.'_secondary_color','#FFD203')}};
        --secondary-color-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_secondary_color_rgb','255, 210, 3')) }};
        --newspaper-section-bg: {{get_static_option($prefix.'_newspaper_section_bg','#141414')}};
        --newspaper-section-bg-2: {{get_static_option($prefix.'_newspaper_section_bg_2','#F9F9F9')}};
        --border-color: {{get_static_option($prefix.'_border_color','#e9e9e9')}};
        --border-color-2: {{get_static_option($prefix.'_border_color_2','#f3f3f3')}};
        --heading-color: {{get_static_option($prefix.'_heading_color','#1D2635')}};
        --body-color: {{get_static_option($prefix.'_body_color','#777D86')}};
        --light-color: {{get_static_option($prefix.'_light_color','#777D86')}};
        --review-color: {{get_static_option($prefix.'_review_color','#FABE50')}};

        @if(empty(get_static_option('custom_font')))
 --heading-font: {{get_static_option('heading_font_family_'.$suffix,'Inter')}};
        --body-font: {{get_static_option('body_font_family_'.$suffix,'Inter')}};
        font-size: 16px;
        font-weight: 400;
        font-family: var(--body-font) !important;
    @endif

    }

    @endif

  @if($prefix == 'construction')

    :root {
        --main-color-one: {{get_static_option($prefix.'_main_color_one','#fe762a')}};
        --main-color-one-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_main_color_one_rgb','254, 118, 42')) }};
        --main-color-two: {{get_static_option($prefix.'_main_color_two','#ff6b2c')}};
        --main-color-two-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_main_color_two_rgb','255, 107, 44')) }};
        --construction-section-bg: {{get_static_option($prefix.'_section_bg','#FFFDF6')}};
        --construction-section-bg-2: {{get_static_option($prefix.'_section_bg_2','#F9F9F9')}};
        --construction-section-bg-3: {{get_static_option($prefix.'_section_bg_3','#141414')}};
        --white: {{get_static_option($prefix.'_white','#ffffff')}};
        --white-rgb: {{get_static_option($prefix.'_white_rgb','255, 255, 255')}};
        --black: {{get_static_option($prefix.'_black','#000')}};
        --black-rgb: {{get_static_option($prefix.'_black_rgb','0, 0, 0')}};
        --border-color: {{get_static_option($prefix.'_border_color','#ebebeb')}};
        --border-color-two: {{get_static_option($prefix.'_border_color_two','#eff0f1')}};
        --heading-color: {{get_static_option($prefix.'_heading_color','#1D2635')}};
        --body-color: {{get_static_option($prefix.'_body_color','#8a8f96')}};
        --paragraph-color: {{get_static_option($prefix.'_paragraph_color','#777D86')}};
        --light-color: {{get_static_option($prefix.'_light_color','#777D86')}};
        --review-color: {{get_static_option($prefix.'_review_color','#FABE50')}};

        @if(empty(get_static_option('custom_font')))
         --heading-font: {{get_static_option('heading_font_family_'.$suffix,'Inter')}};
        --body-font: {{get_static_option('body_font_family_'.$suffix,'Inter')}};
        font-family: var(--body-font);
    @endif

    }

    @endif


    @if($prefix == 'consultancy')

    :root {
        --main-color-one: {{get_static_option($prefix.'_main_color_one','#3b50e0')}};
        --main-color-one-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_main_color_one_rgb','59, 80, 22')) }};
        --main-color-two: {{get_static_option($prefix.'_main_color_two','#ff6b2c')}};
        --main-color-two-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_main_color_two_rgb','255, 107, 44')) }};
        --consulting-section-bg: {{get_static_option($prefix.'_section_bg','#FFFDF6')}};
        --consulting-section-bg-2: {{get_static_option($prefix.'_section_bg_2','#F9F9F9')}};
        --consulting-section-bg-3: {{get_static_option($prefix.'_section_bg_3','#141414')}};
        --white: {{get_static_option($prefix.'_white','#ffffff')}};
        --white-rgb: {{get_static_option($prefix.'_white_rgb','255, 255, 255')}};
        --black: {{get_static_option($prefix.'_black','#000')}};
        --black-rgb: {{get_static_option($prefix.'_black_rgb','0, 0, 0')}};
        --border-color: {{get_static_option($prefix.'_border_color','#ebebeb')}};
        --border-color-two: {{get_static_option($prefix.'_border_color_two','#eff0f1')}};
        --heading-color: {{get_static_option($prefix.'_heading_color','#1D2635')}};
        --body-color: {{get_static_option($prefix.'_body_color','#8a8f96')}};
        --paragraph-color: {{get_static_option($prefix.'_paragraph_color','#666')}};
        --light-color: {{get_static_option($prefix.'_light_color','#8a8f96')}};
        --review-color: {{get_static_option($prefix.'_review_color','#FABE50')}};

        @if(empty(get_static_option('custom_font')))
         --heading-font: {{get_static_option('heading_font_family_'.$suffix,'Inter')}};
        --body-font: {{get_static_option('body_font_family_'.$suffix,'Inter')}};
        font-family: var(--body-font);
    @endif

    }

    @endif


    @if($prefix == 'wedding')
    :root {
        --main-color-one: {{get_static_option($prefix.'_main_color_one','#fe6866')}};
        --main-color-one-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_main_color_one_rgb','254, 104, 102')) }};
        --secondary-color: {{get_static_option($prefix.'_secondary_color','#fe7596')}};
        --secondary-color-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_secondary_color_rgb','254, 117, 150')) }};
        --wedding-bg-secondary: {{get_static_option($prefix.'_section_bg_secondary','#452b4e')}};
        --wedding-section-bg: {{get_static_option($prefix.'_section_bg','#FFFAF0')}};
        --wedding-section-bg-2: {{get_static_option($prefix.'_section_bg_2','#F9F9F9')}};
        --wedding-section-bg-3: {{get_static_option($prefix.'_section_bg_3','#f1f1f1')}};
        --wedding-main-gradient: linear-gradient(97.78deg, var(--main-color-one) -35.38%, var(--secondary-color) 50.16%, var(--main-color-one) 126.02%);
        --wedding-main-gradient-reverse: linear-gradient(97.78deg, var(--secondary-color) -35.38%, var(--main-color-one) 50.16%, var(--secondary-color) 126.02%);
        --white: {{get_static_option($prefix.'_white','#ffffff')}};
        --white-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_white_rgb','255, 255, 255')) }};
        --black: {{get_static_option($prefix.'_black','#000')}};
        --black-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_black_rgb','0, 0, 0')) }};
        --border-color: {{get_static_option($prefix.'_border_color','#ebebeb')}};
        --border-color-two: {{get_static_option($prefix.'_border_color_two','#eff0f1')}};
        --success-color: {{get_static_option($prefix.'_success_color','#2FAB73')}};
        --delete-color: {{get_static_option($prefix.'_delete_color','#e00000')}};
        --heading-color: {{get_static_option($prefix.'_heading_color','#1D2635')}};
        --body-color: {{get_static_option($prefix.'_body_color','#8a8f96')}};
        --paragraph-color: {{get_static_option($prefix.'_paragraph_color','#666')}};
        --paragraph-color-two: {{get_static_option($prefix.'_paragraph_color_two','#5e4c47')}};
        --light-color: {{get_static_option($prefix.'_light_color','#8a8f96')}};
        --review-color: {{get_static_option($prefix.'_review_color','#FABE50')}};

        @if(empty(get_static_option('custom_font')))
             --heading-font: {{get_static_option('heading_font_family_'.$suffix,'serif')}};
        --body-font: {{get_static_option('body_font_family_'.$suffix,'sans-serif')}};
    @endif

    }

    @endif




 @if($prefix == 'photography')


    :root {

        --main-color-one: {{get_static_option($prefix.'_main_color_one','#f84f8c')}};
        --main-color-one-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_main_color_one_rgb','248, 79, 140')) }};
        --main-color-two: {{get_static_option($prefix.'_main_color_two','#ff7a03')}};
        --main-color-two-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_main_color_two_rgb','255, 122, 3')) }};
        --secondary-color: {{get_static_option($prefix.'_secondary_color','#fe7596')}};
        --secondary-color-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_secondary_color_rgb','254, 117, 150')) }};
        --photography-section-bg: {{get_static_option($prefix.'_section_bg','#FFFAF0')}};
        --photography-section-bg-2: {{get_static_option($prefix.'_section_bg_2','#F9F9F9')}};
        --photography-section-bg-3: {{get_static_option($prefix.'_section_bg_3','#f1f1f1')}};
        --photography-main-gradient: linear-gradient(91.48deg, #DDD6F3 -14.99%, rgba(250, 172, 168, 0) 141.24%);
        --photography-main-gradient-reverse: linear-gradient(97.78deg, var(--secondary-color) -35.38%, var(--main-color-one) 50.16%, var(--secondary-color) 126.02%);
        --white: {{get_static_option($prefix.'_white','#ffffff')}};
        --white-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_white_rgb','255, 255, 255')) }};
        --black: {{get_static_option($prefix.'_black','#000')}};
        --black-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_black_rgb','0, 0, 0')) }};
        --border-color: {{get_static_option($prefix.'_border_color','#ebebeb')}};
        --border-color-two: {{get_static_option($prefix.'_border_color_two','#eff0f1')}};
        --success-color: {{get_static_option($prefix.'_success_color','#2FAB73')}};
        --delete-color: {{get_static_option($prefix.'_delete_color','#e00000')}};
        --heading-color: {{get_static_option($prefix.'_heading_color','#1D2635')}};
        --body-color: {{get_static_option($prefix.'_body_color','#8a8f96')}};
        --paragraph-color: {{get_static_option($prefix.'_paragraph_color','#666')}};
        --paragraph-color-two: {{get_static_option($prefix.'_paragraph_color_two','#5e4c47')}};
        --light-color: {{get_static_option($prefix.'_light_color','#8a8f96')}};
        --review-color: {{get_static_option($prefix.'_review_color','#FABE50')}};

        @if(empty(get_static_option('custom_font')))
         --heading-font: {{get_static_option('heading_font_family_'.$suffix,'serif')}};
        --body-font: {{get_static_option('body_font_family_'.$suffix,'sans-serif')}};
    @endif

    }

    @endif


   @if($prefix == 'portfolio')

    :root {

        --main-color-one: {{get_static_option($prefix.'_main_color_one','#c37236')}};
        --main-color-one-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_main_color_one_rgb','195, 114, 54')) }};
        --secondary-color: {{get_static_option($prefix.'_secondary_color','#fde5b5')}};
        --secondary-color-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_secondary_color_rgb','253, 229, 181')) }};
        --portfolio-section-bg: {{get_static_option($prefix.'_section_bg','#353543')}};
        --portfolio-section-bg-2: {{get_static_option($prefix.'_section_bg_2','#DDDDDD')}};
        --portfolio-section-bg-3: {{get_static_option($prefix.'_section_bg_3','#f1f1f1')}};
        --photography-main-gradient: linear-gradient(91.48deg, #DDD6F3 -14.99%, rgba(250, 172, 168, 0) 141.24%);
        --photography-main-gradient-reverse: linear-gradient(97.78deg, var(--secondary-color) -35.38%, var(--main-color-one) 50.16%, var(--secondary-color) 126.02%);
        --white: {{get_static_option($prefix.'_white','#ffffff')}};
        --white-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_white_rgb','255, 255, 255')) }};
        --black: {{get_static_option($prefix.'_black','#000')}};
        --black-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_black_rgb','0, 0, 0')) }};
        --border-color: {{get_static_option($prefix.'_border_color','#ebebeb')}};
        --border-color-two: {{get_static_option($prefix.'_border_color_two','#eff0f1')}};
        --success-color: {{get_static_option($prefix.'_success_color','#2FAB73')}};
        --delete-color: {{get_static_option($prefix.'_delete_color','#e00000')}};
        --heading-color: {{get_static_option($prefix.'_heading_color','#1D2635')}};
        --body-color: {{get_static_option($prefix.'_body_color','#8a8f96')}};
        --paragraph-color: {{get_static_option($prefix.'_paragraph_color','#666')}};
        --paragraph-color-two: {{get_static_option($prefix.'_paragraph_color_two','#5e4c47')}};
        --light-color: {{get_static_option($prefix.'_light_color','#8a8f96')}};
        --review-color: {{get_static_option($prefix.'_review_color','#FABE50')}};

        @if(empty(get_static_option('custom_font')))
         --heading-font: {{get_static_option('heading_font_family_'.$suffix,'serif')}};
        --body-font: {{get_static_option('body_font_family_'.$suffix,'sans-serif')}};
    @endif

    }

    @endif



  @if($prefix == 'software-business')

     @php
         $prefix = 'software';
         $suffix = 'theme_software';
      @endphp

    :root {
        --main-color-one: {{get_static_option($prefix.'_main_color_one','#ff805D')}};
        --main-color-one-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_main_color_one_rgb','255, 128, 93')) }};
        --secondary-color: {{get_static_option($prefix.'_secondary_color','#452b4e')}};
        --secondary-color-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_secondary_color_rgb','69, 43, 78')) }};
        --softwareFirm-section-bg: {{get_static_option($prefix.'_section_bg','#FFBD76')}};
        --softwareFirm-bg-secondary: {{get_static_option($prefix.'_section_bg_secondary','#452b4e')}};
        --softwareFirm-section-bg-2: {{get_static_option($prefix.'_section_bg_2','#F9F9F9')}};
        --softwareFirm-section-bg-3: {{get_static_option($prefix.'_section_bg_3','#f1f1f1')}};
        --white: {{get_static_option($prefix.'_white','#ffffff')}};
        --white-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_white_rgb','255, 255, 255')) }};
        --black: {{get_static_option($prefix.'_black','#000')}};
        --black-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_black_rgb','0, 0, 0')) }};
        --border-color: {{get_static_option($prefix.'_border_color','#ebebeb')}};
        --border-color-two: {{get_static_option($prefix.'_border_color_two','#eff0f1')}};
        --success-color: {{get_static_option($prefix.'_success_color','#2FAB73')}};
        --heading-color: {{get_static_option($prefix.'_heading_color','#1D2635')}};
        --body-color: {{get_static_option($prefix.'_body_color','#8a8f96')}};
        --paragraph-color: {{get_static_option($prefix.'_paragraph_color','#666')}};
        --paragraph-color-two: {{get_static_option($prefix.'_paragraph_color_two','#5e4c47')}};
        --light-color: {{get_static_option($prefix.'_light_color','#8a8f96')}};
        --review-color: {{get_static_option($prefix.'_review_color','#FABE50')}};

        @if(empty(get_static_option('custom_font')))
         --heading-font: {{get_static_option('heading_font_family_'.$suffix,'serif')}};
        --body-font: {{get_static_option('body_font_family_'.$suffix,'sans-serif')}};
    @endif

    }

    @endif


  @if($prefix == 'barber-shop')
     @php
         $suffix = 'theme_barber_shop';
      @endphp

    :root {
        --main-color-one: {{get_static_option($prefix.'_main_color_one','#e76144')}};
        --main-color-one-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_main_color_one_rgb','231, 97, 68')) }};
        --secondary-color: {{get_static_option($prefix.'_secondary_color','#ff8867')}};
        --secondary-color-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_secondary_color_rgb','255, 136, 103')) }};
        --barberShop-section-bg: {{get_static_option($prefix.'_section_bg','#F9F9F9')}};
        --barberShop-bg-main: {{get_static_option($prefix.'_section_bg_main','#FFF5F2')}};
        --barberShop-section-bg-2: {{get_static_option($prefix.'_section_bg_2','#F9F9F9')}};
        --barberShop-section-bg-3: {{get_static_option($prefix.'_section_bg_3','#f1f1f1')}};
        --white: {{get_static_option($prefix.'_white','#ffffff')}};
        --white-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_white_rgb','255, 255, 255')) }};
        --black: {{get_static_option($prefix.'_black','#000')}};
        --black-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_black_rgb','0, 0, 0')) }};
        --border-color: {{get_static_option($prefix.'_border_color','#ebebeb')}};
        --border-color-two: {{get_static_option($prefix.'_border_color_two','#eff0f1')}};
        --success-color: {{get_static_option($prefix.'_success_color','#2FAB73')}};
        --delete-color: {{get_static_option($prefix.'_delete_color','#d10909')}};
        --heading-color: {{get_static_option($prefix.'_heading_color','#1D2635')}};
        --body-color: {{get_static_option($prefix.'_body_color','#8a8f96')}};
        --paragraph-color: {{get_static_option($prefix.'_paragraph_color','#666')}};
        --paragraph-color-two: {{get_static_option($prefix.'_paragraph_color_two','#5e4c47')}};
        --light-color: {{get_static_option($prefix.'_light_color','#8a8f96')}};
        --review-color: {{get_static_option($prefix.'_review_color','#FABE50')}};

        @if(empty(get_static_option('custom_font')))
         --heading-font: {{get_static_option('heading_font_family_'.$suffix,'serif')}};
        --body-font: {{get_static_option('body_font_family_'.$suffix,'sans-serif')}};

        @endif
  @endif


@if($prefix == 'hotel-booking')
     @php
         $suffix = $prefix = str_replace('-','_', $prefix);
      @endphp

    :root {
            --main-color-one: {{get_static_option($prefix.'_main_color_one','#e76144')}};
            --main-color-one-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_main_color_one_rgb','231, 97, 68')) }};
            --secondary-color: {{get_static_option($prefix.'_secondary_color','#ff8867')}};
            --secondary-color-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_secondary_color_rgb','255, 136, 103')) }};
            --barberShop-section-bg: {{get_static_option($prefix.'_section_bg','#F9F9F9')}};
            --barberShop-bg-main: {{get_static_option($prefix.'_section_bg_main','#FFF5F2')}};
            --barberShop-section-bg-2: {{get_static_option($prefix.'_section_bg_2','#F9F9F9')}};
            --barberShop-section-bg-3: {{get_static_option($prefix.'_section_bg_3','#f1f1f1')}};
            --white: {{get_static_option($prefix.'_white','#ffffff')}};
            --white-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_white_rgb','255, 255, 255')) }};
            --black: {{get_static_option($prefix.'_black','#000')}};
            --black-rgb: {{ replace_rgb_from_css_variable(get_static_option($prefix.'_black_rgb','0, 0, 0')) }};
            --border-color: {{get_static_option($prefix.'_border_color','#ebebeb')}};
            --border-color-two: {{get_static_option($prefix.'_border_color_two','#eff0f1')}};
            --success-color: {{get_static_option($prefix.'_success_color','#2FAB73')}};
            --delete-color: {{get_static_option($prefix.'_delete_color','#d10909')}};
            --heading-color: {{get_static_option($prefix.'_heading_color','#1D2635')}};
            --body-color: {{get_static_option($prefix.'_body_color','#8a8f96')}};
            --paragraph-color: {{get_static_option($prefix.'_paragraph_color','#666')}};
            --paragraph-color-two: {{get_static_option($prefix.'_paragraph_color_two','#5e4c47')}};
            --light-color: {{get_static_option($prefix.'_light_color','#8a8f96')}};
            --review-color: {{get_static_option($prefix.'_review_color','#FABE50')}};

            @if(empty(get_static_option('custom_font')))
             --heading-font: {{get_static_option('heading_font_family_'.$suffix,'serif')}};
             --body-font: {{get_static_option('body_font_family_'.$suffix,'sans-serif')}};
            @endif
    @endif
</style>
