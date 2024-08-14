<section class="softwareFirm_business_area softwareFirm-bg-secondary padding-top-100 padding-bottom-100">
    <div class="softwareFirm_business__shapes">
        <span></span>
        <span></span>
        <span></span>
    </div>
    <div class="container">
        <div class="row gy-5 mt-1 justify-content-between align-items-center">
            <div class="col-lg-6 col-md-9">
                <div class="softwareFirm_business">
                    <div class="softwareFirm_business__thumb">
                        {!! render_image_markup_by_attachment_id($data['left_image']) !!}
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="softwareFirm_business softwareFirm_business__wrapper white">
                    <div class="softwareFirm_sectionTitle text-left">
                        <h2 class="title">{{$data['title']}}</h2>
                        <p class="section_para mt-3">{{$data['description']}}</p>
                    </div>
                    <ul class="softwareFirm_business__list list_none mt-4 mt-lg-5">
                       @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key => $ti)
                           @php
                               $title = $ti ?? '';
                               $title_url = $data['repeater_data']['repeater_title_url_'.get_user_lang()][$key]  ?? '';
                           @endphp
                            <li><a href="{{$title_url}}"> {{$title}}</a></li>
                       @endforeach
                    </ul>
                    <div class="btn-wrapper mt-4 mt-lg-5">
                        <a href="{{$data['button_url']}}" class="softwareFirm_cmn_btn btn_bg_1 radius-10">{{$data['button_text']}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
