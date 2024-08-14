<?php



namespace Modules\Portfolio\Helpers\DataTableHelpers;

use http\Env\Request;
use PDF;

class PortfolioGeneral
{
    public static function bulkCheckbox($id){
        return <<<HTML
<div class="bulk-checkbox-wrapper">
    <input type="checkbox" class="bulk-checkbox" name="bulk_delete[]" value="{$id}">
</div>
HTML;

    }

    public static function image($image_id){
        return render_attachment_preview_for_admin($image_id);
    }

    public static function deletePopover($url){
            $token = csrf_token();
            return <<<HTML
<a tabindex="0" class="btn btn-danger btn-xs mb-3 mx-1 swal_delete_button">
    <i class="las la-trash"></i>
</a>
<form method='post' action='{$url}' class="d-none">
<input type='hidden' name='_token' value='{$token}'>
<br>
<button type="submit" class="swal_form_submit_btn d-none"></button>
 </form>
HTML;

    }

    public static function editIcon($url){
        return <<<HTML
<a class="btn btn-primary btn-xs mb-3 mx-1" href="{$url}">
    <i class="las la-edit"></i>
</a>
HTML;

    }

    public static function viewIcon($url){
        return <<<HTML
<a class="btn btn-info btn-xs mb-3 mr-1" target="_blank" href="{$url}">
    <i class="las la-eye"></i>
</a>
HTML;

    }

    public static function cloneIcon($action,$id){
        $csrf = csrf_field();
        return <<<HTML
<form action="{$action}" method="post" class="d-inline">
{$csrf}
    <input type="hidden" name="item_id" value="{$id}">
    <button type="submit" title="clone this to new draft" class="btn btn-xs btn-secondary btn-sm mb-3 mr-1"><i class="las la-copy"></i></button>
</form>
HTML;

    }

    public static function statusSpan($status){
        $output = '';

        if($status === 'draft'){
            $output .= '<span class="alert alert-primary" >'.__('Draft').'</span>';
        }elseif($status === 'archive'){
            $output .= '<span class="alert alert-warning" >'.__('Archive').'</span>';
        }elseif($status === 'pending'){
            $output .= '<span class="alert alert-warning" >'.__('Pending').'</span>';
        }elseif($status === 'complete'){
            $output .= '<span class="alert alert-success" >'.__('Complete').'</span>';
        }elseif($status === 'close'){
            $output .= '<span class="alert alert-danger" >'.__('Close').'</span>';
        }elseif($status === 'in_progress'){
            $output .= '<span class="alert alert-info" >'.__('In Progress').'</span>';
        }elseif($status === 'publish'){
            $output .= '<span class="alert alert-success" >'.__('Publish').'</span>';
        }elseif($status === 'approved'){
            $output .= '<span class="alert alert-success" >'.__('Approved').'</span>';
        }elseif($status === 'confirm'){
            $output .= '<span class="alert alert-success" >'.__('Confirm').'</span>';
        }elseif($status === 'yes'){
            $output .= '<span class="alert alert-success" >'.__('Yes').'</span>';
        }elseif($status === 'no'){
            $output .= '<span class="alert alert-danger" >'.__('No').'</span>';
        }elseif($status === 'cancel'){
            $output .= '<span class="alert alert-danger" >'.__('Cancel').'</span>';
        }elseif($status === 'reject'){
            $output .= '<span class="alert alert-danger" >'.__('Reject').'</span>';
        }elseif($status == '0'){
            $output .= '<span class="alert alert-warning p-2" >'.__('Draft').'</span>';
        }elseif($status == '1'){
            $output .= '<span class="alert alert-success p-2" >'.__('Complete').'</span>';
        }elseif($status == '2'){
            $output .= '<span class="alert alert-warning" >'.__('Pending').'</span>';
        }

        return $output;
    }

    public static function paymentAccept($url){
        $token = csrf_token();
        return <<<HTML
<a tabindex="0" class="btn btn-success btn-xs mb-3 mr-1 swal_change_approve_payment_button">
    <i class="ti-check"></i>
</a>
<form method='post' action='{$url}' class="d-none">
    <input type='hidden' name='_token' value='{$token}'>
    <br>
    <button type="submit" class="swal_form_submit_btn d-none"></button>
</form>

HTML;

}

    public static function viewAttachment($data){

        $image_url = url('assets/uploads/attachment/'.$data->manual_payment_attachment);
        return <<<HTML
    <a href="{$image_url}" class="btn btn-primary btn-xs mb-3 mr-1 " target="_blank">
      View Attachment
    </a>

HTML;

    }

    public static function invoiceBtn($url,$id){
        $csrf = csrf_field();
        $title = __('Invoice');
        return <<<HTML
 <form action="{$url}"  method="post">{$csrf}
    <input type="hidden" name="id" id="invoice_generate_order_field" value="{$id}">
    <button class="btn btn-success mb-2" type="submit">{$title}</button>
</form>
HTML;

    }

    public static function reminderMail($url,$id){
        $csrf = csrf_field();
        return <<<HTML
<form action="{$url}"  method="post">
{$csrf}
    <input type="hidden" name="id" value="{$id}">
    <button class="btn btn-secondary mb-2" type="submit"><i class="fas fa-bell"></i></button>
</form>
HTML;

}

    public static function change_status($url,$id,$email){

        $csrf = csrf_field();
        $button_text = __('Change Status');
        $form_title = __('Donation Status Change');
        $select_text = __('Select Status');
        $email_text = __('with email notification');
        $message_text = __('Message');

        $cusotm_text = __('Enable/Disable Custom Message or Notice');

        $modal_close_button_text = __('Close');
        $modal_submit_button_text = __('Submit');

        $pending_text = __('Pending');
        $complete_text = __('Complete');
        $cancel_text = __('Cancel');



return <<<HTML

    <a class="btn btn-info text-white mb-2 donation_status_change_btn"
      data-toggle="modal"
      data-id="$id"
      data-target="#send_status_change_mail_to_user_modal"
    >{$button_text}</a>

    <div class="modal fade" id="send_status_change_mail_to_user_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{$form_title}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                </div>
                <form action="{$url}" id="send_mail_to_subscriber_edit_modal_form"  method="post">
                {$csrf}
                    <div class="modal-body">

                        <input type="hidden" name="id" class="donation_status_change_id">
                        <input type="hidden" name="email" value="{$email}">

                         <div class="form-group">
                            <label for="email">{$email_text}</label>
                            <input type="text" readonly class="form-control" class="email"" value="{$email}">
                        </div>

                         <div class="form-group">
                                <label for="site_maintenance_mode"><strong>{$cusotm_text}</strong></label>
                                <label class="switch yes">
                                    <input type="checkbox" name="custom_message_enable_disable" class="custom_message_enable_disable">
                                    <span class="slider onff"></span>
                                </label>
                            </div>

                          <div class="form-group custom_message d-none">
                            <label for="email">{$message_text}</label>
                            <textarea  class="form-control" name="message"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="email">$select_text</label>
                            <select name="status" class="form-control">
                                <option value="pending">{$pending_text}</option>
                                <option value="complete">{$complete_text}</option>
                                <option value="cancel">{$cancel_text}</option>
                            </select>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{$modal_close_button_text}</button>
                        <button id="submit" type="submit" class="btn btn-primary">{$modal_submit_button_text}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


HTML;

    }

    public static function anchor($url,$text,$class='primary'){
        return <<<HTML
<a class="btn btn-xs mb-3 mr-1 btn-{$class}" href="{$url}">{$text}</a>
HTML;
    }



}//end class
