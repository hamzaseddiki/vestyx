<?php


namespace Modules\Job\Helpers\DataTableHelpers;


use App\Enums\DonationPaymentStatusEnum;
use Modules\Job\Enums\EmployeeTypeEnum;
use Modules\Job\Enums\WorkingTypeEnum;

class JobDatatable
{
    public static function infoColumn($row,$default_lang)
    {
        $output = '<ul class="all_donation_info_column">';
        $output .= '<li><strong>' . __('Title') . ' :</strong> ' . $row->getTranslation('title',$default_lang) . '</li>';
        $output .= '<li><strong>' . __('Job Experience') . ' :</strong> ';
        $output .= $row->experience ?? '';
        $output .= '<li><strong>' . __('Job Designation') . ' :</strong> ';
        $output .= $row->getTranslation('designation',get_user_lang()) ?? '';
        $output .= '<li><strong>' . __('Employee Type') . ' :</strong> ';
        $output .= EmployeeTypeEnum::getText($row->employee_type) ?? 0;
        $output .= '<li><strong>' . __('Working Days') . ' :</strong> ' . $row->working_days . '</li>';
        $output .= '<li><strong>' . __('Working Type') . ':</strong> ';
        $output .= WorkingTypeEnum::getText($row->working_type);
        $output .= '<li><strong>' . __('Job Location') . ' :</strong> ';
        $output .= $row->job_location ;
        $output .= '<li><strong>' . __('Company Name') . ': </strong> ';
        $output .=  ($row->company_name) ;
        $output .= '<li><strong>' . __('Salary Offer') . ': </strong> ';
        $output .=  amount_with_currency_symbol($row->salary_offer);
        $output .= '<li><strong>' . __('Deadline') . ': </strong> ';
        $output .=  date('d-m-Y',strtotime($row->deadline)) ;
        $output .= '<li><strong>' . __('Application Fee') . ': </strong> ';
        $output .=  amount_with_currency_symbol($row->application_fee);
        $output .= '<li><strong>' . __('Created At') . ' :</strong> ' . date("d-M-Y", strtotime($row->created_at)) . '</li>';
        $output .= '</li>';
        $output .= '</ul>';

        return $output;
    }

    public static function paymentInfoColumn($row){

        $output = '<ul>';
        $output .= '<li><strong>'.__('Job Title').': </strong> '.purify_html(optional($row->job)->getTranslation('title',get_user_lang())).'</li>';
        $output .= '<li><strong>'.__('Company Name').': </strong> '.purify_html(optional($row->job)->getTranslation('company_name',get_user_lang())).'</li>';
        $output .= '<li><strong>'.__('Job Designation').': </strong> '.purify_html(optional($row->job)->getTranslation('designation',get_user_lang())).'</li>';
        $output .= '<li><strong>'.__('Apply Date').': </strong> '.$row->created_at?->format('d-m-Y').'</li>';
        $output .= '<li><strong>'.__('Deadline').': </strong> '.$row->job?->deadline.'</li>';
        $output .= '<li><strong>'.__('Name').': </strong> '.purify_html($row->name).'</li>';
        $output .= '<li><strong>'.__('Email').': </strong> '.purify_html($row->email).'</li>';
        $output .= '<li><strong>'.__('Phone').': </strong> '.purify_html($row->phone).'</li>';

        if(!empty($row->payment_gateway)) {
            $output .= '<li><strong>' . __('Application Fee') . ': </strong> ' . amount_with_currency_symbol($row->amount) . '</li>';
            $output .= '<li><strong>' . __('Payment Gateway') . ': </strong> ' . ucwords(str_replace('_', ' ', $row->payment_gateway)) . '</li>';

            if ( $row->payment_gateway == 'manual_payment_') {
                $output .= '<li><strong>' . __('Transaction ID') . ': </strong> ' . $row->transaction_id . '</li>';
            }
        }

        if($row->payment_gateway == 'bank_transfer'){
            $output .= '<li><strong>' . __('View Attachment') . ': </strong> ';
            $output .=  self::get_anchor(url('assets/uploads/job-applications/'.$row->manual_payment_attachment),'Click Here');
        }

        $output .= '</ul>';

        return $output;
    }

    public static function get_job_status_with_markup($row)
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
