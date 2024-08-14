<?php

namespace Database\Seeders\Tenant\ModuleData\Knowledgebase;

use App\Helpers\SeederHelpers\JsonDataModifier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Modules\Knowledgebase\Entities\Knowledgebase;
use Modules\Knowledgebase\Entities\KnowledgebaseCategory;
use Modules\Service\Entities\Service;

class KnowledgebaseSeed
{
   public static function execute()
   {

       if (!Schema::hasTable('knowledgebases')) {

           Schema::create('knowledgebases', function (Blueprint $table) {
               $table->id();
               $table->unsignedBigInteger('category_id');
               $table->text('title');
               $table->text('slug');
               $table->longText('description');
               $table->string('image');
               $table->bigInteger('views')->default(0);
               $table->boolean('status')->default(1);
               $table->timestamps();

           });

       }


       $package = tenant()->user()->first()?->payment_log()->first()?->package()->first() ?? [];
       $all_features = $package->plan_features ?? [];

       $payment_log = tenant()->user()->first()?->payment_log()?->first() ?? [];
       if(empty($all_features) && $payment_log->status != 'trial'){
           return;
       }

       $check_feature_name = $all_features->pluck('feature_name')->toArray();

       if (in_array('knowledgebase', $check_feature_name)) {

           $object = new JsonDataModifier('article','article');
           $data = $object->getColumnData([
               'title',
               'description',
               'category_id',
               'slug',
               'status',
               'image',
               'views',
           ]);
           Knowledgebase::insert($data);

       }
   }
}
