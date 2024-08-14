
<option value="" selected>{{__('Select Theme')}}</option>
@foreach($themes as $theme)
    @php
             $continueLoop = false;
             $theme_details = getIndividualThemeDetails($theme->slug);
             $lang_suffix = '_'.get_user_lang();
             $theme_name = get_static_option_central($theme->slug.'_theme_name'.$lang_suffix) ?? $theme_details['name'];

           //Here we checking is theme anailale or plugin deActivate
            $is_available = get_static_option_central($theme->slug.'_theme_is_available');
            $formattedName1 = str_replace([' ', '-'], '', ucwords($theme->name, ' -'));

            $only_path = 'core/modules_statuses.json';
            if (!file_exists($only_path) && !is_dir($only_path)) {
            }
            $module_status_path = file_get_contents($only_path);
            $all_data_decoded = json_decode($module_status_path);

            foreach ($all_data_decoded as $key => $item) {
                if($formattedName1 == $key) {
                    if($item == false)
                    {
                        $continueLoop = true;
                    }
                }
            }
            if($is_available != 'on'){
                $continueLoop = true;
            }
        //end
    @endphp
    @continue($continueLoop ?? false)
    <option value="{{$theme->slug}}" data-theme_code="{{$theme->slug}}">{{ $theme_name }}</option>
@endforeach
