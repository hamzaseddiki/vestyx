
<div class="socialArea mb-30">
    <h2 class="infoTitle2  mb-30">{{__('Share')}}</h2>
    @php
        $img = get_attachment_image_by_id($donation->image);
        $image_url = $img['img_url'];
        $route = route('tenant.frontend.donation.activities.single',$donation->slug);
        $title = $donation->getTranslation('title',get_user_lang());
    @endphp
    <ul class="listing">
        {!! single_post_share($route,$title,$image_url) !!}
    </ul>
</div>
