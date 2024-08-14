<div class="comment_load_show" data-items="5">
    <div class="itemReview mb-40">

        <div class="small-tittle mb-24">
            <h3>{{__('Comments')}} ({{$commentCount}})</h3>
        </div>

        @foreach($comments as $comment)
            <div class="singleReview blog_comment_container">
                <div class="client1Img">
                    {!! render_image_markup_by_attachment_id($comment->user?->image,'thumb') !!}
                </div>
                <div class="reviewText">
                    <div class="d-flex align-items-center">
                        <h3>{{$comment->user?->name}}</h3>
                        <div class="review_icon">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <span> {{ $comment->created_at?->format('M d, Y') }}</span>
                    <p>
                        {!! purify_html($comment->comment_content ?? '') !!}
                    </p>
                </div>
            </div>
      @endforeach
    </div>
</div>


@if($commentCount)
    @if($commentCount > 5)
        <div class="btn-wrapper load_more_container">
            <a href="javascript:void(0)" class="cmn-btn1 w-100 mb-40 load_more_button">{{ __('Load More') }}</a>
        </div>
    @endif
@endif
