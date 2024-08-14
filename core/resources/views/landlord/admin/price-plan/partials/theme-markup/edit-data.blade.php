@php
    $data_base_features = $plan->plan_features?->pluck('feature_name')->toArray();
@endphp

<div class="form-group">
    <label for="status">{{__('Themes')}}</label>
    <select name="themes[]" class="price_plan_themes form-control" multiple="multiple" style="width:100%">
        @foreach(getAllThemeDataForAdmin() as $theme)
            @php
                $lang_suffix = '_'.default_lang();
                $theme_name = get_static_option_central($theme->slug.'_theme_name'.$lang_suffix);
                $is_available = get_static_option_central($theme->slug.'_theme_is_available');
            @endphp
            @continue($is_available != 'on')
            <option value="{{'theme-'.$theme->slug}}" {{ in_array('theme-'.$theme->slug,$data_base_features) ? 'selected' : '' }}>{{$theme_name ?? ''}}</option>
        @endforeach
    </select>
</div>
