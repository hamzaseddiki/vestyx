<?php

namespace Modules\Donation\Actions\Donation;

use App\Helpers\SanitizeInput;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Donation\Entities\Donation;

class DonationAdminAction
{
    public function store_execute(Request $request) {

        $notice = [];

        try {
            DB::beginTransaction();

            $faq_item = $request->faq ?? ['title' => ['']];
            $donation = new Donation();
            $donation->setTranslation('title', $request->lang, SanitizeInput::esc_html($request->title))
                ->setTranslation('description', $request->lang, $request->description)
                ->setTranslation('excerpt', $request->lang, SanitizeInput::esc_html($request->excerpt));

            $slug = !empty($request->slug) ? $request->slug : Str::slug($request->title);
            $created_slug = create_slug($slug,'Donation',true, 'Donation');
            $donation->slug = SanitizeInput::esc_html($created_slug);

            $donation->category_id = $request->category_id;
            $donation->status = $request->status;
            $donation->amount = $request->amount;
            $donation->deadline = $request->deadline;
            $donation->created_by = 'admin';
            $donation->creator_id = Auth::guard('admin')->id();
            $donation->image = $request->image;
            $donation->image_gallery = $request->image_gallery;
            $donation->popular = $request->popular;
            $donation->faq =  serialize($faq_item);

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

            $donation->save();
            $donation->metainfo()->create($Metas);
            DB::commit();

            $notice['msg'] = __('Donation Created Successfully..');
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
            $donation =  Donation::findOrFail($id);
            $faq_item = $request->faq ?? ['title' => ['']];

            $donation->setTranslation('title', $request->lang, SanitizeInput::esc_html($request->title))
                ->setTranslation('description', $request->lang, $request->description)
                ->setTranslation('excerpt', $request->lang, SanitizeInput::esc_html($request->excerpt));

            $slug = !empty($request->slug) ? $request->slug : Str::slug($request->title);
            $created_slug = create_slug($slug,'Donation',true, 'Donation');
            $donation->slug = $donation->slug == $request->slug ? $donation->slug : $created_slug;

            $donation->category_id = $request->category_id;
            $donation->status = $request->status;
            $donation->amount = $request->amount;
            $donation->deadline = $request->deadline;
            $donation->created_by = 'admin';
            $donation->creator_id = Auth::guard('admin')->id();
            $donation->image = $request->image;
            $donation->image_gallery = $request->image_gallery;
            $donation->popular = $request->popular;
            $donation->faq =  serialize($faq_item);
            $donation->save();

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


            if (is_null($donation->metainfo()->first())){
                $donation->metainfo()->create($metas);
            }else{
                $donation->metainfo()->update($metas);
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

        try {

            DB::beginTransaction();
            $donation_details = Donation::find($request->item_id);

            $data = Donation::create([
                'title' => $donation_details->getTranslation('title',default_lang()),
                'slug' => !empty($donation_details->slug) ? $donation_details->slug : \Str::slug($donation_details->title),
                'description' => $donation_details->getTranslation('description',default_lang()),
                'amount' => $donation_details->amount,
                'status' => 0,
                'image' => $donation_details->image,
                'image_gallery' => $donation_details->image_gallery,
                'deadline' => $donation_details->deadline,
                'faq' => $donation_details->faq,
                'creator_id' => $donation_details->creator_id,
                'created_by' => 'admin',
                'category_id' => $donation_details->category_id,
                'excerpt' => $donation_details->getTranslation('excerpt',default_lang()),
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

            $notice['msg'] = __('Donation Cloned Successfully..');
            $notice['type'] = __('success');

        }catch (\Exception $e){
            DB::rollBack();
            $notice['msg'] = $e->getMessage();
            $notice['type'] = __('danger');
        }

        return $notice;

    }



}
