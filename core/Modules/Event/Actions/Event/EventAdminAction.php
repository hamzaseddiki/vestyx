<?php

namespace Modules\Event\Actions\Event;

use Illuminate\Support\Facades\DB;
use App\Helpers\SanitizeInput;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Event\Entities\Event;


class EventAdminAction
{
    public function  store_execute(Request $request) {

        $notice = [];

        DB::beginTransaction();

        try {
            $event = new Event();
            $this->common_data($event,$request,'create');
            DB::commit();

            $notice['msg'] = __('Event Created Successfully..');
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
        DB::beginTransaction();

        try {
            $event = Event::findOrFail($id);


            if (is_null($event->metainfo()->first())){
                $this->common_data($event,$request,'create');
            }else{
                $this->common_data($event,$request,'update');
            }

            DB::commit();

            $notice['msg'] = __('Donation Updated Successfully..');
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

        DB::beginTransaction();

        try {
            $event_details = Event::find($request->item_id);

            $data = Event::create([
                'title' => $event_details->getTranslation('title',default_lang()),
                'slug' => !empty($event_details->slug) ? $event_details->slug : \Str::slug($event_details->title),
                'content' => $event_details->getTranslation('content',default_lang()),
                'cost' => $event_details->cost,
                'status' => 0,
                'image' => $event_details->image,
                'date' => $event_details->date,
                'time' => $event_details->time,
                'category_id' => $event_details->category_id,
                'organizer' => $event_details->organizer,
                'organizer_email' => $event_details->organizer_email,
                'organizer_phone' => $event_details->organizer_phone,
                'venue_location' => $event_details->venue_location,
                'total_ticket' => $event_details->total_ticket,
            ]);

            $meta_object = optional($event_details->metainfo);

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

            $data->metainfo()->create($Metas);
            DB::commit();

            $notice['msg'] = __('Event Cloned Successfully..');
            $notice['type'] = __('success');

        }catch (\Exception $e){
            DB::rollBack();
            $notice['msg'] = $e->getMessage();
            $notice['type'] = __('danger');
        }

        return $notice;

    }

    private function common_data($event,$request, $meta_action_type)
    {

        $event->setTranslation('title', $request->lang, SanitizeInput::esc_html($request->title))
            ->setTranslation('content', $request->lang, $request->description);

        $slug = !empty($request->slug) ? $request->slug : Str::slug($request->title);
        $created_slug = create_slug($slug,'Event',true, 'Event');
        $event->slug = SanitizeInput::esc_html($created_slug);

        $event->category_id = $request->category_id;
        $event->status = $request->status;
        $event->date = $request->date;
        $event->time = $request->time;
        $event->cost = $request->cost;
        $event->total_ticket = $request->total_ticket;

        $event->organizer = $request->organizer;
        $event->organizer_email = $request->organizer_email;
        $event->organizer_phone = $request->organizer_phone;
        $event->venue_location = $request->venue_location;
        $event->image = $request->image;
        $event->save();

        $event->metainfo()->$meta_action_type([
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
        ]);
    }




}
