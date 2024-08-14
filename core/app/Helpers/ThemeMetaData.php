<?php

namespace App\Helpers;

use DebugBar\DataCollector\Renderable;
use Illuminate\View\View;
use JetBrains\PhpStorm\Pure;

class ThemeMetaData
{
    public function __construct(public ?string $themeSlug = null)
    {

    }

    public function getSelectedThemeData()
    {
        $selectedThemeData = null;
        $tenant_theme = tenant()->theme_slug ?? '';
        $theme = $this->getIndividualThemeData($tenant_theme);
        if (!empty($theme)) {
            $selectedThemeData = $theme;
        }

        return $selectedThemeData;
    }

    public function getSelectedThemeSlug()
    {
        $themeData = $this->getSelectedThemeData();
        return !empty($themeData) ? $themeData->slug : null;
    }

    public function getHeaderHook()
    {
        $headerHook = [];
        $themeMeta = $this->getSelectedThemeData();
        if (!empty($themeMeta) && property_exists($themeMeta, 'headerHook')) {
            $headerHook = current($themeMeta->headerHook);
        }

        return $headerHook;
    }

    public function getFooterHook()
    {
        $footerHook = [];
        $themeMeta = $this->getSelectedThemeData();
        if (!empty($themeMeta) && property_exists($themeMeta, 'footerHook')) {
            $footerHook = current($themeMeta->footerHook);
        }

        return $footerHook;
    }

    public function getHeaderHookCssFiles()
    {
        $file_name = [];
        $headerHook = $this->getHeaderHook();
        if (!empty($headerHook) && property_exists($headerHook, 'style')) {
            foreach ($headerHook->style as $item) {
                $file_name[] = $item;
            }
        }

        return $file_name;
    }

    public function getHeaderHookRtlCssFiles()
    {
        $file_name = [];
        $headerHook = $this->getHeaderHook();
        if (!empty($headerHook) && property_exists($headerHook, 'rtl_style')) {
            foreach ($headerHook->rtl_style as $item) {
                $file_name[] = $item;
            }
        }

        return $file_name;
    }

    public function getHeaderHookJsFiles()
    {
        $file_name = [];
        $headerHook = $this->getHeaderHook();
        if (!empty($headerHook) && property_exists($headerHook, 'script')) {
            foreach ($headerHook->script as $item) {
                $file_name[] = $item;
            }
        }

        return $file_name;
    }

    public function getFooterHookCssFiles()
    {
        $file_name = [];
        $footerHook = $this->getFooterHook();
        if (!empty($footerHook) && property_exists($footerHook, 'style')) {
            foreach ($footerHook->style as $item) {
                $file_name[] = $item;
            }
        }

        return $file_name;
    }

    public function getFooterHookJsFiles()
    {
        $file_name = [];
        $footerHook = $this->getFooterHook();
        if (!empty($footerHook) && property_exists($footerHook, 'script')) {
            foreach ($footerHook->script as $item) {
                $file_name[] = $item;
            }
        }

        return $file_name;
    }

    public function renderHeaderHookBladeFile()
    {
        $headerHook = $this->getHeaderHook();
        $current_theme = $this->getSelectedThemeSlug();
        $return_val = '';

        if (!empty($headerHook) && property_exists($headerHook, 'blade')) {
            if (count($headerHook->blade) > 0) {
                foreach ($headerHook->blade as $bl) {
                    $file_name = 'themes.' . $current_theme . '.headerHookTemplate.' . $bl;
                    if (\view()->exists($file_name)) {
                        $return_val .= \view($file_name)->render() . "\n";
                    }
                }
            }
        }

        return $return_val;
    }

    public function renderFooterHookBladeFile()
    {
        $footerHook = $this->getFooterHook();
        $current_theme = $this->getSelectedThemeSlug();
        $return_val = '';
        if (!empty($footerHook) && property_exists($footerHook, 'blade')) {
            if (count($footerHook->blade) > 0) {
                foreach ($footerHook->blade as $bl) {
                    $file_name = 'themes.' . $current_theme . '.footerHookTemplate.' . $bl;
                    if (\view()->exists($file_name)) {
                        $return_val .= \view($file_name)->render() . "\n";
                    }
                }

            }
        }

        return $return_val;
    }

    public function getFooterWidgetArea()
    {
        $widget_area_file_name = '';
        $footerHook = $this->getFooterHook();

        if (!empty($footerHook) && property_exists($footerHook, 'widgetArea')) {
            if (!empty($footerHook->widgetArea)) {
                $widget_area_file_name = $footerHook->widgetArea;
            }
        }

        return $widget_area_file_name;
    }

