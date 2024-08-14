
<section class="teamArea section-padding">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row">
            @foreach($data['repeater_data']['repeater_name_'.get_user_lang()] ?? [] as $key => $name)
            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                <div class="singleTeam-global tilt-effect mb-24">
                    <div class="team-img">
                        <a href="#">
                            {!! render_image_markup_by_attachment_id($data['repeater_data']['repeater_image_'.get_user_lang()][$key] ?? '' ) !!}
                        </a>
                        <!-- Blog Social -->
                        <ul class="teamSocial">
                            <li class="single"><a href="{{$data['repeater_data']['repeater_facebook_url_'.get_user_lang()][$key] ?? '' }}" class="social"><i class="fab fa-facebook-f"></i></a></li>
                            <li class="single"><a href="{{$data['repeater_data']['repeater_twitter_url_'.get_user_lang()][$key] ?? '' }}" class="social"><i class="fab fa-twitter"></i></a></li>
                            <li class="single"><a href="{{$data['repeater_data']['repeater_instagram_url_'.get_user_lang()][$key] ?? '' }}" class="social"><i class="fa-brands fa-instagram"></i></a></li>
                        </ul>
                    </div>
                    <div class="teamCaption">
                        <h3><a href="#" class="title">{{$name ?? ''}}</a></h3>
                        <p class="pera">{{$data['repeater_data']['repeater_designation_'.get_user_lang()][$key] ?? '' }}</p>
                    </div>
                </div>
            </div>

            @endforeach
        </div>
    </div>
</section>
