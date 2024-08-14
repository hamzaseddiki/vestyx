<style>
    .comment-container {
        margin-bottom: 20px;
        padding: 10px;
        border: 1px solid #ccc;
        background-color: #f9f9f9;
    }

    .reply-container {
        margin-left: 20px;
        padding: 10px;
        border: 1px solid #ccc;
        background-color: #f1f1f1;
    }
</style>

<div class="comment_load_show " data-items="5">
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
                    </div>
                    <span> {{ $comment->created_at?->format('M d, Y') }}</span>
                    <p>
                        {!! purify_html($comment->comment_content ?? '') !!}
                    </p>

                    @if($comment->comment_replay)
                    <div class="reply-container mt-3">
                        <div class="d-flex align-items-center">
                            <h3>  <b class="badge bg-secondary">Author</b></h3>
                        </div>
                        <span> {{ $comment->comment_replay ? $comment->comment_replay->created_at?->format('M d, Y'): '' }}</span>
                        <p> {{ $comment->comment_replay ? $comment->comment_replay->comment_content : ""}}</p>
                    </div>
                    @endif
                    @if(is_null($comment->comment_replay) && auth()->guard('admin'))
                        <div>
                            <button type="button" id="showInput" data-id="{{$comment->id}}" class="btn btn-outline-secondary float-sm-end mb-3 ShowInput">
                                {{__('Reply')}}</button>
                        </div>
                    @endif
                </div>
            </div>

        <div id="inputContainer_{{$comment->id}}" style="display: none;">
            <form action="" class="contactUs mb-5" id="blog-comment-form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="user_id" value="{{ auth()->guard('web')->id() }}">
                <input type="hidden" name="blog_id" value="{{ $blog->id }}">
                <input type="hidden" id="parent_id_{{$comment->id}}" name="parent_id" value="{{ $comment->id }}">

                <div class="error-message"></div>
                <div class="col-lg-12">
                    <div class="input-form input-form2">
                        <input placeholder="{{__('write anything')}}" type="text" id="comment_content_{{$comment->id}}" name="comment_content" value="">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="btn-wrapper mb-10">
                        <button type="button" onClick="blogReply({{$comment->id}})"  class="cmn-btn1">{{__('Reply')}}</button>
                    </div>
                </div>
            </form>
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


