<?php

namespace App\Http\Controllers\Landlord\Frontend;

use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\Mail\BasicMail;
use Artesaos\SEOTools\SEOMeta;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Blog\Entities\Blog;
use Modules\Blog\Entities\BlogCategory;
use Modules\Blog\Entities\BlogComment;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use App\Traits\SeoDataConfig;
use function Sodium\increment;

class BlogController extends Controller
{
    use SEOToolsTrait,SeoDataConfig;
    private const BASE_PATH = 'landlord.frontend.blog.';

    public function blog_single($slug)
    {
        $blog_post = Blog::where(['slug'=> $slug,'status'=> 1])->first();

        if (empty($blog_post)) {
            abort(404);
        }

        Blog::where(['slug'=> $slug,'status'=> 1])->increment('views');

        $comments = BlogComment::select('id','user_id','blog_id','comment_content','created_at')->where('blog_id',$blog_post->id)->orderBy('id','desc')->take(5)->get();
        $all_related_blogs = Blog::select('id','admin_id','title','blog_content','created_at','views','image','slug')->orderBy('id','desc')->take(3)->get();
        $comments_count = BlogComment::select('id','user_id','blog_id','comment_content','created_at')->where('blog_id',$blog_post->id)->count();
        $this->setMetaDataInfo($blog_post);
        return view(self::BASE_PATH.'blog-single',compact('blog_post','comments','comments_count','all_related_blogs'));
    }

    public function category_wise_blog_page($id)
    {
        if (empty($id)) {
            abort(404);
        }

        $all_blogs = Blog::usingLocale(GlobalLanguage::default_slug())->where(['category_id' => $id,'status' => 1])->orderBy('id', 'desc')->paginate(get_static_option('category_page_item_show'));
        $category = BlogCategory::where(['id' => $id, 'status' => 1])->first();
        $category_name = $category->getTranslation('title',get_user_lang());

        return view(self::BASE_PATH.'category')->with([
            'all_blogs' => $all_blogs,
            'category_name' => $category_name,
        ]);
    }

    public function blog_search_page(Request $request)
    {
        $request->validate([
            'search' => 'required'
        ],
            ['search.required' => 'Enter anything to search']);

        $all_blogs = Blog::Where('title', 'LIKE', '%' . $request->search . '%')
            ->orderBy('id', 'desc')->paginate(get_static_option('search_page_item_show'));

        return view(self::BASE_PATH.'search')->with([
            'all_blogs' => $all_blogs,
            'search_term' => $request->search,
        ]);
    }

    public function tags_wise_blog_page($tag)
    {

        $all_blogs = Blog::Where('tags', 'LIKE', '%' . $tag . '%')
            ->orderBy('id', 'desc')->paginate(4);

        return view(self::BASE_PATH.'blog-tags')->with([
            'all_blogs' => $all_blogs,
            'tag_name' => $tag,

        ]);
    }

    public function blog_comment_store(Request $request)
    {

        $request->validate([
            'comment_content' => 'required'
        ]);

        $content = BlogComment::create([
            'blog_id' => $request->blog_id,
            'user_id' => $request->user_id,
            'commented_by' => 'user',
            'comment_content' => SanitizeInput::esc_html($request->comment_content),
        ]);

        return response()->json([
            'msg' => __('Your comment sent successfully'),
            'type' => 'success',
            'status' => 'ok',
            'content' => $content,
        ]);
    }

    public function load_more_comments(Request $request)
    {

        $all_comment = BlogComment::with(['blog', 'user'])
            ->where('blog_id',$request->id)
            ->orderBy('id','desc')
            ->skip($request->items)
            ->take(5)
            ->get();

        $markup = '';
        foreach ($all_comment as $item) {

            $avatar_image = render_image_markup_by_attachment_id(get_static_option('single_blog_page_comment_avatar_image'),'','',false);
            $commented_user_image = $item->user?->image ? render_image_markup_by_attachment_id($item->user?->image) : $avatar_image;

            $name = optional($item->user)->name ?? '';
            $created_at = date('d F Y', strtotime($item->created_at ?? ''));
            $comment_content = purify_html($item->comment_content);

            $markup .= <<<HTML


  <div class="singleReview blog_comment_container">
            <div class="client1Img">
                {$commented_user_image}
            </div>
            <div class="reviewText">
                <div class="d-flex align-items-center">
                    <h3>{$name}</h3>
                    <div class="review_icon">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <span> {$created_at}</span>
                <p>
                    {$comment_content}
                </p>
            </div>
        </div>
HTML;
}

        return response()->json(['blogComments' => $all_comment, 'markup' => $markup]);
    }
}
