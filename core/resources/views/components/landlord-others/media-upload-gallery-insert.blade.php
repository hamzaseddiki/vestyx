<div class="form-group ">
    <label for="image">{{$title ?? 'Gallery Image'}}</label>
    <div class="media-upload-btn-wrapper">
        <div class="img-wrap"></div>
        <input type="hidden" name="{{$name ?? 'gallery_image'}}">
        <button type="button" class="btn btn-info media_upload_form_btn"
                data-btntitle="{{__('Select Image')}}"
                data-modaltitle="{{__('Upload Image')}}"
                data-toggle="modal"
                data-mulitple="true"
                data-target="#media_upload_modal">
            {{__('Upload Gallery Image')}}
        </button>
    </div>
</div>
