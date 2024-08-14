<section class="photography_service_area padding-top-100 padding-bottom-100" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="photography_service__shape">
        {!! render_image_markup_by_attachment_id($data['right_image']) !!}
    </div>
    <div class="container">
        <div class="photography_sectionTitle">
            <h2 class="title">{{$data['title']}}</h2>
        </div>
        <div class="row g-4 mt-4">
            @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key => $title)
                @php
                    $image = $data['repeater_data']['repeater_image_'.get_user_lang()][$key] ?? '';
                    $title_url = $data['repeater_data']['repeater_title_url_'.get_user_lang()][$key] ?? '';
                    $description = $data['repeater_data']['repeater_description_'.get_user_lang()][$key] ?? '';
                @endphp
                <div class="col-lg-4 col-md-6">
                    <div class="photography_service__single center-text radius-10">
                        <div class="photography_service__single__icon">
                            {!! render_image_markup_by_attachment_id($image) !!}
                        </div>
                        <div class="photography_service__single__contents mt-4">
                            <h4 class="photography_service__single__title"><a href="{{$title_url}}">{{$title ?? ''}}</a></h4>
                            <p class="photography_service__single__para mt-3">{{$description}}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
