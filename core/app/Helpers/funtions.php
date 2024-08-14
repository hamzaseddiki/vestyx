<?php

use App\Facades\GlobalLanguage;
use App\Helpers\SanitizeInput;
use App\Models\Coupon;
use App\Models\PaymentLogs;
use App\Models\StaticOption;
use Illuminate\Support\Facades\Cache;
use Artesaos\SEOTools\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\OpenGraph;
use Artesaos\SEOTools\SEOTools;
use \Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Modules\Appointment\Entities\AppointmentTax;

/* all helper function will be here */

/**
 * @param $option_name
 * @param $default
 * @return mixed|null
 */
function get_static_option($option_name, $default = null)
{
    $value = Cache::remember($option_name, 30, function () use ($option_name) {
        try {
            return StaticOption::where('option_name', $option_name)->first();
        } catch (\Exception $e) {
            return null;
        }
    });

    return $value->option_value ?? $default;
}

function get_static_option_central($option_name, $default = null)
{
    $value = Cache::remember($option_name, 30, function () use ($option_name) {
        try {
            return \App\Models\StaticOptionCentral::where('option_name', $option_name)->first();
        } catch (\Exception $e) {
            return null;
        }
    });

    return $value->option_value ?? $default;
}


function get_user_lang()
{
    return $lang = \App\Facades\GlobalLanguage::user_lang_slug();
}


function get_attachment_image_by_id($id, $size = null, $default = false): array
{
    $image_details = Cache::remember('media_image_' . $id, 300, function () use ($id) {
        return \App\Models\MediaUploader::find($id);
    });
    $return_val = [];
    $image_url = '';

    if (!empty($id) && !empty($image_details)) {

        $tenant_subdomain = '';
        if (tenant()) {
            $tenant_user = tenant()->user()->first() ?? null;
            $tenant_subdomain = !is_null($tenant_user) ?tenant()->id . '/' : '';
        }

        $path_prefix = is_null(tenant()) ? 'assets/landlord' : 'assets/tenant';
        $path = global_asset($path_prefix . '/uploads/media-uploader/' . $tenant_subdomain);
        $base_path = global_assets_path($path_prefix . '/uploads/media-uploader/' . $tenant_subdomain);
        $image_url = $path . $image_details->path;
        switch ($size) {
            case "large":
                if ($base_path . 'large/large-' . $image_details->path && !is_dir($base_path . 'large/large-' . $image_details->path)) {
                    $image_url = $path . '/large/large-' . $image_details->path;
                }
                break;
            case "grid":
                if ($base_path . 'grid/grid-' . $image_details->path && !is_dir($base_path . 'grid/grid-' . $image_details->path)) {
                    $image_url = $path . '/grid/grid-' . $image_details->path;
                }
                break;
            case "thumb":
                if ($base_path . 'thumb/thumb-' . $image_details->path && !is_dir($base_path . 'thumb/thumb-' . $image_details->path)) {
                    $image_url = $path . '/thumb/thumb-' . $image_details->path;
                }
                break;
            default:
                if (is_numeric($id) && file_exists($base_path . $image_details->path) && !is_dir($base_path . $image_details->path)) {
                    $image_url = $path . '/' . $image_details->path;
                }
                break;
        }
    }

    if (!empty($image_details)) {
        $return_val['image_id'] = $image_details->id;
        $return_val['path'] = $image_details->path;
        $return_val['img_url'] = $image_url;
        $return_val['img_alt'] = $image_details->alt;
    } elseif (empty($image_details) && $default) {
        $return_val['img_url'] = global_asset('no-image.jpeg');
        $return_val['img_alt'] = '';
    }

    return $return_val;
}


function get_lnadlord_attachment_image_by_id_without_query($image_details,$id, $size = null, $default = false): array
{

    $return_val = [];
    $image_url = '';

    if (!empty($id) && !empty($image_details)) {

        $path_prefix = 'assets/landlord';
        $path = $path_prefix . '/uploads/media-uploader/';
        $base_path = global_assets_path($path_prefix . '/uploads/media-uploader/');
        $image_url = $path . $image_details->path;

        switch ($size) {
            case "large":
                if ($base_path . 'large/large-' . $image_details->path && !is_dir($base_path . 'large/large-' . $image_details->path)) {
                    $image_url = $path . '/large/large-' . $image_details->path;
                }
                break;
            case "grid":
                if ($base_path . 'grid/grid-' . $image_details->path && !is_dir($base_path . 'grid/grid-' . $image_details->path)) {
                    $image_url = $path . '/grid/grid-' . $image_details->path;
                }
                break;
            case "thumb":
                if ($base_path . 'thumb/thumb-' . $image_details->path && !is_dir($base_path . 'thumb/thumb-' . $image_details->path)) {
                    $image_url = $path . '/thumb/thumb-' . $image_details->path;
                }
                break;
            default:
                if (is_numeric($id) && file_exists($base_path . $image_details->path) && !is_dir($base_path . $image_details->path)) {
                    $image_url = $path . '/' . $image_details->path;
                }
                break;
        }
    }

    if (!empty($image_details)) {
        $return_val['image_id'] = $image_details->id;
        $return_val['path'] = $image_details->path;
        $return_val['img_url'] = $image_url;
        $return_val['img_alt'] = $image_details->alt;
    } elseif (empty($image_details) && $default) {
        $return_val['img_url'] = global_asset('no-image.jpeg');
        $return_val['img_alt'] = '';
    }

    return $return_val;
}




function get_attachment_image_by_path($id, $path, $alt = null, $size = null, $default = false): array
{
    $image_details = Cache::remember('media_image_' . $id, 300, function () use ($id, $path) {
        return $path;
    });
    $return_val = [];
    $image_url = '';

    if (!empty($image_details)) {

        $tenant_subdomain = '';
        if (tenant()) {
            $tenant_user = tenant()->user()->first() ?? null;
            $tenant_subdomain = !is_null($tenant_user) ? tenant()->id . '/' : '';
        }

        $path_prefix = is_null(tenant()) ? 'assets/landlord' : 'assets/tenant';
        $path = global_asset($path_prefix . '/uploads/media-uploader/' . $tenant_subdomain);
        $base_path = global_assets_path($path_prefix . '/uploads/media-uploader/' . $tenant_subdomain);
        $image_url = $path . $image_details;
        switch ($size) {
            case "large":
                if ($base_path . 'large/large-' . $image_details && !is_dir($base_path . 'large/large-' . $image_details)) {
                    $image_url = $path . '/large/large-' . $image_details;
                }
                break;
            case "grid":
                if ($base_path . 'grid/grid-' . $image_details && !is_dir($base_path . 'grid/grid-' . $image_details)) {
                    $image_url = $path . '/grid/grid-' . $image_details;
                }
                break;
            case "thumb":
                if ($base_path . 'thumb/thumb-' . $image_details && !is_dir($base_path . 'thumb/thumb-' . $image_details)) {
                    $image_url = $path . '/thumb/thumb-' . $image_details;
                }
                break;
            default:
                if (is_numeric($id) && file_exists($base_path . $image_details) && !is_dir($base_path . $image_details)) {
                    $image_url = $path . '/' . $image_details;
                }
                break;
        }
    }

    if (!empty($image_details)) {
        $return_val['image_id'] = $id;
        $return_val['path'] = $image_details;
        $return_val['img_url'] = $image_url;
        $return_val['img_alt'] = $alt;
    } elseif (empty($image_details) && $default) {
        $return_val['img_url'] = global_asset('no-image.jpeg');
        $return_val['img_alt'] = '';
    }

    return $return_val;
}


