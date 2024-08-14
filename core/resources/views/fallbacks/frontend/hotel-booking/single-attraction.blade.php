@extends('tenant.frontend.frontend-page-master')
@php
    $post_img = null;
    $user_lang = get_user_lang();
@endphp

@section('page-title')
    {{$data['title']}}
@endsection

@section('title')
    {{$data['title']}}
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
        .single-blog-details-thumb {
            height: 400px;
            width: 100%;
            border-radius: 20px;
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
        }
        .single-blog-details-thumb img {
            height: 100%;
            width: 100%;
            object-fit: cover;
            object-position: center;
        }
    </style>
@endsection

@section('content')
    @php
        $lang_slug = request()->get('lang') ?? \App\Facades\GlobalLanguage::default_slug();
    @endphp
    <section class="blog-details-area pat-100 pab-100">
        <div class="container">
            <div class="shop-contents-wrapper-details">
                <div class="row justify-content-center gy-5">
                    <div class="col-xl-8 col-lg-8">
                        <div class="blog-details-wrapper">
                            <div class="single-blog-details">
                                    <h2 class="single-blog-details-content-title fw-500"> {{$data['title']}} </h2>
                                    <div class="single-blog-details-content-tags mt-3">
                                    </div>
                                    <div class="single-blog-details-thumb mt-4"{!! render_background_image_markup_by_attachment_id(@$data['image_id']) !!}
                                    >
                                    </div>
                                    <h2 class="single-blog-details-content-title fw-500 mt-5">{{__('About')}} {{$data['title']}}: </h2>
                                    <p class="single-blog-details-content-para mt-4"> {{@$data['description']}} </p>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
    @yield("custom-ajax-scripts")
    <script>
        $(document).on('submit', '#checkoutForm', function (e) {
            e.preventDefault();
            var form = $(this);
            var formID = form.attr('id');
            var formSelector = document.getElementById(formID);
            var formData = new FormData(formSelector);

            $.ajax({
                url: "{{route('tenant.frontend.checkout')}}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{csrf_token()}}",
                },
                processData: false,
                contentType: false,
                data:formData,
                success: function (data) {
                    if (data.warning_msg)
                    {
                        CustomSweetAlertTwo.warning(data.warning_msg)
                    }
                    else{
                        window.location.href = data.redirect_url;
                    }

                }
            })
        });

        // Get today's date
        var today = new Date();

        // Calculate the date 14 days from today
        var sevenDaysFromToday = new Date(today);
        sevenDaysFromToday.setDate(today.getDate() + 13);

        flatpickr(".test-picker", {
            enableTime: false,
            minDate: "today",
            maxDate:sevenDaysFromToday,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            // Other options...
        });
    </script>

@endsection
