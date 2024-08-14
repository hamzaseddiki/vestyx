<?php


namespace App\Helpers\DataTableHelpers;


use App\BlogComment;
use App\Helpers\LanguageHelper;
use http\Env\Request;
use App\Blog;
use App\Helpers\FlashMsg;

class General
{
    public static function bulkCheckbox($id){
        return <<<HTML
<div class="bulk-checkbox-wrapper">
    <input type="checkbox" class="bulk-checkbox" name="bulk_delete[]" value="{$id}">
</div>
HTML;

    }

    public static function viewAttachment($data){

        $image_url = url('assets/uploads/attachment/'.$data->manual_payment_attachment);
        return <<<HTML
    <a href="{$image_url}" class="btn btn-primary btn-xs mb-3 mr-1 " target="_blank" data-bs-toggle="tooltip" title="View Details">
      <i class="las la-eye"></i>
    </a>

HTML;

    }

    public static function image($image_id){
        return render_attachment_preview_for_admin($image_id);
    }

    public static function deletePopover($url){
            $token = csrf_token();
            return <<<HTML
<a tabindex="0" class="btn btn-danger btn-xs mb-3 mr-1 swal_delete_button"
 data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"
>
    <i class="la la-trash"></i>
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
<a class="btn btn-primary btn-xs mb-3 mr-1" href="{$url}"
 data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"
>
    <i class="la la-edit"></i>
</a>
HTML;

    }

    public static function viewIcon($url){
        return <<<HTML
<a class="btn btn-info btn-xs mb-3 mr-1" target="_blank" href="{$url}"
data-bs-toggle="tooltip" data-bs-placement="top" title="View"
>
    <i class="la la-eye"></i>
</a>
HTML;

    }

    public static function checkIcon($url){
        return <<<HTML
<a class="btn btn-success btn-xs mb-3 mr-1" href="{$url}"
data-bs-toggle="tooltip" data-bs-placement="top" title="Accept Payment"
>
    <i class="la la-check"></i>
</a>
HTML;

    }


    public static function viewAnalytics($url){

        $test = __('View Analytics');
        return <<<HTML
<a class="btn btn-warning btn-sm mb-3 mr-1" href="{$url}">
    {$test}
</a>
HTML;

}

    public static function viewComments($route,$id){

        $commentsCOunt = \Modules\Blog\Entities\BlogComment::where('blog_id',$id)->count();

        $test = __('View Comments') . '<strong class="text-white"> ('.$commentsCOunt.') </strong>';
        return <<<HTML
        <a class="btn btn-success btn-sm mb-3 mr-1" href="{$route}">
            {$test}
        </a>
HTML;

}

    public static function blogApprove($id){
        $title = __('Approve This Blog');
        $csrf = csrf_field();
        $action = route('admin.blog.approve');
        return <<<HTML
    <form action="{$action}" method="post"
          enctype="multipart/form-data">
          {$csrf}
        <input type="hidden" name="id" value="{$id}">
        <button class="btn btn-info text-white mb-2"
                type="submit">{$title}
        </button>
    </form>
HTML;

    }

    public static function cloneIcon($action,$id){
        $csrf = csrf_field();
        return <<<HTML
<form action="{$action}" method="post" class="d-inline">
{$csrf}
    <input type="hidden" name="item_id" value="{$id}">
    <button type="submit" data-bs-toggle="tooltip" title="clone this to new draft" class="btn btn-xs btn-secondary btn-sm mb-3 mr-1"><i class="la la-copy"></i></button>
</form>
HTML;

    }

    public static function statusSpan($status){
        $output = '';
        if($status === 0){
            $output .= '<span class="alert alert-warning" >'.__('Draft').'</span>';
        }elseif($status === 1){
            $output .= '<span class="alert alert-success" >'.__('Published').'</span>';
        }
        return $output;
    }

    public static function get_domain_name($data){
        $output = '';

        if(!empty($data->domain?->domain)):
            $output.= '<span class="badge badge-info"> '.$data->domain?->domain.' </span>';
        else:
            $output.= '<span class="badge badge-danger"> '.__('Not Available').'</span>';
        endif;

        return $output;
    }

    public static function browse_tenant_sites($data){
        $output = '';
        if(!empty($data->domain?->domain)):
           $website_link = tenant_url_with_protocol($data->domain?->domain);
           $url = tenant_url_with_protocol($data->domain?->domain);
           $hash_token = hash_hmac('sha512',$data->user?->username.'_'.$data->id,$data->unique_key);
           $finale_url = $url.'/token-wise-login/'.$hash_token;

            $output.= '<div class="browse_td">
              <a style="text-decoration: none" href="'.$website_link.'" class="badge badge-primary" target="_blank">'.__('Visit Website').'</a>
              <a style="text-decoration: none" href="'.$finale_url.'" class="badge badge-success" target="_blank">'.__('Login as super admin').'</a>
           </div>';
        endif;

        return $output;
    }

    public static function paymentAccept($id = null,$url){
        $token = csrf_token();
        return <<<HTML
<a tabindex="0" class="btn btn-success btn-xs mb-3 mr-1 swal_change_approve_payment_button">
    <i class="las la-check"></i>
</a>
<form method='post' action='{$url}' class="d-none">
    <input type='hidden' name='_token' value='{$token}'>
    <input type='hidden' name='id' value='{$id}'>
    <br>
    <button type="submit" class="swal_form_submit_btn d-none"></button>
</form>

HTML;

    }

    public static function invoiceBtn($url,$id){
        $csrf = csrf_field();
        $title = __('Invoice');
        return <<<HTML
 <form action="{$url}"  method="post">{$csrf}
    <input type="hidden" name="id" id="invoice_generate_order_field" value="{$id}">
    <button class="btn btn-success btn-sm mb-2" type="submit">{$title}</button>
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

public static function anchor($url,$text,$class='primary'){
        return <<<HTML
<a class="btn btn-xs mb-3 mr-1 btn-{$class}" href="{$url}">{$text}</a>
HTML;
}

    public static function get_anchor($url, $text){
        return '<a  href="'.$url.'" target="_blank">'.__($text).'</a>';
    }





}//end class
