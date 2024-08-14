<?php

namespace Modules\EmailTemplate\Traits\EmailTemplate\Tenant;

use Illuminate\Support\Str;

trait EventEmailTemplate
{
    /**
     * send eventBookingReminderMail
     * */
    public function eventBookingReminderMail($booking_details)
    {
        $message = get_static_option('event_booking_reminder_' . get_user_lang() . '_message');
        $message = $this->parseBookingInfo($message, $booking_details);
        return [
            'subject' => get_static_option('event_booking_reminder_' . get_user_lang() . '_subject'),
            'message' => $message,
        ];
    }

    /**
     * send eventBookingUserMail
     * */
    public function eventBookingPaymentAcceptMail($booking_details)
    {
        $message = get_static_option('event_booking_payment_accept_' . get_user_lang(). '_message');
        $message = $this->parseBookingInfo($message, $booking_details);
        return [
            'subject' => get_static_option('event_booking_payment_accept_' . get_user_lang() . '_subject'),
            'message' => $message,
        ];
    }

    /**
     * send eventBookingUserMail
     * */
    public function eventBookingUserMail($booking_details)
    {
        $message = get_static_option('event_booking_user_mail_' . get_user_lang() . '_message');
        $message = $this->parseBookingInfo($message, $booking_details);
        return [
            'subject' => get_static_option('event_booking_user_mail_' . get_user_lang(). '_subject'),
            'message' => $message,
        ];
    }

    /**
     * send eventBookingAdminMail
     * */
    public function eventBookingAdminMail($booking_details)
    {
        $message = get_static_option('event_booking_admin_mail_' .get_user_lang(). '_message');
        $message = $this->parseBookingInfo($message, $booking_details);
        return [
            'subject' => get_static_option('event_booking_admin_mail_' . get_user_lang() . '_subject'),
            'message' => $message,
        ];
    }

    private function parseBookingInfo($message, $booking_details)
    {

        $message = str_replace(
            [
                '@attendance_id',
                '@attendance_date',
                '@event_title',
                '@billing_name',
                '@billing_email',
                '@payment_gateway',
                '@payment_date',
                '@cost',
                '@payment_status',
                '@user_dashboard',
                '@site_title',
                '@billing_info',
                '@event_item',
            ],
            [
                $booking_details->id,
                $booking_details->created_at->format('d F Y H:m:s'),
                optional($booking_details->event)->title,
                optional($booking_details->payment)->name,
                optional($booking_details->payment)->email,
                str_replace('_',' ',optional($booking_details->payment)->package_gateway),
                optional(optional($booking_details->payment)->created_at)->format('d F Y H:m:s'),
                amount_with_currency_symbol($booking_details->event_cost),
                optional($booking_details->payment)->status,
                '<div class="btn-wrap"><a href="' . route('user.home') . '" class="anchor-btn">' . __('more info') . '</a></div>',
                get_static_option('site_' . get_user_lang(). '_title'),
                $this->eventBillingInfo($booking_details),
                $this->eventItemInfo($booking_details),
            ], $message);
        return $message;
    }

    private function eventBillingInfo($booking_details)
    {
        $output = '<div class="billing-wrap"><ul class="billing-details">';
        $output .= '<li><strong>'.__('Attendance ID').':</strong> #'.$booking_details->id.'</li>';
        $output .= '<li><strong>'.__('Name').':</strong> '.optional($booking_details->payment)->name.'</li>';
        $output .= '<li><strong>'.__('Email').':</strong> '.optional($booking_details->payment)->email.'</li>';
        $output .= '<li><strong>'.__('Payment Method').':</strong> '.str_replace('_',' ',optional($booking_details->payment)->package_gateway).'</li>';
        $output .= '<li><strong>'.__('Payment Status').':</strong> '.optional($booking_details->payment)->status.'</li>';
        $output .= '<li><strong>'.__('Transaction id').':</strong> '.optional($booking_details->payment)->transaction_id.'</li>';
        $output .= '</ul></div>';
        return $output;
    }

    private function eventItemInfo($booking_details)
    {
        $output = '<div class="events-wrap"><div class="single-events-list-item event-order-success-page">';
        $output .= '<div class="thumb">';
        $output .= render_image_markup_by_attachment_id(optional($booking_details->event)->image,'','grid');
        $output .= '<div class="time-wrap"><span class="date">'.date('d',strtotime(optional($booking_details->event)->date)).'</span><span class="month">'.date('M',strtotime(optional($booking_details->event)->date)).'</span></div>';
        $output .= ' </div>'; //thumb end div
        $output .= ' <div class="content-area">';
        $output .= ' <div class="top-part">';
        $output .= '<a href="'.route('frontend.events.single',optional($booking_details->event)->slug).'"><h4 class="title">'.optional($booking_details->event)->title.'</h4></a>';
        $output .= '<span class="location"> <strong>'.__('Location').' :</strong> '.optional($booking_details->event)->venue_location.'</span>';
        $output .= ' </div>';
        $output .=' <p>'.strip_tags(Str::words(str_replace('&nbsp;',' ',optional($booking_details->event)->content),20)).'</p>';
        $output .= '</div></div></div>';

        return $output;
    }
}
