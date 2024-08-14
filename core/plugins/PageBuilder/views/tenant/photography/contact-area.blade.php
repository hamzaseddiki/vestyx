
<section class="photography_contact_area photography-main-gradient padding-bottom-100 padding-top-100"
        data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="photography_contact__shapes">
        <img src="{{global_asset('assets/tenant/frontend/themes/img/photography/contact/photography_contactStar.svg')}}" alt="">
        <img src="{{global_asset('assets/tenant/frontend/themes/img/photography/contact/photography_contactCamera.svg')}}" alt="">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="photography_contact__wrapper">
                    <div class="photography_contact center-text">
                        <h2 class="photography_contact__title">{{$data['title']}} </h2>
                        <p class="photography_contact__para mt-4">{{$data['description']}}</p>
                        <div class="btn-wrapper mt-lg-5 mt-4">
                            <a href="{{$data['button_url']}}" class="photography_cmn_btn btn_bg_1 radius-30">{{$data['button_text']}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
