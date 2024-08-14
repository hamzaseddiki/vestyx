<?php

namespace Modules\Job\Actions\Job;

use Illuminate\Support\Facades\DB;
use App\Helpers\SanitizeInput;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Event\Entities\Event;
use Modules\Job\Entities\Job;


class JobAdminAction
{
    public function store_execute(Request $request) {

        $notice = [];

        DB::beginTransaction();

        try {
            $event = new Job();
            $this->common_data($event,$request,'create');
            DB::commit();

            $notice['msg'] = __('Job Created Successfully..');
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
            $job = Job::findOrFail($id);
            $this->common_data($job,$request,'update');
            DB::commit();

            $notice['msg'] = __('Job Updated Successfully..');
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
            $job_details = Job::find($request->item_id);
            $data = Job::create([

                'title' => $job_details->getTranslation('title',default_lang()),
                'slug' => !empty($job_details->slug) ? $job_details->slug : \Str::slug($job_details->title),
                'description' => $job_details->getTranslation('description',default_lang()),
                'job_location' => $job_details->job_location,
                'company_name' => $job_details->company_name,
                'designation' => $job_details->designation,
                'experience' => $job_details->experience,
                'employee_type' => $job_details->employee_type,
                'working_days' => $job_details->working_days,
                'working_type' => $job_details->working_type,
                'salary_offer' => $job_details->salary_offer,
                'image' => $job_details->image,
                'deadline' => $job_details->deadline,
                'application_fee_status' => $job_details->application_fee_status,
                'application_fee' => $job_details->application_fee,
                'status' => 0,
                'category_id' => $job_details->category_id,
            ]);

            $meta_object = optional($job_details->metainfo);

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

    private function common_data($job,$request,$meta_action_type)
    {
        $job->setTranslation('title', $request->lang, SanitizeInput::esc_html($request->title))
            ->setTranslation('description', $request->lang, $request->description)
            ->setTranslation('job_location', $request->lang, $request->job_location)
            ->setTranslation('company_name', $request->lang, $request->company_name)
            ->setTranslation('designation', $request->lang, $request->designation);

        $slug = !empty($request->slug) ? $request->slug : Str::slug($request->title);
        $created_slug = create_slug($slug,'Job',true, 'Job');
        $job->slug = SanitizeInput::esc_html($created_slug);

        $job->experience = $request->experience;
        $job->employee_type = $request->employee_type;
        $job->working_days = $request->working_days;
        $job->working_type = $request->working_type;
        $job->salary_offer = $request->salary_offer;
        $job->image = $request->image;
        $job->deadline = $request->deadline;
        $job->application_fee_status = $request->application_fee_status;
        $job->application_fee = $request->application_fee;
        $job->status = $request->status;
        $job->category_id = $request->category_id;
        $job->save();

        $job->metainfo()->$meta_action_type([
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
