<?php

namespace Modules\Service\Actions\Service;
use App\Facades\GlobalLanguage;
use App\Helpers\ResponseMessage;
use App\Helpers\SanitizeInput;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Blog\Entities\Blog;
use Modules\Service\Entities\Service;

class ServiceAction
{
    public $message;

    public function __construct($message = [])
    {
        $this->message = $message;
    }

    public function store_execute(Request $request) {

        try {
            \DB::beginTransaction();
            $service = new Service();
            $this->common_action($service, $request,'create');
            $service->save();

            $this->message['msg'] = __('Service Created Successfully..!');
            $this->message['type'] = 'success';
            \DB::commit();

        }catch (\Exception $e){
            \DB::rollBack();
            $this->message['msg'] = __($e->getMessage());
            $this->message['type'] = 'danger';
        }

        return $this->message;
    }


    public function update_execute(Request $request ,$id)
    {
        try {
            $service =  Service::findOrFail($id);

            if (is_null($service->metainfo()->first())){
                $this->common_action($service, $request,'create');
            }else{
                $this->common_action($service, $request,'update');
            }

            $this->message['msg'] = __('Service updated Successfully..!');
            $this->message['type'] = 'success';
            \DB::commit();

        }catch (\Exception $e){
            \DB::rollBack();
            $this->message['msg'] = __($e->getMessage());
            $this->message['type'] = 'danger';
        }

        return $this->message;
    }


    private function common_action(Service $service, Request $request ,$meta_action): void
    {
        $service->setTranslation('title', $request->lang, SanitizeInput::esc_html($request->title))
            ->setTranslation('description', $request->lang,$request->description);
        $service->slug = empty($request->slug) ? Str::slug($request->title) : Str::slug($request->slug);
        $service->category_id = $request->category_id;
        $service->image = $request->image;
        $service->meta_tag = $request->meta_tag;
        $service->meta_description = $request->meta_description;
        $service->status = $request->status;
        $service->save();

        $service->metainfo()->$meta_action([
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
