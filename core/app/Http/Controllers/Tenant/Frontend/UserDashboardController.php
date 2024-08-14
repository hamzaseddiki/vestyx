<?php

namespace App\Http\Controllers\Tenant\Frontend;

use App\Events\SupportMessage;
use App\Helpers\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\PaymentLogs;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use App\Models\Tenant;
use App\Models\User;
use App\Models\WeddingPaymentLog;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\Appointment\Entities\AppointmentPaymentLog;
use Modules\Donation\Entities\DonationPaymentLog;
use Modules\Event\Entities\EventPaymentLog;
use Modules\HotelBooking\Entities\BookingInformation;
use Modules\HotelBooking\Http\Services\ServicesHelpers;
use Modules\Job\Entities\JobPaymentLog;
use Modules\Product\Entities\ProductOrder;
use Modules\Product\Entities\ProductSellInfo;


class UserDashboardController extends Controller
{
    const BASE_PATH = 'tenant.frontend.user.dashboard.';

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function user_index(){
        $total_event = EventPaymentLog::where('user_id',$this->logged_user_details()->id)->count();
        $total_donation = DonationPaymentLog::where('user_id',$this->logged_user_details()->id)->count();
        $total_product= ProductOrder::where('user_id',$this->logged_user_details()->id)->count();
        $support_tickets = SupportTicket::where('user_id',$this->logged_user_details()->id)->count();
        $job_applications = JobPaymentLog::where('user_id',$this->logged_user_details()->id)->count();
        $wedding_plans = WeddingPaymentLog::where('user_id',$this->logged_user_details()->id)->count();
        $total_appointment = AppointmentPaymentLog::where('user_id',$this->logged_user_details()->id)->count();
        $recent_logs = PaymentLogs::where('user_id',$this->logged_user_details()->id)->orderBy('id','desc')->take(10)->get();

        if(Schema::hasTable('booking_informations'))
        {
            $hotel_bookings = [
                "total_reservations" => BookingInformation::where('user_id',$this->logged_user_details()->id)->where('payment_status', '!=', 3)->paginate(10),
                "accepted_reservations" => BookingInformation::where('user_id',$this->logged_user_details()->id)->where('payment_status',1)->count(),
                "cancled_reservations" => BookingInformation::where('user_id',$this->logged_user_details()->id)->where('payment_status',3)->count(),
                "pending_reservations" => BookingInformation::where('user_id',$this->logged_user_details()->id)->where('payment_status',0)->count(),
            ];
        }else
        {
            $hotel_bookings=[
                "total_reservations" => null,
                "accepted_reservations" => null,
                "cancled_reservations" => null,
                "pending_reservations" => null,
            ];
        }

        return view(self::BASE_PATH.'user-home')->with(
            [
                'total_event' => $total_event,
                'total_donation' => $total_donation,
                'support_tickets' => $support_tickets,
                'recent_logs' => $recent_logs,
                'job_applications' => $job_applications,
                'total_product' => $total_product,
                'wedding_plans' => $wedding_plans,
                'total_appointment' => $total_appointment,
                'hotel_bookings' => $hotel_bookings,
            ]);
    }

    public function user_email_verify_index(){
        $user_details = Auth::guard('web')->user();
        if ($user_details->email_verified == 1){
            return redirect()->route('user.home');
        }
        if (is_null($user_details->email_verify_token)){
            Tenant::find($user_details->id)->update(['email_verify_token' => Str::random(20)]);
            $user_details = Tenant::find($user_details->id);

            $message_body = __('Here is your verification code : ').' <span class="verify-code"> <b>'.$user_details->email_verify_token.'</b></span>';

            try{
                Mail::to($user_details->email)->send(new BasicMail([
                    'subject' => __('Verify your email address'),
                    'message' => $message_body
                ]));
            }catch(\Exception $e){
                //hanle error
            }
        }
        return view('tenant.frontend.user.email-verify');
    }

