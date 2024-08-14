<div class="meta-information-wrapper">
    <h4 class="mb-4">{{__('Meta Information For SEO')}}</h4>
    <div class="d-flex align-items-start mb-8 metainfo-inner-wrap">
        <div class="nav flex-column nav-pills me-3" role="tablist" aria-orientation="vertical">
            <button class="nav-link active"  data-bs-toggle="pill" data-bs-target="#v-general-meta-info" type="button" role="tab"  aria-selected="true">
                {{__('General Meta Info')}}</button>
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#v-facebook-meta-info" type="button" role="tab"  aria-selected="false">
                {{__('Facebook Meta Info')}}</button>
            <button class="nav-link"  data-bs-toggle="pill" data-bs-target="#v-twitter-meta-info" type="button" role="tab"  aria-selected="false">
                {{__('Twitter Meta Info')}}
            </button>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="v-general-meta-info" role="tabpanel" >
                <x-fields.input name="meta_title" label="{{__('Meta Title')}}"  value="{{ old('meta_title') }}"/>
                <x-fields.textarea name="meta_description" label="{{__('Meta Description')}}"  value="{{ old('meta_description') }}"/>
                <x-fields.media-upload name="meta_image" title="{{__('Meta Image')}}" dimentions="1200x1200"/>
            </div>
            <div class="tab-pane fade" id="v-facebook-meta-info" role="tabpanel" >
                <x-fields.input name="meta_fb_title" label="{{__('Meta Title')}}" value="{{ old('meta_fb_title') }}"/>
                <x-fields.textarea name="meta_fb_description" label="{{__('Meta Description')}}" value="{{ old('meta_fb_description') }}"/>
                <x-fields.media-upload name="meta_fb_image" title="{{__('Meta Image')}}" dimentions="1200x1200"/>
            </div>
            <div class="tab-pane fade" id="v-twitter-meta-info" role="tabpanel" >
                <x-fields.input name="meta_tw_title" label="{{__('Meta Title')}}" value="{{ old('meta_tw_title') }}"/>
                <x-fields.textarea name="meta_tw_description" label="{{__('Meta Description')}}" value="{{ old('meta_tw_description') }}"/>
                <x-fields.media-upload name="meta_tw_image" title="{{__('Meta Image')}}" dimentions="1200x1200"/>
            </div>
        </div>
    </div>
</div>
