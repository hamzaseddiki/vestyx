<section class="articlesArea section-padding2" id="Down">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_top']}}">
        @if(!empty($data['title']))
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-7 col-md-10 col-sm-10">
                    <div class="section-tittle text-center mb-50">
                        {!! get_modified_title_knowledgebase($data['title']) !!}
                    </div>
                </div>
            </div>
        @endif
        <div class="row">

            @foreach($data['all_knowledgebase'] as $category)
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="singleArticles mb-24 wow fadeInLeft" data-wow-delay="0.0s">
                        <div class="icon-wrap">
                            <div class="icon">
                                {!! render_image_markup_by_attachment_id($category->image) !!}
                            </div>
                        </div>
                        <a href="{{ route('tenant.frontend.knowledgebase.category',['id' => $category->id, 'any' => \Illuminate\Support\Str::slug($category->title)]) }}">
                            <h4 class="articlesTittle">{{ $category->getTranslation('title',get_user_lang()) }}</h4>
                        </a>
                        <ul class="listing">
                            @foreach($category->knowledgebase as $art)
                                <li class="listItem">
                                    {!! render_image_markup_by_attachment_id($art->image,'icon') !!}
                                    <a href="{{ route('tenant.frontend.knowledgebase.single',$art->slug) }}">
                                         <blockquote class="articlesTag">{{$art->getTranslation('title',get_user_lang())}}</blockquote>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="btn-wrapper">
                            <a href="{{$data['explore_url']}}" class="more-btn">{{$data['explore_text']}}</a>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>

        <div class="row">
            <div class="col-log-12">
                <div class="btn-wrapper text-center mt-40">
                    <a href="{{$data['button_url']}}" class="cmn-btn4 hero-btn">{{$data['button_text']}}</a>
                </div>
            </div>
        </div>
    </div>
</section>
