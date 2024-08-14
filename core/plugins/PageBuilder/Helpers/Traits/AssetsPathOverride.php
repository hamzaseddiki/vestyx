<?php


namespace Plugins\PageBuilder\Helpers\Traits;


trait AssetsPathOverride
{
    /**
     * this method will override the default assets path of page builder
     * */

    public function setAssetsFilePath(){
        return 'assets/plugins/PageBuilder/images';
    }
}

