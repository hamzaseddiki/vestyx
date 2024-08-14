@extends('tenant.frontend.frontend-page-master')

@section('title')
    {!! $page_post->title !!}
@endsection

@section('page-title')
    {!! $page_post->title !!}
@endsection

@section('style')
    <style>
        .discount-timer {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
            margin: 0 20px;
            z-index: 95;
        }
        .discount-timer .global-timer .syotimer__body {
            gap: 10px 15px;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
        }
        @media (min-width: 1400px) and (max-width: 1599.98px) {
            .discount-timer .global-timer .syotimer__body {
                gap: 10px;
            }
        }
        @media (min-width: 1200px) and (max-width: 1399.98px) {
            .discount-timer .global-timer .syotimer__body {
                gap: 10px;
            }
        }
        @media (min-width: 992px) and (max-width: 1199.98px) {
            .discount-timer .global-timer .syotimer__body {
                gap: 10px;
            }
        }
        @media (min-width: 300px) and (max-width: 991.98px) {
            .discount-timer .global-timer .syotimer__body {
                gap: 10px;
            }
        }
        .discount-timer .global-timer .syotimer__body .syotimer-cell .syotimer-cell__value {
            font-size: 32px;
            line-height: 36px;
        }
        @media (min-width: 1400px) and (max-width: 1599.98px) {
            .discount-timer .global-timer .syotimer__body .syotimer-cell .syotimer-cell__value {
                font-size: 28px;
            }
        }
        @media (min-width: 1200px) and (max-width: 1399.98px) {
            .discount-timer .global-timer .syotimer__body .syotimer-cell .syotimer-cell__value {
                font-size: 28px;
            }
        }
        @media (min-width: 992px) and (max-width: 1199.98px) {
            .discount-timer .global-timer .syotimer__body .syotimer-cell .syotimer-cell__value {
                font-size: 28px;
            }
        }
        @media (min-width: 300px) and (max-width: 991.98px) {
            .discount-timer .global-timer .syotimer__body .syotimer-cell .syotimer-cell__value {
                font-size: 28px;
            }
        }
        .discount-timer .global-timer .syotimer__body .syotimer-cell .syotimer-cell__unit {
            font-size: 18px;
            line-height: 28px;
        }
        @media (min-width: 1400px) and (max-width: 1599.98px) {
            .discount-timer .global-timer .syotimer__body .syotimer-cell .syotimer-cell__unit {
                font-size: 16px;
            }
        }
        @media (min-width: 1200px) and (max-width: 1399.98px) {
            .discount-timer .global-timer .syotimer__body .syotimer-cell .syotimer-cell__unit {
                font-size: 16px;
            }
        }
        @media (min-width: 992px) and (max-width: 1199.98px) {
            .discount-timer .global-timer .syotimer__body .syotimer-cell .syotimer-cell__unit {
                font-size: 16px;
            }
        }
        @media (min-width: 300px) and (max-width: 991.98px) {
            .discount-timer .global-timer .syotimer__body .syotimer-cell .syotimer-cell__unit {
                font-size: 16px;
            }
        }

        li.singleOption.list.d-flex.justify-content-between.active a ,
        li.singleOption.list.d-flex.justify-content-between.active {
            color: var(--main-color-one);
        }

        .selected-flex-list .category_filter_top i
        {
            cursor: pointer;
            color: var(--main-color-one);
            margin-top: 5px;
        }

        .selected-flex-list .size_filter_top i
        {
            cursor: pointer;
            color: var(--main-color-one);
            margin-top: 5px;
        }

        .selected-flex-list .color_filter_top i
        {
            cursor: pointer;
            color: var(--main-color-one);
            margin-top: 5px;
        }

        .selected-flex-list .rating_filter_top i
        {
            cursor: pointer;
            color: var(--main-color-one);
            margin-top: 5px;
        }


        .selected-flex-list .rating_filter_top .rating_icon
        {
            cursor: pointer;
            color: var(--main-color-one);
            margin-top: 5px;
        }



        li.rating-filter.list {
            position: relative;
            z-index: 2;
            display: block;
            padding-left: 26px;
            margin-top: 10px;
        }


        li.rating-filter.list.active::before {
            font-family: "Line Awesome Free";
            font-weight: 900;
            content: "";
            background: var(--main-color-one);
            border-color: var(--main-color-one);
        }

        .selected-flex-list .tag_filter_top i
        {
            cursor: pointer;
            color: var(--main-color-one);
            margin-top: 5px;
        }
    </style>
@endsection

