
<div class="form-group permalink">
    <div class="permalink_top_part d-none">
        <label class="text-dark">{{__('Permalink * :')}} <span class="{{ @$value === 'blog' ? 'permalink_top_url_class' : 'permalink_top_url_class_for_pages' }}"></span></label>
        <span class="display-inline permalink_top_slug_show"></span>
        <button class="btn btn-primary btn-sm slug_edit_button"> <i class="las la-edit"></i> </button>
    </div>

    <div class="permalink_bottom_part d-none">
        <input type="text" name="slug" class="form-control permalink_bottom_blog_slug_input_field mt-2" >
        <button class="btn btn-info btn-sm slug_update_button mt-2" >{{__('Update')}}</button>
    </div>
</div>

