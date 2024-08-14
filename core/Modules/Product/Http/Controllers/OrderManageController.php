<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Mail\EventMail;
use App\Mail\OrderReply;
use App\Models\FormBuilder;
use App\Models\Language;
use App\Models\PaymentLogs;
use App\Models\PricePlan;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Modules\Event\Entities\EventPaymentLog;
use Modules\Product\Entities\OrderProducts;
use Modules\Product\Entities\ProductInventory;
use Modules\Product\Entities\ProductInventoryDetail;
use Modules\Product\Entities\ProductOrder;

class OrderManageController extends Controller
{
    private const ROOT_PATH = 'product::admin.product-order-manage.';

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function all_orders(Request $request)
    {
        $typeArr = ['pending', 'in_progress', 'cancel', 'complete'];
        if (isset($request->filter) && in_array($request->filter, $typeArr))
        {
            $all_orders = ProductOrder::where('status', $request->filter)->orderBy('id', 'desc')->get();
            return view(self::ROOT_PATH . 'order-manage-all')->with(['all_orders' => $all_orders]);
        }

        $all_orders = ProductOrder::orderBy('id', 'desc')->get();
        return view(self::ROOT_PATH . 'order-manage-all')->with(['all_orders' => $all_orders]);
    }

    public function view_order($id)
    {
        if (!empty($id)) {
            $order = ProductOrder::find($id);
        }

        return view(self::ROOT_PATH . 'order-view', compact('order'));
    }

    public function pending_orders()
    {
        $all_orders = PaymentLogs::where('status', 'pending')->get();
        return view(self::ROOT_PATH . 'order-manage-pending')->with(['all_orders' => $all_orders]);
    }

    public function completed_orders()
    {
        $all_orders = PaymentLogs::where('status', 'complete')->get();
        return view(self::ROOT_PATH . 'order-manage-completed')->with(['all_orders' => $all_orders]);
    }

    public function in_progress_orders()
    {
        $all_orders = PaymentLogs::where('status', 'in_progress')->get();
        return view(self::ROOT_PATH . 'order-manage-in-progress')->with(['all_orders' => $all_orders]);
    }

    public function change_status(Request $request)
    {
        $this->validate($request, [
            'order_status' => 'required|string|max:191',
            'payment_status' => 'nullable',
            'order_id' => 'required|string|max:191'
        ]);

        $order_details = ProductOrder::find($request->order_id);

        if ($order_details->payment_status === 'success' && isset($request->payment_status))
        {
            return redirect()->back()->withErrors(['msg' => __('You can not change this payment status')]);
        }

        $order_details->status = $request->order_status;
        if (isset($request->payment_status))
        {
            $order_details->payment_status = $request->payment_status;
        }
        $order_details->save();

        if ($order_details->status === 'cancel')
        {
            $this->undostock($order_details);
        }

        $data['subject'] = __('Your order status has been changed');
        $data['message'] = __('Hello') . ' ' . $order_details->name . '<br>';
        $data['message'] .= __('Your order') . ' #' . $order_details->id . ' ';
        $data['message'] .= __('status has been changed to') . ' ' . str_replace('_', ' ', $request->order_status) . '.';

        //send mail while order status change
        try {
            Mail::to($order_details->email)->send(new BasicMail($data['message'], $data['subject']));

        } catch (\Exception $e) {

            return redirect()->back()->with(['type' => 'danger', 'msg' => $e->getMessage()]);
        }


        return redirect()->back()->with(['msg' => __('Payment Logs Status Update Success...'), 'type' => 'success']);
    }

    private function undostock($order) {
        $ordered_products = OrderProducts::where('order_id', $order->id)->get();
        foreach ($ordered_products ?? [] as $product) {
            if ($product->variant_id !== null)
            {
                $variants = ProductInventoryDetail::where(['product_id' => $product->product_id, 'id' => $product->variant_id])->get();
                if (!empty($variants))
                {
                    foreach ($variants ?? [] as $variant)
                    {
                        $variant->increment('stock_count', $product->quantity);
                        $variant->decrement('sold_count', $product->quantity);
                    }
                }
            }

            $product_inventory = ProductInventory::where('product_id', $product->product_id ?? 0)->first();
            $product_inventory->increment('stock_count', $product->quantity ?? 0);
            $product_inventory->sold_count = $product_inventory->sold_count == null ? 1 : $product_inventory->sold_count - $product->quantity;
            $product_inventory->save();
        }
    }