function formatBytes($size, $precision = 2)
{
    $base = log($size, 1024);
    $suffixes = array('', 'KB', 'MB', 'GB', 'TB');

    return round(1024 ** ($base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
}


/**
 * @param $key
 * @param $value
 * @return bool
 */
function update_static_option($key, $value): bool
{

    $static_option = null;
    if ($static_option === null) {
        try {
            $static_option = StaticOption::query();
        } catch (\Exception $e) {
        }
    }
    try {
        $static_option->updateOrCreate(['option_name' => $key], [
            'option_name' => $key,
            'option_value' => $value
        ]);
    } catch (\Exception $e) {
        return false;
    }

    \Illuminate\Support\Facades\Cache::forget($key);
    return true;
}

function update_static_option_central($key, $value): bool
{
    $static_option = null;
    if ($static_option === null) {
        try {
            $static_option = \App\Models\StaticOptionCentral::query();
        } catch (\Exception $e) {
        }
    }
    try {
        $static_option->updateOrCreate(['option_name' => $key], [
            'option_name' => $key,
            'option_value' => $value
        ]);
    } catch (\Exception $e) {
        return false;
    }

    \Illuminate\Support\Facades\Cache::forget($key);
    return true;
}

function delete_static_option($key)
{
    try {
        return StaticOption::where('option_name', $key)->delete();
    } catch (\Exception $e) {
        //handle error
    }
}

function filter_static_option_value(string $index, array $array = [])
{
    return $array[$index] ?? '';
}

function render_favicon_by_id($id): string
{

    $site_favicon = get_attachment_image_by_id($id, "full", false);
    $output = '';
    if (!empty($site_favicon)) {
        $output .= '<link rel="icon" href="' . $site_favicon['img_url'] . '" type="image/png">';
    }
    return $output;
}

function render_image_markup_by_attachment_path($id, $alt, $path, $class = null, $size = 'full', $default = false): string
{
    $image_details = get_attachment_image_by_path($id, $path, $alt, $size, $default);
    if (!empty($image_details)) {
        $class_list = !empty($class) ? 'class="' . $class . '"' : '';
        $output = '<img src="' . $image_details['img_url'] . '" ' . $class_list . ' alt="' . $image_details['img_alt'] . '"/>';
    }
    return $output;
}

function render_image_markup_by_attachment_id($id, $class = null, $size = 'full', $default = false, $data_animation = null, $data_delay = null): string
{
    if (empty($id) && !$default) return '';
    $output = '';

    $image_details = get_attachment_image_by_id($id, $size, $default);
    if (!empty($image_details)) {
        $class_list = !empty($class) ? 'class="' . $class . '"' : '';
        $output = '<img src="' . $image_details['img_url'] . '" ' . $class_list. 'alt="' . $image_details['img_alt'].'"'. 'data-animation="'.$data_animation.'"'. 'data-delay="'.$data_delay. '"/>';
    }
    return $output;
}

function get_theme_image($slug)
{
    switch ($slug) {
        case 'theme-1':
            return $theme_image = asset('assets/img/theme/th1.jpg');

        case 'theme-2':
            return $theme_image = asset('assets/img/theme/th2.jpg');

        case 'theme-3':
            return $theme_image = asset('assets/img/theme/th3.jpg');
    }
}

function render_background_image_markup_by_attachment_id($id, $size = 'full'): string
{
    if (empty($id)) return '';
    $output = '';

    $image_details = get_attachment_image_by_id($id, $size);
    if (!empty($image_details)) {
        $output = 'style="background-image: url(' . $image_details['img_url'] . ');"';
    }
    return $output;
}

function render_image_url_by_attachment_id($id, $class = null, $size = 'full', $default = false, $data_animation = null, $data_delay = null): string
{
    if (empty($id) && !$default) return '';
    $output = '';
    $image_details = get_attachment_image_by_id($id, $size, $default);
    if (!empty($image_details)) {
        $output = $image_details['img_url'];
    }
    return $output;
}

function render_og_meta_image_by_attachment_id($id, $size = 'full'): string
{
    if (empty($id)) return '';
    $output = '';

    $image_details = get_attachment_image_by_id($id, $size);
    if (!empty($image_details)) {
        $output = ' <meta property="og:image" content="' . $image_details['img_url'] . '" />';
    }
    return $output;
}

function get_footer_copyright_text($lang_slug)
{
    $footer_copyright_text = get_static_option('site_' . $lang_slug . '_footer_copyright_text');
    return str_replace(array('{copy}', '{year}'), array('&copy;', date('Y')), $footer_copyright_text);
}

function get_footer_copyright_text_tenant($lang_slug)
{
    $footer_copyright_text = get_static_option('site_' . $lang_slug . '_footer_copyright_text');
    $site_title = get_static_option('site_'.get_user_lang().'_title');
    $data = str_replace(array('{copy}', '{year}'), array('&copy;', date('Y')), $footer_copyright_text);
    $root = url('/');

    return  <<<TCOPYRIGHT
           <p class="pera wow ladeInUp" data-wow-delay="0.0s">
               <a href="{$root}" target="_blank">{$site_title}</a>
                {$data}
            </p>
TCOPYRIGHT;

}

function get_modified_title($title)
{
    if (str_contains($title, '{h}') && str_contains($title, '{/h}')) {
        $text = explode('{h}', $title);

        $highlighted_word = explode('{/h}', $text[1])[0];

        $highlighted_text = '<span class="color">' . $highlighted_word . '</span>';
        return $final_title = '<h2 class="tittle p-0">' . str_replace('{h}' . $highlighted_word . '{/h}', $highlighted_text, $title) . '</h2>';

    } else {
        return $final_title = '<h2 class="tittle p-0">' . $title . '</h2>';
    }
}

function get_modified_title_tenant($title)
{
    if (str_contains($title, '{h}') && str_contains($title, '{/h}'))
    {
        $text = explode('{h}',$title);
        $highlighted_word = explode('{/h}', $text[1])[0];
        $highlighted_text = '<span class="textColor">'. $highlighted_word .'</span>';
        return $final_title = '<h1 class="tittle">'.str_replace('{h}'.$highlighted_word.'{/h}', $highlighted_text, $title).'</h1>';
    } else {
        return $final_title = '<h1 class="tittle">'. $title .'</h2>';
    }
}




function get_modified_title_tenant_event($title)
{

    if (str_contains($title, '{h}') && str_contains($title, '{/h}'))
    {
        $text = explode('{h}',$title);
        $highlighted_word = explode('{/h}', $text[1])[0];

        $highlighted_text = '';
        for ($i = 0; $i< strlen($highlighted_word); $i++){
            $highlighted_text.= '<span class="single" style="--i:'.$i.'">'. $highlighted_word[$i] .'</span>';
        }


        $higlight_word_markup = ' <span class="textFlip tittleBgOne wow ladeInRight" data-wow-delay="0.0s"> '.$highlighted_text. '</span>';

        return  '<h2 class="tittle"> '. str_replace('{h}'.$highlighted_word.'{/h}', $higlight_word_markup, $title). ' </h2>';

    } else {
        return '<h2 class="tittle"><span class="single">'. $title .'</span></h2>';
    }
}


function get_modified_title_tenant_event_header($title)
{

    if (str_contains($title, '{h}') && str_contains($title, '{/h}'))
    {
        $text = explode('{h}',$title);
        $highlighted_word = explode('{/h}', $text[1])[0];

        $highlighted_text = '';
        for ($i = 0; $i< strlen($highlighted_word); $i++){

            $highlighted_text.= '<span class="single" style="--i:'.$i.'">'. $highlighted_word[$i] .'</span>';
        }

        $higlight_word_markup = ' <span class="textFlip tittleBgColor colorEffect2"> '.$highlighted_text. '</span>';

        return  '<h1 class="tittle textEffect" data-animation="ladeInUp" data-delay="0.1s"> '. str_replace('{h}'.$highlighted_word.'{/h}', $higlight_word_markup, $title). ' </h1>';

    } else {
        return '<h1 class="tittle textEffect" data-animation="ladeInUp" data-delay="0.1s"><span class="single">'. $title .'</span></h1>';
    }
}


function get_modified_title_knowledgebase($title)
{
    if (str_contains($title, '{h}') && str_contains($title, '{/h}')) {
        $text = explode('{h}', $title);

        $highlighted_word = explode('{/h}', $text[1])[0];

        $highlighted_text = '<span class="circle">' . $highlighted_word . '</span>';
        return $final_title = '<h1 class="tittle wow fadeInUp" data-wow-delay="0.0s">' . str_replace('{h}' . $highlighted_word . '{/h}', $highlighted_text, $title) . '</h1>';

    } else {
        return $final_title = '<h1 class="tittle wow fadeInUp" data-wow-delay="0.0s">' . $title . '</h1>';
    }
}


function get_modified_title_ticket($title)
{
    if (str_contains($title, '{h}') && str_contains($title, '{/h}')) {
        $text = explode('{h}', $title);

        $highlighted_word = explode('{/h}', $text[1])[0];

        $highlighted_text = '<span class="tittleBg">' . $highlighted_word . '</span>';
        return $final_title = '<h1 class="tittle wow fadeInUp" data-wow-delay="0.0s">' . str_replace('{h}' . $highlighted_word . '{/h}', $highlighted_text, $title) . '</h1>';

    } else {
        return $final_title = '<h1 class="tittle wow fadeInUp" data-wow-delay="0.0s">' . $title . '</h1>';
    }
}

function get_modified_title_agency($title)
{
    if (str_contains($title, '{h}') && str_contains($title, '{/h}')) {
        $text = explode('{h}', $title);

        $highlighted_word = explode('{/h}', $text[1])[0];

        $highlighted_text = '<span class="title_style">' . $highlighted_word . '</span>';
        return '</span>' . str_replace('{h}' . $highlighted_word . '{/h}', $highlighted_text, $title) . '</span>';

    }else{
        return  '</span>' .$title ?? '';
    }
}

function get_modified_title_agency_two($title)
{

    if (str_contains($title, '{h}') && str_contains($title, '{/h}')) {
        $text = explode('{h}', $title);

        $highlighted_word = explode('{/h}', $text[1])[0];


        $highlighted_text = '<span class="title_style">' . $highlighted_word . '</span>';

          return '<h2 class="title">' . str_replace('{h}' . purify_html($highlighted_word) . '{/h}', $highlighted_text, $title) . '</h2>';


    } else {
        return '<h2 class="title">' . $title . '</h2>';
    }
}



function get_price_plan_expire_status($date_expire): string
{
    $expire_date = Carbon::parse($date_expire);
    $now_date = \Carbon\Carbon::parse()->today();
    return $now_date > $expire_date ? 'expired' : 'active';
}

function get_trial_status($payment_log_create_date, $trial_days): string
{
    $create_date = $payment_log_create_date;
    $trial_expire_date = \Carbon\Carbon::parse($create_date)->addDays($trial_days);
    $now_date = \Carbon\Carbon::parse(now());

    return $now_date->greaterThan($trial_expire_date) ? __('expired') : __('active');
}

function get_trial_days_left($tenant){
    $trial_days = optional(optional($tenant->payment_log)->package)->trial_days;
    $will_expire = \Illuminate\Support\Carbon::parse($tenant->created_at)->addDays($trial_days);
    return Carbon::now()->diffInDays($will_expire, false);
}


function setEnvValue(array $values)
{
    $envFile = app()->environmentFilePath();

    $str = file_get_contents($envFile);

    if (count($values) > 0) {

        foreach ($values as $envKey => $envValue) {

            $str .= "\n"; // In case the searched variable is in the last line without \n
            $keyPosition = strpos($str, "{$envKey}=");

            $endOfLinePosition = strpos($str, "\n", $keyPosition);
            $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

            // If key does not exist, add it
            if ($keyPosition === false || !$endOfLinePosition || !$oldLine) {
                $str .= "{$envKey}={$envValue}\n";
            } else {
                $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
            }
        }
    }

    $str = substr($str, 0, -1);
    if (!file_put_contents($envFile, $str)) return false;
    return true;
}

function addQuotes($str)
{
    return '"' . $str . '"';
}

function site_title()
{
    return get_static_option('site_' . \App\Facades\GlobalLanguage::default_slug() . '_title');
}

function site_global_email()
{
    $admin_mail_check = is_null(tenant()) ? get_static_option('site_global_email') : get_static_option('tenant_site_global_email');
    return $admin_mail_check;
}

function get_tenant_website_url($user_details)
{
    return '//' . $user_details->subdomain . '.' . current(config('tenancy.central_domains'));
}

function route_prefix($end = null)
{
    $prefix = is_null(tenant()) ? 'landlord' : 'tenant';
    return $prefix . '.' . $end;
}

function render_attachment_preview_for_admin($id)
{
    $markup = '';
    $header_bg_img = get_attachment_image_by_id($id, null, true);
    $img_url = $header_bg_img['img_url'] ?? '';
    $img_alt = $header_bg_img['img_alt'] ?? '';
    if (!empty($img_url)) {
        $markup = sprintf('<div class="attachment-preview"><div class="thumbnail"><div class="centered"><img class="avatar user-thumb" src="%1$s" alt="%2$s"></div></div></div>', $img_url, $img_alt);
    }
    return $markup;
}


function render_drag_drop_form_builder_markup($content = '')
{
    $output = '';

    $form_fields = json_decode($content);
    $output .= '<ul id="sortable" class="available-form-field main-fields">';
    if (!empty($form_fields)) {
        $select_index = 0;
        foreach ($form_fields->field_type as $key => $ftype) {
            $args = [];
            $required_field = '';
            if (property_exists($form_fields, 'field_required')) {
                $filed_requirement = (array)$form_fields->field_required;
                $required_field = !empty($filed_requirement[$key]) ? 'on' : '';
            }
            if ($ftype == 'select') {
                $args['select_option'] = isset($form_fields->select_options[$select_index]) ? $form_fields->select_options[$select_index] : '';
                $select_index++;
            }
            if ($ftype == 'file') {
                $args['mimes_type'] = isset($form_fields->mimes_type->$key) ? $form_fields->mimes_type->$key : '';
            }
            $output .= render_drag_drop_form_builder_field_markup($key, $ftype, $form_fields->field_name[$key], $form_fields->field_placeholder[$key], $required_field, $args);
        }
    } else {
        $output .= render_drag_drop_form_builder_field_markup('1', 'text', 'your-name', 'Your Name', '');
    }

    $output .= '</ul>';
    return $output;
}

function render_drag_drop_form_builder_field_markup($key, $type, $name, $placeholder, $required, $args = [])
{
    $required_check = !empty($required) ? 'checked' : '';
    $placeholder = htmlspecialchars(strip_tags($placeholder));
    $name = htmlspecialchars(strip_tags($name));
    $type = htmlspecialchars(strip_tags($type));
    $output = '<li class="ui-state-default">
                     <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                    <span class="remove-fields">x</span>
                    <a data-toggle="collapse" href="#fileds_collapse_' . $key . '" role="button"
                       aria-expanded="false" aria-controls="collapseExample">
                        ' . ucfirst($type) . ': <span
                                class="placeholder-name">' . $placeholder . '</span>
                    </a>';
    $output .= '<div class="collapse" id="fileds_collapse_' . $key . '">
            <div class="card card-body margin-top-30">
                <input type="hidden" class="form-control" name="field_type[]"
                       value="' . $type . '">
                <div class="form-group">
                    <label>' . __('Name') . '</label>
                    <input type="text" class="form-control " name="field_name[]"
                           placeholder="' . __('enter field name') . '"
                           value="' . $name . '" >
                </div>
                <div class="form-group">
                    <label>' . __('Placeholder/Label') . '</label>
                    <input type="text" class="form-control field-placeholder"
                           name="field_placeholder[]" placeholder="' . __('enter field placeholder/label') . '"
                           value="' . $placeholder . '" >
                </div>
                <div class="form-group">
                    <label><strong>' . __('Required') . '</strong></label>
                    <label class="switch">
                        <input type="checkbox" class="field-required" ' . $required_check . ' name="field_required[' . $key . ']">
                        <span class="slider-yes-no"></span>
                    </label>
                </div>';
    if ($type == 'select') {
        $output .= '<div class="form-group">
                        <label>' . __('Options') . '</label>
                            <textarea name="select_options[]" class="form-control max-height-120" cols="30" rows="10"
                                required>' . strip_tags($args['select_option']) . '</textarea>
                           <small>' . __('separate option by new line') . '</small>
                    </div>';
    }
    if ($type == 'file') {
        $output .= '<div class="form-group"><label>' . __('File Type') . '</label><select name="mimes_type[' . $key . ']" class="form-control mime-type">';
        $output .= '<option value="mimes:jpg,jpeg,png"';
        if (isset($args['mimes_type']) && $args['mimes_type'] == 'mimes:jpg,jpeg,png') {
            $output .= "selected";
        }
        $output .= '>' . __('mimes:jpg,jpeg,png') . '</option>';

        $output .= '<option value="mimes:txt,pdf"';
        if (isset($args['mimes_type']) && $args['mimes_type'] == 'mimes:txt,pdf') {
            $output .= "selected";
        }
        $output .= '>' . __('mimes:txt,pdf') . '</option>';

        $output .= '<option value="mimes:doc,docx"';
        if (isset($args['mimes_type']) && $args['mimes_type'] == 'mimes:mimes:doc,docx') {
            $output .= "selected";
        }
        $output .= '>' . __('mimes:mimes:doc,docx') . '</option>';

        $output .= '</select></div>';
    }
    $output .= '</div></div></li>';

    return $output;
}


function get_default_language()
{
    $defaultLang = \App\Models\Language::where('default', 1)->first();
    return $defaultLang->slug;
}

function core_path($path)
{
    return str_replace('core/', '', public_path($path));
}

function global_assets_path($path)
{
    return str_replace(['core/public/', 'core\\public\\'], '', public_path($path));
}

function get_page_slug($id, $default = null)
{
    return \App\Models\Page::where('id', $id)->first()->slug ?? $default;
}

function render_gallery_image_attachment_preview($gal_image)
{
    if (empty($gal_image)) {
        return;
    }
    $output = '';
    $gallery_images = explode('|', $gal_image);
    foreach ($gallery_images as $gl_img) {
        $work_section_img = get_attachment_image_by_id($gl_img, null, true);
        if (!empty($work_section_img)) {
            $output .= sprintf('<div class="attachment-preview"><div class="thumbnail"><div class="centered"><img class="avatar user-thumb" src="%1$s" alt=""> </div></div></div>', $work_section_img['img_url']);
        }
    }
    return $output;
}

function render_frontend_sidebar($location, $args = [])
{
    $output = '';
    $all_widgets = \App\Models\Widgets::where(['widget_location' => $location])->orderBy('widget_order', 'ASC')->get();
    foreach ($all_widgets as $widget) {
        $output .= \Plugins\WidgetBuilder\WidgetBuilderSetup::render_widgets_by_name_for_frontend([
            'name' => $widget->widget_name,
            'location' => $location,
            'id' => $widget->id,
            'column' => $args['column'] ?? false
        ]);
    }
    return $output;
}

function render_admin_panel_widgets_list()
{
    return \Plugins\WidgetBuilder\WidgetBuilderSetup::get_admin_panel_widgets();
}

function get_admin_sidebar_list()
{
    return \Plugins\WidgetBuilder\WidgetBuilderSetup::get_admin_widget_sidebar_list();
}

function render_admin_saved_widgets($location)
{
    $output = '';
    $all_widgets = \App\Models\Widgets::where(['widget_location' => $location])->orderBy('widget_order', 'asc')->get();
    foreach ($all_widgets as $widget) {
        $output .= \Plugins\WidgetBuilder\WidgetBuilderSetup::render_widgets_by_name_for_admin([
            'name' => $widget->widget_name,
            'id' => $widget->id,
            'type' => 'update',
            'order' => $widget->widget_order,
            'location' => $widget->widget_location
        ]);
    }

    return $output;
}

function single_post_share($url, $title, $img_url)
{
    $output = '';
    //get current page url
    $encoded_url = urlencode($url);
    //get current page title
    $post_title = str_replace(' ', '%20', $title);


    //all social share link generate
    $facebook_share_link = 'https://www.facebook.com/sharer/sharer.php?u=' . $encoded_url;
    $twitter_share_link = 'https://twitter.com/intent/tweet?text=' . $post_title . '&amp;url=' . $encoded_url . '&amp;via=Crunchify';
    $linkedin_share_link = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $encoded_url . '&amp;title=' . $post_title;
    $pinterest_share_link = 'https://pinterest.com/pin/create/button/?url=' . $encoded_url . '&amp;media=' . $img_url . '&amp;description=' . $post_title;
    $whatsapp_share_link = 'https://api.whatsapp.com/send?text=*' . $post_title.' '.$encoded_url ;
    $telegram_share_link = 'https://telegram.me/share/url?url=' .$encoded_url ;

    $output = '
        <li class="listItem"><a target="_blank" href="'.$facebook_share_link.'"><img src="'.global_asset('assets/tenant/frontend/themes/img/icon/donation-social-fb.svg').'" alt=""></a></li>
        <li class="listItem"><a target="_blank" href="'.$linkedin_share_link.'"><img src="'.global_asset('assets/tenant/frontend/themes/img/icon/donation-social-Linkedin.svg').'" alt=""></a></li>
        <li class="listItem"><a target="_blank" href="'.$pinterest_share_link.'"><img src="'.global_asset('assets/tenant/frontend/themes/img/icon/donation-social-Pinterest.svg').'" alt=""></a></li>
        <li class="listItem"><a target="_blank" href="#"><img src="'.global_asset('assets/tenant/frontend/themes/img/icon/donation-social-Wattpad.svg').'" alt=""></a></li>
        <li class="listItem"><a target="_blank" href="'.$whatsapp_share_link.'"><img src="'.global_asset('assets/tenant/frontend/themes/img/icon/donation-social-Whatsapp.svg').'" alt=""></a></li>
        <li class="listItem"><a target="_blank" href="'.$twitter_share_link.'"><img src="'.global_asset('assets/tenant/frontend/themes/img/icon/donation-social-Twitter.svg').'" alt=""></a></li>
        <li class="listItem"><a target="_blank" href="'.$linkedin_share_link.'"><img src="'.global_asset('assets/tenant/frontend/themes/img/icon/donation-social-Linkedin.svg').'" alt=""></a></li>
    ';

    return $output;
}


function single_blog_post_share($url, $title, $img_url)
{
    $output = '';
    //get current page url
    $encoded_url = urlencode($url);
    //get current page title
    $post_title = str_replace(' ', '%20', $title);


    //all social share link generate
    $facebook_share_link = 'https://www.facebook.com/sharer/sharer.php?u=' . $encoded_url;
    $twitter_share_link = 'https://twitter.com/intent/tweet?text=' . $post_title . '&amp;url=' . $encoded_url . '&amp;via=Crunchify';
    $linkedin_share_link = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $encoded_url . '&amp;title=' . $post_title;
    $telegram_share_link = 'https://telegram.me/share/url?url=' .$encoded_url ;

    $output = '
        <a href="'.$facebook_share_link.'" class="wow ladeInUp social animated" data-wow-delay="0.0s"><i class="fab fa-facebook-f icon"></i></a>
        <a href="'.$telegram_share_link.'" class="wow ladeInUp social animated" data-wow-delay="0.1s"><i class="fab fa-instagram icon"></i></a>
        <a href="'.$linkedin_share_link.'" class="wow ladeInUp social animated" data-wow-delay="0.2s"><i class="fab fa-linkedin-in icon"></i></a>
        <a href="'.$twitter_share_link.'" class="wow ladeInUp social animated" data-wow-delay="0.3s"><i class="fab fa-twitter icon"></i></a>
    ';

    return $output;
}

//New Menu Functions
function render_pages_list($lang = null)
{
    $instance = new \Plugins\MenuBuilder\MenuBuilderHelpers();
    return $instance->get_static_pages_list($lang);
}

function render_dynamic_pages_list($lang = null)
{
    $instance = new \Plugins\MenuBuilder\MenuBuilderHelpers();
    return $instance->get_post_type_page_list($lang);
}

function render_mega_menu_list($lang = null)
{
    $instance = new \Plugins\MenuBuilder\MegaMenuBuilderSetup();
    return $instance->render_mega_menu_list($lang);
}

function render_draggable_menu($id)
{
    $instance = new \Plugins\MenuBuilder\MenuBuilderAdminRender();
    return $instance->render_admin_panel_menu($id);
}

function render_frontend_menu($id)
{
    $instance = new \Plugins\MenuBuilder\MenuBuilderFrontendRender();
    return $instance->render_frrontend_panel_menu($id);
}

function get_navbar_style()
{
    $fallback = get_static_option('global_navbar_variant');
    if (request()->routeIs(route_prefix() . 'dynamic.page')) {
        $page_info = \App\Models\Page::where(['slug' => request()->path()])->first();
        return !is_null($page_info) && !is_null($page_info->navbar_variant) ? $page_info->navbar_variant : $fallback;
    } elseif (request()->routeIs('homepage')) {
        $page_info = \App\Models\Page::find(get_static_option('home_page'));
        return !is_null($page_info) ? $page_info->navbar_variant : $fallback;

    } elseif (request()->is('/')) {
        $page_info = \App\Models\Page::find(get_static_option('home_page'));
        return !is_null($page_info) ? $page_info->navbar_variant : $fallback;
    }
    return $fallback;
}


function get_footer_style()
{

    $fallback = get_static_option('global_footer_variant') ?? 01;
    if (request()->routeIs(route_prefix() . 'dynamic.page')) {

        $page_info = \App\Models\Page::where(['slug' => request()->path()])->first();
        return !is_null($page_info) && !is_null($page_info->footer_variant) ? $page_info->footer_variant : $fallback;
    } elseif (request()->routeIs('homepage')) {

        $page_info = \App\Models\Page::find(get_static_option('home_page'));
        return !is_null($page_info) ? $page_info->footer_variant : $fallback;

    } elseif (request()->is('/')) {

        $page_info = \App\Models\Page::find(get_static_option('home_page'));
        return !is_null($page_info) ? $page_info->footer_variant : $fallback;
    }

    return $fallback;
}

function purify_html_raw($html)
{
    return \Mews\Purifier\Facades\Purifier::clean($html);
}

function get_user_lang_direction()
{
    $default = \App\Models\Language::where('default', 1)->first();
    $user_direction = \App\Models\Language::where('slug', session()->get('lang'))->first();
    return !empty(session()->get('lang')) ? ($user_direction?->direction ?? 'ltr') : ($default?->direction ?? 'ltr');
}

function get_user_lang_direction_bool()
{
    $default = \App\Models\Language::where('default', 1)->first();
    $user_direction = \App\Models\Language::where('slug', session()->get('lang'))->first();

    $bool = !empty(session()->get('lang')) ? ($user_direction?->direction == 1) : ($default?->direction == 1);

    if($bool)
    {
        return 'true';
    }else
    {
        return 'false';
    }
}

function get_language_name_by_slug($slug)
{
    $data = \App\Models\Language::where('slug', $slug)->first();
    return $data->name;
}

function get_country_field($name, $id, $class)
{
    return '<select style="height:50px;" name="' . $name . '" id="' . $id . '" class="' . $class . '"><option value="">' . __('Select Country') . '</option><option value="Afghanistan" >Afghanistan</option><option value="Albania" >Albania</option><option value="Algeria" >Algeria</option><option value="American Samoa" >American Samoa</option><option value="Andorra" >Andorra</option><option value="Angola" >Angola</option><option value="Anguilla" >Anguilla</option><option value="Antarctica" >Antarctica</option><option value="Antigua and Barbuda" >Antigua and Barbuda</option><option value="Argentina" >Argentina</option><option value="Armenia" >Armenia</option><option value="Aruba" >Aruba</option><option value="Australia" >Australia</option><option value="Austria" >Austria</option><option value="Azerbaijan" >Azerbaijan</option><option value="Bahamas" >Bahamas</option><option value="Bahrain" >Bahrain</option><option value="Bangladesh" >Bangladesh</option><option value="Barbados" >Barbados</option><option value="Belarus" >Belarus</option><option value="Belgium" >Belgium</option><option value="Belize" >Belize</option><option value="Benin" >Benin</option><option value="Bermuda" >Bermuda</option><option value="Bhutan" >Bhutan</option><option value="Bolivia" >Bolivia</option><option value="Bosnia and Herzegovina" >Bosnia and Herzegovina</option><option value="Botswana" >Botswana</option><option value="Bouvet Island" >Bouvet Island</option><option value="Brazil" >Brazil</option><option value="British Indian Ocean Territory" >British Indian Ocean Territory</option><option value="Brunei Darussalam" >Brunei Darussalam</option><option value="Bulgaria" >Bulgaria</option><option value="Burkina Faso" >Burkina Faso</option><option value="Burundi" >Burundi</option><option value="Cambodia" >Cambodia</option><option value="Cameroon" >Cameroon</option><option value="Canada" >Canada</option><option value="Cape Verde" >Cape Verde</option><option value="Cayman Islands" >Cayman Islands</option><option value="Central African Republic" >Central African Republic</option><option value="Chad" >Chad</option><option value="Chile" >Chile</option><option value="China" >China</option><option value="Christmas Island" >Christmas Island</option><option value="Cocos (Keeling) Islands" >Cocos (Keeling) Islands</option><option value="Colombia" >Colombia</option><option value="Comoros" >Comoros</option><option value="Cook Islands" >Cook Islands</option><option value="Costa Rica" >Costa Rica</option><option value="Croatia (Hrvatska)" >Croatia (Hrvatska)</option><option value="Cuba" >Cuba</option><option value="Cyprus" >Cyprus</option><option value="Czech Republic" >Czech Republic</option><option value="Democratic Republic of the Congo" >Democratic Republic of the Congo</option><option value="Denmark" >Denmark</option><option value="Djibouti" >Djibouti</option><option value="Dominica" >Dominica</option><option value="Dominican Republic" >Dominican Republic</option><option value="East Timor" >East Timor</option><option value="Ecuador" >Ecuador</option><option value="Egypt" >Egypt</option><option value="El Salvador" >El Salvador</option><option value="Equatorial Guinea" >Equatorial Guinea</option><option value="Eritrea" >Eritrea</option><option value="Estonia" >Estonia</option><option value="Ethiopia" >Ethiopia</option><option value="Falkland Islands (Malvinas)" >Falkland Islands (Malvinas)</option><option value="Faroe Islands" >Faroe Islands</option><option value="Fiji" >Fiji</option><option value="Finland" >Finland</option><option value="France" >France</option><option value="France, Metropolitan" >France, Metropolitan</option><option value="French Guiana" >French Guiana</option><option value="French Polynesia" >French Polynesia</option><option value="French Southern Territories" >French Southern Territories</option><option value="Gabon" >Gabon</option><option value="Gambia" >Gambia</option><option value="Georgia" >Georgia</option><option value="Germany" >Germany</option><option value="Ghana" >Ghana</option><option value="Gibraltar" >Gibraltar</option><option value="Greece" >Greece</option><option value="Greenland" >Greenland</option><option value="Grenada" >Grenada</option><option value="Guadeloupe" >Guadeloupe</option><option value="Guam" >Guam</option><option value="Guatemala" >Guatemala</option><option value="Guernsey" >Guernsey</option><option value="Guinea" >Guinea</option><option value="Guinea-Bissau" >Guinea-Bissau</option><option value="Guyana" >Guyana</option><option value="Haiti" >Haiti</option><option value="Heard and Mc Donald Islands" >Heard and Mc Donald Islands</option><option value="Honduras" >Honduras</option><option value="Hong Kong" >Hong Kong</option><option value="Hungary" >Hungary</option><option value="Iceland" >Iceland</option><option value="India" >India</option><option value="Indonesia" >Indonesia</option><option value="Iran (Islamic Republic of)" >Iran (Islamic Republic of)</option><option value="Iraq" >Iraq</option><option value="Ireland" >Ireland</option><option value="Isle of Man" >Isle of Man</option><option value="Israel" >Israel</option><option value="Italy" >Italy</option><option value="Ivory Coast" >Ivory Coast</option><option value="Jamaica" >Jamaica</option><option value="Japan" >Japan</option><option value="Jersey" >Jersey</option><option value="Jordan" >Jordan</option><option value="Kazakhstan" >Kazakhstan</option><option value="Kenya" >Kenya</option><option value="Kiribati" >Kiribati</option><option value="Korea, Democratic People\'s Republic of" >Korea, Democratic People\'s Republic of</option><option value="Korea, Republic of" >Korea, Republic of</option><option value="Kosovo" >Kosovo</option><option value="Kuwait" >Kuwait</option><option value="Kyrgyzstan" >Kyrgyzstan</option><option value="Lao People\'s Democratic Republic" >Lao People\'s Democratic Republic</option><option value="Latvia" >Latvia</option><option value="Lebanon" >Lebanon</option><option value="Lesotho" >Lesotho</option><option value="Liberia" >Liberia</option><option value="Libyan Arab Jamahiriya" >Libyan Arab Jamahiriya</option><option value="Liechtenstein" >Liechtenstein</option><option value="Lithuania" >Lithuania</option><option value="Luxembourg" >Luxembourg</option><option value="Macau" >Macau</option><option value="Madagascar" >Madagascar</option><option value="Malawi" >Malawi</option><option value="Malaysia" >Malaysia</option><option value="Maldives" >Maldives</option><option value="Mali" >Mali</option><option value="Malta" >Malta</option><option value="Marshall Islands" >Marshall Islands</option><option value="Martinique" >Martinique</option><option value="Mauritania" >Mauritania</option><option value="Mauritius" >Mauritius</option><option value="Mayotte" >Mayotte</option><option value="Mexico" >Mexico</option><option value="Micronesia, Federated States of" >Micronesia, Federated States of</option><option value="Moldova, Republic of" >Moldova, Republic of</option><option value="Monaco" >Monaco</option><option value="Mongolia" >Mongolia</option><option value="Montenegro" >Montenegro</option><option value="Montserrat" >Montserrat</option><option value="Morocco" >Morocco</option><option value="Mozambique" >Mozambique</option><option value="Myanmar" >Myanmar</option><option value="Namibia" >Namibia</option><option value="Nauru" >Nauru</option><option value="Nepal" >Nepal</option><option value="Netherlands" >Netherlands</option><option value="Netherlands Antilles" >Netherlands Antilles</option><option value="New Caledonia" >New Caledonia</option><option value="New Zealand" >New Zealand</option><option value="Nicaragua" >Nicaragua</option><option value="Niger" >Niger</option><option value="Nigeria" >Nigeria</option><option value="Niue" >Niue</option><option value="Norfolk Island" >Norfolk Island</option><option value="North Macedonia" >North Macedonia</option><option value="Northern Mariana Islands" >Northern Mariana Islands</option><option value="Norway" >Norway</option><option value="Oman" >Oman</option><option value="Pakistan" >Pakistan</option><option value="Palau" >Palau</option><option value="Palestine" >Palestine</option><option value="Panama" >Panama</option><option value="Papua New Guinea" >Papua New Guinea</option><option value="Paraguay" >Paraguay</option><option value="Peru" >Peru</option><option value="Philippines" >Philippines</option><option value="Pitcairn" >Pitcairn</option><option value="Poland" >Poland</option><option value="Portugal" >Portugal</option><option value="Puerto Rico" >Puerto Rico</option><option value="Qatar" >Qatar</option><option value="Republic of Congo" >Republic of Congo</option><option value="Reunion" >Reunion</option><option value="Romania" >Romania</option><option value="Russian Federation" >Russian Federation</option><option value="Rwanda" >Rwanda</option><option value="Saint Kitts and Nevis" >Saint Kitts and Nevis</option><option value="Saint Lucia" >Saint Lucia</option><option value="Saint Vincent and the Grenadines" >Saint Vincent and the Grenadines</option><option value="Samoa" >Samoa</option><option value="San Marino" >San Marino</option><option value="Sao Tome and Principe" >Sao Tome and Principe</option><option value="Saudi Arabia" >Saudi Arabia</option><option value="Senegal" >Senegal</option><option value="Serbia" >Serbia</option><option value="Seychelles" >Seychelles</option><option value="Sierra Leone" >Sierra Leone</option><option value="Singapore" >Singapore</option><option value="Slovakia" >Slovakia</option><option value="Slovenia" >Slovenia</option><option value="Solomon Islands" >Solomon Islands</option><option value="Somalia" >Somalia</option><option value="South Africa" >South Africa</option><option value="South Georgia South Sandwich Islands" >South Georgia South Sandwich Islands</option><option value="South Sudan" >South Sudan</option><option value="Spain" >Spain</option><option value="Sri Lanka" >Sri Lanka</option><option value="St. Helena" >St. Helena</option><option value="St. Pierre and Miquelon" >St. Pierre and Miquelon</option><option value="Sudan" >Sudan</option><option value="Suriname" >Suriname</option><option value="Svalbard and Jan Mayen Islands" >Svalbard and Jan Mayen Islands</option><option value="Swaziland" >Swaziland</option><option value="Sweden" >Sweden</option><option value="Switzerland" >Switzerland</option><option value="Syrian Arab Republic" >Syrian Arab Republic</option><option value="Taiwan" >Taiwan</option><option value="Tajikistan" >Tajikistan</option><option value="Tanzania, United Republic of" >Tanzania, United Republic of</option><option value="Thailand" >Thailand</option><option value="Togo" >Togo</option><option value="Tokelau" >Tokelau</option><option value="Tonga" >Tonga</option><option value="Trinidad and Tobago" >Trinidad and Tobago</option><option value="Tunisia" >Tunisia</option><option value="Turkey" >Turkey</option><option value="Turkmenistan" >Turkmenistan</option><option value="Turks and Caicos Islands" >Turks and Caicos Islands</option><option value="Tuvalu" >Tuvalu</option><option value="Uganda" >Uganda</option><option value="Ukraine" >Ukraine</option><option value="United Arab Emirates" >United Arab Emirates</option><option value="United Kingdom" >United Kingdom</option><option value="United States" >United States</option><option value="United States minor outlying islands" >United States minor outlying islands</option><option value="Uruguay" >Uruguay</option><option value="Uzbekistan" >Uzbekistan</option><option value="Vanuatu" >Vanuatu</option><option value="Vatican City State" >Vatican City State</option><option value="Venezuela" >Venezuela</option><option value="Vietnam" >Vietnam</option><option value="Virgin Islands (British)" >Virgin Islands (British)</option><option value="Virgin Islands (U.S.)" >Virgin Islands (U.S.)</option><option value="Wallis and Futuna Islands" >Wallis and Futuna Islands</option><option value="Western Sahara" >Western Sahara</option><option value="Yemen" >Yemen</option><option value="Zambia" >Zambia</option><option value="Zimbabwe" >Zimbabwe</option></select>';
}

function render_footer_copyright_text()
{
    $footer_copyright_text = get_static_option('site_' . get_user_lang() . '_footer_copyright');
    $footer_copyright_text = str_replace('{copy}', '&copy;', $footer_copyright_text);
    $footer_copyright_text = str_replace('{year}', date('Y'), $footer_copyright_text);

    return purify_html_raw($footer_copyright_text);
}


function render_page_meta_data($blog_post)
{

    $site_url = url('/');
    $meta_title = optional($blog_post->metainfo)->title;
    $meta_description = optional($blog_post->metainfo)->description;
    $meta_image = get_attachment_image_by_id(optional($blog_post->metainfo)->image)['img_url'] ?? "";

    $facebook_meta_title = optional($blog_post->metainfo)->tw_title;
    $facebook_meta_description = optional($blog_post->metainfo)->fb_description;
    $facebook_meta_image = get_attachment_image_by_id(optional($blog_post->metainfo)->fb_image)['img_url'] ?? "";

    $twitter_meta_title = optional($blog_post->metainfo)->twitter_meta_tags;
    $twitter_meta_description = optional($blog_post->metainfo)->tw_description;
    $twitter_meta_image = get_attachment_image_by_id(optional($blog_post->metainfo)->tw_image)['img_url'] ?? "";


    return <<<HTML
       <meta property="meta_title" content="{$meta_title}">
       <meta property="og:image"content="{$meta_image}">
       <meta property="meta_description" content="{$meta_description}">
       <!--Facebook-->
       <meta property="og:url"content="{$site_url}" >
       <meta property="og:type"content="{$facebook_meta_title}" >
       <meta property="og:title"content="{$meta_title}" >
       <meta property="og:description"content="{$facebook_meta_description}" >
       <meta property="og:image"content="{$facebook_meta_image}">
       <!--Twitter-->
       <meta name="twitter:site" content="{$site_url}" >
       <meta name="twitter:title" content="{$twitter_meta_title}" >
       <meta name="twitter:description" content="$twitter_meta_description">
       <meta name="twitter:image" content="{$twitter_meta_image}">
HTML;

}

function script_currency_list()
{
    return \Xgenious\Paymentgateway\Base\GlobalCurrency::script_currency_list();
}

function site_currency_symbol($text = false)
{
    //custom symbol
    $custom_symbol = get_static_option('site_custom_currency_symbol');
    if(!empty($custom_symbol)){
         return $custom_symbol;
    }

    $all_currency = [
        'USD' => '$', 'EUR' => '€', 'INR' => '₹', 'IDR' => 'Rp', 'AUD' => 'A$', 'SGD' => 'S$', 'JPY' => '¥', 'GBP' => '£', 'MYR' => 'RM', 'PHP' => '₱', 'THB' => '฿', 'KRW' => '₩', 'NGN' => '₦', 'GHS' => 'GH₵', 'BRL' => 'R$',
        'BIF' => 'FBu', 'CAD' => 'C$', 'CDF' => 'FC', 'CVE' => 'Esc', 'GHP' => 'GH₵', 'GMD' => 'D', 'GNF' => 'FG', 'KES' => 'K', 'LRD' => 'L$', 'MWK' => 'MK', 'MZN' => 'MT', 'RWF' => 'R₣', 'SLL' => 'Le', 'STD' => 'Db', 'TZS' => 'TSh', 'UGX' => 'USh', 'XAF' => 'FCFA', 'XOF' => 'CFA', 'ZMK' => 'ZK', 'ZMW' => 'ZK', 'ZWD' => 'Z$',
        'AED' => 'د.إ', 'AFN' => '؋', 'ALL' => 'L', 'AMD' => '֏', 'ANG' => 'NAf', 'AOA' => 'Kz', 'ARS' => '$', 'AWG' => 'ƒ', 'AZN' => '₼', 'BAM' => 'KM', 'BBD' => 'Bds$', 'BDT' => '৳', 'BGN' => 'Лв', 'BMD' => '$', 'BND' => 'B$', 'BOB' => 'Bs', 'BSD' => 'B$', 'BWP' => 'P', 'BZD' => '$',
        'CHF' => 'CHf', 'CNY' => '¥', 'CLP' => '$', 'COP' => '$', 'CRC' => '₡', 'CZK' => 'Kč', 'DJF' => 'Fdj', 'DKK' => 'Kr', 'DOP' => 'RD$', 'DZD' => 'دج', 'EGP' => 'E£', 'ETB' => 'ብር', 'FJD' => 'FJ$', 'FKP' => '£', 'GEL' => 'ლ', 'GIP' => '£', 'GTQ' => 'Q',
        'GYD' => 'G$', 'HKD' => 'HK$', 'HNL' => 'L', 'HRK' => 'kn', 'HTG' => 'G', 'HUF' => 'Ft', 'ILS' => '₪', 'ISK' => 'kr', 'JMD' => '$', 'KGS' => 'Лв', 'KHR' => '៛', 'KMF' => 'CF', 'KYD' => '$', 'KZT' => '₸', 'LAK' => '₭', 'LBP' => 'ل.ل.', 'LKR' => 'ரூ', 'LSL' => 'L',
        'MAD' => 'MAD', 'MDL' => 'L', 'MGA' => 'Ar', 'MKD' => 'Ден', 'MMK' => 'K', 'MNT' => '₮', 'MOP' => 'MOP$', 'MRO' => 'MRU', 'MUR' => '₨', 'MVR' => 'Rf', 'MXN' => 'Mex$', 'NAD' => 'N$', 'NIO' => 'C$', 'NOK' => 'kr', 'NPR' => 'रू', 'NZD' => '$', 'PAB' => 'B/.', 'PEN' => 'S/', 'PGK' => 'K',
        'PKR' => '₨', 'PLN' => 'zł', 'PYG' => '₲', 'QAR' => 'QR', 'RON' => 'lei', 'RSD' => 'din', 'RUB' => '₽', 'SAR' => 'SR', 'SBD' => 'Si$', 'SCR' => 'SR', 'SEK' => 'kr', 'SHP' => '£', 'SOS' => 'Sh.so.', 'SRD' => '$', 'SZL' => 'E', 'TJS' => 'ЅM',
        'TRY' => '₺', 'TTD' => 'TT$', 'TWD' => 'NT$', 'UAH' => '₴', 'UYU' => '$U', 'UZS' => 'so\'m', 'VND' => '₫', 'VUV' => 'VT', 'WST' => 'WS$', 'XCD' => '$', 'XPF' => '₣', 'YER' => '﷼', 'ZAR' => 'R'
    ];

    $symbol = '$';
    $global_currency = get_static_option('site_global_currency');
    foreach ($all_currency as $currency => $sym) {
        if ($global_currency == $currency) {
            $symbol = $text ? $currency : $sym;
            break;
        }
    }
    return $symbol;
}

function amount_with_currency_symbol($amount, $text = false)
{
    $decimal_status = get_static_option('currency_amount_type_status');
    $decimal_or_integer_condition =  !empty($decimal_status) ? 2 : 0;

    $amount = number_format((float)$amount, $decimal_or_integer_condition, '.', ',');
    $position = get_static_option('site_currency_symbol_position');
    $symbol = site_currency_symbol($text);
    $return_val = $symbol . $amount;
    $space = '';
    if ($position == 'right') {
        $return_val = $amount . $symbol;
    }
    return $return_val;
}

function render_form_field_for_frontend($form_content)
{
    if (empty($form_content)) {
        return;
    }
    $output = '';
    $form_fields = json_decode($form_content);
    $select_index = 0;
    $options = [];
    foreach ($form_fields->field_type as $key => $value) {
        if (!empty($value)) {
            if ($value == 'select') {
                $options = explode("\n", $form_fields->select_options[$select_index]);
            }
            $required = isset($form_fields->field_required->$key) ? $form_fields->field_required->$key : '';
            $mimes = isset($form_fields->mimes_type->$key) ? $form_fields->mimes_type->$key : '';
            $output .= get_field_by_type($value, $form_fields->field_name[$key], $form_fields->field_placeholder[$key], $options, $required, $mimes);
            if ($value == 'select') {
                $select_index++;
            };
        }
    }
    return $output;
}

function module_dir($moduleName)
{
    return 'core/Modules/'.$moduleName.'/';
}

function get_module_view($moduleName, $fileName)
{
    return strtolower($moduleName).'::payment-gateway-view.'.$fileName;
}

function render_payment_gateway_for_form($cash_on_delivery = false)
{
    $output = '<div class="form-group col-12 payment-gateway-wrapper">';
    $output .= '<input id="slected_gateway_from_helper" type="hidden" name="selected_payment_gateway"
     value="' . get_static_option('site_default_payment_gateway') . '">';

    $all_gateway = \App\Models\PaymentGateway::all();
    $payment_gateway_list = !empty($all_gateway) ? $all_gateway->toArray() : [];

    //todo append payment gateway name from modules
    $modules_payment_gateway = getAllPaymentGatewayListWithImage();
    $all_gateway_with_custom = !empty($modules_payment_gateway) ? array_merge($payment_gateway_list, $modules_payment_gateway) : $payment_gateway_list;

    $output .= '<ul class="payment-gateway-list">';
    foreach ($all_gateway_with_custom as $gateway) {
        if ($gateway['status'] == true) {
            $selected = (get_static_option('site_default_payment_gateway') == $gateway['name']) ? 'selected' : '';
            $output .= '<li data-gateway="' . $gateway['name'] . '" class="single-gateway-item ' . $selected . '">';


            if (array_key_exists('module', $gateway))
            {
                $output .= '<img src="'.loadPaymentGatewayLogo(moduleName: $gateway['module'], gatewayName: $gateway['name']).'"';
            } else {
                $output .= render_image_markup_by_attachment_id($gateway['image']);
            }

            $output .= '</li>';
        }
    }
    $output .= '</ul>';


    $conditon = in_array(get_static_option('site_default_payment_gateway'),['manual_payment_','manual_payment']) ? 'd-block' : 'd-none';
    $conditon_2 = get_static_option('site_default_payment_gateway') == 'bank_transfer' ? 'd-block' : 'd-none';
    $conditon_3 = get_static_option('site_default_payment_gateway') == 'kinetic' ? 'd-block' : 'd-none';

       //manual payment markup passing
        $output .= ' <div class="form-group manual_payment_transaction_field '.$conditon.'">
                        <div class="label">'.__('Enter Manual Payment Transaction ID').'</div>
                        <input class="form-control btn-sm mb-3 py-3 p-3" type="text" name="transaction_id">
                        <p class="help-info">'.get_manual_payment_description().'</p>
                    </div>';

        //bank payment markup passing
        $output .= ' <div class="form-group bank_payment_field '.$conditon_2.'">
                            <div class="label">'.__('Upload Bank Transfer Document').'</div>
                            <input class="form-control btn-sm mb-3 py-3 p-3" type="file" name="manual_payment_attachment">
                            <p class="help-info my-3">'.get_bank_payment_description().'</p>
                        </div>';

        //add extra markup for kinetic payment
    $output .= ' <div class="form-group kinetic_payment_field '.$conditon_3.'">

                            <div class="label">'.__('Choose Payment Method').'</div>
                            <select name="kineticpay_bank" id="kineticpay_bank" class="select " data-allow_clear="true" data-placeholder="Select Bank">
                                <option value="" selected="selected">Select Bank</option>
                                <option value="ABMB0212">Alliance Bank Malaysia Berhad</option>
                                <option value="ABB0233">Affin Bank Berhad</option>
                                <option value="AMBB0209">AmBank (M) Berhad</option>
                                <option value="BCBB0235">CIMB Bank Berhad</option>
                                <option value="BIMB0340">Bank Islam Malaysia Berhad</option>
                                <option value="BKRM0602">Bank Kerjasama Rakyat Malaysia Berhad</option>
                                <option value="BMMB0341">Bank Muamalat (Malaysia) Berhad</option>
                                <option value="BSN0601">Bank Simpanan Nasional Berhad</option>
                                <option value="CIT0219">Citibank Berhad</option>
                                <option value="HLB0224">Hong Leong Bank Berhad</option>
                                <option value="HSBC0223">HSBC Bank Malaysia Berhad</option>
                                <option value="KFH0346">Kuwait Finance House</option>
                                <option value="MB2U0227">Maybank2u / Malayan Banking Berhad</option>
                                <option value="MBB0228">Maybank2E / Malayan Banking Berhad E</option>
                                <option value="OCBC0229">OCBC Bank (Malaysia) Berhad</option>
                                <option value="PBB0233">Public Bank Berhad</option>
                                <option value="RHB0218">RHB Bank Berhad</option>
                                <option value="SCB0216">Standard Chartered Bank (Malaysia) Berhad</option>
                                <option value="UOB0226">United Overseas Bank (Malaysia) Berhad</option>
                            </select>
                        </div>';

    //extra field data for payment gateway
   $output.= '<div class="payment_gateway_extra_field_information_wrap">';
    $output .= renderAllPaymentGatewayExtraInfoBlade();

    $output .= '</div>';
    $output .= '</div>';
    return $output;

}

function get_user_name_by_id($id)
{
    $user = \App\Models\User::find($id);
    return $user;
}


function set_seo_data($request)
{

    $request_data = [
        'meta_title' => SEOMeta::setTitle($request->meta_title),
        'meta_description' => SEOMeta::setDescription($request->meta_description),
        'meta_image' => SEOTools::jsonLd()->addImage($request->meta_image),

        'meta_fb_title' => OpenGraph::setTitle($request->meta_fb_title),
        'meta_fb_description' => OpenGraph::setDescription($request->meta_fb_description),
        'fb_image' => OpenGraph::addImages($request->fb_image),

        'meta_tw_title' => TwitterCard::setTitle($request->meta_tw_title),
        'meta_tw_description' => TwitterCard::setDescription($request->meta_tw_description),
        'tw_image' => TwitterCard::setImage($request->tw_image),
    ];

    return $request_data;
}

function canonical_url()
{
    if(get_static_option('site_canonical_settings') == 0){
        return url()->current();
    }

    if (\Illuminate\Support\Str::startsWith($current = url()->current(), 'https://www')) {
        return str_replace('https://www.', 'https://', $current);
    }
    return str_replace('https://', 'https://www.', $current);
}

function get_time_difference($time_type, $to)
{
    $from = \Illuminate\Support\Carbon::now();
    $type = 'diffIn' . ucfirst($time_type);

    $difference = $from->$type($to);

    return $difference;
}

function get_typography_suffix_by_theme($theme)
{
    $suffix = 'donation';

    if(!empty($theme)){

        switch ($theme){
            case 'donation':
                $suffix = 'theme_donation';
                break;

            case 'event':
                $suffix = 'theme_event';
                break;

            case 'job-find':
                $suffix = 'theme_job';
                break;

            case 'article-listing':
                $suffix = 'theme_knowledgebase';
                break;

            case 'support-ticketing':
                $suffix = 'theme_support_ticket';
                break;

            case 'eCommerce':
                $suffix = 'theme_ecommerce';
                break;

            case 'agency':
                $suffix = 'theme_agency';
                break;

            case 'newspaper':
                $suffix = 'theme_newspaper';
                break;

            case 'construction':
                $suffix = 'theme_construction';
                break;

            case 'consultancy':
                $suffix = 'theme_consultancy';
                break;

            case 'wedding':
                $suffix = 'theme_wedding';
                break;

            case 'photography':
                $suffix = 'theme_photography';
                break;

            case 'portfolio':
                $suffix = 'theme_portfolio';
                break;

            case 'software-business':
                $suffix = 'theme_software';
                break;

            case 'barber-shop':
                $suffix = 'theme_barber_shop';
                break;
        }
    }

    return $suffix;
}
function load_google_fonts_landlord()
{
    //google fonts link;
    $fonts_url = 'https://fonts.googleapis.com/css2?family=';
    //body fonts


    $body_font_family = get_static_option('body_font_family') ?? 'Open Sans';
    $heading_font_family = get_static_option('heading_font_family') ?? 'Montserrat';

    $load_body_font_family = str_replace(' ', '+', $body_font_family);
    $body_font_variant = get_static_option('body_font_variant');
    $body_font_variant_selected_arr = !empty($body_font_variant) ? unserialize($body_font_variant, ['class' => false]) : ['400'];
    $load_body_font_variant = is_array($body_font_variant_selected_arr) ? implode(';', $body_font_variant_selected_arr) : '400';

    $body_italic = '';
    preg_match('/1,/', $load_body_font_variant, $match);
    if (count($match) > 0) {
        $body_italic = 'ital,';
    } else {
        $load_body_font_variant = str_replace('0,', '', $load_body_font_variant);
    }


    $fonts_url .= $load_body_font_family . ':' . $body_italic . 'wght@' . str_replace('regular','400',$load_body_font_variant);
    $load_heading_font_family = str_replace(' ', '+', $heading_font_family);
    $heading_font_variant = get_static_option('heading_font_variant');
    $heading_font_variant_selected_arr = !empty($heading_font_variant) ? unserialize($heading_font_variant, ['class' => false]) : ['400'];
    $load_heading_font_variant = is_array($heading_font_variant_selected_arr) ? implode(';', $heading_font_variant_selected_arr) : '400';

    if (!empty(get_static_option('heading_font')) && $heading_font_family != $body_font_family) {

        $heading_italic = '';
        preg_match('/1,/', $load_heading_font_variant, $match);
        if (count($match) > 0) {
            $heading_italic = 'ital,';
        } else {
            $load_heading_font_variant = str_replace('0,', '', $load_heading_font_variant);
        }

        $fonts_url .= '&family=' . $load_heading_font_family . ':' . $heading_italic . 'wght@' . str_replace('regular','400',$load_heading_font_variant);
    }

    return sprintf('<link rel="preconnect" href="https://fonts.gstatic.com"> <link href="%1$s&display=swap" rel="stylesheet">', $fonts_url);
}



function load_google_fonts()
{
    //google fonts link;
    $fonts_url = 'https://fonts.googleapis.com/css2?family=';
    //body fonts

    $theme_suffix = get_typography_suffix_by_theme(get_static_option('tenant_default_theme'));

    $body_font_family = get_static_option('body_font_family_'.$theme_suffix) ?? 'Open Sans';
    $heading_font_family = get_static_option('heading_font_family_'.$theme_suffix) ?? 'Montserrat';

    $load_body_font_family = str_replace(' ', '+', $body_font_family);
    $body_font_variant = get_static_option('body_font_variant_'.$theme_suffix);
    $body_font_variant_selected_arr = !empty($body_font_variant) ? unserialize($body_font_variant, ['class' => false]) : ['400'];
    $load_body_font_variant = is_array($body_font_variant_selected_arr) ? implode(';', $body_font_variant_selected_arr) : '400';

    $body_italic = '';
    preg_match('/1,/', $load_body_font_variant, $match);
    if (count($match) > 0) {
        $body_italic = 'ital,';
    } else {
        $load_body_font_variant = str_replace('0,', '', $load_body_font_variant);
    }

    $fonts_url .= $load_body_font_family . ':' . $body_italic . 'wght@' . str_replace('regular','400',$load_body_font_variant);
    $load_heading_font_family = str_replace(' ', '+', $heading_font_family);
    $heading_font_variant = get_static_option('heading_font_variant_'.$theme_suffix);
    $heading_font_variant_selected_arr = !empty($heading_font_variant) ? unserialize($heading_font_variant, ['class' => false]) : ['400'];
    $load_heading_font_variant = is_array($heading_font_variant_selected_arr) ? implode(';', $heading_font_variant_selected_arr) : '400';

    if (!empty(get_static_option('heading_font_'.$theme_suffix)) && $heading_font_family != $body_font_family) {

        $heading_italic = '';
        preg_match('/1,/', $load_heading_font_variant, $match);
        if (count($match) > 0) {
            $heading_italic = 'ital,';
        } else {
            $load_heading_font_variant = str_replace('0,', '', $load_heading_font_variant);
        }

        $fonts_url .= '&family=' . $load_heading_font_family . ':' . $heading_italic . 'wght@' . str_replace('regular','400',$load_heading_font_variant);
    }

    return sprintf('<link rel="preconnect" href="https://fonts.gstatic.com"> <link href="%1$s&display=swap" rel="stylesheet">', $fonts_url);
}

function wrap_random_number($number)
{
    return random_int(111111, 999999) . $number . random_int(111111, 999999);
}

function purify_html($html)
{
    return strip_tags(\Mews\Purifier\Facades\Purifier::clean($html));
}

function tenant_url_with_protocol($url){
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
        $protocol = "https://";
    }else{
        $protocol = "http://";
    }

    return $protocol.$url;
}

function create_slug($sluggable_text, $model_name, $is_module = false, $module_name = null, $column_name = 'slug')  // Idea from Suzon extended by Md Zahid
{
    if ($is_module)
    {
        $model_path = 'Modules\\'.ucwords($module_name).'\Entities\\' . ucwords($model_name);
    } else {
        $model_path = '\App\Models\\' . ucwords($model_name);
    }

    $slug = \Illuminate\Support\Str::slug($sluggable_text);
    $check = true;

    do{
        $old_category = (new $model_path)->where($column_name, $slug)->orderBy('id','desc')->first();

        if ($old_category != null) {
            $old_category_name = $old_category->slug;
            $exploded = explode('-', $old_category_name);

            if (array_key_exists(1, $exploded)) {
                $number = end($exploded);

                if (is_numeric($number) == true)
                {
                    $number = (int)$number;
                    array_pop($exploded);

                    $final_array = array_merge($exploded, Arr::wrap(++$number));

                    $slug = implode('-',$final_array);
                } else {
                    $slug .= '-1';
                }
            } else {
                $slug .= '-1';
            }
        }else{
            $check = false;
        }
    } while ($check);

    return $slug;
}


function get_all_main_feature_create_permission($order_details){

    $check_image = '<img src="'.asset('assets/landlord/frontend/img/icon/check.svg').'" class="icon" alt="image">';
    $output = '';

    if(!empty($order_details->page_permission_feature)):
    $output.=  '<li class="single"> '.$check_image.' '.__('Page').$order_details->page_permission_feature.'</li>';
    endif;

      if(!empty($order_details->blog_permission_feature)):
          $output.=  '<li class="single"> '.$check_image.''.__('Blog').$order_details->blog_permission_feature .'</li>';
    endif;

     if(!empty($order_details->product_permission_feature)):
         $output.=  '<li class="single"> '.$check_image.''.__('Product').$order_details->product_permission_feature .'</li>';
      endif;

    if(!empty($order_details->service_permission_feature)):
        $output.=   '  <li class="single"> '.$check_image.''.__('Service').$order_details->service_permission_feature .'</li>';
    endif;

    if(!empty($order_details->donation_permission_feature)):
        $output.=  '  <li class="single"> '.$check_image.''.__('Donation').$order_details->donation_permission_feature .'</li>';
    endif;

    if(!empty($order_details->job_permission_feature)):
        $output.=   '  <li class="single"> '.$check_image.''.__('Job').$order_details->job_permission_feature .'</li>';
    endif;

    if(!empty($order_details->event_permission_feature)):
        $output.=  '  <li class="single"> '.$check_image.''.__('Event').$order_details->event_permission_feature .'</li>';
    endif;

    if(!empty($order_details->knowledgebase_permission_feature)):
        $output.= '  <li class="single"> '.$check_image.''.__('Article').$order_details->knowledgebase_permission_feature .'</li>';
    endif;

    if(!empty($order_details->portfolio_permission_feature)):
        $output.= '  <li class="single"> '.$check_image.''.__('Portfolio').$order_details->portfolio_permission_feature .'</li>';
    endif;

    return $output;
}


function get_manual_payment_description()
{
    $data = \App\Models\PaymentGateway::where('name','manual_payment_')->first();
    $manual_payment_description = '';

    if(!empty($data)){
        $decoded = json_decode($data->credentials);
        $manual_payment_description = $decoded->description;
        $manual_payment_description = str_replace(array('https://{url}', 'http://{url}'), array(url('/'), url('/')), $manual_payment_description);
    }

    return $manual_payment_description;
}

function get_bank_payment_description()
{
    $data = \App\Models\PaymentGateway::where('name','bank_transfer')->first();
    $manual_payment_description = '';

    if(!empty($data)){
        $decoded = json_decode($data->credentials);
        $manual_payment_description = $decoded->description;
        $manual_payment_description = str_replace(array('https://{url}', 'http://{url}'), array(url('/'), url('/')), $manual_payment_description);
    }

    return $manual_payment_description;
}

function default_lang()
{
    return \App\Facades\GlobalLanguage::default_slug();
}

function default_lang_name()
{
    $lang =  \App\Facades\GlobalLanguage::default_slug();
    return \App\Models\Language::where('slug',$lang)->first()?->name ?? '';
}

function render_img_url_data_attr($id, $attr)
{
    $header_bg_img = get_attachment_image_by_id($id, null, true);
    $img_url = $header_bg_img['img_url'] ?? '';
    return sprintf('data-%1$s="%2$s"', $attr, $img_url);
}


function custom_file_upload($file)
{
    $file_name = '';
    if (isset($file)){
        $uploaded_file = $file;
        $file_extension = $uploaded_file->getClientOriginalExtension();
        $file_name =  pathinfo($uploaded_file->getClientOriginalName(),PATHINFO_FILENAME).time().'.'.$file_extension;
        $uploaded_file->move('assets/uploads/custom-file',$file_name);
    }

    return $file_name;
}


function get_blog_created_user_image($admin_id)
{
    $admin = null;
    if(!empty($admin_id)){
         $admin = \App\Models\Admin::find($admin_id)->image ?? [];
    }
    return $admin;
}

function get_percentage($amount, $numb)
{
    if ($amount > 0) {
        return round($numb / ($amount / 100), 2);
    }
    return 0;
}

function get_dynamic_page_name_by_id($id)
{
    $name = '';
    if(!empty($id)){
        $name = \App\Models\Page::find($id)?->slug;
    }
    return $name ?? "x";
}

function get_plan_left_days($package_id, $tenant_expire_date)
{
    $order_details = \App\Models\PricePlan::find($package_id) ?? '';

    $package_start_date = '';
    $package_expire_date = '';

    if (!empty($order_details)) {
        if ($order_details->type == 0) { //monthly
            $package_start_date = Carbon::now()->format('d-m-Y h:i:s');
            $package_expire_date = Carbon::now()->addMonth(1)->format('d-m-Y h:i:s');

        } elseif ($order_details->type == 1) { //yearly
            $package_start_date = Carbon::now()->format('d-m-Y h:i:s');
            $package_expire_date = Carbon::now()->addYear(1)->format('d-m-Y h:i:s');
        } else { //lifetime
            $package_start_date = \Illuminate\Support\Carbon::now()->format('d-m-Y h:i:s');
            $package_expire_date = null;
        }
    }

    $left_days = 0;
    if ($package_expire_date != null) {
        $old_days_left = Carbon::now()->diff($tenant_expire_date);

        if ($old_days_left->invert == 0) {
            $left_days = $old_days_left->days;
        }

        $renew_left_days = 0;
        $renew_left_days = Carbon::parse($package_expire_date)->diffInDays();

        $sum_days = $left_days + $renew_left_days;
        $new_package_expire_date = Carbon::today()->addDays($sum_days)->format("d-m-Y h:i:s");
    } else {
        $new_package_expire_date = null;
    }

    return $left_days == 0 ? $package_expire_date : $new_package_expire_date;
}

function moduleExists($name): bool
{
    $module_status = json_decode(file_get_contents(__DIR__.'/../../modules_statuses.json'));
    return property_exists($module_status,$name) ? $module_status->$name : false;
}

 function sohan_custom_charecter_or_word_length_count(string $source, string $subject) : int
 {

    if(!empty($subject) && strlen($subject) < 2){
        $count = 0;
        for ($i=0; $i < strlen($source); $i++) {
            if ($source[$i] == $subject) {
                $count += 1;
            }
        }

        return $count;

    }else{
       return substr_count($source,$subject);
    }
}

function get_string_line_breaker($string, $number){

    if(!empty($string)){

        $explode = explode(' ',$string);
        $take_data = array_slice($explode,0,$number);

        $new_data = implode(' ',$take_data) . PHP_EOL;

        $final_data = array_diff($explode,$take_data);
        $result =  $new_data . '<span class="lineBreak"></span>'. implode(' ',$final_data);

        return $result;
    }

}

function get_product_dynamic_price($product_object)
{
    $is_expired = 0;
    $campaign_name = null;
    (double)$regular_price = $product_object->price;
    (double)$sale_price = $product_object->sale_price;
    $discount = null;

    if (!is_null($product_object?->campaign_product)) {
        if ($product_object?->campaign_product?->campaign?->status == 'publish') {
            $start_date = \Carbon\Carbon::parse($product_object?->campaign_product?->start_date);
            $end_date = \Carbon\Carbon::parse($product_object?->campaign_product?->end_date);

            if ($start_date->lessThanOrEqualTo(now()) && $end_date->greaterThanOrEqualTo(now())) {
                (string)$campaign_name = $product_object?->campaign_product?->campaign?->title;
                (double)$sale_price = $product_object?->campaign_product?->campaign_price;
                (double)$regular_price = $product_object->sale_price;

                $discount = 100 - round(($sale_price / $regular_price) * 100);
                $is_expired = 1;
            }
        }
    }

    $data['campaign_name'] = $campaign_name;
    $data['sale_price'] = $sale_price;
    $data['regular_price'] = $regular_price;
    $data['discount'] = $discount;
    $data['is_expired'] = $is_expired;

    return $data;
}


function render_product_star_rating_markup_with_count($product_object): string
{

    $sum = 0;
    $product_review = $product_object->reviews ?? [];
    $product_count = count($product_review) < 1 ? 1 : count($product_review);

    if ($product_count >= 1) {
        foreach ($product_review as $review) {
            $sum += $review?->rating;
        }
    } else {
        $sum = current($product_review)?->rating ?? 0;
    }

    $rating = $sum / $product_count;
    $star = (int)(2 * $rating) . '0';

    $rating_markup = '';
    if ($sum > 0) {
        $rating_markup = '<div class="ratings">
                            <span class="hide-rating"></span>
                            <span class="show-rating" style="width: ' . $star . '%' . '"></span>
                        </div>
                        <p>
                            <span class="total-ratings">(' . $product_count . ')</span>
                        </p>';
    }

    return '<div class="rating-wrap mt-2">
                 ' . $rating_markup . '
            </div>';
}

function product_prices($product_object, $class = '')
{
    $markup = '';
    $sale_price = $product_object->sale_price;
    if ($product_object->price != null) {
        $regular_price = $product_object->price;


//        $markup = '<div class="productPrice"><strong class="regularPrice ' . $class . '">' . amount_with_currency_symbol($regular_price) . '</strong>';
//        $markup .= '<span class="offerPrice">' . amount_with_currency_symbol($sale_price) . '</span></div>';

       //  todo here offerPrice class displayed regular price in related product list
        $markup = '<div class="productPrice"><strong class="regularPrice ' . $class . '">' . amount_with_currency_symbol($sale_price) . '</strong>';
        $markup .= '<span class="offerPrice">' . amount_with_currency_symbol($regular_price) . '</span></div>';

        return $markup;
    }

    return '<span class="flash-prices ' . $class . '">' . amount_with_currency_symbol($sale_price) . '</span>';
}

function render_star_rating_markup($rating): string
{
    $star = (int)(2 * $rating) . '0';

    return '<div class="rating-wrap mt-2">
                 <div class="ratings">
                      <span class="hide-rating"></span>
                      <span class="show-rating" style="width: ' . $star . '%' . '"></span>
                 </div>
            </div>';
}


function get_product_shipping_tax_data($billing_info)
{
    $data['shipping_cost'] = 0;
    $data['product_tax'] = 0;
    if ($billing_info) {
        if ($billing_info->state_id) {
            $data['product_tax'] = \Modules\TaxModule\Entities\StateTax::where(['country_id' => $billing_info->country_id, 'state_id' => $billing_info->state_id])->select('id', 'tax_percentage')->first()['tax_percentage'];
        } else {
            $data['product_tax'] = \Modules\TaxModule\Entities\CountryTax::where('country_id', $billing_info->country_id)->select('id', 'tax_percentage')->first()->toArray()['tax_percentage'];
        }
    }

    return $data;
}

function tenant_module_migrations_file_path($moduleName){
    return str_replace('database','',database_path()).'Modules/'.$moduleName.'/Database/Migrations';
}

function get_tenant_storage_info($format = 'kb')
{
    $file_size = 0;
    $tenant = tenant() ? tenant()->id : '';
    $scan_path = Storage::disk("root_url")->allFiles('assets/tenant/uploads/media-uploader/' . $tenant);

    foreach ($scan_path as $file) {
        clearstatcache();
        $exploded = explode('/', $file);
        if ($exploded[count($exploded) - 1] === '.DS_Store' || $file === 'NAN') {
            continue;
        }

        $file_size += filesize($file);
    }

    if (strtolower($format) == 'kb') {
        $file_size /= 1024;
    } elseif (strtolower($format) == 'mb') {
        $file_size = (($file_size / 1024)) / 1024;
    }

    return $file_size;
}

function get_slider_language_deriection(){
    return get_user_lang_direction() == 1 ? 'true' : 'false';
}

function get_consultancy_subtitle_line_breaker($subtitle)
{

    $main_subtitle = $subtitle ?? '';
    $explode = explode(' ',$main_subtitle) ?? [];

    $first_three_words = count($explode) > 3 ? array_slice($explode,0,4) : current($explode);
    $last_words = count($explode) > 3 ? array_diff($explode,$first_three_words) : end($explode);

    $final_first = !empty($first_three_words) ? is_string($first_three_words) ? $first_three_words :  implode(' ', $first_three_words) : '';
    $final_last = !empty($last_words) ? is_string($last_words) ? $last_words :  implode(' ',$last_words) : '';

        return '<h2 class="title">
                <span class="title__top">
                    '.$final_first.'
                    <span class="title__top__shape">
                        <img src="'.global_asset('assets/tenant/frontend/themes/img/consultancy/banner/consulting_banner_title_shape.svg').'" alt="">
                    </span>
                </span>
                <span class="title__bottom">
                    '.$final_last.'
                </span>
            </h2>';

}


function float_amount_with_currency_symbol($amount, $text = false): string
{
    $symbol = site_currency_symbol($text);
    $position = get_static_option('site_currency_symbol_position');

    if (empty($amount)) {
        $return_val = $symbol . $amount;
        if ($position == 'right') {
            $return_val = $amount . $symbol;
        }
    }

    $amount = number_format((float)$amount, 2, '.', '');

    $return_val = $symbol . $amount;

    if ($position == 'right') {
        $return_val = $amount . $symbol;
    }

    return $return_val;
}

function get_tenant_package_features() : array
{
    $package = tenant()->user()->first()?->payment_log()->first()?->package()->first() ?? [];
    $all_features = $package->plan_features ?? [];
    $check_feature_name = $all_features->pluck('feature_name')->toArray();

    return $check_feature_name;
}

function get_amount_after_landlord_coupon_apply($package_price,$coupon_code)
{
    $amount = 0;
    $coupon = Coupon::where('code',$coupon_code)->first();

    if(!empty($coupon)){
        if($coupon->discount_type == 'percentage'){
            $amount = $package_price - ($coupon->discount_amount / 100) * $package_price;
        }else{
            $amount = $package_price - $coupon->discount_amount;
        }
    }

    return $amount;
}

function get_amount_after_landlord_coupon_apply_discount($package_price,$coupon_code)
{
    $amount = 0;
    $coupon = Coupon::where('code',$coupon_code)->first();

    if(!empty($coupon)){
        if($coupon->discount_type == 'percentage'){
            $amount = ($coupon->discount_amount / 100) * $package_price;
        }else{
            $amount = $coupon->discount_amount;
        }
    }

    return $amount;
}

function get_modified_title_photography($title)
{
    $arrow_image_path = global_asset('assets/tenant/frontend/themes/img/photography/photography_section_titleShape.svg');

    if(!empty($title)){

        $main_title = $title;
        $explode = explode(' ',$main_title) ?? [];
        $last_word = array_slice($explode,-1) ?? [];
        $first_words = array_diff($explode,$last_word) ?? [];

        $final_first = !empty($first_words) ? implode(' ', $first_words) : ' ';
        $final_last = !empty($last_word) ? implode(' ',$last_word) : '';

        return '<h2 class="title">'.$final_first.'
                <span class="title__shapes"> '.$final_last.'
                    <img src="'. $arrow_image_path.'" alt="">
                </span>
            </h2>';
    }

}

function get_modified_title_portfolio($title)
{
    if (str_contains($title, '{h}') && str_contains($title, '{/h}')) {
        $text = explode('{h}', $title);

        $highlighted_word = explode('{/h}', $text[1])[0];

        $highlighted_text = '<span class="title__style">' . $highlighted_word . '</span>';
        return $final_title = '<h2 class="title">' . str_replace('{h}' . $highlighted_word . '{/h}', $highlighted_text, $title) . '</h2>';

    } else {
        return $final_title = '<h2 class="title">' . $title . '</h2>';
    }
}

function get_modified_title_barber($title)
{
    if (str_contains($title, '{h}') && str_contains($title, '{/h}')) {
        $text = explode('{h}', $title);

        $highlighted_word = explode('{/h}', $text[1])[0];

        $highlighted_text = '<span class="barberShop_banner__titleColor">' . $highlighted_word . '</span>';
        return $final_title = '<h1 class="barberShop_banner__title">' . str_replace('{h}' . $highlighted_word . '{/h}', $highlighted_text, $title) . '</h1>';

    } else {
        return $final_title = '<h1 class="barberShop_banner__title">' . $title . '</h1>';
    }
}

function get_modified_title_barber_two($title)
{
    if (str_contains($title, '{h}') && str_contains($title, '{/h}')) {
        $text = explode('{h}', $title);

        $highlighted_word = explode('{/h}', $text[1])[0];

        $highlighted_text = '<span class="titleColor">' . $highlighted_word . '</span>';
        return $final_title = '<h1 class="title">' . str_replace('{h}' . $highlighted_word . '{/h}', $highlighted_text, $title) . '</h1>';

    } else {
        return $final_title = '<h1 class="title">' . $title . '</h1>';
    }
}

function get_modified_title_barber_three($title)
{
    if (str_contains($title, '{h}') && str_contains($title, '{/h}')) {
        $text = explode('{h}', $title);

        $highlighted_word = explode('{/h}', $text[1])[0];

        $highlighted_text = '<span class="barberShop_banner__titleColor">' . $highlighted_word . '</span>';
        return $final_title = '<h2 class="barberShop_banner__title">' . str_replace('{h}' . $highlighted_word . '{/h}', $highlighted_text, $title) . '</h2>';

    } else {
        return $final_title = '<h2 class="barberShop_banner__title">' . $title . '</h2>';
    }
}

function theme_path($name)
{
    $theme = resource_path('views/themes/' . $name);
    return !empty($theme) ? $theme : '';
}
function theme_assets($file, $theme = ''): string
{
    $name = \App\Facades\ThemeDataFacade::getSelectedThemeSlug();
    return 'core/resources/views/themes/' . (empty($theme) ? $name : $theme) . '/assets/' . $file;
}

function theme_screenshots($name): string
{
    return 'core/resources/views/themes/' . $name . '/screenshot/';
}


function loadCss($file): string
{
    return route('tenant.custom.css.file.url', $file);
}


function loadJs($file): string
{
    return route('tenant.custom.js.file.url', $file);
}

function loadScreenshot($theme)
{
    return route('theme.primary.screenshot', $theme);
}

/**
 * @see themeView
 * @param string $view
 * @param array $data
 * @return mixed
 */
function themeView($view, $data = [])
{
    return \App\Facades\ThemeDataFacade::renderThemeView($view, $data);
}

function externalAddonImagepath($moduleName)
{
    return 'core/Modules/'.$moduleName.'/assets/addon-image/'; // 'assets/plugins/PageBuilder/images'
}

function getSelectedThemeSlug()
{
    return \App\Facades\ThemeDataFacade::getSelectedThemeSlug();
}

function getSelectedThemeData()
{
    return \App\Facades\ThemeDataFacade::getSelectedThemeData();
}

function getAllThemeDataForAdmin()
{
    return \App\Facades\ThemeDataFacade::getAllThemeDataForAdmin();
}

function getAllThemeData()
{
    return \App\Facades\ThemeDataFacade::getAllThemeData();
}

function getIndividualThemeDetails($theme_slug)
{
    return \App\Facades\ThemeDataFacade::getIndividualThemeDetails($theme_slug);
}

function renderPrimaryThemeScreenshot($theme_slug)
{
    return \App\Facades\ThemeDataFacade::renderPrimaryThemeScreenshot($theme_slug);
}

function renderFooterHookBladeFile()
{
    return \App\Facades\ThemeDataFacade::renderFooterHookBladeFile();
}

function theme_custom_name($theme_data)
{
    return !empty(get_static_option_central($theme_data->slug.'_theme_name')) ? get_static_option_central($theme_data->slug.'_theme_name') : $theme_data->name;
}

function get_data_without_extra_space_or_new_line($string)
{
    return trim(preg_replace('/\s\s+/', ' ',$string));
}

function replace_rgb_from_css_variable($key)
{
    return str_replace(['rgb(',')'],'',$key);
}

function get_landlord_modified_title($title)
{
    $condition_of_extra = get_static_option('section_title_extra_design_status');
    $title_class_condition = !empty($condition_of_extra) ? 'tittle' : 'title';

    if (str_contains($title, '{h}') && str_contains($title, '{/h}'))
    {
        $text = explode('{h}',$title);

        $highlighted_word = explode('{/h}', $text[1])[0];

        $highlighted_text = '<span class="color">'. $highlighted_word .'</span>';
        $final_title = '<h1 class="'.$title_class_condition.' wow fadeInUp" data-wow-delay="0.0s">'.str_replace('{h}'.$highlighted_word.'{/h}', $highlighted_text, $title).'</h2>';
    } else {
        $final_title = '<h1 class="'.$title_class_condition.' wow fadeInUp" data-wow-delay="0.0s">'. $title .'</h2>';
    }

    return $final_title;
}

function get_date_by_format($date = null)
{
    $formated_date = $date;

    $admin_set_style = get_static_option('date_display_style');

    if($admin_set_style == 'style_one'){
        $formated_date = date('d-m-Y',strtotime($date));

    }else if($admin_set_style == 'style_two'){
        $formated_date = date('d M, Y',strtotime($date));

    }else if($admin_set_style == 'style_three'){
        $formated_date = date('Y/m/d',strtotime($date));

    }else if($admin_set_style == 'style_four'){
        $formated_date = date('Y-m-d',strtotime($date));
    }

    return $formated_date;
}

function toFixed($number, $decimals = 2): string
{
    return number_format($number, $decimals, '.', "");
}

function get_page_builder_addon_preview_image( string $dir_name, string $image_name )
{
    return url('assets/plugins/PageBuilder/images/Tenant/'.$dir_name.'/'.$image_name);
}

function replace_instruction_url($data)
{
    $redirect_path = '#!';

    if(!empty($data)) {

        $check_for_other = str_contains($data,'@url');

        if ($check_for_other == true) {
            $redirect_path = str_replace(['@url','@url/'],[
                url('/'),
                url('/'). '/',

            ],$data);

         //   $redirect_path = url('/') . '/' . $redirect_path;


        } else if ($data == '@color_settings') {
            $redirect_path = 'admin-home/general-settings/color-settings';

        } else if ($data == '@logo_settings') {
            $redirect_path = 'admin-home/general-settings/site-identity';


        } else if ($data == '@basic_settings') {
            $redirect_path = 'admin-home/general-settings/basic-settings';


        } else if ($data == '@email_settings') {
            $redirect_path = 'admin-home/general-settings/email-settings';


        } else if ($data == '@edit_forms') {
            $redirect_path = 'admin-home/custom-form-builder/all';

        } else if ($data == '@edit_pages') {
            $redirect_path = 'admin-home/pages';

        } else if ($data == '@customize_menu') {
            $redirect_path = 'admin-home/menu/menu-edit/1';


        } else if ($data == '@customize_footer') {
            $redirect_path = 'admin-home/tenant/widgets';


        } else if ($data == '@connect_domain') {
            $redirect_path = 'admin-home/custom-domain/custom-domain-request';


        } else if ($data == '@edit_seo') {
            $redirect_path = 'admin-home/general-settings/seo-settings';


        } else if ($data == '@edit_profile') {
            $redirect_path = 'admin-home/edit-profile';


        } else if ($data == '@set_language') {
            $redirect_path = 'admin-home/languages';

        } else if ($data == '@page_settings') {
            $redirect_path = 'admin-home/general-settings/page-settings';
        }

    }


    return $redirect_path;
}

function get_appointment_tax_amount($appointment_id, $subtotal)
{

    $tax_amount = 0;

    if(!empty($appointment_id)){
        $tax_info = AppointmentTax::where('appointment_id',$appointment_id)->first();

        if(!empty($tax_info)){
            $tax_amount = $tax_info->tax_type == 'exclusive' ? $subtotal * $tax_info->tax_amount / 100 : 0;
        }
    }

    return $tax_amount;

}

function get_appointment_tax_amount_percentage($appointment_id)
{
    $percentage = 0;

    if(!empty($appointment_id)){
        $percentage = AppointmentTax::where('appointment_id',$appointment_id)->first();
        $percentage = !empty($percentage->tax_amount) ? '<small class="text-success">('.$percentage->tax_amount . '%)'.'</small>' : '';

    }

    return $percentage;

}

function SMTP_test(){
    try{
        $transport = \Illuminate\Support\Facades\Mail::newInstance('smtp.exemple.com', '465', 'ssl');
        $transport->setUsername('username@exemple.com');
        $transport->setPassword('supersecret');
        $mailer = \Swift_Mailer::newInstance($transport);
        $mailer->getTransport()->start();
        return 'ok';
    } catch (Swift_TransportException $e) {
        return $e->getMessage();
    } catch (Exception $e) {
        return $e->getMessage();
    }
}