    public function getHeaderNavbarArea()
    {
        $navbar_area_file_name = '';
        $headerHook = $this->getHeaderHook();

        if (!empty($headerHook) && property_exists($headerHook, 'navbarArea')) {
            if (!empty($headerHook->navbarArea)) {
                $navbar_area_file_name = $headerHook->navbarArea;
            }
        }

        return $navbar_area_file_name;
    }

    public function getHeaderBreadcrumbArea()
    {
        $navbar_area_file_name = '';
        $headerHook = $this->getHeaderHook();

        if (!empty($headerHook) && property_exists($headerHook, 'breadcrumbArea')) {
            if (!empty($headerHook->breadcrumbArea)) {
                $navbar_area_file_name = $headerHook->breadcrumbArea;
            }
        }

        return $navbar_area_file_name;
    }

    /**
     * @param string $view
     * @param array $data
     * @method renderThemeView
     */
//    public function renderThemeView($view = '', $data = []): Application|Factory|View
//    {
//        $theme_slug = $this->getSelectedThemeSlug();
//        return view('themes.' . $theme_slug . '.frontend.' . $view, $data);
//    }

    public function renderThemeView($view = '', $data = []): Application|Factory|View
    {
        $theme_slug = $this->getSelectedThemeSlug();
        $view_path = 'themes.'.$theme_slug.'.frontend.'.$view;
        if(!view()->exists($view_path)){
            $view_path = 'fallbacks.frontend.'.$view;
        }

        return view($view_path, $data);
    }

    public function getAllThemeDataForAdmin()
    {
        $allThemeData = [];
        $allDirectories = glob(base_path() . '/resources/views/themes/*', GLOB_ONLYDIR);
        foreach ($allDirectories as $dire) {
            //todo scan all the json file
            $currFolderName = pathinfo($dire, PATHINFO_BASENAME);
            $themeInformation = $this->getIndividualThemeData($currFolderName);

            if (property_exists($themeInformation, 'slug')) {
                if ($themeInformation->slug == 'default') {
                    continue;
                }
                $allThemeData[$currFolderName] = $themeInformation;
            }
        }
        return $allThemeData;
    }

    public function getAllThemeData()
    {
        $allThemeData = [];
        $allDirectories = glob(base_path() . '/resources/views/themes/*', GLOB_ONLYDIR);
        foreach ($allDirectories as $dire) {
            //todo scan all the json file
            $currFolderName = pathinfo($dire, PATHINFO_BASENAME);
            $themeInformation = $this->getIndividualThemeData($currFolderName);

            if (property_exists($themeInformation, 'status') && $themeInformation->status) {
                if (property_exists($themeInformation, 'slug')) {
                    if ($themeInformation->slug == 'default') {
                        continue;
                    }
                    $allThemeData[$currFolderName] = $themeInformation;
                }
            }
        }
        return $allThemeData;
    }

    public function getAllThemeSlug()
    {
        $themeSlugArray = [];
        $allThemeData = getAllThemeData();

        $index = 0;
        foreach ($allThemeData as $data) {
            if (property_exists($data, 'status') && $data->status) {
                if (property_exists($data, 'slug')) {
                    if ($data->slug == 'default') {
                        continue;
                    }
                    $themeSlugArray[$index++] = $data->slug;
                }
            }
        }
        return $themeSlugArray;
    }

    public function getDefaultThemeData()
    {
        $allThemeData = [];
        $allDirectories = glob(base_path() . '/resources/views/themes/*', GLOB_ONLYDIR);
        foreach ($allDirectories as $dire) {
            //todo scan all the json file
            $currFolderName = pathinfo($dire, PATHINFO_BASENAME);
            $themeInformation = $this->getIndividualThemeData($currFolderName);

            if (property_exists($themeInformation, 'slug')) {
                if ($themeInformation->slug != 'default') {
                    continue;
                }
                $allThemeData[$currFolderName] = $themeInformation;
            }
        }

        return !empty($allThemeData) ? current($allThemeData) : $allThemeData;
    }

    private function getIndividualThemeData(string $themeName, bool $returnType = false)
    {
        $filePath = theme_path($themeName) . '/theme.json';
        if (file_exists($filePath) && !is_dir($filePath)) {
            //cache data for 10days
            return json_decode(file_get_contents($filePath), $returnType);
        }
    }

