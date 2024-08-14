@extends('tenant.frontend.frontend-page-master')
@php
    $post_img = null;
    $user_lang = get_user_lang();
@endphp

@section('page-title')
{{__('reservation confirmed')}}
@endsection

@section('title')
 {{__('reservation confirmed')}}
@endsection

@section('meta-data')

@endsection

@section('style')
    <style>
        .singleBlog-details .blogCaption .cartTop {
            margin-bottom: 16px;
        }
        .singleBlog-details .blogCaption .cartTop .listItmes {
            display: inline-block;
            margin-right: 10px;
            font-size: 16px;
            font-weight: 300;
        }
        .singleBlog-details .blogCaption .cartTop .listItmes .icon {
            color: var(--peragraph-color);
            margin-right: 10px;
        }
    </style>
@endsection

@section('content')
    @php
        $lang_slug = request()->get('lang') ?? \App\Facades\GlobalLanguage::default_slug();
        $data = session('data');

    @endphp

        <!-- Confirmation area Starts -->
    <section class="confirmation-area section-bg-2 pat-100 pab-100">
        <div class="container">
            <div class="confirmation-contents center-text">
                <div class="confirmation-contents-icon">
                    <i class="las la-check"></i>
                </div>
                <h4 class="confirmation-contents-title"> Your reservation is confirmed </h4>
                <p class="confirmation-contents-para"> Dear {{@$data->name}}, Your reservation <a href="javascript:void(0)"> {{@$data->reservation_id}} </a> has been confirmed. Please check your mail for reservation invoice. Thanks for reserving our hotel! </p>
                <div class="btn-wrapper flex-btn mt-4 mt-lg-5">
                    <a href="{{url("/")}}" class="cmn-btn btn-outline-1 color-one"> Back to Home </a>
                </div>
            </div>
        </div>
    </section>
    <!-- Checkout area end -->

@endsection

@section('scripts')
    @yield("custom-ajax-scripts")
@endsection
