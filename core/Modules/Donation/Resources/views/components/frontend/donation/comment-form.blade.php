
@if(auth()->guard('web')->check())
    <div class="simplePresentCart cart mb-50 ">
        <div class="small-tittle mb-30">
            <h3 class="tittle">{{__('Write a comment')}}</h3>
        </div>
        <form action="" class="contactUs" id="blog-comment-form" enctype="multipart/form-data">
        @csrf
            <input type="hidden" name="user_id" value="{{ auth()->guard('web')->id() }}">
            <input type="hidden" name="donation_id" value="{{ $donation->id }}">

            <div class="error-message"></div>
            <div class="col-lg-12">
                <div class="input-form input-form2">
                    <textarea placeholder="Write anything.." name="comment_content"></textarea>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="btn-wrapper mb-10">
                    <button type="button" id="submitComment" class="cmn-btn1">{{__('Comment')}}</button>
                </div>
            </div>
        </form>
    </div>

 @else
    <x-ajax-user-login-markup/>
@endif
