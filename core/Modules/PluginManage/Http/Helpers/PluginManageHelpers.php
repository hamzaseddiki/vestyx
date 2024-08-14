<?php

namespace Modules\PluginManage\Http\Helpers;

use App\Helpers\ModuleMetaData;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Nwidart\Modules\Facades\Module;

class PluginManageHelpers
{
    //todo:: with with multiple plugin meta example, plugin list etc

    public static function getPluginInfo($pluginDirName){
        return (new PluginJsonFileHelper($pluginDirName));
    }

    public static function getPluginLists()
    {
        $allDirectories = glob(base_path() . '/Modules/*', GLOB_ONLYDIR);


        $pluginList = [];
        foreach ($allDirectories as $dir){

            $currFolderName = pathinfo($dir, PATHINFO_BASENAME);
            $pluginInfo = (new PluginJsonFileHelper($currFolderName))->metaInfo();
            $pluginList[] = $pluginInfo;
        };

        return $pluginList;
    }

    public static function runModuleDatabase($moduleName, $status)
    {
        $modulePath = Module::find($moduleName) ? Module::find($moduleName)->getPath() : "";
        if(!empty($modulePath))
        {
            $migrationsDirectory = $modulePath . '/Database/Migrations';
            $files = File::files($migrationsDirectory);
            if (count($files) > 0 && $status === true)
            {
                Artisan::call('module:migrate', [
                    'module' => $moduleName,
                    '--force' => true
                ]);
            }
        }
    }
}
