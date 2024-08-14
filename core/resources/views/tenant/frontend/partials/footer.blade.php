<div class="modal product-quick-view-bg-color" id="product_quick_view" tabindex="-1" role="dialog"
     aria-labelledby="productModal" aria-hidden="true"></div>


@include('tenant.frontend.partials.widget-area')

<div class="mouseCursor cursorOuter"></div>
<div class="mouseCursor cursorInner"></div>
<div class="progressParent">
    <svg class="backCircle svg-inner" width="100%" height="100%" viewBox="-1 -1 102 102">
        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"/>
    </svg>
</div>
@php
    $theme_footer_css_files = \App\Facades\ThemeDataFacade::getFooterHookCssFiles();
@endphp
@foreach($theme_footer_css_files ?? [] as $cssFile)
    <link rel="stylesheet" href="{{ loadCss($cssFile) }}" type="text/css"/>
@endforeach

@php
    $loadCoreScript = loadCoreScript();

@endphp
@if(in_array('jquery.min', $loadCoreScript))
    <script src="{{global_asset('assets/common/js/jquery-3.6.1.min.js')}}"></script>
@endif
@if(in_array('popper.min', $loadCoreScript))
    <script src="{{global_asset('assets/tenant/frontend/themes/js/popper.min.js')}}"></script>
@endif
@if(in_array('bootstrap', $loadCoreScript))
    <script src="{{global_asset('assets/tenant/frontend/themes/js/bootstrap.js')}}"></script>
@endif

@if(in_array('plugin', $loadCoreScript))
    <script src="{{global_asset('assets/tenant/frontend/themes/js/plugin.js')}}"></script>
@endif

@if(in_array('main', $loadCoreScript))
    <script src="{{global_asset('assets/tenant/frontend/themes/js/main.js')}}"></script>
@endif
@if(in_array('loopcounter', $loadCoreScript))
    <script src="{{global_asset('assets/tenant/frontend/themes/js/loopcounter.js')}}"></script>
@endif
@if(in_array('toastr.min', $loadCoreScript))
    <script src="{{global_asset('assets/common/js/toastr.min.js')}}"></script>
@endif
@if(in_array('sweetalert2', $loadCoreScript))
    <script src="{{global_asset('assets/landlord/common/js/sweetalert2.js')}}"></script>
@endif
@if(in_array('star-rating.min', $loadCoreScript))
    <script src="{{global_asset('assets/common/js/star-rating.min.js')}}"></script>
@endif
@if(in_array('jquery.magnific-popup', $loadCoreScript))
    <script src="{{global_asset('assets/common/js/jquery.magnific-popup.js')}}"></script>
@endif
@if(in_array('md5', $loadCoreScript))
    <script src="{{global_asset('assets/common/js/md5.js')}}"></script>
@endif
@if(in_array('jquery.syotimer.min', $loadCoreScript))
    <script src="{{global_asset('assets/common/js/jquery.syotimer.min.js')}}"></script>
@endif

@if(in_array('viewport.jquery', $loadCoreScript))
    <script src="{{global_asset('assets/tenant/frontend/themes/js/viewport.jquery.js')}}"></script>
@endif
@if(in_array('odometer', $loadCoreScript))
    <script src="{{global_asset('assets/tenant/frontend/themes/js/odometer.js')}}"></script>
@endif
@if(in_array('nouislider-8.5.1.min', $loadCoreScript))
    <script src="{{global_asset('assets/common/js/nouislider-8.5.1.min.js')}}"></script>
@endif
@if(in_array('CustomLoader', $loadCoreScript))
    <script src="{{global_asset('assets/common/js/CustomLoader.js')}}"></script>
@endif
@if(in_array('CustomSweetAlertTwo', $loadCoreScript))
    <script src="{{global_asset('assets/common/js/CustomSweetAlertTwo.js')}}"></script>
@endif
@if(in_array('SohanCustom', $loadCoreScript))
    <script src="{{global_asset('assets/common/js/SohanCustom.js')}}"></script>
@endif

@php
    $theme_footer_js_files = \App\Facades\ThemeDataFacade::getFooterHookJsFiles();
@endphp
@foreach($theme_footer_js_files ?? [] as $jsFile)
    @if(!empty($jsFile))
        <script type="text/javascript" src="{{loadJs($jsFile) }}"></script>
    @endif
@endforeach


{!! \App\Facades\ThemeDataFacade::renderFooterHookBladeFile() !!}


