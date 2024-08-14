<?php

namespace Database\Seeders\Tenant\ModuleData;

use App\Models\PricePlan;
use Illuminate\Database\Seeder;
use const Database\Seeders\Tenant\PHP_EOL;

class PricePlanSeed extends Seeder
{
    public function run()
    {
        $this->price_plan_store('Basic','أساسي',10);
        $this->price_plan_store('Business','اعمال',20);
        $this->price_plan_store('Professional','احترافي',30);
    }

    private function price_plan_store($title_en,$title_ar,$price)
    {
        $lang_en = 'en_US';
        $lang_ar = 'ar';
        $price_plan = new PricePlan();
        $price_plan->setTranslation('title',$lang_en,$title_en);
        $price_plan->setTranslation('title',$lang_ar,$title_ar);
        $price_plan->setTranslation('features',$lang_en, 'feature one'.PHP_EOL.'feature two'.PHP_EOL.'feature three'.PHP_EOL.'feature four');
        $price_plan->setTranslation('features',$lang_ar, 'feature one'.PHP_EOL.'feature two'.PHP_EOL.'feature three'.PHP_EOL.'feature four');
        $price_plan->type = null;
        $price_plan->status = 1;
        $price_plan->price = $price;
        $price_plan->save();
    }
}
