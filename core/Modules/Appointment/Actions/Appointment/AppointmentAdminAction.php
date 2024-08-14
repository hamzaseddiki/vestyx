<?php

namespace Modules\Appointment\Actions\Appointment;

use Illuminate\Support\Facades\DB;
use Modules\Appointment\Entities\AdditionalAppointment;
use Modules\Appointment\Entities\Appointment;
use App\Helpers\SanitizeInput;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Appointment\Entities\AppointmentTax;

class AppointmentAdminAction
{
    public function store_execute(Request $request) {

        $notice = [];

        try {
            DB::beginTransaction();

            $appointment = new Appointment();
            $appointment->setTranslation('title', $request->lang, SanitizeInput::esc_html($request->title))
                ->setTranslation('description',$request->lang, purify_html($request->description));

            $slug = !empty($request->slug) ? $request->slug : Str::slug($request->title);
            $created_slug = create_slug($slug,'Appointment',true, 'Appointment');
            $appointment->slug = SanitizeInput::esc_html($created_slug);
            $appointment->appointment_category_id = $request->appointment_category_id;
            $appointment->appointment_subcategory_id = $request->appointment_subcategory_id;
            $appointment->status = $request->status;
            $appointment->image = $request->image;
            $appointment->price = $request->price;
            $appointment->is_popular = $request->is_popular;
            $appointment->tax_status = $request->tax_status;
            $appointment->person = $request->person;
            $appointment->sub_appointment_status = $request->sub_appointment_status;

            $Metas = [
                'title' => [$request->lang => SanitizeInput::esc_html($request->meta_title)],
                'description' => [$request->lang => SanitizeInput::esc_html($request->meta_description)],
                'image' => $request->meta_image,
                //twitter
                'tw_image' => $request->tw_image,
                'tw_title' => SanitizeInput::esc_html($request->meta_tw_title),
                'tw_description' => SanitizeInput::esc_html($request->meta_tw_description),
                //facebook
                'fb_image' => $request->fb_image,
                'fb_title' =>SanitizeInput::esc_html($request->meta_fb_title),
                'fb_description' => SanitizeInput::esc_html($request->meta_fb_description),
            ];

            $appointment->save();
            $appointment->metainfo()->create($Metas);


            //Sub appointment store
                if($request->sub_appointment_status == 'on'){
                 $sub_appointment_ids = $request->sub_appointment_ids;

                    if(count($sub_appointment_ids) > 0){
                        foreach ($sub_appointment_ids as $sub_id){
                            AdditionalAppointment::create([
                                'appointment_id' =>  $appointment->id,
                                'sub_appointment_id' => $sub_id,
                            ]);
                        }
                    }
                }
            //Sub appointment store


             //Store Tax Data
                if(!is_null($request->tax_status)){
                    AppointmentTax::create([
                        'appointment_id' =>  $appointment->id,
                        'tax_type' => $request->tax_type ?? 'inclusive',
                        'tax_amount' => $request->tax_amount,
                    ]);
                }
             //Store Tax Data


            DB::commit();

            $notice['msg'] = __('Sub Appointment Created Successfully..');
            $notice['type'] = __('success');

        }catch (\Exception $e){

            DB::rollBack();
            $notice['msg'] = $e->getMessage();
            $notice['type'] = __('danger');

        }

        return $notice;
    }


