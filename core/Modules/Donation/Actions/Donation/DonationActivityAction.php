<?php

namespace Modules\Donation\Actions\Donation;

use App\Helpers\ResponseMessage;
use App\Helpers\SanitizeInput;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Donation\Entities\DonationActivity;

class DonationActivityAction
{
    public function store_execute(Request $request) {

        try {
            DB::beginTransaction();

            $donation_activity = new DonationActivity();
            $donation_activity->setTranslation('title', $request->lang, SanitizeInput::esc_html($request->title))
                ->setTranslation('description',$request->lang, $request->description);

            $slug = !empty($request->slug) ? $request->slug : Str::slug($request->title);
            $created_slug = create_slug($slug,'DonationActivity',true, 'Donation');
            $donation_activity->slug = SanitizeInput::esc_html($created_slug);
            $donation_activity->category_id = $request->category_id;
            $donation_activity->status = $request->status;
            $donation_activity->image = $request->image;

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

            $donation_activity->save();
            $donation_activity->metainfo()->create($Metas);
            DB::commit();

        }catch (\Exception $e){
            DB::rollBack();
            return response()->danger(ResponseMessage::delete($e->getMessage()));
        }

    }


    public function update_execute(Request $request ,$id)
    {
        try {
            DB::beginTransaction();

            $donation_edit_activity = DonationActivity::findOrFail($id);
            $donation_edit_activity->setTranslation('title', $request->lang, SanitizeInput::esc_html($request->title))
                                   ->setTranslation('description', $request->lang, $request->description);

            $slug = !empty($request->slug) ? $request->slug : Str::slug($request->title);
            $created_slug = create_slug($slug,'DonationActivity',true, 'Donation');

            $donation_edit_activity->slug = $donation_edit_activity->slug == $request->slug ? $donation_edit_activity->slug : $created_slug;
            $donation_edit_activity->category_id = $request->category_id;
            $donation_edit_activity->status = $request->status;
            $donation_edit_activity->image = $request->image;
            $donation_edit_activity->save();

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


            if (is_null($donation_edit_activity->metainfo()->first())){
                $donation_edit_activity->metainfo()->create($metas);
            }else{
                $donation_edit_activity->metainfo()->update($metas);
            }

            DB::commit();

        }catch (\Exception $e){
            DB::rollBack();
            return redirect()->back()->with(['msg' => $e->getMessage(), 'type' => 'danger']);
         }

    }

}
