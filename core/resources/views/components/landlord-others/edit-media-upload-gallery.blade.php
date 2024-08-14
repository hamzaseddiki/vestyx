@php
    $size = $size ?? '1920x1280';
@endphp

<div class="form-group">
    <label for="og_meta_image">{{ $label ?? __('Image Gallery')}}</label>
    <div class="media-upload-btn-wrapper mb-2">
        <div class="img-wrap">
            {!! render_gallery_image_attachment_preview($value  ?? '') !!}
        </div>
        <input type="hidden" id="{{$id ?? ''}}" name="{{$name ?? ''}}"
               value="{{$value ?? ''}}">
        <button type="button" class="btn btn-info media_upload_form_btn"
                data-btntitle="{{__('Select Image')}}"
                data-modaltitle="{{__('Upload  Image')}}" data-toggle="modal"
                data-mulitple="true"
                data-target="#media_upload_modal">
            {{'Change Image'}}
        </button>
    </div>
    <small class="form-text text-muted mt-2">{{__('allowed image format: jpg,jpeg,png')}}</small><br>
    <small class="form-text text-muted">{{__('allowed image size : ') . $size ?? '1920x1280'  }}</small>
</div>
