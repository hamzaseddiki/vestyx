@php

    if(!isset($tag)){

        $tag = null;
        $tag_name = "";
    }else{
        $tag_name_arr = $tag->pluck("tag_name")?->toArray();
        $tag_name = implode(",",$tag_name_arr ?? []);
    }

    if(!isset($singlebadge)){
        $singlebadge = null;
    }

@endphp

<div class="general-info-wrapper px-3">
    <h5 class="dashboard-common-title-two">{{ __("Product Tags and Badge") }}</h5>
    <div class="dashboard-input mt-4">
        <label class="dashboard-label color-light mb-2"> {{ __("Tags") }} </label>
        <input type="text" name="tags" class="form--control tags_input radius-10" data-role="tagsinput" value="{{ $tag_name }}">
    </div>

    <div class="general-info-form mt-0 mt-lg-4">
        <label class="dashboard-label color-light mb-2"> {{ __("Labels") }} </label>
        <div class="d-flex gap-2">
            <input type="hidden" name="badge_id" value="{{ $singlebadge }}" id="badge_id_input"/>
            @foreach($badges as $badge)
                <div class="badge-item d-flex {{ $badge->id === $singlebadge ? "active" : "" }}" data-badge-id="{{ $badge->id }}">
                    <div class="icon">
                        {{render_image_markup_by_attachment_id($badge->badgeImage, null,'thumb')}}
                    </div>
                    <div class="content">
                        <h6 class="title">{{ $badge->getTranslation('name',$defaultLang) }}</h6>
                        <span class="badge badge-{{ $badge->type ? 'success bg-success' : 'warning bg-warning' }}">{{ $badge->type ? __("Permanent") : __("Temporary") }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
