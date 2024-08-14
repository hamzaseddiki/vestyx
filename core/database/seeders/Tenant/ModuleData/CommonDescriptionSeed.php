<?php

namespace Database\Seeders\Tenant\ModuleData;

use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductShippingReturnPolicy;

class CommonDescriptionSeed
{
    public static function execute()
    {
        //for Product
        $products = Product::all();
        foreach ($products as $item){
              $item->setTranslation('description','en_US',self::description_english());
              $item->setTranslation('description','ar',self::description_arabic());
              $item->setTranslation('summary','en_US',self::description_english());
              $item->setTranslation('summary','ar',self::description_arabic());
              $item->save();
        }

        //Product Shipping Return Policy
        $products = ProductShippingReturnPolicy::all();
        foreach ($products as $item){
            $item->setTranslation('shipping_return_description','en_US',self::description_english());
            $item->setTranslation('shipping_return_description','ar',self::description_arabic());
            $item->save();
        }

    }


    private static function description_english() : string
    {
        $desc = 'We are very much greatful to you for your donation. Your little effort help us to change big community life I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally
        We are very much greatful to you for your donation. Your little effort help us to change big community life I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual . I must explain to you how all this mistaken idea of denouncing pleasure .
        We are very much greatful to you for your donation. Your little effort help us to change big community life I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual . I must explain to you how all this mistaken idea of denouncing pleasure .
        We are very much greatful to you for your donation. Your little effort help us to change big community life I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual . I must explain to you how all this mistaken idea of denouncing pleasure .
        We are very much greatful to you for your donation. Your little effort help us to change big community life I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual . I must explain to you how all this mistaken idea of denouncing pleasure ';

        return $desc;
    }


    private static function description_arabic() : string
    {
        $desc = 'نحن ممتنون جدًا لك على تبرعك. يساعدنا مجهودك الصغير في تغيير الحياة المجتمعية الكبيرة ، ويجب أن أشرح لك كيف ولدت كل هذه الفكرة الخاطئة المتمثلة في إدانة المتعة ومدح الألم ، وسأقدم لك وصفًا كاملاً للنظام ، وأشرح التعاليم الفعلية للمستكشف العظيم. الحقيقة ، صانع السعادة البشرية. لا أحد يرفض أو يكره أو يتجنب المتعة نفسها ، لأنها متعة ، ولكن لأن أولئك الذين لا يعرفون كيف يسعون وراء المتعة بعقلانية
        نحن ممتنون جدًا لك على تبرعك. يساعدنا مجهودك الصغير في تغيير الحياة المجتمعية الكبيرة ، ويجب أن أشرح لك كيف ولدت كل هذه الفكرة الخاطئة المتمثلة في إدانة المتعة ومدح الألم ، وسأقدم لك وصفًا كاملاً للنظام ، وأشرح لك حقيقة الأمر. يجب أن أشرح لك كيف كل هذه الفكرة الخاطئة للتنديد باللذة.
        نحن ممتنون جدًا لك على تبرعك. يساعدنا مجهودك الصغير في تغيير الحياة المجتمعية الكبيرة ، ويجب أن أشرح لك كيف ولدت كل هذه الفكرة الخاطئة المتمثلة في إدانة المتعة ومدح الألم ، وسأقدم لك وصفًا كاملاً للنظام ، وأشرح لك حقيقة الأمر. يجب أن أشرح لك كيف كل هذه الفكرة الخاطئة للتنديد باللذة.
        نحن ممتنون جدًا لك على تبرعك. يساعدنا مجهودك الصغير في تغيير الحياة المجتمعية الكبيرة ، ويجب أن أشرح لك كيف ولدت كل هذه الفكرة الخاطئة المتمثلة في إدانة المتعة ومدح الألم ، وسأقدم لك وصفًا كاملاً للنظام ، وأشرح لك حقيقة الأمر. يجب أن أشرح لك كيف كل هذه الفكرة الخاطئة للتنديد باللذة.
        نحن ممتنون جدًا لك على تبرعك. يساعدنا مجهودك الصغير في تغيير الحياة المجتمعية الكبيرة ، ويجب أن أشرح لك كيف ولدت كل هذه الفكرة الخاطئة المتمثلة في إدانة المتعة ومدح الألم ، وسأقدم لك وصفًا كاملاً للنظام ، وأشرح لك حقيقة الأمر. يجب أن أشرح لك كيف كل هذه الفكرة الخاطئة للتنديد باللذة
        ';

        return $desc;
    }




}