@section('content')
    <!-- Shop area starts -->
    <div class="eventListing padding-top-100 padding-bottom-20">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    @if($errors->any())
                        <div class="alert alert-danger search-results-fields">
                            <ul class="list-none">
                                <button type="button" class="close btn-sm" data-bs-dismiss="alert">×</button>
                                @foreach($errors->all() as $error)
                                    <li> {{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
                @include('product::frontend.shop.partials.shop-search')
                @include('product::frontend.shop.partials.shop-sidebar-content')

                @include('product::frontend.shop.partials.shop-grid-products')

        </div>
    </div>
    <!-- Shop area end -->
@endsection

@section('scripts')
    <script>
        $(function () {
            $(document).on('click', '.active-list .list', function() {
                $(this).siblings().removeClass('active');
                $(this).addClass('active');

                $('.click-hide-filter').removeClass('d-none');
            });

            $(document).on('click', '.active-list .list a span.ad-values', function() {
                $(this).parent().parent().siblings().removeClass('active');
                $(this).parent().parent().addClass('active');
            });

            $(document).on('click', '.category-filter', function (){

                let value = $(this).attr('data-value');
                let slug = $(this).attr('data-slug');

                let filter_container = $('#_porduct_fitler_item');
                filter_container.find('.category_filter_top').remove();
                filter_container.append(`<li class="category_filter_top list-item mx-2" value="${value}"
                    data-filter='category-lists' data-slug='${slug}'>${value} <i class="las la-times ml-2 click-hide"></i></li> `);
            });

            $(document).on('click', '.size-filter', function (){
                let value = $(this).attr('data-value');
                let slug = $(this).attr('data-slug');

                let filter_container = $('#_porduct_fitler_item');
                filter_container.find('.size_filter_top').remove();
                filter_container.append(`<li class="size_filter_top list-item mx-2" value="${value}"
                    data-filter='size-lists' data-slug='${slug}'>${value} <i class="las la-times ml-2 click-hide"></i></li> `);
            });

            $(document).on('click', '.color-filter', function (){
                let value = $(this).attr('data-value');
                let slug = $(this).attr('data-slug');

                let filter_container = $('#_porduct_fitler_item');
                filter_container.find('.color_filter_top').remove();
                filter_container.append(`<li class="color_filter_top list-item mx-2" value="${value}"
                    data-filter='color-lists' data-slug='${slug}'>${value} <i class="las la-times ml-2 click-hide"></i></li> `);
            });

            $(document).on('click', '.rating-filter', function (){
                let slug = $(this).attr('data-slug');

                let filter_container = $('#_porduct_fitler_item');
                filter_container.find('.rating_filter_top').remove();
                filter_container.append(`<li class="rating_filter_top list-item mx-2"
                    data-filter='filter-lists' data-slug='${slug}'>${slug} <i class="las la-star"></i> <i class="las la-times ml-2 click-hide"></i></li> `);
            });

            $(document).on('click', '.tag-filter', function (){
                let slug = $(this).attr('data-slug');

                let filter_container = $('#_porduct_fitler_item');
                filter_container.find('.tag_filter_top').remove();
                filter_container.append(`<li class="tag_filter_top list-item mx-2"
                    data-filter='tag-lists' data-slug='${slug}'>${slug} <i class="las la-times ml-2 click-hide"></i></li> `);
            });

            $(document).on('click', 'ul.pagination .page-item a', function (e) {
                e.preventDefault();
                console.log('seve')
                filter_product_request($(this).data('page'));
            })

            $(document).on('click', '.list, .price-search-btn', function (e) {
                // e.preventDefault();
                let currentPage = $(".pagination .page-item .page-link.active").attr("data-page");
                console.log(currentPage,'88')
                filter_product_request(currentPage);
            })

            // Wishlist Product
            $(document).on('click', '.wishlist-btn', function (e) {
                let el = $(this);
                let product = el.data('product_id');
                console.log('s');
                $.ajax({
                    url: '{{route('tenant.shop.wishlist.product')}}',
                    type: 'GET',
                    data: {
                        product_id: product
                    },
                    beforeSend: function () {
                        $('.loader').show();
                    },
                    success: function (data) {
                        $('.loader').hide();

                        if (data.type === 'success') {
                            toastr.success(data.msg)
                        } else {
                            toastr.error(data.msg);
                        }
                    },
                    error: function (data) {
                        $('.loader').hide();
                    }
                });
            });

            /*========================================
                Click Clear Filter
            ========================================*/
            $(document).on('click', '.click-hide-filter .click-hide', function () {
                let filter_name = '.' + $(this).parent().data('filter') + ' .active';

                $(filter_name).removeClass('active');
                console.log('five');
                filter_product_request();
                $(this).parent().remove();

                let filter_children = $('.selected-flex-list').children();
                if (filter_children.length === 0) {
                    $('.selectder-filter-contents').addClass('d-none');
                }
            });

            $(document).on('click', '.click-hide-filter .click-hide-parent', function () {
                let filter_name = $(this).data('filter');

                if (filter_name === 'all') {
                    $('.active-list .active').removeClass('active');

                    $('.ui-range-value-min .min_price').text('0');
                    $('.ui-range-value-min input').val(0);

                    $('.ui-range-value-max .max_price').text('10000');
                    $('.ui-range-value-max input').val(10000);

                    $('.noUi-base .noUi-connect').css('left', '0%');
                    $('.noUi-base .noUi-background').css('left', '100%');
                    console.log('four');
                    filter_product_request();

                    $('.selectder-filter-contents').addClass('d-none');
                    $(this).siblings('ul').html('');
                }
            });

            $(document).on('change', '.shop-nice-select .sorting_shop_page', function (e) {
                let sort = $(this).val();
                let currentPage = $(".pagination .page-item .page-link.active").attr("data-page");
                console.log('three');
                filter_product_request(currentPage, sort);
            });

            /*========================================
                Range Slider
            ========================================*/
            let i = document.querySelector(".ui-range-slider");
            if (void 0 !== i && null !== i) {
                let j = parseInt(i.parentNode.getAttribute("data-start-min"), 10),
                    k = parseInt(i.parentNode.getAttribute("data-start-max"), 10),
                    l = parseInt(i.parentNode.getAttribute("data-min"), 10),
                    m = parseInt(i.parentNode.getAttribute("data-max"), 10),
                    n = parseInt(i.parentNode.getAttribute("data-step"), 10),
                    o = document.querySelector(".ui-range-value-min span"),
                    p = document.querySelector(".ui-range-value-max span"),
                    q = document.querySelector(".ui-range-value-min input"),
                    r = document.querySelector(".ui-range-value-max input");

                noUiSlider.create(i, {
                    start: [j, k],
                    connect: !0,
                    step: n,
                    range: {
                        min: l,
                        max: m
                    },
                    behaviour: 'tap'
                }), i.noUiSlider.on("change", function (a, b) {
                    let c = a[b];

                    b ? (p.innerHTML = Math.round(c), r.value = Math.round(c)) : (o.innerHTML = Math.round(c), q.value = Math.round(c))
                let currentPage = $(".pagination .page-item .page-link.active").attr("data-page");
                console.log('two');
                    filter_product_request(currentPage);
                })
            }

            function filter_product_request(page = null, sort = null) {
                console.log('hi');
                let url = '{{route('tenant.shop')}}';
                let category_slug = $('.category-lists .active').data('slug')
                let size_slug = $('.size-lists .active').data('slug')
                let color_slug = $('.color-lists .active').data('slug')
                let rating = $('.filter-lists .active').data('slug');
                let min_price = $('.ui-range-value-min input').val();
                let max_price = $('.ui-range-value-max input').val();

                console.log(category_slug,size_slug,color_slug,rating, min_price ,max_price)

                let tag_slug = $('.tag-lists .active').data('slug');
                let requestPage = null;
                if (page !== null) {
                    requestPage = page
                }

                let sortBy = null;
                if (sort !== null) {
                    sortBy = sort;
                }

                $.ajax({
                    type: 'GET',
                    url: url,
                    data: {
                        'category': category_slug,
                        'size': size_slug,
                        'color': color_slug,
                        'rating': rating,
                        'min_price': min_price,
                        'max_price': max_price,
                        'tag': tag_slug,
                        'page': requestPage,
                        'sort': sortBy
                    },

                    beforeSend: function () {
                       CustomLoader.start();
                    },
                    success: function (data) {
                        // main route
                        $(".grid-product-list").html(data.grid)
                        $(".list-product-list").html(data.list)

                        $(".shop-icons.active").trigger('click');

                        let paginationData = data.pagination;
                        let fromItems = paginationData.from;
                        let toItems = paginationData.to;
                        let totalItems = paginationData.total;

                        $('.showing-results').text('{{__('Showing')}} ' + fromItems + ' - ' + totalItems + ' of ' + totalItems + ' {{__('Results')}}');

                        setInterval(() => {
                           CustomLoader.end();
                        }, 700)
                    },
                    error: function (data) {

                    }
                });
            }

            $(document).on('keyup', 'input[name=search]', function (e) {
                let search = $(this).val();

                if (search === '') {
                    setTimeout(() => {
                        location.reload();
                    }, 500)
                }

                $.ajax({
                    type: 'GET',
                    url: '{{route('tenant.shop.search')}}',
                    data: {
                        'search': search,
                    },

                    beforeSend: function () {
                       CustomLoader.start();
                    },
                    success: function (data) {
                        $(".grid-product-list").html(data.grid)
                        $(".list-product-list").html(data.list)

                        $(".shop-icons.active").trigger('click');

                        let paginationData = data.pagination;
                        let fromItems = paginationData.from !== null ? paginationData.from : 0;
                        let toItems = paginationData.to;
                        let totalItems = paginationData.total;

                        $('.showing-results').text('{{__('Showing')}} ' + fromItems + ' - ' + totalItems + ' of ' + totalItems + ' {{__('Results')}}');

                        setInterval(() => {
                            CustomLoader.end();
                        }, 700)
                    },
                    error: function (data) {

                    }
                });
            });

            /*========================================
                Product Quick View Modal
            ========================================*/
            $(document).on('click', 'a.popup-modal', function (e) {
                let el = $(this).parent();
                let id = el.data('id');
                let modal = $('#product-modal');

                $.ajax({
                    type: 'GET',
                    url: '{{route('tenant.shop.quick.view')}}',
                    data: {
                        'id': id,
                    },

                    beforeSend: function () {
                        $('.loader').show();
                    },
                    success: function (data) {
                        modal.html(data.product_modal);

                        setInterval(() => {
                            $('.loader').hide();
                        }, 700)
                    },
                    error: function (data) {

                    }
                });
            });
        });
    </script>
@endsection