    public function getIndividualThemeDetails(string $themeName, bool $returnType = false)
    {
        $details = [];
        $theme_meta = $this->getIndividualThemeData($themeName);
        $default_meta = $this->getDefaultThemeData();

        if (!empty($theme_meta)) {
            if (property_exists($theme_meta, 'name')) {
                $details['name'] = $theme_meta->name;
            } else {
                $details['name'] = $default_meta->name;
            }

            if (property_exists($theme_meta, 'slug')) {
                $details['slug'] = $theme_meta->slug;
            } else {
                $details['slug'] = $default_meta->slug;
            }

            if (property_exists($theme_meta, 'description')) {
                $details['description'] = $theme_meta->description;
            } else {
                $details['description'] = $default_meta->description;
            }
        }
        return $details;
    }

    public function getIndividualScreenshotMeta(string $themeSlug)
    {
        $screenshot = [];
        $theme_meta = $this->getIndividualThemeData($themeSlug);
        if (!empty($theme_meta) && property_exists($theme_meta, 'screenshot')) {
            $screenshot = $theme_meta->screenshot;
            if (!empty($screenshot) && count($screenshot) > 0) {
                $screenshot = current($screenshot);
            }
        }

        return $screenshot;
    }

    /**
     * @param string $themeSlug
     * @method getIndividualThemeScreenshot
     * @return array
     */

    public function getIndividualThemeScreenshot(string $themeSlug): array
    {
        $screenshot_list = [];
        $screenshot_dir = theme_screenshots($themeSlug);

        if (is_dir($screenshot_dir) && !is_file($screenshot_dir)) {
            $all_files = \File::allFiles($screenshot_dir);
            if (!empty($all_files)) {
                foreach ($all_files as $item) {
                    $extension = pathinfo($item, PATHINFO_EXTENSION);
                    if (in_array($extension, ['jpg', 'png', 'jpeg'])) {
                        $theme_meta = $this->getIndividualScreenshotMeta($themeSlug);
                        if (!empty($theme_meta) && pathinfo($item, PATHINFO_BASENAME) == $theme_meta->primary) {
                            $screenshot_list['primary'] = pathinfo($item);
                        } else {
                            $screenshot_list['secondary'][] = pathinfo($item);
                        }
                    }
                }
            }
        }

        return $screenshot_list;
    }

    public function renderPrimaryThemeScreenshot($themeSlug)
    {
        $src_markup = '';
        $image_data = $this->getIndividualThemeScreenshot($themeSlug);
        if (!empty($image_data)) {
            if (array_key_exists('primary', $image_data)) {
                $primary = $image_data['primary'];
            } else {
                $default_theme = $this->getDefaultThemeData();
                $default_screenshot = $this->getIndividualThemeScreenshot($default_theme->slug);

                if (array_key_exists('primary', $default_screenshot)) {
                    $themeSlug = property_exists($default_theme, 'slug') ? $default_theme->slug : 'default';
                    $primary = $default_screenshot['primary'];
                }
            }
            $src_markup = global_asset(theme_assets('screenshot', $themeSlug) . '/' . $primary['basename']);
            $src_markup = str_replace('/assets', '', $src_markup);
        }

        return $src_markup;
    }

    public function loadCoreStyle()
    {
        $all_styles = [
            "bootstrap",
            "plugin",
            "toastr",
            "odometer",
            "developer"
        ];

        $current_theme = $this->getHeaderHook();
        if (property_exists($current_theme, 'loadCoreStyle')) {
            $core_styles = $current_theme->loadCoreStyle;
            $temp_array = [];

            foreach ($all_styles as $value) {
                $item_key = $core_styles->$value ?? "not-found";

                if ($item_key != false) {
                    $temp_array[] = $value;
                }
            }

            // assign temp_array into all_styles and after that unset it from memory
            $all_styles = $temp_array;
            unset($temp_array);
        }

        return $all_styles;
    }

    public function loadCoreScript()
    {
        $all_scripts = [
            "jquery.min",
            "popper.min",
            "bootstrap",
            "plugin",
            "main",
            "loopcounter",
            "toastr.min",
            "sweetalert2",
            "star-rating.min",
            "jquery.magnific-popup",
            "md5",
            "jquery.syotimer.min",
            "viewport.jquery",
            "odometer",
            "nouislider-8.5.1.min",
            "CustomLoader",
            "CustomSweetAlertTwo",
            "SohanCustom"
        ];

        $current_theme = $this->getFooterHook();
        if (property_exists($current_theme, 'loadCoreScript')) {
            $core_script = $current_theme->loadCoreScript;
            $temp_array = [];

            foreach ($all_scripts as $value) {
                $item_key = $core_script->$value ?? "not-found";

                if ($item_key != false) {
                    $temp_array[] = $value;
                }
            }

            // assign temp_array into all_styles and after that unset it from memory
            $all_scripts = $temp_array;
            unset($temp_array);
        }

        return $all_scripts;
    }
}
