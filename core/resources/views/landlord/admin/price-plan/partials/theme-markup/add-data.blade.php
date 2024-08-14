<div class="form-group">
 <label for="status">{{__('Themes')}}</label>
    <select name="themes[]" class="price_plan_themes form-control" multiple="multiple" style="width:100%">

        @foreach(getAllThemeDataForAdmin() as $theme)
            @php
                $continueLoop  = false;
                if($theme->status === true) {
                     $theme_details = getIndividualThemeDetails($theme->slug);
                    $lang_suffix = '_'.default_lang();
                    $theme_name = get_static_option_central($theme->slug.'_theme_name'.$lang_suffix) ?? $theme_details['name'];
                    $is_available = get_static_option_central($theme->slug.'_theme_is_available');
                    $formattedName1 = str_replace([' ', '-'], '', ucwords($theme_name, ' -'));
                }
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
            @endphp

            @continue($continueLoop ?? false)
            <option value="{{'theme-'.$theme->slug}}">{{$theme_name}}</option>
        @endforeach
    </select>
</div>
