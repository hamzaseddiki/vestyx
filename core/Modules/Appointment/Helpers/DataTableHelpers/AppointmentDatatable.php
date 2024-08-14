<?php


namespace Modules\Appointment\Helpers\DataTableHelpers;


use App\Enums\DonationPaymentStatusEnum;
use App\Enums\StatusEnums;
use App\Facades\GlobalLanguage;
use Modules\Appointment\Entities\AppointmentComment;

class AppointmentDatatable
{
    public static function infoColumn($row,$default_lang)
    {
        $output = '<ul class="all_donation_info_column">';
        $output .= '<li><strong>' . __('Title') . ':</strong> ' . $row->getTranslation('title',$default_lang) . '</li>';
        $output .= '<li><strong>' . __('Category') . ':</strong> ' . $row->category?->getTranslation('title',$default_lang) . '</li>';
        $output .= '<li><strong>' . __('Subcategory') . ':</strong> ' . $row->subcategory?->getTranslation('title',$default_lang) . '</li>';
        $output .= '<li><strong>' . __('Created At') . ':</strong> ' . date("d-m-Y", strtotime($row->created_at)) . '</li>';
        $output .= '<li><strong>' . __('Price') . ':</strong> ' . amount_with_currency_symbol($row->price) . '</li>';
        $output .= '<li><strong>' . __('Person') . ':</strong> ' . $row->person . '</li>';

        $output .= '</li>';

        if(!empty($row->is_popular)){
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
        $output .= '<li><strong>'.__('Order Date').': </strong> '.date_format($row->created_at,'d M Y').'</li>';
        $output .= '<li><strong>'.__('Appointment Title').': </strong> '.purify_html(optional($row->appointment)->getTranslation('title',get_user_lang())).'</li>';
        $output .= '<li><strong>'.__('Name').': </strong> '.purify_html($row->name).'</li>';
        $output .= '<li><strong>'.__('Email').': </strong> '.purify_html($row->email).'</li>';
        $output .= '<li><strong>'.__('Phone').': </strong> '.purify_html($row->phone).'</li>';

        $output .= '<li><strong>'.__('Appointment Price').': </strong> '.amount_with_currency_symbol($row->appointment_price).'</li>';
        $output .= '<li><strong>'.__('Appointment Tax').': </strong> '.'<span class="text-success mr-2">'.purify_html(get_appointment_tax_amount_percentage($row->appointment_id)) .'</span>'.amount_with_currency_symbol($row->tax_amount).'</li>';
        $output .= '<li><strong>'.__('Appointment Subtotal').': </strong> '.amount_with_currency_symbol($row->subtotal).'</li>';
        $output .= '<li><strong>'.__('Total Amount').': </strong> '.amount_with_currency_symbol($row->total_amount).'</li>';
        $output .= '<li><strong>'.__('Payment Gateway').': </strong> '.ucwords(str_replace('_',' ',$row->payment_gateway)).'</li>';
        $output .= '<li><strong>'.__('Appointment Date').': </strong> '.$row->appointment_date.'</li>';
        $output .= '<li><strong>'.__('Appointment Time').': </strong> '.$row->appointment_time.'</li>';

        if ( $row->payment_gateway != 'bank_transfer'){
            $output .= '<li><strong>'.__('Transaction ID').': </strong> '.$row->transaction_id.'</li>';
        }

        if($row->payment_gateway == 'bank_transfer'){
            $output .= '<li><strong>' . __('View Attachment') . ': </strong> ';
            $output .=  self::get_anchor(url('assets/uploads/attachment/'.$row->manual_payment_attachment),'Click Here');
        }


        $output .= '</ul>';

        return $output;
    }

    public static function paymentAdditionalInfoColumn($additional){

        $output = '<ul>';
        foreach ($additional ?? [] as $sub){
            $output .= '<li><strong>'.__('Title').': </strong> '.$sub->getTranslation('title',default_lang()).'</li>';
            $output .= '<li><strong>'.__('Price').': </strong> '.amount_with_currency_symbol($sub->price).'</li>';
            $output .= '<br>';
        }

        $output .= '</ul>';

        return $output;
    }

    public static function get_status_with_markup($row)
    {
        $output = '';
        if($row->payment_status == 'complete'){
            $output .= '<li><span class="badge badge-info">'.ucwords($row->payment_status) .'</span> </li>';
        }else{
            $output .= '<li><span class="badge badge-dark">'.ucwords($row->payment_status) .'</span> </li>';
        }

        return $output;
    }


    public static function get_anchor($url, $text){
        return '<a  href="'.$url.'" target="_blank">'.__($text).'</a>';
    }

    public static function viewComments($route,$id){

        $commentsCOunt = AppointmentComment::where('appointment_id',$id)->count();

        $test = __('View Comments') . '<strong class="text-white"> ('.$commentsCOunt.') </strong>';
        return <<<HTML
        <a class="btn btn-success btn-sm mb-3 mr-1" href="{$route}">
            {$test}
        </a>
HTML;

    }

}
