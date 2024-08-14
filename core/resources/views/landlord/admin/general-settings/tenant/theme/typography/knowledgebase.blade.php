@if(get_static_option('tenant_default_theme') == 'article-listing')

<div class="col-4">
    <h4 class="mb-4 text-primary">{{__('Theme (Knowledgebase)')}}</h4>

    <div class="form-group">
        <label for="body_font_family">{{__('Font Family')}}</label>
        <select class="form-control nice-select wide body_font_family" name="body_font_family_{{$suffix}}" id="body_font_family" data-theme="{{$suffix}}">
            @foreach($google_fonts as $font_family => $font_variant)
                <option value="{{$font_family}}" @if($font_family == get_static_option('body_font_family_'.$suffix)) selected @endif>{{$font_family}}</option>
            @endforeach
        </select>
    </div>

    <br><br>


    <div class="form-group">
        <label for="body_font_variant">{{__('Font Variant')}}</label>
        @php
            $font_family_selected = get_static_option('body_font_family_'.$suffix) ?? get_static_option('body_font_family_'.$suffix) ;
            $get_font_family_variants = property_exists($google_fonts,$font_family_selected) ? (array) $google_fonts->$font_family_selected : ['variants' => array('regular')];
        @endphp
        <select class="form-control nice-select wide body_font_variant_{{$suffix}}" multiple id="body_font_variant" name="body_font_variant_{{$suffix}}[]">
            @foreach($get_font_family_variants['variants'] as $variant)
                @php
                    $selected_variant = !empty(get_static_option('body_font_variant_'.$suffix)) ? unserialize(get_static_option('body_font_variant_'.$suffix)) : [];
                @endphp
                <option value="{{$variant}}" @if(in_array($variant,$selected_variant)) selected @endif>{{str_replace(['0,','1,'],['','i'],$variant)}}</option>
            @endforeach
        </select>
    </div>

    <br><br>

    <h4 class="header-title margin-top-80 mt-3">{{__("Heading Font (Knowledgebase)")}}</h4>
    <div class="form-group">
        <label for="heading_font">{{__('Heading Font')}}</label>
        <label class="switch">
            <input type="checkbox" name="heading_font_{{$suffix}}" class="heading_font"  @if(!empty(get_static_option('heading_font_'.$suffix))) checked @endif id="heading_font" data-theme="{{$suffix}}">
            <span class="slider"></span>
        </label>
        <small>{{__('Use different font family for heading tags ( h1,h2,h3,h4,h5,h6)')}}</small>
    </div>
    <br>

    <div class="heading_container">
    <div class="form-group">
        <label for="heading_font_family">{{__('Font Family')}}</label>
        <select class="form-control nice-select wide heading_font_family" name="heading_font_family_{{$suffix}}" id="heading_font_family" data-theme="{{$suffix}}">
            @foreach($google_fonts as $font_family => $font_variant)
                <option value="{{$font_family}}" @if($font_family == get_static_option('heading_font_family_'.$suffix)) selected @endif>{{$font_family}}</option>
            @endforeach
        </select>
    </div>
    <br><br>
    <div class="form-group margin-top-50">
        <label for="heading_font_variant">{{__('Font Variant')}}</label>
        @php
            $font_family_selected = get_static_option('heading_font_family_'.$suffix) ?? '';
            $get_font_family_variants = property_exists($google_fonts,$font_family_selected) ? (array) $google_fonts->$font_family_selected : ['variants' => array('regular')];
        @endphp
        <select class="form-control nice-select wide heading_font_variant_{{$suffix}}" multiple name="heading_font_variant_{{$suffix}}[]" id="heading_font_variant">
            @foreach($get_font_family_variants['variants'] as $variant)
                @php
                    $selected_variant = !empty(get_static_option('heading_font_variant_'.$suffix)) ? unserialize(get_static_option('heading_font_variant_'.$suffix)) : [];
                @endphp
                <option value="{{$variant}}"
                        @if(in_array($variant,$selected_variant)) selected @endif>{{str_replace(['0,','1,'],['','i'],$variant)}}</option>
            @endforeach
        </select>
    </div>
</div>
</div>
@endif
