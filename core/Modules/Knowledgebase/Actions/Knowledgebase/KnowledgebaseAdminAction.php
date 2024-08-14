<?php

namespace Modules\Knowledgebase\Actions\Knowledgebase;

use Illuminate\Support\Facades\DB;
use App\Helpers\SanitizeInput;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Knowledgebase\Entities\Knowledgebase;


class KnowledgebaseAdminAction
{
    public function store_execute(Request $request) {

        $notice = [];
        DB::beginTransaction();

        try {
            $event = new Knowledgebase();
            $this->common_data($event,$request,'create');
            DB::commit();

            $notice['msg'] = __('Knowledgebase Created Successfully..');
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
            $knowledgebase = Knowledgebase::findOrFail($id);

            if (is_null($knowledgebase->metainfo()->first())){
                $this->common_data($knowledgebase,$request,'create');
            }else{
                $this->common_data($knowledgebase,$request,'update');
            }

            DB::commit();

            $notice['msg'] = __('Knowledgebase Updated Successfully..');
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
            $knowledgebase_details = Knowledgebase::find($request->item_id);
            $data = Knowledgebase::create([
                'title' => $knowledgebase_details->getTranslation('title',get_user_lang()),
                'slug' => !empty($knowledgebase_details->slug) ? $knowledgebase_details->slug : \Str::slug($knowledgebase_details->title),
                'description' => $knowledgebase_details->getTranslation('description',get_user_lang()),
                'image' => $knowledgebase_details->image,
                'status' => 0,
                'category_id' => $knowledgebase_details->category_id,
            ]);

            $meta_object = optional($knowledgebase_details->metainfo);

            $Metas = [
                'title' => $meta_object->getTranslation('title',get_user_lang()),
                'description' => $meta_object->getTranslation('description',get_user_lang()),
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

            $notice['msg'] = __('Knowledgebase Cloned Successfully..');
            $notice['type'] = __('success');

        }catch (\Exception $e){
            DB::rollBack();
            $notice['msg'] = $e->getMessage();
            $notice['type'] = __('danger');
        }

        return $notice;

    }

    private function common_data($knowledgebase,$request,$meta_action_type)
    {

        $knowledgebase->setTranslation('title', $request->lang, SanitizeInput::esc_html($request->title))
                      ->setTranslation('description', $request->lang, $request->description);

        $slug = !empty($request->slug) ? $request->slug : Str::slug($request->title);
        $knowledgebase->slug = SanitizeInput::esc_html($slug);
        $knowledgebase->status = $request->status;
        $knowledgebase->category_id = $request->category_id;
        $knowledgebase->image = $request->image;


        $filename = $this->upload_multiple_files($request->files) ?? [];

        if( $request->file_update == 'update'){
          if(count($request->files) > 0) {
             $old_files = json_decode($knowledgebase->files) ?? [];
              foreach ($old_files as $file){
                  if(file_exists('assets/uploads/article-files/'.$file) && !is_dir('assets/uploads/article-files/'.$file)){
                      unlink('assets/uploads/article-files/'.$file);
                  }
              }
              $knowledgebase->files = json_encode($filename) ?? (object) [];
          }else{

              $knowledgebase->files = !is_null($knowledgebase->files) && count(json_decode($knowledgebase->files)) > 0 ? $knowledgebase->files : null;
          }

        }else{

          $knowledgebase->files = json_encode($filename) ?? (object) [];
        }

        $knowledgebase->save();

        $knowledgebase->metainfo()->$meta_action_type([
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

    private function upload_multiple_files($files)
    {
        $names = [];
            foreach ($files as $items){
                foreach ($items as $file){
                    if(isset($file)){
                        $uploaded_file = $file;
                        $file_extension = $uploaded_file->getClientOriginalExtension();
                        $file_name = pathinfo($uploaded_file->getClientOriginalName(),PATHINFO_FILENAME).time().'.'.$file_extension;
                        $names[] = $file_name;
                        $uploaded_file->move('assets/uploads/article-files/',$file_name);
                    }
                }
            }
            return $names;

    }

}
