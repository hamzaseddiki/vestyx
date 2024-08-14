@php
    $user_lang = get_user_lang();
@endphp

@forelse($allKnowledgebaseCategory as $item)
        <div class="col-xl-6 col-lg-6 col-md-6">
            <div class="singleArticles mb-24 wow fadeInLeft" data-wow-delay="0.0s">
                <div class="icon-wrap mb-4">
                        {!! render_image_markup_by_attachment_id($item->image) !!}
                </div>
                <a href="{{route('tenant.frontend.knowledgebase.single',$item->slug)}}">
                    <h4 class="articlesTittle">{{ $item->getTranslation('title',get_user_lang()) }}</h4>
                </a>
            </div>
        </div>
    @empty
        <div class="col-lg-12">
            <div class="alert alert-warning event_filter_top_message">
                <h4 class="text-center">{!! __('No Article Available In ') .' : ' . purify_html($searchTerm) ?? '' !!}</h4>
            </div>
        </div>
    @endforelse
