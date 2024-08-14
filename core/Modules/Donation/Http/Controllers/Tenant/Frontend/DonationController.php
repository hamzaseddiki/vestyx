<?php

namespace Modules\Donation\Http\Controllers\Tenant\Frontend;

use App\Facades\GlobalLanguage;
use App\Helpers\SanitizeInput;
use App\Traits\SeoDataConfig;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Donation\Entities\Donation;
use Modules\Donation\Entities\DonationCategory;
use Modules\Donation\Entities\DonationComment;
use Modules\Donation\Entities\DonationPaymentLog;


class DonationController extends Controller
{
    use SEOToolsTrait,SeoDataConfig;
    private const BASE_PATH = 'donation.';

    public function donation_single($slug)
    {
        $donation = Donation::where(['slug'=> $slug,'status'=> 1])->first();

        if (empty($donation)) {
            abort(404);
        }

        Donation::where(['slug'=> $slug,'status'=> 1])->increment('views');
        $this->setMetaDataInfo($donation);
        $comments = DonationComment::select('id','user_id','donation_id','comment_content','created_at')->where('donation_id',$donation->id)->orderBy('id','desc')->take(5)->get();
        $comments_count = DonationComment::select('id','user_id','donation_id','comment_content','created_at')->where('donation_id',$donation->id)->count();
        $all_donors_data = DonationPaymentLog::select('id','name','created_at','amount')->where('donation_id',$donation->id)->orderBy('id','desc')->take(5)->get();

        return themeView(self::BASE_PATH.'donation-single',compact('donation','comments','comments_count','all_donors_data'));
    }

    public function category_wise_donation_page($id)
    {
        if (empty($id)) {
            abort(404);
        }

        $all_donation = Donation::usingLocale(GlobalLanguage::default_slug())->where(['category_id' => $id,'status' => 1])->orderBy('id', 'desc')->paginate(4);
        $category = DonationCategory::where(['id' => $id, 'status' => 1])->first();
        $category_name = $category->getTranslation('title',get_user_lang());

        return themeView(self::BASE_PATH.'category')->with([
            'all_donation' => $all_donation,
            'category_name' => $category_name,
        ]);
    }


    public function donation_comment_store(Request $request)
    {

        $request->validate([
            'comment_content' => 'required'
        ]);

        $content = DonationComment::create([
            'donation_id' => $request->donation_id,
            'user_id' => $request->user_id,
            'commented_by' => 'user',
            'comment_content' => SanitizeInput::esc_html($request->comment_content),
        ]);

        return response()->json([
            'msg' => __('Your comment sent succefully'),
            'type' => 'success',
            'status' => 'ok',
            'content' => $content,
        ]);
    }

    public function load_more_comments(Request $request)
    {

        $all_comment = DonationComment::with(['donation', 'user'])
            ->where('donation_id',$request->id)
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

      return response()->json(['donationComments' => $all_comment, 'markup' => $markup]);
    }


    public function donation_payment($id)
    {
        $donation = Donation::findOrFail($id);
        if (!empty($donation->deadline) && $donation->deadline <= now()) {
            return view('errors.403')->with(['message' => __('Donation Expired')]);
        }

        return themeView(self::BASE_PATH.'donation-payment.payment',compact('donation'));

    }

    public function donation_payment_success($id)
    {
        $extract_id = substr($id, 6);
        $extract_id = substr($extract_id, 0, -6);

        $check_id = !empty($extract_id) ? $extract_id : $id;
        $donation_logs = DonationPaymentLog::find($check_id);

        $donation = Donation::find($donation_logs->donation_id);
        return themeView(self::BASE_PATH . 'donation-payment.success')->with(['donation_logs' => $donation_logs, 'donation' => $donation]);
    }

    public function donation_payment_cancel()
    {
        return themeView(self::BASE_PATH . 'donation-payment.cancel');
    }



}