    public function order_reminder(Request $request)
    {
        $order_details = ProductOrder::find($request->id);

        //send order reminder mail
        $data['subject'] = __('your order is still in pending at') . ' ' . get_static_option('site_' . get_default_language() . '_title');
        $data['message'] = __('hello') . ' ' . $order_details->name . '<br>';
        $data['message'] .= __('your order') . ' #' . $order_details->id . ' ';
        $data['message'] .= __('is still in pending, to complete your order go to');
        $data['message'] .= ' <a href="' . route('tenant.user.home') . '">' . __('your dashboard') . '</a>';
        //send mail while order status change

        try {
            Mail::to($order_details->email)->send(new BasicMail($data['message'], $data['subject']));
        } catch (\Exception $e) {
            return redirect()->back()->with(['type' => 'danger', 'msg' => $e->getMessage()]);
        }

        return redirect()->back()->with(['msg' => __('PaymentLogs Reminder Mail Send Success...'), 'type' => 'success']);
    }

    public function send_mail(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|max:191',
            'name' => 'required|string|max:191',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);
        $subject = str_replace('{site}', get_static_option('site_' . get_default_language() . '_title'), $request->subject);
        $data = [
            'name' => $request->name,
            'message' => $request->message,
        ];
        try {
            Mail::to($request->email)->send(new OrderReply($data, $subject));
        } catch (\Exception $e) {
            return redirect()->back()->with(['type' => 'danger', 'msg' => $e->getMessage()]);
        }
        return redirect()->back()->with(['msg' => __('Order Reply Mail Send Success...'), 'type' => 'success']);
    }


    public function all_payment_logs()
    {
        $paymeng_logs = PaymentLogs::all();
        return view('tenant.admin.payment-logs.payment-logs-all')->with(['payment_logs' => $paymeng_logs]);
    }

    public function payment_logs_delete(Request $request, $id)
    {
        PaymentLogs::find($id)->delete();
        return redirect()->back()->with(['msg' => __('Payment Log Delete Success...'), 'type' => 'danger']);
    }

    public function payment_logs_approve(Request $request, $id)
    {
        $payment_logs = PaymentLogs::find($id);
        $payment_logs->status = 'complete';
        $payment_logs->save();

        PaymentLogs::where('id', $payment_logs->order_id)->update(['payment_status' => 'complete']);

        $order_details = PaymentLogs::find($payment_logs->order_id);
        $package_details = PricePlan::where('id', $order_details->package_id)->first();
        $payment_details = PaymentLogs::where('order_id', $payment_logs->order_id)->first();
        $all_fields = unserialize($order_details->custom_fields);
        unset($all_fields['package']);

        $all_attachment = unserialize($order_details->attachment);
        $order_page_form_mail = get_static_option('order_page_form_mail');
        $order_mail = $order_page_form_mail ? $order_page_form_mail : get_static_option('site_global_email');

        $subject = __('your order has been placed');
        $message = __('your order has been placed.') . ' #' . $payment_logs->order_id;
        $message .= ' ' . __('at') . ' ' . date_format($order_details->created_at, 'd F Y H:m:s');
        $message .= ' ' . __('via') . ' ' . str_replace('_', ' ', $payment_details->package_gateway);

        Mail::to($payment_details->email)->send(new PlacePaymentLogs([
            'data' => $order_details,
            'subject' => $subject,
            'message' => $message,
            'package' => $package_details,
            'attachment_list' => $all_attachment,
            'payment_log' => $payment_details
        ]));

        return redirect()->back()->with(['msg' => __('Manual Payment Accept Success'), 'type' => 'success']);
    }

    public function order_success_payment()
    {
        $all_languages = Language::all();
        return view(self::ROOT_PATH . 'order-success-page')->with(['all_languages' => $all_languages]);
    }

    public function update_order_success_payment(Request $request)
    {
        $all_language = Language::all();
        foreach ($all_language as $lang) {
            $this->validate($request, [
                'site_order_success_page_' . $lang->slug . '_title' => 'nullable',
                'site_order_success_page_' . $lang->slug . '_description' => 'nullable',
            ]);
            $title = 'site_order_success_page_' . $lang->slug . '_title';
            $description = 'site_order_success_page_' . $lang->slug . '_description';

            update_static_option($title, $request->$title);
            update_static_option($description, $request->$description);
        }
        return redirect()->back()->with(['msg' => __('PaymentLogs Success Page Update Success...'), 'type' => 'success']);
    }

    public function order_cancel_payment()
    {
        $all_languages = Language::all();
        return view(self::ROOT_PATH . 'order-cancel-page')->with(['all_languages' => $all_languages]);
    }

    public function update_order_cancel_payment(Request $request)
    {

        $all_language = Language::all();
        foreach ($all_language as $lang) {
            $this->validate($request, [
                'site_order_cancel_page_' . $lang->slug . '_title' => 'nullable',
                'site_order_cancel_page_' . $lang->slug . '_subtitle' => 'nullable',
                'site_order_cancel_page_' . $lang->slug . '_description' => 'nullable',
            ]);
            $title = 'site_order_cancel_page_' . $lang->slug . '_title';
            $subtitle = 'site_order_cancel_page_' . $lang->slug . '_subtitle';
            $description = 'site_order_cancel_page_' . $lang->slug . '_description';

            update_static_option($title, $request->$title);
            update_static_option($subtitle, $request->$subtitle);
            update_static_option($description, $request->$description);
        }
        return redirect()->back()->with(['msg' => __('PaymentLogs Cancel Page Update Success...'), 'type' => 'success']);
    }

    public function bulk_action(Request $request)
    {
        $all = PaymentLogs::find($request->ids);
        foreach ($all as $item) {
            $item->delete();
        }
        return response()->json(['status' => 'ok']);
    }

    public function payment_log_bulk_action(Request $request)
    {
        $all = PaymentLogs::find($request->ids);
        foreach ($all as $item) {
            $item->delete();
        }
        return response()->json(['status' => 'ok']);
    }

    public function order_report(Request $request)
    {

        $order_data = '';
        $query = PaymentLogs::query();
        if (!empty($request->start_date)) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if (!empty($request->end_date)) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        if (!empty($request->order_status)) {
            $query->where(['status' => $request->order_status]);
        }
        if (!empty($request->payment_status)) {
            $query->where(['payment_status' => $request->payment_status]);
        }
        $error_msg = __('select start & end date to generate order report');
        if (!empty($request->start_date) && !empty($request->end_date)) {
            $query->orderBy('id', 'DESC');
            $order_data = $query->paginate($request->items);
            $error_msg = '';
        }

        return view(self::ROOT_PATH . 'order-report')->with([
            'order_data' => $order_data,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'items' => $request->items,
            'order_status' => $request->order_status,
            'payment_status' => $request->payment_status,
            'error_msg' => $error_msg
        ]);
    }

    public function payment_report(Request $request)
    {

        $order_data = '';
        $query = PaymentLogs::query();
        if (!empty($request->start_date)) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if (!empty($request->end_date)) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        if (!empty($request->payment_status)) {
            $query->where(['status' => $request->payment_status]);
        }
        $error_msg = __('select start & end date to generate payment report');
        if (!empty($request->start_date) && !empty($request->end_date)) {
            $query->orderBy('id', 'DESC');
            $order_data = $query->paginate($request->items);
            $error_msg = '';
        }

        return view('tenant.admin.payment-logs.payment-report')->with([
            'order_data' => $order_data,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'items' => $request->items,
            'payment_status' => $request->payment_status,
            'error_msg' => $error_msg
        ]);
    }

    public function index()
    {
        $all_custom_form = FormBuilder::all();
        return view(self::ROOT_PATH . 'form-section')->with(['all_custom_form' => $all_custom_form]);
    }

    public function udpate(Request $request)
    {
        $this->validate($request, [
            'order_form' => 'nullable|string',
        ]);

        $all_language = Language::all();

        foreach ($all_language as $lang) {

            $this->validate($request, [
                'order_page_' . $lang->slug . '_form_title' => 'nullable|string',
            ]);
            $field = 'order_page_' . $lang->slug . '_form_title';
            update_static_option('order_page_' . $lang->slug . '_form_title', $request->$field);
        }

        update_static_option('order_form', $request->order_form);

        return redirect()->back()->with(['msg' => __('Settings Updated....'), 'type' => 'success']);
    }

    public function generate_order_invoice(Request $request)
    {
        $payment_details = ProductOrder::find($request->id);
        if (empty($payment_details)) {
            return abort(404);
        }

        $pdf = PDF::loadview('tenant.frontend.invoice.order', ['payment_details' => $payment_details])->setOptions([
            'defaultFont' => 'sans-serif',
            'isRemoteEnabled'=>true
        ]);


        return $pdf->download('package-invoice.pdf');
    }

    public function order_manage_settings(Request $request)
    {
        if ($request->method() == 'GET')
        {
            return view(self::ROOT_PATH.'order-settings');
        } else {
            $this->validate($request, [
                'receiving_email' => 'nullable|email',
            ]);

            update_static_option('order_receiving_email', $request->receiving_email);

            return redirect()->back()->with(['msg' => __('Settings Updated....'), 'type' => 'success']);
        }
    }

    public function product_payment_accept($id)
    {
        $payment_details = ProductOrder::findOrFail($id);
        $payment_details->status = 'complete';
        $payment_details->payment_status = 'complete';
        $payment_details->save();

        $customer_subject = __('Your product order payment approved in').' '.get_static_option('site_'.get_user_lang().'_title');
        $admin_subject = __('Payment Approved successfully..!').' '.get_static_option('site_'.get_user_lang().'_title');
        $message = __('Product order approved');

        try {
            Mail::to(get_static_option('tenant_site_global_email'))->send(new BasicMail($message, $admin_subject,));
            Mail::to($payment_details->email)->send(new BasicMail( $message, $customer_subject));

        } catch (\Exception $e) {
            return redirect()->back()->with(['type' => 'danger', 'msg' => $e->getMessage()]);
        }

        return redirect()->back()->with(['msg' => __('Payment Accepted Successfully..!'), 'type' => 'success']);
    }
}
