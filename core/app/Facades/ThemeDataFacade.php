<?php

namespace App\Facades;

use App\Helpers\ThemeMetaData;
use Illuminate\Support\Facades\Facade;


/**
 * @see ThemeMetaData
 * @method static getSelectedThemeData
 * @method static getSelectedThemeSlug
 * @method static getAllThemeData
 *
 * @method static loadCoreScript
 * @method static loadCoreStyle
 *
 * @method static getAllThemeDataForAdmin
 * @method static getIndividualThemeData
 * @method static getHeaderHook
 * @method static getFooterHook
 * @method static getHeaderHookCssFiles
 * @method static getHeaderHookJsFiles
 * @method static getFooterHookCssFiles
 * @method static getFooterHookJsFiles
 * @method static renderHeaderHookBladeFile
 * @method static renderFooterHookBladeFile
 * @method static renderThemeView($view = '', $data = [])
 * @method static getAllThemeScreenshot($theme_slug)
 * @method static renderPrimaryThemeScreenshot($theme_slug)
 * @method static getIndividualThemeDetails($theme_slug)
 * @method static getHeaderHookRtlCssFiles
 * */
class ThemeDataFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ThemeDataFacade';
    }
}
