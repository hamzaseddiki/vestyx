<?php

namespace Modules\EmailTemplate\Traits\EmailTemplate\Tenant;

trait DonationEmailTemplate
{

    public function donationReminderMail($donation_log_details)
    {
        $message = get_static_option('donation_payment_reminder_mail_' . get_user_lang(). '_message');
        $message = $this->parseDonationInfo($message, $donation_log_details);
        return [
            'subject' => get_static_option('donation_payment_reminder_mail_' . get_user_lang() . '_subject'),
            'message' => $message,
        ];
    }


    public function donationPaymentAcceptMail($donation_log_details)
    {
        $message = get_static_option('donation_payment_accept_mail_' .get_user_lang(). '_message');
        $message = $this->parseDonationInfo($message, $donation_log_details);
        return [
            'subject' => get_static_option('donation_payment_accept_mail_' . get_user_lang() . '_subject'),
            'message' => $message,
        ];
    }

//donationUserMail
    public function donationUserMail($donation_log_details,$lang=null)
    {

		$language_slug = $lang ?? get_user_lang();
        $message = get_static_option('donation_user_mail_' . $language_slug . '_message');


        $message = $this->parseDonationInfo($message, $donation_log_details);
        return [
			//donation_user_mail_pt_PT_subject
            'subject' => get_static_option('donation_user_mail_' . $language_slug . '_subject'),
            'message' => $message,
            'language' => $language_slug,
            'type' => 'donation',
            'data' => $donation_log_details
        ];
    }


    public function donationAdminMail($donation_log_details)
    {
        $message = get_static_option('donation_admin_mail_' . get_user_lang(). '_message');
        $message = $this->parseDonationInfo($message, $donation_log_details);
        return [
            'subject' => get_static_option('donation_admin_mail_' . get_user_lang() . '_subject'),
            'message' => $message,
            'type' => 'test',
        ];
    }

    private function parseDonationInfo($message, $donation_log_details)
    {
        $message = str_replace(
            [
                '@donation_id',
                '@donor_name',
                '@donation_cause_title',
                '@payment_gateway',
                '@payment_status',
                '@donation_time',
                '@amount',
                '@amount_title',
                '@donation_info',
                '@user_dashboard',
                '@site_title',
            ],
            [
                $donation_log_details->id,
                $donation_log_details->name,
                optional($donation_log_details->cause)->getTranslation('title',get_user_lang()),
                str_replace('-','_',$donation_log_details->payment_gateway),
                $donation_log_details->status,
                $donation_log_details->created_at->format('D,  d-m-y h:i:s'),
                amount_with_currency_symbol($donation_log_details->amount),
                $this->donationAmountTitle($donation_log_details),
                $this->donationInfo($donation_log_details),
                ' <a href="'.route('user.home').'">'.__('your dashboard').'</a>',
                get_static_option('site_' . get_user_lang() . '_title')
            ], $message);
        return $message;
    }

    private function donationInfo($donation_log_details)
    {
        $output = '<table>';
        $output .= '<tr><td>'.__('Transação').'</td> <td>#'.$donation_log_details->id.'</td> </tr>';
        $output .= '<tr><td>'.__('Nome da Campanha').'</td> <td>'.optional($donation_log_details->cause)->getTranslation('title',get_user_lang()).'</td> </tr>';
        $output .= '<tr><td>'.__('Valor da Doação').'</td> <td>'.amount_with_currency_symbol($donation_log_details->amount).'</td> </tr>';
        $output .= '<tr><td>'.__('Forma de pagamento').'</td> <td>'.ucfirst(str_replace('_',' ',$donation_log_details->payment_gateway)).'</td> </tr>';
        $output .= '<tr><td>'.__('Estado do pagamento').'</td> <td>'.$donation_log_details->status.'</td> </tr>';
        $output .= '<tr><td>'.__('Nº da Transação').'</td> <td>'.$donation_log_details->transaction_id.'</td> </tr>';
        $output .= '</table>';
        return $output;
    }

    private function donationAmountTitle($donation_log_details)
    {
        return ' <div class="price-wrap">'.amount_with_currency_symbol($donation_log_details->amount).'</div>';
    }
}
