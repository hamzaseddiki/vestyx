<?php

namespace Modules\Blog\Actions\Blog;
use App\Facades\GlobalLanguage;
use App\Helpers\ResponseMessage;
use App\Helpers\SanitizeInput;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Blog\Entities\Blog;
use mysql_xdevapi\Exception;

class BlogAction
{

    public function store_execute(Request $request) {

        try {
            \DB::beginTransaction();
            $blog = new Blog();
            $blog->setTranslation('title', $request->lang, SanitizeInput::esc_html($request->title))
                ->setTranslation('blog_content', $request->lang, $request->blog_content)
                ->setTranslation('excerpt', $request->lang, SanitizeInput::esc_html($request->excerpt));

            $slug = !empty($request->slug) ? $request->slug : Str::slug($request->title);
            $created_slug = create_slug($slug,'Blog',true,'Blog');
            $blog->slug = SanitizeInput::esc_html($created_slug);

            $blog->category_id = $request->category_id;
            $blog->visibility = $request->visibility;
            $blog->status = $request->status;
            $blog->admin_id = Auth::guard('admin')->user()->id;
            $blog->user_id = Auth::guard('admin')->user()->id;
            $blog->author = Auth::guard('admin')->user()->name;
            $blog->image = $request->image;
            $blog->image_gallery = $request->image_gallery;
            $blog->views = 0;
            $blog->tags = $request->tags;
            $blog->created_by = 'admin';

            $Metas = [
                'title' => [$request->lang => SanitizeInput::esc_html($request->meta_title)],
                'description' => [$request->lang => SanitizeInput::esc_html($request->meta_description)],
                'image' => $request->meta_image,
                //twitter
                'tw_image' => $request->meta_tw_image,
                'tw_title' => SanitizeInput::esc_html($request->meta_tw_title),
                'tw_description' => SanitizeInput::esc_html($request->meta_tw_description),
                //facebook
                'fb_image' => $request->meta_fb_image,
                'fb_title' =>SanitizeInput::esc_html($request->meta_fb_title),
                'fb_description' => SanitizeInput::esc_html($request->meta_fb_description),
            ];

            $blog->save();
            $blog->metainfo()->create($Metas);
            \DB::commit();

        }catch (\Exception $e){

            \DB::rollBack();
            return response()->danger($e->getMessage());
        }
    }


    public function update_execute(Request $request ,$id)
    {
        try {

            $blog_update =  Blog::findOrFail($id);
            $blog_update->setTranslation('title',$request->lang, SanitizeInput::esc_html($request->title))
                ->setTranslation('blog_content',$request->lang,$request->blog_content)
                ->setTranslation('excerpt',$request->lang, SanitizeInput::esc_html($request->excerpt));

            $slug = !empty($request->slug) ? $request->slug : Str::slug($request->title);
            $created_slug = create_slug($slug,'Blog',true, 'Blog');
            $blog_update->slug = $blog_update->slug == $request->slug ? $blog_update->slug : $created_slug;

            $blog_update->category_id = $request->category_id;
            $blog_update->visibility = $request->visibility;
            $blog_update->status = $request->status;
            $blog_update->image = $request->image;
            $blog_update->image_gallery = $request->image_gallery;
            $blog_update->tags = $request->tags;
            $blog_update->save();


            $metaData = [
                'title' => [$request->lang => SanitizeInput::esc_html($request->meta_title)],
                'description' => [$request->lang => SanitizeInput::esc_html($request->meta_description)],
                'image' => $request->meta_image,
                //twitter
                'tw_image' => $request->meta_tw_image,
                'tw_title' =>  SanitizeInput::esc_html($request->meta_tw_title),
                'tw_description' => SanitizeInput::esc_html($request->meta_tw_description),
                //facebook
                'fb_image' => $request->meta_fb_image,
                'fb_title' => SanitizeInput::esc_html($request->meta_fb_title),
                'fb_description' => SanitizeInput::esc_html($request->meta_fb_description),
            ];

            if (is_null($blog_update->metainfo()->first())){
                $blog_update->metainfo()->create($metaData);
            }else{
                $blog_update->metainfo()->update($metaData);
            }

            \DB::commit();

        }catch (\Exception $e){
            \DB::rollBack();
            return response()->danger($e->getMessage());
        }
    }

    public function clone_blog_execute(Request $request)
    {
        try {
            \DB::beginTransaction();
            $blog_details = Blog::findOrFail($request->item_id);
            $cloned_data = Blog::create([
                'category_id' =>  $blog_details->category_id,
                'slug' => !empty($blog_details->slug) ? $blog_details->slug : Str::slug($blog_details->title),
                'blog_content' => $blog_details->getTranslation('blog_content',default_lang()) ?? 'draft blog content',
                'title' => $blog_details->getTranslation('title',default_lang()) ,
                'status' => 0,
                'excerpt' => $blog_details->excerpt,
                'image' => $blog_details->image,
                'image_gallery' => $blog_details->image,
                'views' => 0,
                'user_id' => $blog_details->user_id,
                'admin_id' => Auth::guard('admin')->user()->id,
                'author' => Auth::guard('admin')->user()->name,
                'created_by' => $blog_details->created_by,
            ]);

            $meta_object = optional($blog_details->metainfo);
            $Metas = [
                'title' => $meta_object->getTranslation('title',default_lang()),
                'description' => $meta_object->getTranslation('description',default_lang()),
                'image' => $meta_object->image,

                'tw_title' => $meta_object->meta_tw_image,
                'tw_description' => $meta_object->tw_description,
                'tw_image' => $meta_object->tw_image,

                'fb_image' => $meta_object->meta_fb_image,
                'fb_title' => $meta_object->fb_title,
                'fb_description' => $meta_object->fb_description,
            ];

            $cloned_data->metainfo()->create($Metas);
            \DB::commit();

        }catch (\Exception $e){
            \DB::rollBack();
            return response()->danger($e->getMessage());
        }

    }

}
