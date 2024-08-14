<?php


namespace Modules\Portfolio\Helpers\DataTableHelpers;


use App\Enums\StatusEnums;
use App\Facades\GlobalLanguage;

class PortfolioDatatable
{
    public static function infoColumn($row,$default_lang)
    {
        $output = '<ul class="all_donation_info_column">';
        $output .= '<li><strong>' . __('Title') . ':</strong> ' . $row->getTranslation('title',$default_lang) . '</li>';
        $output .= '<li><strong>' . __('Client') . ':</strong> ' .  $row->getTranslation('client',$default_lang) . '</li>';
        $output .= '<li><strong>' . __('Design') . ':</strong> ' .  $row->getTranslation('design',$default_lang)  . '</li>';
        $output .= '<li><strong>' . __('Typography') . ':</strong> ' .  $row->getTranslation('typography',$default_lang) . '</li>';

        if(!empty($row->file)){
           $output .= '<li><strong>' . __('File ') . ':</strong> <a target="_blank" href="'.url('assets/uploads/custom-file/'.$row->file).'" > '.$row->file.'</a> </li>';
        }

        $output .= '<li><strong>' . __('Download ') . ':</strong> ' . $row->download. '</li>';
        $output .= '<li><strong>' . __('Created At') . ':</strong> ' . date("d M, Y", strtotime($row->created_at)) . '</li>';
        $output .= '</li>';

        $output .= '</ul>';
        return $output;
    }

    public static function aboutUpdate($id)
    {
        $title = __('Donation Updates');
        $url = route('tenant.admin.single.donation.update',$id);
        return <<<HTML
        <a href="{$url}"
           class="btn btn-success text-white mb-3 mr-1 btn-sm">{$title}
        </a>
HTML;
    }
    public static function comments($id)
    {
        $title = __('Cause Comments');
        $url = route('tenant.admin.donation.comments.view',$id);
        return <<<HTML
        <a href="{$url}"
           class="btn btn-primary text-white mb-3 mr-1 btn-sm">{$title}
        </a>
HTML;
    }

    public static function campaignApprove($id){
        $title = __('Approve This Campaign');
        $csrf = csrf_field();
        $action = route('admin.donation.approve');
        return <<<HTML
    <form action="{$action}" method="post"
          enctype="multipart/form-data">
          {$csrf}
        <input type="hidden" name="id" value="{$id}">
        <button class="btn btn-warning text-white mb-2"
                type="submit">{$title}
        </button>
    </form>
HTML;

    }

    public static function paymentInfoColumn($row){

        $data = $row->recurings;

        $id = '';
        foreach ($data as $it){
            $id = $it->cause_log_id ?? '';
        }
        $monthly_payment_check = !empty($id) ? '<li class="text-primary"><strong class="text-primary">'.__('Payment type').': </strong> '.__('Monthly').'</li>' : '';

        $output = '<ul>';
        $output .= $monthly_payment_check;
        $output .= '<li><strong>'.__('Donation Title').': </strong> '.purify_html(optional($row->cause)->title).'</li>';
        $output .= '<li><strong>'.__('Amount').': </strong> '.amount_with_currency_symbol($row->amount).'</li>';
        $output .= '<li><strong>'.__('Name').': </strong> '.purify_html($row->name).'</li>';
        $output .= '<li><strong>'.__('Email').': </strong> '.purify_html($row->email).'</li>';

        if(!empty($row->gift)){

            $output .= '<li><strong>'.__('Gift Title').': </strong> '.purify_html(optional($row->gift)->title).'</li>';
            $output .= '<li><strong>'.__('Phone').': </strong> '.purify_html($row->phone).'</li>';
            $output .= '<li><strong>'.__('Address').': </strong> '.purify_html($row->address).'</li>';
            $output .= '<li><strong>'.__('Delivery Date').': </strong> '.purify_html(optional($row->gift)->delivery_date).'</li>';

            $gifts = optional($row->gift)->gifts;
            $colors = ['warning','info','primary','success'];

            $output.= '<li>';
            $output.= '<strong>'.__('Gifts :').'</strong>';
                foreach (json_decode($gifts) ?? [] as $key => $item){
                    $output.= '<span class=" mx-1 badge badge-'.$colors[$key % count($colors)].'">' .$item.'</span>' ;
                }
             $output.= '</li>';

          }

        $output .= '<li><strong>'.__('Admin Charge').': </strong> '.amount_with_currency_symbol($row->admin_charge).'</li>';
        $output .= '<li><strong>'.__('Payment Gateway').': </strong> '.ucwords(str_replace('_',' ',$row->payment_gateway)).'</li>';
        if ($row->status === 'complete' && $row->payment_gateway != 'manual_payment_'){
            $output .= '<li><strong>'.__('Transaction ID').': </strong> '.$row->transaction_id.'</li>';
        }
        $output .= '<li><strong>'.__('Date').': </strong> '.date_format($row->created_at,'d M Y').'</li>';
        $output .= '</ul>';

        return $output;
    }


    public static function withdrawInfoColumn($row){

        $output = '<ul>';
        $output .= '<li><strong>'.__('Cause').': </strong> '.purify_html(optional($row->cause)->title).'</li>';
        $output .= '<li><strong>'.__('Requested By').': </strong> '.purify_html(optional($row->user)->name ?? __('untitled')).' ('.optional($row->user)->username.')'.'</li>';
        if(!empty($row->cause)){
            $withdraw_able_amount_without_admin_charge = optional($row->cause)->raised - optional($row->cause)->withdraws->where('payment_status' ,'!=', 'reject')->pluck('withdraw_request_amount')->sum();
            $charge_text = '';
            $donation_charge_form = get_static_option('donation_charge_form');
            if ($donation_charge_form === 'campaign_owner'){
                $charge_text = __('after admin charge applied');
                $output .= '<li><strong>'.__('Admin Charged From This Campaign').': </strong> '.amount_with_currency_symbol( DonationHelpers::get_donation_charge_for_campaign_owner($withdraw_able_amount_without_admin_charge)).'</li>';
                $withdraw_able_amount_without_admin_charge -= DonationHelpers::get_donation_charge_for_campaign_owner($withdraw_able_amount_without_admin_charge);
            }
            $output .= '<li><strong>'.__('Available For Withdraw Amount').' '.$charge_text.': </strong> '.amount_with_currency_symbol($withdraw_able_amount_without_admin_charge).'</li>';
        }

        $output .= '<li><strong>'.__('Requested Withdraw Amount').': </strong> '.amount_with_currency_symbol($row->withdraw_request_amount).'</li>';
        $output .= '<li><strong>'.__('Payment Gateway').': </strong> '.ucwords(str_replace('_',' ',$row->payment_gateway)).'</li>';
        $output .= '<li><strong>'.__('Payment Status').': </strong> '.$row->payment_status.'</li>';
        $output .= '<li><strong>'.__('Date').': </strong> '.date_format($row->created_at,'d M Y').'</li>';
        if($row->payment_status === 'approved'){
            $output .= '<li><strong>'.__('Approved Date').': </strong> '.date_format($row->updated_at,'d M Y').'</li>';
        }
        $output .= '</ul>';
        return $output;
    }

}
