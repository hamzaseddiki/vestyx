@if(Auth::guard('web')->check())
    <div id="reviews" class="tab-content-item">
    <div class="single-details-tab">
        <div class="review-form my-5">
            <h3>{{__('Leave a Review')}}</h3>
            <form>
                <input type="hidden" class="rating-count" value="">
                <div class="ratings mt-4">
                    <select class="star-rating">
                        <option value="5">{{__('Excellent')}}</option>
                        <option value="4">{{__('Very Good')}}</option>
                        <option value="3" selected>{{__('Average')}}</option>
                        <option value="2">{{__('Poor')}}</option>
                        <option value="1">{{__('Terrible')}}</option>
                    </select>
                </div>

                <div class="form-group mt-4">
                    <textarea rows="8" type="text" name="review_text" class="form-control review-text" id="review-text"></textarea>
                </div>

                <div class="btn-wrapper text-end mt-4">
                    <button type="submit" id="review-submit-btn" class="cmn-btn btn-small cmn-btn-bg-2 radius-0">{{__('Submit Review')}}</button>
                </div>
            </form>
        </div>

        <div class="tab-review">
            <div class="all-reviews">
                @foreach($product->reviews->take(5) ?? [] as $review)
                    <div class="about-seller-flex-content">
                        <div class="about-seller-thumb">
                            <a href="javascript:void(0)">
                                {!! render_image_markup_by_attachment_id($review?->user?->image) !!}
                            </a>
                        </div>

                        <div class="about-seller-content">
                            <h5 class="title fw-500">
                                <a href="javascript:void(0)"> {{$review?->user?->name}} </a>
                            </h5>

                            {!! render_star_rating_markup($review->rating) !!}

                            <p class="about-review-para"> {{$review->review_text}} </p>
                            <span class="review-date"> {{$review->created_at?->diffForHumans()}} </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="btn-wrapper mt-4">
                <a href="javascript:void(0)" class="cmn-btn btn-small cmn-btn-bg-2 radius-0 see-more-review" data-items="5"> {{__('See More')}} </a>
            </div>
        </div>
    </div>
</div>
@else
    <section class="loginArea section-padding2">
        <div class="container">
            <div class="row justify-content-center">

                <div class="col-xxl-6 col-xl-7 col-lg-9">
                    <form action="#" class="form-Wrapper" id="login_form_order_page">
                        <div class="row">

                            <div class="col-lg-12">
                                <div class="section-tittle section-tittle2 text-center mb-30">
                                    <h2 class="tittle p-0">{{__('Login in your ')}}<span class="color"> {{__('Account')}}</span></h2>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <div class="error-wrap"></div>
                                <div class="input-form input-form2">
                                    <input type="text" name="username" placeholder="Enter your username">
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <div class="input-form input-form2">
                                    <input type="password" name="password" placeholder="Password">
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="passRemember">
                                    <label class="checkWrap2">{{__('Remember me')}}
                                        <input class="effectBorder" type="checkbox" value="">
                                        <span class="checkmark"></span>
                                    </label>
                                    <!-- forgetPassword -->
                                    <div class="forgetPassword mb-25">
                                        <a href="{{route('tenant.user.forget.password')}}" class="forgetPass">{{__('Forget passwords?')}}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="btn-wrapper text-center mt-20">
                                    <button type="button" id="login_btn" class="cmn-btn1 w-100 mb-40">{{__('Login')}}</button>

                                    <p class="sinUp mb-20"><span>{{__('Don’t have an account ?')}} </span>
                                        <a href="{{route('tenant.user.register')}}" class="singApp">{{__('Sign Up')}}</a>
                                    </p>

                                    <x-social-login-markup/>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endif
