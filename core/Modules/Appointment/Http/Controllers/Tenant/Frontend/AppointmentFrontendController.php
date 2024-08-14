<?php

namespace Modules\Appointment\Http\Controllers\Tenant\Frontend;

use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\Mail\BasicMail;
use App\Models\PaymentLogs;
use App\Models\PricePlan;
use Artesaos\SEOTools\SEOMeta;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Modules\Appointment\Entities\Appointment;
use Modules\Appointment\Entities\AppointmentCategory;
use Modules\Appointment\Entities\AppointmentComment;
use Modules\Appointment\Entities\AppointmentDay;
use Modules\Appointment\Entities\AppointmentDayType;
use Modules\Appointment\Entities\AppointmentPaymentLog;
use Modules\Appointment\Entities\AppointmentSchedule;
use Modules\Appointment\Entities\AppointmentTax;
use Modules\Appointment\Entities\SubAppointment;
use Modules\Blog\Entities\Blog;
use Modules\Blog\Entities\BlogCategory;
use Modules\Blog\Entities\BlogComment;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use App\Traits\SeoDataConfig;
use function Sodium\increment;

class AppointmentFrontendController extends Controller
{
    use SEOToolsTrait,SeoDataConfig;
   // private const BASE_PATH = 'appointment::tenant.frontend.appointments.';
    private const BASE_PATH = 'appointments.';

    public function appointment_single($slug)
    {
        $appointment = Appointment::where(['slug'=> $slug,'status'=> 1])->first();

        if (empty($appointment)) {
            abort(404);
        }

        Appointment::where(['slug'=> $slug,'status'=> 1])->increment('views');

        $comments = AppointmentComment::select('id','user_id','appointment_id','comment_content','created_at')->where('appointment_id',$appointment->id)->orderBy('id','desc')->take(5)->get();
        $all_related_appointments = Appointment::select('id','title','description','created_at','views','image','slug')->orderBy('id','desc')->take(3)->get();
        $comments_count = AppointmentComment::select('id','user_id','appointment_id','comment_content','created_at')->where('appointment_id',$appointment->id)->count();
        $this->setMetaDataInfo($appointment);
        return themeView(self::BASE_PATH.'appointment-single',compact('appointment','all_related_appointments','comments','comments_count'));

    }

    public function category_wise_appointment_page($id)
    {
        if (empty($id)) {
            abort(404);
        }

        $all_blogs = Appointment::usingLocale(GlobalLanguage::default_slug())->where(['appointment_category_id' => $id,'status' => 1])->orderBy('id', 'desc')->paginate(4);
        $category = AppointmentCategory::where(['id' => $id, 'status' => 1])->first();
        $category_name = $category->getTranslation('title',get_user_lang());

        return themeView(self::BASE_PATH.'appointment-category')->with([
            'all_blogs' => $all_blogs,
            'category_name' => $category_name,
        ]);

    }

    public function appointment_search_page(Request $request)
    {
        $request->validate([
            'search' => 'required'
        ],
            ['search.required' => 'Enter anything to search']);

        $all_blogs = Appointment::Where('title', 'LIKE', '%' . $request->search . '%')
            ->orderBy('id', 'desc')->paginate(4);

        return themeView(self::BASE_PATH.'appointment-search')->with([
            'all_blogs' => $all_blogs,
            'search_term' => $request->search,
        ]);

    }


    public function appointment_comment_store(Request $request)
    {
        $request->validate([
            'comment_content' => 'required'
        ]);

        $content = AppointmentComment::create([
            'appointment_id' => $request->appointment_id,
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

        $all_comment = AppointmentComment::with(['appointment', 'user'])
            ->where('appointment_id',$request->id)
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


    public function appointment_order_page($slug)
    {
        $order_details = Appointment::with('sub_appointments')->where('slug',$slug)->firstOrFail();
        $day_types = AppointmentDayType::select('id','title','status')->where('status',1)->get();

        return themeView(self::BASE_PATH.'appointment-payment.order-page')->with([
            'order_details' => $order_details,
            'day_types' => $day_types,
        ]);
    }


    public function get_schedule_via_time_data(Request $request)
    {
        $day = Carbon::make($request->date)->format("l");
        $day = AppointmentDay::with("times","times.type")->where('key',$day)->firstOrFail();
        $tab_id = $request->tab_id;
        $typeWiseTime = $day->times?->groupBy('day_type');

        if($typeWiseTime->count() < 1){
            return '<div class="alert alert-warning"> <h6>'.__("No schedule available").'</h6> </div>';
        }

        return themeView(self::BASE_PATH.'controller-markup.schedule',compact('tab_id','day','typeWiseTime'))->render();
    }

    public function payment_page(Request $request)
    {
        $request->validate([
            'appointment_date' => 'required|date',
            'schedule_time' => 'required',
        ]);

        $appointment_id = $request->appointment_id;
        $sub_appointment_ids = $request->sub_appointment_ids;
        $appointment_date = $request->appointment_date;
        $schedule_time = $request->schedule_time;

        Session::put('appointment_order_data',[
            'appointment_id' => $appointment_id,
            'sub_appointment_ids' => $sub_appointment_ids,
            'appointment_date' => $appointment_date,
            'schedule_time' => $schedule_time,
        ]);

        $order_details = Appointment::findOrFail($appointment_id);
        $appointment_order_session_data = \session()->get('appointment_order_data');

        $sub_appointments = [];
        if(!empty($appointment_order_session_data['sub_appointment_ids'])){
            $sub_appointments = SubAppointment::select('id','title','price')->whereIn('id',$appointment_order_session_data['sub_appointment_ids'])->get();
        }

        return themeView(self::BASE_PATH.'appointment-payment.payment-page',compact('order_details','sub_appointments'));
    }


    public function appointment_payment_success($id)
    {
        $extract_id = substr($id, 6);
        $extract_id =  substr($extract_id, 0, -6);

        $payment_details = '';
        if(!empty($extract_id)){
            $payment_details = AppointmentPaymentLog::find($extract_id);
        }

        return themeView(self::BASE_PATH.'appointment-payment.success',compact('payment_details'));

    }

    public function appointment_payment_cancel()
    {
        return themeView(self::BASE_PATH.'appointment-payment.cancel');
    }


}