@if(!empty(tenant()->id) && file_exists('assets/tenant/frontend/themes/js/dynamic-scripts/'.tenant()->id.'-style.css') && !is_dir('assets/tenant/frontend/themes/js/dynamic-scripts/'.tenant()->id.'-style.css'))
    <script
        src="{{global_asset('assets/tenant/frontend/themes/js/dynamic-scripts/'.tenant()->id.'-script.js')}}"></script>
@endif


<x-custom-js.newsletter-store/>
<x-custom-js.tenant-newsletter-store/>
<x-custom-js.query-submit/>
<x-custom-js.contact-form-store/>
<x-custom-js.lang-change/>
<x-custom-js.advertisement/>


{{--Module Js--}}
<x-blog::frontend.custom-js.category-show/>
<x-service::frontend.custom-js.category-show/>
@include('product::frontend.js.general')
@include('product::frontend.js.quick-view-js')
{{--Module Js--}}

@include('landlord.frontend.partials.gdpr-cookie')

@yield('scripts')
@yield('footer-scripts')


<script>
    CustomLoader.start()

    //For manual and bank transfer
    $(document).on('click', '.payment-gateway-list .single-gateway-item', function () {

        let gateway = $(this).data('gateway');
        $('#slected_gateway_from_helper').val(gateway);
        $('.checkout-form').find('.payment_gateway_passing_clicking_name').val(gateway);


        if (gateway == 'manual_payment_') {
            $('.manual_payment_transaction_field').removeClass('d-none');
        } else {
            $('.manual_payment_transaction_field').addClass('d-none');
        }

        if (gateway == 'bank_transfer') {
            $('.bank_payment_field').removeClass('d-none');
        } else {
            $('.bank_payment_field').addClass('d-none');
        }
    });

    $(".video-play-btn").magnificPopup({
        type: "video"
    }),
        $(".image-popup").magnificPopup({
            type: "image",
            gallery: {enabled: !0}
        });

    /*
    ========================================
      agency  counter Odometer
    ========================================
    */

    $(".single_counter__count").each(function () {
        $(this).isInViewport(function (status) {
            if (status === "entered") {
                for (var i = 0; i < document.querySelectorAll(".odometer").length; i++) {
                    var el = document.querySelectorAll('.odometer')[i];
                    el.innerHTML = el.getAttribute("data-odometer-final");
                }
            }
        });
    });

    /*
    ========================================
        counter Odometer
    ========================================
    */
    $(".consulting_single_counter__count").each(function () {
        console.log('working from footer.php')
        $(this).isInViewport(function (status) {
            if (status === "entered") {
                for (var i = 0; i < document.querySelectorAll(".odometer").length; i++) {
                    var el = document.querySelectorAll('.odometer')[i];
                    el.innerHTML = el.getAttribute("data-odometer-final");
                }
            }
        });
    });

    console.log($(".consulting_single_counter__count").length)

    $(document).ready(function () {
        $('.niceSelectt').niceSelect();

        loopcounter('flash-countdown');
        dynamicLoopCounter('.campaign-countdown');

        function dynamicLoopCounter(className) {
            // todo:: first we need to get length of this class
            if ($(className).length > 1) {
                // todo:: we need to create a unique class for each item
                let commonClass = "countDownTimer-"
                let loopIndex = 0;
                $(className).each(function () {
                    loopIndex++;
                    let countDownClass = commonClass + loopIndex;
                    $(this).addClass(countDownClass);

                    loopcounter(countDownClass);
                })

                return "countdown is running";
            }

            // todo:: now remove dot from class name if exist
            className = className.replace(".", "");
            loopcounter(className);
        }

        $('.contactArea #check').addClass('form-checkbox');
        $('.contactArea #check').removeClass('form-control');
    });


    /*
========================================
  Common Faq accordion
========================================
*/
    $('.consultingFaq__contents .consultingFaq__title').on('click', function (e) {
        var element = $(this).parent('.consultingFaq__item');
        if (element.hasClass('open')) {
            element.removeClass('open');
            element.find('.consultingFaq__panel').removeClass('open');
            element.find('.consultingFaq__panel').slideUp(300);
        } else {
            element.addClass('open');
            element.children('.consultingFaq__panel').slideDown(300);
            element.siblings('.consultingFaq__item').children('.consultingFaq__panel').slideUp(300);
            element.siblings('.consultingFaq__item').removeClass('open');
            element.siblings('.consultingFaq__item').find('.consultingFaq__title').removeClass('open');
            element.siblings('.consultingFaq__item').find('.consultingFaq__panel').slideUp(300);
        }
    });

    $(window).on('load', function () {
        CustomLoader.end()
    })
</script>
</body>
</html>
