<?php

namespace Modules\Appointment\Http\Controllers\Tenant\Frontend;

use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\Mail\BasicMail;
use App\Models\PaymentLogs;
use App\Models\PricePlan;
use Artesaos\SEOTools\SEOMeta;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Appointment\Entities\Appointment;
use Modules\Appointment\Entities\AppointmentCategory;
use Modules\Appointment\Entities\AppointmentComment;
use Modules\Appointment\Entities\AppointmentDayType;
use Modules\Appointment\Entities\SubAppointment;
use Modules\Appointment\Entities\SubAppointmentComment;
use Modules\Blog\Entities\Blog;
use Modules\Blog\Entities\BlogCategory;
use Modules\Blog\Entities\BlogComment;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use App\Traits\SeoDataConfig;
use function Sodium\increment;

class SubAppointmentFrontendController extends Controller
{
    use SEOToolsTrait,SeoDataConfig;

    private const BASE_PATH = 'sub-appointments.';

    public function sub_appointment_single($slug)
    {
        $sub_appointment = SubAppointment::where(['slug'=> $slug,'status'=> 1])->first();

        if (empty($sub_appointment)) {
            abort(404);
        }

        SubAppointment::where(['slug'=> $slug,'status'=> 1])->increment('views');

        $comments = SubAppointmentComment::select('id','user_id','sub_appointment_id','comment_content','created_at')->where('sub_appointment_id',$sub_appointment->id)->orderBy('id','desc')->take(5)->get();
        $all_related_sub_appointments = SubAppointment::select('id','title','description','created_at','views','image','slug')->orderBy('id','desc')->take(3)->get();
        $comments_count = SubAppointmentComment::select('id','user_id','sub_appointment_id','comment_content','created_at')->where('sub_appointment_id',$sub_appointment->id)->count();
        $this->setMetaDataInfo($sub_appointment);
        return themeView(self::BASE_PATH.'sub-appointment-single',compact('sub_appointment','all_related_sub_appointments','comments','comments_count'));

    }




    public function sub_appointment_comment_store(Request $request)
    {
        $request->validate([
            'comment_content' => 'required'
        ]);

        $content = SubAppointmentComment::create([
            'sub_appointment_id' => $request->sub_appointment_id,
            'user_id' => $request->user_id,
            'commented_by' => Auth::guard('web')->user()->name ?? '',
            'parent_id' => 1,
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

        $all_comment = SubAppointmentComment::with(['sub_appointment', 'user'])
            ->where('sub_appointment_id',$request->id)
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
                </div>
                <span> {$created_at}</span>
                <p>
                    {$comment_content}
                </p>
            </div>
        </div>
HTML;
}
        return response()->json([
            'blogComments' => $all_comment,
            'markup' => $markup
        ]);
    }



}