    public function reset_user_email_verify_code(){
        $user_details = Auth::guard('web')->user();
        if ($user_details->email_verified == 1){
            return redirect()->route('user.home');
        }

        $message_body = __('Here is your verification code : ').' <span class="verify-code">'.$user_details->email_verify_token.'</span>';
        try{

        }catch(\Exception $e){
            Mail::to($user_details->email)->send(new BasicMail([
                'subject' => __('Verify your email address'),
                'message' => $message_body
            ]));
        }

        return redirect()->route('user.email.verify')->with(['msg' => __('Resend Verify Email Success'),'type' => 'success']);
    }

    public function user_email_verify(Request $request){
        $this->validate($request,[
            'verification_code' => 'required'
        ],[
            'verification_code.required' => __('verify code is required')
        ]);
        $user_details = Auth::guard('web')->user();
        $user_info = Tenant::where(['id' =>$user_details->id,'email_verify_token' => $request->verification_code])->first();
        if (empty($user_info)){
            return redirect()->back()->with(['msg' => __('your verification code is wrong, try again'),'type' => 'danger']);
        }
        $user_info->email_verified = 1;
        $user_info->save();
        return redirect()->route('user.home');
    }

    public function user_profile_update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'nullable|string|max:191',
            'state' => 'nullable|string|max:191',
            'city' => 'nullable|string|max:191',
            'zipcode' => 'nullable|string|max:191',
            'country' => 'nullable|string|max:191',
            'address' => 'nullable|string',
            'image' => 'nullable|string',
        ],[
            'name.' => __('name is required'),
            'email.required' => __('email is required'),
            'email.email' => __('provide valid email'),
        ]);
        User::find(Auth::guard('web')->user()->id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'company' => $request->company,
                'address' => $request->address,
                'state' => $request->state,
                'city' => $request->city,
                'country' => $request->country,
                'image' => $request->image,
            ]
        );

        return redirect()->back()->with(['msg' => __('Profile Update Success'), 'type' => 'success']);
    }

    public function user_password_change(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed'
        ],
            [
                'old_password.required' => __('Old password is required'),
                'password.required' => __('Password is required'),
                'password.confirmed' => __('password must have be confirmed')
            ]
        );

        $user = User::findOrFail(Auth::guard('web')->user()->id);

        if (Hash::check($request->old_password, $user->password)) {

            $user->password = Hash::make($request->password);
            $user->save();
            Auth::guard('web')->logout();

            return redirect()->route('tenant.user.login')->with(['msg' => __('Password Changed Successfully'), 'type' => 'success']);
        }

        return redirect()->back()->with(['msg' => __('Somethings Going Wrong! Please Try Again or Check Your Old Password'), 'type' => 'danger']);
    }

    public function logged_user_details(){
        $old_details = '';
        if (empty($old_details)){
            $old_details = User::findOrFail(Auth::guard('web')->user()->id);
        }
        return $old_details;
    }
    public function edit_profile()
    {
        return view(self::BASE_PATH.'edit-profile')->with(['user_details' => $this->logged_user_details()]);
    }

    public function change_password()
    {
        return view(self::BASE_PATH.'change-password');
    }

    public function user_all_reservation()
    {
        $user_reservations = BookingInformation::where('user_id',$this->logged_user_details()->id)->paginate(10);
       return view(self::BASE_PATH.'all-reservation')->with([ 'user_reservations' => $user_reservations]);
    }

    public function hotel_bookings()
    {
        $user_reservations = BookingInformation::where('user_id',$this->logged_user_details()->id)->paginate(10);
       return view(self::BASE_PATH.'hotel-booking')->with([ 'user_reservations' => $user_reservations]);
    }

    public function view_reservation($id)
    {
        $reservation_details = BookingInformation::where('id',$id)->first();
       return view(self::BASE_PATH.'view-reservation')->with([ 'reservation_details' => $reservation_details]);
    }

    public function support_tickets(){
        $all_tickets = SupportTicket::where('user_id',$this->logged_user_details()->id)->paginate(10);
        return view(self::BASE_PATH.'support-tickets')->with([ 'all_tickets' => $all_tickets]);
    }

    public function all_user_canceled_reservation(){
        $user_canceled_reservations = BookingInformation::where('user_id',$this->logged_user_details()->id)->where('payment_status',3)->paginate(10);
        return view(self::BASE_PATH.'canceled-reservation')->with([ 'user_canceled_reservations' => $user_canceled_reservations]);
    }
    public function all_user_pending_reservation(){
        $user_reservations = BookingInformation::where('user_id',$this->logged_user_details()->id)->where('payment_status',0)->paginate(10);
        return view(self::BASE_PATH.'all-reservation')->with([ 'user_reservations' => $user_reservations]);
    }
    public function all_user_approved_reservation(){
        $user_reservations = BookingInformation::where('user_id',$this->logged_user_details()->id)->where('payment_status',1)->paginate(10);
        return view(self::BASE_PATH.'all-reservation')->with([ 'user_reservations' => $user_reservations]);
    }

    public function reservation_cancle_request($id) {
     $bool =  BookingInformation::find($id)->update([
          'payment_status' =>4
      ]);
        return response()->danger(ResponseMessage::delete());
    }

    public function support_ticket_priority_change(Request $request){
        $this->validate($request,[
            'priority' => 'required|string|max:191'
        ]);
        SupportTicket::findOrFail($request->id)->update([
            'priority' => $request->priority,
        ]);
        return 'ok';
    }

    public function support_ticket_status_change(Request $request){
        $this->validate($request,[
            'status' => 'required|string|max:191'
        ]);
        SupportTicket::findOrFail($request->id)->update([
            'status' => $request->status,
        ]);
        return 'ok';
    }
    public function support_ticket_view(Request $request,$id){
        $ticket_details = SupportTicket::findOrFail($id);
        $all_messages = SupportTicketMessage::where(['support_ticket_id'=>$id])->get();
        $q = $request->q ?? '';
        return view(self::BASE_PATH.'view-ticket')->with(['ticket_details' => $ticket_details,'all_messages' => $all_messages,'q' => $q]);
    }

    public function support_ticket_message(Request $request){
        $this->validate($request,[
            'ticket_id' => 'required',
            'user_type' => 'required|string|max:191',
            'message' => 'required',
            'send_notify_mail' => 'nullable|string',
            'file' => 'nullable|mimes:zip',
        ]);

        $ticket_info = SupportTicketMessage::create([
            'support_ticket_id' => $request->ticket_id,
            'user_id' => Auth::guard('web')->id(),
            'type' => $request->user_type,
            'message' => $request->message,
            'notify' => $request->send_notify_mail ? 'on' : 'off',
        ]);

        if ($request->hasFile('file')){
            $uploaded_file = $request->file;
            $file_extension = $uploaded_file->getClientOriginalExtension();
            $file_name =  pathinfo($uploaded_file->getClientOriginalName(),PATHINFO_FILENAME).time().'.'.$file_extension;
            $uploaded_file->move('assets/uploads/ticket',$file_name);
            $ticket_info->attachment = $file_name;
            $ticket_info->save();
        }

        //send mail to user
        event(new SupportMessage($ticket_info));

        return redirect()->back()->with(['msg' => __('Mail Send Success'), 'type' => 'success']);
    }

    public function all_user_donation(){
        $all_user_donation = DonationPaymentLog::where('user_id',$this->logged_user_details()->id)->orderBy('id','DESC')->paginate(3);
        return view(self::BASE_PATH.'donation-logs')->with(['all_user_donation' => $all_user_donation]);
    }

    public function donation_invoice_generate(Request $request){
        $donation_details = DonationPaymentLog::find($request->id);

        if (empty($donation_details)) {
            return abort(404);
        }

        $pdf = PDF::loadview('tenant.frontend.invoice.donation', ['donation_details' => $donation_details])
                    ->setOptions(['defaultFont' => 'sans-serif','isRemoteEnabled'=>true]);
        return $pdf->download('donation-invoice.pdf');
    }

    public function all_user_wedding(){
        $all_user_wedding = WeddingPaymentLog::where('user_id',$this->logged_user_details()->id)->orderBy('id','DESC')->paginate(4);
        return view(self::BASE_PATH.'wedding-logs')->with(['all_user_wedding' => $all_user_wedding]);
    }

    public function wedding_invoice_generate(Request $request){
        $wedding_details = WeddingPaymentLog::find($request->id);
        if (empty($wedding_details)) {
            return abort(404);
        }

        $pdf = PDF::loadview('tenant.frontend.invoice.wedding', ['wedding_details' => $wedding_details])
            ->setOptions(['defaultFont' => 'sans-serif','isRemoteEnabled'=>true]);
        return $pdf->download('wedding-invoice.pdf');
    }

    public function all_user_event(){
        $all_user_event = EventPaymentLog::where('user_id',$this->logged_user_details()->id)->orderBy('id','DESC')->paginate(5);
        return view(self::BASE_PATH.'event-logs')->with(['all_user_event' => $all_user_event]);
    }

    public function event_invoice_generate(Request $request){
        $event_details = EventPaymentLog::find($request->id);
        if (empty($event_details)) {
            return abort(404);
        }

        $pdf = PDF::loadview('tenant.frontend.invoice.event', ['event_details' => $event_details])
            ->setOptions(['defaultFont' => 'sans-serif','isRemoteEnabled'=>true]);
        return $pdf->download('event-invoice.pdf');
    }


    public function all_user_job(){
        $all_user_jobs = JobPaymentLog::where('user_id',$this->logged_user_details()->id)->orderBy('id','DESC')->paginate(5);
        return view(self::BASE_PATH.'applied-logs')->with(['all_user_jobs' => $all_user_jobs]);
    }

    public function job_invoice_generate(Request $request){
        $job_details = JobPaymentLog::find($request->id);
        if (empty($job_details)) {
            return abort(404);
        }

        $pdf = PDF::loadview('tenant.frontend.invoice.job', ['job_details' => $job_details])
            ->setOptions(['defaultFont' => 'sans-serif','isRemoteEnabled'=>true]);
        return $pdf->download('job-invoice.pdf');
    }

    public function product_orders(){
        $order_list = ProductSellInfo::where("user_id", \auth("web")->user()->id)->latest()->get();

        return view(self::BASE_PATH.'product-order', compact("order_list"));
    }

    public function product_order_list($id = null){
        $order_list = ProductOrder::when(!empty($id), function ($query) use ($id) {
            $query->with("shipping");
            $query->where("id",$id);
        })->where("user_id", \auth("web")->user()->id)
            ->latest()->paginate(10);

        if (!empty($id)){
            $order = $order_list->first();
            return view(self::BASE_PATH.'product-order-details', compact("order"));
        }

        return view(self::BASE_PATH.'product-order-list', compact("order_list"));
    }


    public function all_user_appointment(){
        $all_user_appointments = AppointmentPaymentLog::where('user_id',$this->logged_user_details()->id)->orderBy('id','DESC')->paginate(5);
        return view(self::BASE_PATH.'appointment-logs')->with(['all_user_appointments' => $all_user_appointments]);
    }

    public function appointment_invoice_generate(Request $request){
        $appointment_details = AppointmentPaymentLog::find($request->id);
        if (empty($appointment_details)) {
            return abort(404);
        }

        $pdf = PDF::loadview('tenant.frontend.invoice.appointment', ['appointment_details' => $appointment_details])
            ->setOptions(['defaultFont' => 'sans-serif','isRemoteEnabled'=>true]);
        return $pdf->download('job-invoice.pdf');
    }





}
