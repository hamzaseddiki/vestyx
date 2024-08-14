<?php

namespace App\Helpers\SeederHelpers;

class JsonDataModifier extends JsonSeeder
{
    public function __construct($dirName,$fileName)
    {
        $this->getFile($dirName,$fileName);
    }


}
