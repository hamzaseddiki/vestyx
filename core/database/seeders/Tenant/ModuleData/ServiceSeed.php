<?php

namespace Database\Seeders\Tenant\ModuleData;

use App\Helpers\ImageDataSeedingHelper;
use App\Helpers\SanitizeInput;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Service\Entities\Service;
use Modules\Service\Entities\ServiceCategory;

class ServiceSeed extends Seeder
{
    public function run()
    {

        $this->service_category_store('Electric');
        $this->service_category_store('Laundry');
        $this->service_category_store('Garments');

        $service_img_one = ImageDataSeedingHelper::insert('assets/tenant/frontend/img/hard-work/04.png');
        $service_img_two = ImageDataSeedingHelper::insert('assets/tenant/frontend/img/hard-work/05.png');
        $service_img_three = ImageDataSeedingHelper::insert('assets/tenant/frontend/img/hard-work/06.png');

        $description = 'Was drawing natural fat respect husband. An as noisy an offer drawn blush place. These tried for way joy wrote witty.
         In mr began music weeks after at begin. Education no dejection so direction pretended household do to. Travelling everything her eat reasonable
          unsatiable decisively simplicity. Morning request be lasting it fortune demands highest of.
        Whether article spirits new her covered hastily sitting her. Money witty books nor son add. Chicken age had evening believe but proceed pretend mrs.
         At missed advice my it no sister. Miss told ham dull knew see she spot near can. Spirit her entire her called.';

        $this->service_store('Ticket System',$description,$service_img_one);
        $this->service_store('Cloud Service',$description,$service_img_two);
        $this->service_store('Data Science',$description,$service_img_three);
    }

    private function service_category_store($title)
    {
        $default_lang = 'en_US';

        $service = new ServiceCategory();
        $service->setTranslation('title',$default_lang, SanitizeInput::esc_html($title));
        $service->status = 1;
        $service->save();
    }

    private function service_store($title,$desc,$image_id)
    {
        $default_lang = 'en_US';
        $cat = ServiceCategory::firstOrFail();

        $service = new Service();
        $service->setTranslation('title',$default_lang, SanitizeInput::esc_html($title))
            ->setTranslation('description',$default_lang, SanitizeInput::esc_html($desc));
        $service->slug = empty($request->slug) ? Str::slug($title) : Str::slug($request->slug);
        $service->category_id = $cat->id;
        $service->image = $image_id;
        $service->meta_tag = 'service';
        $service->meta_description = 'meta description';
        $service->status = 1;
        $service->save();
    }
}
