
<div class="categoriesArea top-padding">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{ $data['padding_bottom'] }}">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-7 col-md-10 col-sm-10">
                <div class="section-tittle text-center mb-50">
                    <h2 class="tittle">{{$data['title']}}</h2>
                </div>
            </div>
        </div>
        <div class="row">
            @php
                $cat_colors = [1,2,3,4,5,6,7];
            @endphp
            @foreach($data['job_categories'] as $data)
                @php
                    $jobs_count = \Modules\Job\Entities\Job::where('category_id',$data->id)->count();
                @endphp
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="singleCategories  mb-24">
                        <div class="icon-wrap">
                            <div class="icon catBg{{$cat_colors[$loop->index % count($cat_colors)]}}">
                                {!! render_image_markup_by_attachment_id($data->image) !!}
                            </div>
                        </div>
            
                        <div class="cap">
                            <h4><a href="{{ route('tenant.frontend.job.category', ['id'=> $data->id, 'any'=> Str::slug($data->title)]) }}" class="title">{!! purify_html($data->getTranslation('title',get_user_lang()))  !!}</a></h4>
                            <p class="pera">({{$jobs_count}} {{$jobs_count > 0 ? '+' : ''}}) {!! purify_html($data->getTranslation('subtitle',get_user_lang())) !!}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