    public function update_execute(Request $request ,$id)
    {


        $notice = [];
        try {

            DB::beginTransaction();

            $appointment = Appointment::findOrFail($id);

            $appointment->setTranslation('title', $request->lang, SanitizeInput::esc_html($request->title))
            ->setTranslation('description', $request->lang,SanitizeInput::esc_html($request->description));

        $slug = !empty($request->slug) ? $request->slug : Str::slug($request->title);
        $created_slug = create_slug($slug,'Appointment',true, 'Appointment');

            $appointment->slug = $appointment->slug == $request->slug ? $appointment->slug : $created_slug;
            $appointment->status = $request->status;
            $appointment->appointment_category_id = $request->appointment_category_id;
            $appointment->appointment_subcategory_id = $request->appointment_subcategory_id;
            $appointment->image = $request->image;
            $appointment->price = $request->price;
            $appointment->is_popular = $request->is_popular;
            $appointment->tax_status = $request->tax_status;
            $appointment->person = $request->person;
            $appointment->sub_appointment_status = $request->sub_appointment_status;
            $appointment->save();

        $metas = [
            'title' => [$request->lang => SanitizeInput::esc_html($request->meta_title)],
            'description' => [$request->lang => SanitizeInput::esc_html($request->meta_description)],
            'image' => $request->meta_image,
            //twitter
            'tw_image' => $request->tw_image,
            'tw_title' =>  SanitizeInput::esc_html($request->meta_tw_title),
            'tw_description' => SanitizeInput::esc_html($request->meta_tw_description),
            //facebook
            'fb_image' => $request->fb_image,
            'fb_title' => SanitizeInput::esc_html($request->meta_fb_title),
            'fb_description' => SanitizeInput::esc_html($request->meta_fb_description),
        ];


        if (is_null($appointment->metainfo()->first())){
            $appointment->metainfo()->create($metas);
        }else{
            $appointment->metainfo()->update($metas);
        }


            //Sub appointment Update
            $appointment->additional_appointments()?->delete();

            if($request->sub_appointment_status == 'on'){
                $sub_appointment_ids = $request->sub_appointment_ids;

                if(count($sub_appointment_ids) > 0){
                    foreach ($sub_appointment_ids as $sub_id){
                        AdditionalAppointment::create([
                            'appointment_id' =>  $appointment->id,
                            'sub_appointment_id' => $sub_id,
                        ]);
                    }
                }
            }
            //Sub appointment Update


            //Update Tax Data
            if(!empty($appointment->tax_status)){
                AppointmentTax::updateOrCreate(
                    [
                        'appointment_id' => $appointment->id
                    ],
                    [
                    'appointment_id' =>  $appointment->id,
                    'tax_type' => $request->tax_type ?? 'inclusive',
                    'tax_amount' => $request->tax_type == 'inclusive' ? null : $request->tax_amount,
                ]);
            }
            //Update Tax Data

            DB::commit();

        $notice['msg'] = __('Appointment Updated Successfully..');
        $notice['type'] = __('success');

        }catch (\Exception $e){
            DB::rollBack();
            $notice['msg'] = $e->getMessage();
            $notice['type'] = __('danger');

         }
        return $notice;

    }

    public function clone_execute(Request $request)
    {
        $notice = [];
        try {
            \DB::beginTransaction();
            $blog_details = Appointment::findOrFail($request->item_id);
            $cloned_data = Appointment::create([
                'slug' => !empty($blog_details->slug) ? $blog_details->slug : Str::slug($blog_details->title),
                'description' => $blog_details->getTranslation('description',default_lang()) ?? 'draft blog content',
                'title' => $blog_details->getTranslation('title',default_lang()) ,
                'appointment_category_id' => $blog_details->appointment_category_id,
                'appointment_subcategory_id' => $blog_details->appointment_subcategory_id,
                'status' => 0,
                'image' => $blog_details->image,
                'views' => 0,
                'price' => $blog_details->price,

            ]);

            $meta_object = optional($blog_details->metainfo);
            $Metas = [
                'title' => $meta_object->getTranslation('title',default_lang()),
                'description' => $meta_object->getTranslation('description',default_lang()),
                'image' => $meta_object->image,

                'tw_title' => $meta_object->tw_title,
                'tw_description' => $meta_object->tw_description,
                'tw_image' => $meta_object->tw_image,

                'fb_image' => $meta_object->fb_image,
                'fb_title' => $meta_object->fb_title,
                'fb_description' => $meta_object->fb_description,
            ];

            $cloned_data->metainfo()->create($Metas);
            \DB::commit();

            $notice['msg'] = __('Appointment Cloned Successfully..');
            $notice['type'] = __('success');

        }catch (\Exception $e){
            \DB::rollBack();
            $notice['msg'] = $e->getMessage();
            $notice['type'] = __('danger');

        }

        return $notice;

    }

}
