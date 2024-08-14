<?php

namespace Modules\Portfolio\Actions\Portfolio;

use App\Helpers\SanitizeInput;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Portfolio\Entities\Portfolio;

class PortfolioAdminAction
{
    public function store_execute(Request $request) {
        $notice = [];

        try {
            DB::beginTransaction();
            $portfolio = new Portfolio();
            $portfolio->setTranslation('title', $request->lang, SanitizeInput::esc_html($request->title))
                ->setTranslation('description', $request->lang, $request->description)
                ->setTranslation('client', $request->lang, SanitizeInput::esc_html($request->client))
                ->setTranslation('design', $request->lang, SanitizeInput::esc_html($request->design))
                ->setTranslation('typography', $request->lang, SanitizeInput::esc_html($request->typography));

            $slug = !empty($request->slug) ? $request->slug : Str::slug($request->title);
            $created_slug = create_slug($slug,'Portfolio',true, 'Portfolio');
            $portfolio->slug = SanitizeInput::esc_html($created_slug);

            $portfolio->category_id = $request->category_id;
            $portfolio->status = $request->status;
            $portfolio->image = $request->image;
            $portfolio->file = custom_file_upload($request->file) ?? NULL;
            $portfolio->image_gallery = $request->image_gallery;
            $portfolio->url = $request->url;
            $portfolio->tags = $request->tags;
            $portfolio->download = 0;

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

            $portfolio->save();
            $portfolio->metainfo()->create($Metas);
            DB::commit();

            $notice['msg'] = __('Portfolio Updated Successfully..!');
            $notice['type'] = 'success';

        }catch (\Exception $e){
            DB::rollBack();
            $notice['msg'] = $e->getMessage();
            $notice['type'] = 'danger';
        }

        return $notice;

    }


    public function update_execute(Request $request ,$id)
    {
        $notice = [];

        try {
         DB::beginTransaction();

        $portfolio =  Portfolio::findOrFail($id);
        $portfolio->setTranslation('title', $request->lang, SanitizeInput::esc_html($request->title))
            ->setTranslation('description', $request->lang, $request->description)
            ->setTranslation('client', $request->lang, SanitizeInput::esc_html($request->client))
            ->setTranslation('design', $request->lang, SanitizeInput::esc_html($request->design))
            ->setTranslation('typography', $request->lang, SanitizeInput::esc_html($request->typography));

            $slug = !empty($request->slug) ? $request->slug : Str::slug($request->title);
            $created_slug = create_slug($slug,'Portfolio',true, 'Portfolio');
            $portfolio->slug = $portfolio->slug == $request->slug ? $portfolio->slug : $created_slug;

            $portfolio->category_id = $request->category_id;
            $portfolio->status = $request->status;
            $portfolio->image = $request->image;

            if(isset($request->file)){
                if(!empty($portfolio->file) && file_exists('assets/uploads/custom-file/'.$portfolio->file)){
                   unlink('assets/uploads/custom-file/'.$portfolio->file);
                }
               $portfolio->file = custom_file_upload($request->file);
            }else{
                $portfolio->file = $portfolio->file;
            }
            $portfolio->image_gallery = $request->image_gallery;
            $portfolio->url = $request->url;
            $portfolio->tags = $request->tags;
            $portfolio->save();

            $portfolio->metainfo()->update([
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

            DB::commit();
            $notice['msg'] = __('Portfolio Updated Successfully..!');
            $notice['type'] = 'success';

        }catch (\Exception $e){
            DB::rollBack();
            $notice['msg'] = $e->getMessage();
            $notice['type'] = 'danger';

         }

         return $notice;
    }

    public function clone_execute(Request $request)
    {
        try {

            DB::beginTransaction();
            $donation_details = Portfolio::find($request->item_id);

            $data = Portfolio::create([
                'title' => $donation_details->getTranslation('title',default_lang()),
                'description' => $donation_details->getTranslation('description',default_lang()),
                'client' => $donation_details->getTranslation('client',default_lang()),
                'design' => $donation_details->getTranslation('design',default_lang()),
                'typography' => $donation_details->getTranslation('typography',default_lang()),
                'slug' => !empty($donation_details->slug) ? $donation_details->slug : \Str::slug($donation_details->title),
                'status' => 0,
                'image' => $donation_details->image,
                'image_gallery' => $donation_details->image_gallery,
                'category_id' => $donation_details->category_id,
                'tags' => $donation_details->tags,
                'url' => $donation_details->url,

            ]);

            $meta_object = optional($donation_details->metainfo);

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

        }catch (\Exception $e){
            DB::rollBack();
            return redirect()->back()->with(['msg' => __('Something went wrong..!'), 'type' => 'danger']);

        }

    }



}
