<div class="socialArea mb-30">
    <div class="small-tittle mb-40">
        <h3 class="tittle lineStyleOne">{{get_static_option('event_social_area_'.get_user_lang().'_title',__('Share'))}}</h3>
    </div>
    @php
        $img = get_attachment_image_by_id($event->image);
        $image_url = $img['img_url'] ?? '';
        $route = route('tenant.frontend.event.single',$event->slug);
        $title = $event->getTranslation('title',get_user_lang());
    @endphp
    <ul class="listing">
        {!! single_post_share($route,$title,$image_url) !!}
    </ul>
</div>
