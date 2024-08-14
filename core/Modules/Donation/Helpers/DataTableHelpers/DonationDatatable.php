<?php


namespace Modules\Donation\Helpers\DataTableHelpers;


use App\Enums\DonationPaymentStatusEnum;
use Modules\Donation\Entities\Donation;

class DonationDatatable
{
    public static function infoColumn($row,$default_lang)
    {
        $output = '<ul class="all_donation_info_column">';
        $output .= '<li><strong>' . __('Title') . ':</strong> ' . $row->getTranslation('title',$default_lang) . '</li>';
        $output .= '<li><strong>' . __('Created At') . ':</strong> ' . date("d - M - Y", strtotime($row->created_at)) . '</li>';
        $output .= '<li><strong>' . __('Created By') . ':</strong> ';
        $output .= optional($row->creator)->name ?? __('Anonymous');
        $output .= '<li><strong>' . __('Deadline') . ':</strong> ';
        $output .= $row->deadline ?? '';
        $output .= '<li><strong>' . __('Goal') . ':</strong> ' . amount_with_currency_symbol($row->amount) . '</li>';
        $output .= '<li><strong>' . __('Raised') . ':</strong> ';
        $output .= $row->raised ? amount_with_currency_symbol($row->raised) : amount_with_currency_symbol(0);
        $output .= '</li>';

        if(!empty($row->popular)){
            $output .= '<li><strong>' . __('Popular') . ': </strong> ';
            $output .=  __(' Yes');
        }

        $output .= '<li><strong>' . __('Views') . ':</strong> ';
        $output .= $row->views ;

        $output .= '</ul>';
        return $output;
    }


    public static function comments($id)
    {
        $donation = Donation::find($id);
        $title = __('Cause Comments') . __(sprintf(' (%d)',$donation->comments?->count()));
        $url = route('tenant.admin.donation.comments.view',$id);
        return <<<HTML
        <a href="{$url}"
           class="btn btn-info text-white mb-3 mr-1 btn-sm">{$title}
        </a>
HTML;
    }


    public static function paymentInfoColumn($row){

        $output = '<ul>';

        $output .= '<li><strong>'.__('Donation Title').': </strong> '.purify_html(optional($row->donation)->getTranslation('title',get_user_lang())).'</li>';
        $output .= '<li><strong>'.__('Amount').': </strong> '.amount_with_currency_symbol($row->amount).'</li>';
        $output .= '<li><strong>'.__('Name').': </strong> '.purify_html($row->name).'</li>';
        $output .= '<li><strong>'.__('Email').': </strong> '.purify_html($row->email).'</li>';

        $output .= '<li><strong>'.__('Payment Gateway').': </strong> '.ucwords(str_replace('_',' ',$row->payment_gateway)).'</li>';
        if ( $row->payment_gateway != 'bank_transfer'){
            $output .= '<li><strong>'.__('Transaction ID').': </strong> '.$row->transaction_id.'</li>';
        }

        if($row->payment_gateway == 'bank_transfer'){
            $output .= '<li><strong>' . __('View Attachment') . ': </strong> ';
            $output .=  self::get_anchor(url('assets/uploads/attachment/'.$row->manual_payment_attachment),'Click Here');
        }

        $output .= '<li><strong>'.__('Date').': </strong> '.date_format($row->created_at,'d M Y').'</li>';
        $output .= '</ul>';

        return $output;
    }

    public static function get_donation_status_with_markup($row)
    {
        $output = '';
        if($row->status == 1){
            $output .= '<li><span class="badge badge-info">'.DonationPaymentStatusEnum::getText($row->status ).'</span> </li>';
        }else{
            $output .= '<li><span class="badge badge-dark">'.DonationPaymentStatusEnum::getText($row->status ).'</span> </li>';
        }

        return $output;
    }


    public static function get_anchor($url, $text){
        return '<a  href="'.$url.'" target="_blank">'.__($text).'</a>';
    }


}
