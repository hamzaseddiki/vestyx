@php
    if(!isset($metaData)){
        $metaData = null;
    }
@endphp

<div class="meta-body-wrapper mt-3">
    <h4 class="dashboard-common-title-two mb-4"> {{ __("Meta SEO") }} </h4>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link general-meta active" id="general-meta-info-tab" data-bs-toggle="tab" data-bs-target="#general-meta-info" type="button" role="tab" aria-controls="home" aria-selected="true">
                {{__("General Meta Info")}}</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="facebook-meta-tab" data-bs-toggle="tab" data-bs-target="#facebook-meta" type="button" role="tab" aria-controls="facebook-meta" aria-selected="false">
                {{__("Facebook meta")}}</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="twitter-meta-tab" data-bs-toggle="tab" data-bs-target="#twitter-meta" type="button" role="tab" aria-controls="twitter-meta" aria-selected="false">
                {{__("Twitter meta")}}</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane py-4 fade show active general-meta-pane" id="general-meta-info" role="tabpanel" aria-labelledby="general-meta-info-tab">
            <h4>General Info</h4>
            <div class="form-group dashboard-input">
                <label for="general-title">{{__("Title")}}</label>
                <input type="text" id="general-title" value="{{ $metaData?->title }}" data-role="tagsinput" class="form--control radius-10 tags_input" name="general_title" placeholder="{{__("General info title")}}">
            </div>
            <div class="form-group">
                <label for="general-description">{{__("Description")}}</label>
                <textarea type="text" id="general-description" name="general_description" class="form--control radius-10 py-2" rows="6" placeholder="{{__("General info description")}}">{{ $metaData?->description }}</textarea>
            </div>
        </div>
        <div class="tab-pane py-4 fade" id="facebook-meta" role="tabpanel" aria-labelledby="facebook-meta-tab">
            <h4>{{__("Facebook Info")}}</h4>
            <div class="form-group dashboard-input">
                <label for="facebook-title">{{__("Title")}}</label>
                <input type="text" id="facebook-title" name="facebook_title" value="{{ $metaData?->fb_title }}" data-role="tagsinput" class="form--control radius-10 tags_input" placeholder="{{__("General info title")}}">
            </div>
            <div class="form-group">
                <label for="facebook-description">{{__("Description")}}</label>
                <textarea type="text" id="facebook-description" name="facebook_description" class="form--control radius-10 py-2" rows="6" placeholder="{{__("General info description")}}">{{ $metaData?->fb_description }}</textarea>
            </div>

            <x-fields.media-upload :id="$metaData?->fb_image" :title="__('General Info Meta Image')" :name="'facebook_image'" :dimentions="'200x200'"/>
        </div>
        <div class="tab-pane py-4 fade" id="twitter-meta" role="tabpanel" aria-labelledby="twitter-meta-tab">
            <h4>{{__("Twitter Info")}}</h4>
            <div class="form-group dashboard-input">
                <label for="general-title">{{__("Title")}}</label>
                <input type="text" id="twitter-title" value="{{ $metaData?->tw_title }}" name="twitter_title" data-role="tagsinput" class="form--control radius-10 tags_input" placeholder="{{__("General info title")}}">
            </div>
            <div class="form-group">
                <label for="general-description">{{__("Description")}}</label>
                <textarea type="text" id="twitter-description" name="twitter_description" class="form--control radius-10 py-2" rows="6" placeholder="{{__("General info description")}}">{{ $metaData?->tw_description }}</textarea>
            </div>

            <x-fields.media-upload :id="$metaData?->tw_image" :title="__('General Info Meta Image')" :name="'twitter_image'" :dimentions="'200x200'"/>
        </div>
    </div>
</div>
