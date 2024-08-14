<section class="photography_story_area photography-main-gradient padding-top-100 padding-bottom-100"
data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row g-5 justify-content-between align-items-center">
            <div class="col-lg-6">
                <div class="photography_story__thumb">
                    <div class="photography_story__thumb__main">
                        {!! render_image_markup_by_attachment_id($data['left_image']) !!}
{{--                        <img src="assets/img/story/photography_story_img.jpg" alt="aboutImg">--}}
                    </div>
                    <div class="photography_story__thumb__sign">
                        <img src="{{global_asset('assets/tenant/frontend/themes/img/photography/story/story_sign.svg')}}" alt="signImg">
                    </div>
                    <div class="photography_story__thumb__shapes">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M28.1666 0.364822C28.1666 0.364822 24.3875 14.0943 28.914 20.5587C33.4404 27.0232 47.6345 28.1678 47.6345 28.1678C47.6345 28.1678 33.905 24.3887 27.4405 28.9151C20.9761 33.4416 19.8315 47.6356 19.8315 47.6356C19.8315 47.6356 23.6106 33.9061 19.0842 27.4417C14.5577 20.9772 0.363694 19.8326 0.363694 19.8326C0.363694 19.8326 14.0932 23.6117 20.5576 19.0853C27.0221 14.5588 28.1666 0.364822 28.1666 0.364822Z" fill="white"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="photography_story">
                    <div class="photography_sectionTitle text-left">
                        <h2 class="title">{{$data['title']}}</h2>
                    </div>
                    <div class="photography_story__contents mt-4 mt-lg-5">
                        <p class="photography_story__para">{{$data['description']}}</p>
                        <div class="photography_story__quality mt-4 mt-lg-5">
                            @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key => $ti)
                                @php
                                    $title = $title ?? '';
                                    $title_url = $data['repeater_data']['repeater_title_url_'.get_user_lang()][$key] ?? '';
                                    $description = $data['repeater_data']['repeater_description_'.get_user_lang()][$key] ?? '';
                                    $image = $data['repeater_data']['repeater_image_'.get_user_lang()][$key] ?? null;
                                @endphp
                                <div class="photography_story__quality__item">
                                    <div class="photography_story__quality__flex">
                                        <div class="photography_story__quality__icon">
                                            {!! render_image_markup_by_attachment_id($image) !!}
                                        </div>
                                        <div class="photography_story__quality__content">
                                            <h4 class="photography_story__quality__title"><a href="{{$title_url}}">{{$title}}</a></h4>
                                            <p class="photography_story__quality__para mt-2">{{$description}}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="btn-wrapper mt-4 mt-lg-5">
                            <a href="{{$data['button_url']}}" class="photography_cmn_btn btn_bg_1 radius-30">{{$data['button_text']}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
