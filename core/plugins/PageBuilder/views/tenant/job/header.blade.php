
<div class="sliderArea sectionImg-bg2 job_filter" {!! render_background_image_markup_by_attachment_id($data['bg_image']) !!}>
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row justify-content-between align-items-center">
            <div class="col-xxl-7 col-xl-7 col-lg-7 ">
                <div class="heroCaption heroPadding">
                    @php
                        $original_title = $data['title'];
                        $explode = explode(' ',$original_title);
                        $after_explode = $explode;

                        $first_words = array_slice($explode,0,3) ?? [];
                        $middle_words = array_slice($explode,3,2) ?? [];

                        $first_middle_merge = array_merge($first_words,$middle_words);
                        $last_words = array_diff($after_explode,$first_middle_merge);
                    @endphp


                    <h1 class="tittle wow fadeInUp" data-wow-delay="0.0s">
                        {{ implode(' ', $first_words) }}
                        <span class="line">{{ implode(' ',$middle_words) }}</span>
                        <span class="icon"></span>
                        {{ implode(' ',$last_words) }}
                    </h1>
                    <p class="pera wow fadeInUp" data-wow-delay="0.1s">{{$data['description']}}</p>
                    <div class="searchBox-wrapper">

                        <!-- form -->
                        <div class="alert alert-danger search_bottom_message d-none mt-3">{{__('Enter title to search')}}</div>
                        <form action="" class="searchBox wow fadeInUp" data-wow-delay="0.3s">
                            <div class="input-form">
                                <input type="text" name="filter_input_search_title" class=" keyup-input filter_input_search_title" placeholder="Job Title or Keyword">
                                <i class="las la-search icon"></i>

                            </div>
                            <div class="input-form location">
                                <input type="text" name="filter_input_search_location" placeholder="Location" class="filter_input_search_location">
                                <i class="las la-map-marker-alt icon"></i>
                            </div>

                            <div class="search-form">
                                <button type="submit" class="search filter_search_button">{{$data['button_text']}}</button>
                            </div>
                        </form>
                    </div>

                    <div class="donateMemberList mb-10">
                        <ul class="listing mb-20">
                            <li class="listItem">
                                <a href="#"><img src="{{global_asset('assets/tenant/frontend/themes/img/gallery/job-find-jobHolder1.jpg')}}" alt="images"></a>
                            </li>
                            <li class="listItem">
                                <a href="#"> <img src="{{global_asset('assets/tenant/frontend/themes/img/gallery/job-find-jobHolder2.jpg')}}" alt="images"></a>
                            </li>
                            <li class="listItem">
                                <a href="#"> <img src="{{global_asset('assets/tenant/frontend/themes/img/gallery/job-find-jobHolder3.jpg')}}" alt="images"></a>
                            </li>
                            <li class="listItem">
                                <a href="#"> <img src="{{global_asset('assets/tenant/frontend/themes/img/gallery/job-find-jobHolder4.jpg')}}" alt="images"></a>
                            </li>
                            <li class="listItem">
                                <a href="#"> <img src="{{global_asset('assets/tenant/frontend/themes/img/gallery/job-find-jobHolder5.jpg')}}" alt="images"></a>
                            </li>
                            <li class="listItem">
                                <a href="#" class="memberCounter" >{{$data['bottom_left_job_qty']}}</a>
                            </li>
                        </ul>

                        @php
                            $left_bottom_text = $data['bottom_left_text'];
                            $explode_bottom = explode(' ',$left_bottom_text);
                            $after_bot_ex = $explode_bottom;
                            $first_words = array_slice($explode_bottom,0,4) ?? [];
                            $last_words = array_diff($after_bot_ex,$first_words);
                        @endphp

                        <p class="pera"> {{ implode(' ',$first_words) }} <span class="lineBreak"></span>{{ implode(' ',$last_words) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-xxl-5 col-xl-5 col-lg-5">
                <div class="hero-man d-none d-lg-block f-right" >
                    {!! render_image_markup_by_attachment_id($data['right_image'],'wow fadeInUp', '0.0s') !!}
                    <div class="notice1 wow fadeInRight" data-wow-delay="0.4s">
                        <div class="icon-wrap"><div class="icon">
                                <i class="lar la-envelope"></i>
                            </div></div>
                        <div class="cap">
                            <p class="pera">{{$data['bottom_right_text']}} </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@php
    $route = route('tenant.dynamic.page', get_page_slug(get_static_option('job_page')))
@endphp

<form action="{{$route}}" method="get" class="all_filter_form">
    <input type="hidden" class="filter_receieved_search_title" name="filter_search_title">
    <input type="hidden" class="filter_receieved_search_location" name="filter_search_location">
</form>


@section('scripts')
    <script>
        $(document).ready(function(){
            let body_main = $('.job_filter');

            setTimeout(function (){
                $('.event_filter_top_message').hide();
            },3000)

            function trigger_form() {
                return $('.all_filter_form').trigger('submit');
            }

            $(document).on('click','.filter_search_button',function(e){
                e.preventDefault();
                let el = $('.filter_input_search_title').val();

                if(el == ''){
                    $('.search_bottom_message').removeClass('d-none');
                    setTimeout(function (){
                        $('.search_bottom_message').addClass('d-none');
                    },2000)
                }else{
                    $('.filter_receieved_search_title').val(body_main.find('.filter_input_search_title').val());
                    $('.filter_receieved_search_location').val(body_main.find('.filter_input_search_location').val());
                    trigger_form();
                }
            })

        });
    </script>
@endsection

