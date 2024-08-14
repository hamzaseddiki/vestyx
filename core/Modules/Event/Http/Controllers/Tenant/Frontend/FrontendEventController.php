<?php

namespace Modules\Event\Http\Controllers\Tenant\Frontend;

use App\Facades\GlobalLanguage;
use App\Helpers\SanitizeInput;
use App\Traits\SeoDataConfig;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Modules\Event\Entities\Event;
use Modules\Event\Entities\EventCategory;
use Modules\Event\Entities\EventComment;
use Modules\Event\Entities\EventPaymentLog;


class FrontendEventController extends Controller
{
    use SEOToolsTrait,SeoDataConfig;
    //private const BASE_PATH = 'event::tenant.frontend.event.';
    private const BASE_PATH = 'event.';

    public function event_single($slug)
    {
        $event = Event::where(['slug'=> $slug,'status'=> 1])->first();

        if (empty($event)) {
            abort(404);
        }

        $this->setMetaDataInfo($event);
        $comments = EventComment::select('id','user_id','event_id','comment_content','created_at')->where('event_id',$event->id)->orderBy('id','desc')->take(5)->get();
        $comments_count = EventComment::select('id','user_id','event_id','comment_content','created_at')->where('event_id',$event->id)->count();
        $all_related_events = Event::where(['status'=>1, 'category_id' => $event->category_id])->orderBy('id','desc')->take(3)->get();
        $all_related_categories = EventCategory::where(['status'=>1])->orderBy('id','desc')->get();


        $all_event_data_by_last_seven_days = EventPaymentLog::select('amount','created_at')->where(['status' => 1])
            ->where('event_id',$event->id)
            ->whereDate('created_at', '>', Carbon::now()->subDays(7))
            ->get()
            ->groupBy(function ($query){
                return Carbon::parse($query->created_at)->format('D, d F Y');
            })->toArray();

        $chart_data= [];
        foreach ($all_event_data_by_last_seven_days as  $amount){
            $chart_data[] =  array_column( $amount,'amount');
        }

        $fetching_data =  $chart_data[0] ?? [];
        $new_chart_array = array_values($fetching_data);


        return themeView(self::BASE_PATH.'event-single',compact(
            'event','comments','comments_count',
            'all_related_events','all_related_categories','new_chart_array'
        ));
    }

    public function category_wise_event_page($id)
    {
        if (empty($id)) {
            abort(404);
        }

        $all_event = Event::usingLocale(GlobalLanguage::default_slug())->where(['category_id' => $id,'status' => 1])->orderBy('id', 'desc')->paginate(4);
        $category = EventCategory::where(['id' => $id, 'status' => 1])->first();
        $category_name = $category->getTranslation('title',get_user_lang());

        return themeView(self::BASE_PATH.'category')->with([
            'all_event' => $all_event,
            'category_name' => $category_name,
        ]);
    }

    public function event_search_page(Request $request)
    {
        $request->validate([
            'search' => 'required'
        ],
            ['search.required' => 'Enter anything to search']);

        $all_event = Event::Where('title', 'LIKE', '%' . $request->search . '%')
            ->orderBy('id', 'desc')->paginate(4);

        return themeView(self::BASE_PATH.'search')->with([
            'all_event' => $all_event,
            'search_term' => $request->search,
        ]);
    }


    public function event_comment_store(Request $request)
    {
        $request->validate([
            'comment_content' => 'required'
        ]);

        $content = EventComment::create([
            'event_id' => $request->event_id,
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
        $all_comment = EventComment::with(['event', 'user'])
            ->where('event_id',$request->id)
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


    public function event_payment($slug)
    {
        $event = Event::where('slug',$slug)->firstOrFail();
        if (!empty($event->date) && $event->date <= now()) {
            return view('errors.403')->with(['message' => __('Event Expired')]);
        }

        return themeView(self::BASE_PATH.'event-payment.payment',compact('event'));

    }

    public function event_payment_success($id)
    {
        $extract_id = substr($id, 6);
        $extract_id = substr($extract_id, 0, -6);

        $check_id = !empty($extract_id) ? $extract_id : $id;
        $event_logs = EventPaymentLog::find($check_id);
        $event = Event::find($event_logs->event_id);

        $qr_code_title = $event->getTranslation('title',get_user_lang()) ?? '';
        $qr_event_url = route('tenant.frontend.event.single',$event->slug);
        $qr_code_venue = $event->venue_location ?? '';
        $qr_code_date = $event->date ?? '';
        $qr_code_time = $event->time ?? '';
        $site_title = get_static_option('site_'.get_user_lang().'_title') ?? '';


 $qr_code_markup = '
 <div>
     <h3> '.__('Event Title').' : '.$qr_code_title.'</h3>
     <p>'.__('Venue').' : '.$qr_code_venue.'</p>
     <p>'.__('Date').' : '.$qr_code_date.'</p>
     <p>'.__('Time').' : '.$qr_code_time.'</p>

     <p class="mt-3">'.__('Link').' : '.$qr_event_url.'</p>
     <p class="mt-5">'.__('Site').' : '.$site_title.'</p>
</div>
';


        return themeView(self::BASE_PATH . 'event-payment.success')->with([
            'event_logs' => $event_logs,
            'event' => $event,
            'qr_code_markup'=> purify_html($qr_code_markup),
        ]);
    }

    public function event_payment_cancel()
    {
        return themeView(self::BASE_PATH . 'event-payment.cancel');
    }

    public function event_ticket_download($id)
    {
        $event_details = EventPaymentLog::find($id);
        if (empty($event_details)) {
            return abort(404);
        }

        $pdf = PDF::loadview('tenant.frontend.ticket.event-ticket', [
            'event_details' => $event_details])->setOptions([
                'defaultFont' => 'sans-serif','isRemoteEnabled'=>true
             ]);
        return $pdf->download('event-ticket.pdf');
    }

}
