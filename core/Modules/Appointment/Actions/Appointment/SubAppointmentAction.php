<?php

namespace Modules\Appointment\Actions\Appointment;

use App\Helpers\SanitizeInput;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Appointment\Entities\SubAppointment;

class SubAppointmentAction
{
    public function store_execute(Request $request) {
        $notice = [];

        try {
            DB::beginTransaction();

            $sub_appointment = new SubAppointment();
            $sub_appointment->setTranslation('title', $request->lang, SanitizeInput::esc_html($request->title))
                ->setTranslation('description',$request->lang, SanitizeInput::esc_html($request->description));

            $slug = !empty($request->slug) ? $request->slug : Str::slug($request->title);
            $created_slug = create_slug($slug,'SubAppointment',true, 'Appointment');
            $sub_appointment->slug = SanitizeInput::esc_html($created_slug);
            $sub_appointment->status = $request->status;
            $sub_appointment->image = $request->image;
            $sub_appointment->price = $request->price;
            $sub_appointment->is_popular = $request->is_popular;
            $sub_appointment->person = $request->person;

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

            $sub_appointment->save();
            $sub_appointment->metainfo()->create($Metas);
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
//        try {
//
//            DB::beginTransaction();

            $sub_appointment = SubAppointment::findOrFail($id);

            $sub_appointment->setTranslation('title', $request->lang, SanitizeInput::esc_html($request->title))
                                   ->setTranslation('description', $request->lang, SanitizeInput::esc_html($request->description));

            $slug = !empty($request->slug) ? $request->slug : Str::slug($request->title);
            $created_slug = create_slug($slug,'SubAppointment',true, 'Appointment');

            $sub_appointment->slug = $sub_appointment->slug == $request->slug ? $sub_appointment->slug : $created_slug;
            $sub_appointment->status = $request->status;
            $sub_appointment->image = $request->image;
            $sub_appointment->price = $request->price;
            $sub_appointment->is_popular = $request->is_popular;
            $sub_appointment->person = $request->person;
            $sub_appointment->save();

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


            if (is_null($sub_appointment->metainfo()->first())){
                $sub_appointment->metainfo()->create($metas);
            }else{
                $sub_appointment->metainfo()->update($metas);
            }

//            DB::commit();

            $notice['msg'] = __('Sub Appointment Updated Successfully..');
            $notice['type'] = __('success');

//        }catch (\Exception $e){
//            DB::rollBack();
//            $notice['msg'] = $e->getMessage();
//            $notice['type'] = __('danger');
//
//         }
        return $notice;

    }

    public function clone_execute(Request $request)
    {
        $notice = [];
        try {
            \DB::beginTransaction();
            $blog_details = SubAppointment::findOrFail($request->item_id);
            $cloned_data = SubAppointment::create([
                'slug' => !empty($blog_details->slug) ? $blog_details->slug : Str::slug($blog_details->title),
                'description' => $blog_details->getTranslation('description',default_lang()) ?? 'draft blog content',
                'title' => $blog_details->getTranslation('title',default_lang()) ,
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

            $notice['msg'] = __('Sub Appointment Cloned Successfully..');
            $notice['type'] = __('success');

        }catch (\Exception $e){
            \DB::rollBack();
            $notice['msg'] = $e->getMessage();
            $notice['type'] = __('danger');

        }

        return $notice;

    }

}
