@extends('tenant.admin.admin-master')
@section('title')
    {{__('Add new Product')}}
@endsection
@section('site-title')
    {{__('Add new Product')}}
@endsection
@section('style')
    <link rel="stylesheet" href="{{global_asset('assets/landlord/admin/css/bootstrap-taginput.css')}}">
    <link rel="stylesheet" href="{{global_asset('assets/common/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{global_asset('assets/landlord/admin/css/module-fix-style.css')}}">
    <x-media-upload.css/>
    <x-summernote.css/>
    <x-product::variant-info.css/>
@endsection
@section('content')
    @php
        $lang_slug = request()->get('lang') ?? \App\Facades\GlobalLanguage::default_slug();
    @endphp

    <div class="dashboard-top-contents">
        <div class="row">
            <div class="col-lg-12">
                <div class="top-inner-contents search-area top-searchbar-wrapper">
                    <div class="dashboard-flex-contetns">
                        <div class="dashboard-left-flex">
                            <h3 class="heading-three fw-500"> {{ __("Add Products") }} </h3>
                        </div>
                        <div class="dashboard-right-flex">
                            <div class="top-search-input">
                                <a class="btn btn-info btn-sm px-4" href="{{route('tenant.admin.product.all')}}">{{__('Back')}}</a>
                            </div>
                        </div>
                        <div class="dashboard-right-flex">
                            <form action="{{route('tenant.admin.product.create')}}" method="get">
                                <x-fields.select name="lang" title="{{__('Language')}}">
                                    @foreach(\App\Facades\GlobalLanguage::all_languages() as $lang)
                                        <option value="{{$lang->slug}}" @if($lang->slug === $lang_slug) selected @endif>{{$lang->name}}</option>
                                    @endforeach
                                </x-fields.select>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dashboard-products-add bg-white radius-20 mt-4">
        <div class="row g-4">
            <div class="col-md-12">
                <div class="row gy-4 d-flex align-items-start">
                    <div class="col-xxl-2 col-xl-3 col-lg-12">
                        <div class="nav flex-column nav-pills border-1 radius-10 me-3" id="v-pills-tab" role="tablist"
                             aria-orientation="vertical">
                            <button class="nav-link active" id="v-pills-general-info-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-general-info-tab" type="button" role="tab"
                                    aria-controls="v-general-info-tab" aria-selected="true"><span
                                        style='font-size:15px; padding-right: 7px;'>&#9679;</span> {{__("General Info")}}
                            </button>
                            <button class="nav-link" id="v-pills-price-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-price-tab" type="button" role="tab" aria-controls="v-price-tab"
                                    aria-selected="false"><span
                                        style='font-size:15px; padding-right: 7px;'>&#9679;</span> {{ __("Price") }}
                            </button>
                            <button class="nav-link" id="v-pills-images-tab-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-images-tab" type="button" role="tab" aria-controls="v-images-tab"
                                    aria-selected="false"><span
                                        style='font-size:15px; padding-right: 7px;'>&#9679;</span> {{ __("Images") }}
                            </button>
                            <button class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-inventory-tab" type="button" role="tab"
                                    aria-controls="v-inventory-tab" aria-selected="false"><span
                                        style='font-size:15px; padding-right: 7px;'>&#9679;</span> {{ __("Inventory") }}
                            </button>
                            <button class="nav-link" id="v-pills-tags-and-label" data-bs-toggle="pill"
                                    data-bs-target="#v-tags-and-label" type="button" role="tab"
                                    aria-controls="v-tags-and-label" aria-selected="false"><span
                                        style='font-size:15px; padding-right: 7px;'>&#9679;</span> {{ __("Tags & Label") }}
                            </button>
                            <button class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-attributes-tab" type="button" role="tab"
                                    aria-controls="v-attributes-tab" aria-selected="false"><span
                                        style='font-size:15px; padding-right: 7px;'>&#9679;</span> {{ __("Attributes") }}
                            </button>
                            <button class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-categories-tab" type="button" role="tab"
                                    aria-controls="v-categories-tab" aria-selected="false"><span
                                        style='font-size:15px; padding-right: 7px;'>&#9679;</span> {{ __("Categories") }}
                            </button>
                            <button class="nav-link" id="v-pills-delivery-option-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-delivery-option-tab" type="button" role="tab"
                                    aria-controls="v-delivery-option-tab" aria-selected="false"><span
                                        style='font-size:15px; padding-right: 7px;'>&#9679;</span> {{ __("Delivery Option") }}
                            </button>
                            <button class="nav-link" id="v-pills-meta-tag-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-meta-tag-tab" type="button" role="tab"
                                    aria-controls="v-meta-tag-tab" aria-selected="false"><span
                                        style='font-size:15px; padding-right: 7px;'>&#9679;</span> {{ __("Product Meta") }}
                            </button>
                            <button class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-settings-tab" type="button" role="tab"
                                    aria-controls="v-settings-tab" aria-selected="false"><span
                                        style='font-size:15px; padding-right: 7px;'>&#9679;</span> {{ __("Product Settings") }}
                            </button>
                            <button class="nav-link" id="v-pills-policy-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-policy-tab" type="button" role="tab"
                                    aria-controls="v-policy-tab" aria-selected="false"><span
                                    style='font-size:15px; padding-right: 7px;'>&#9679;</span> {{ __("Shipping & Return Policy") }}
                            </button>
                        </div>
                    </div>
                    <div class="col-xxl-10 col-xl-9 col-lg-12">
                        <div class="info-right-wrapper">
                            <form data-request-route="{{ route("tenant.admin.product.create") }}" method="post"
                              id="product-create-form">
                                <input type="hidden" name="lang" value="{{$data['default_lang']}}">
                            @csrf
                            <div class="form-button text-end">
                                <button class="btn-sm btn btn-success">{{ __("Create Product") }}</button>
                            </div>
                            <div class="info-right-inner">
                                <div class="tab-content margin-top-10" id="v-pills-tabContent">
                                <div class="tab-pane fade show active" id="v-general-info-tab" role="tabpanel"
                                     aria-labelledby="v-general-info-tab">
                                    <x-product::general-info :brands="$data['brands']" :defaultLang="$data['default_lang']"/>
                                </div>
                                <div class="tab-pane fade" id="v-price-tab" role="tabpanel"
                                     aria-labelledby="v-price-tab">
                                    <x-product::product-price/>
                                </div>
                                <div class="tab-pane fade" id="v-inventory-tab" role="tabpanel"
                                     aria-labelledby="v-inventory-tab">
                                    <x-product::product-inventory :units="$data['units']"/>
                                </div>
                                <div class="tab-pane fade" id="v-images-tab" role="tabpanel"
                                     aria-labelledby="v-images-tab">
                                    <x-product::product-image/>
                                </div>
                                <div class="tab-pane fade" id="v-tags-and-label" role="tabpanel"
                                     aria-labelledby="v-tags-and-label">
                                    <x-product::tags-and-badge :badges="$data['badges']" :defaultLang="$data['default_lang']"/>
                                </div>
                                <div class="tab-pane fade" id="v-attributes-tab" role="tabpanel"
                                     aria-labelledby="v-attributes-tab">
                                    <x-product::product-attribute :is-first="true"
                                                                  :colors="$data['product_colors']"
                                                                  :sizes="$data['product_sizes']"
                                                                  :allAttributes="$data['all_attribute']"/>
                                </div>
                                <div class="tab-pane fade" id="v-categories-tab" role="tabpanel"
                                     aria-labelledby="v-categories-tab">
                                    <x-product::categories :categories="$data['categories']" :defaultLang="$data['default_lang']"/>
                                </div>
                                <div class="tab-pane fade" id="v-delivery-option-tab" role="tabpanel"
                                     aria-labelledby="v-delivery-option-tab">
                                    <x-product::delivery-option :deliveryOptions="$data['deliveryOptions']" :defaultLang="$data['default_lang']"/>
                                </div>
                                <div class="tab-pane fade" id="v-meta-tag-tab" role="tabpanel"
                                     aria-labelledby="v-meta-tag-tab">
                                    <x-product::meta-seo/>
                                </div>
                                <div class="tab-pane fade" id="v-settings-tab" role="tabpanel"
                                     aria-labelledby="v-settings-tab">
                                    <x-product::settings/>
                                </div>
                                <div class="tab-pane fade" id="v-policy-tab" role="tabpanel"
                                     aria-labelledby="v-policy-tab">
                                    <x-product::policy/>
                                </div>
                            </div>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-media-upload.markup/>
        @endsection
        @section('scripts')
            <script src="{{ global_asset('assets/common/js/jquery-ui.min.js') }}" rel="stylesheet"></script>
            <script src="{{global_asset('assets/landlord/admin/js/bootstrap-taginput.min.js')}}"></script>
            <script src="{{global_asset('assets/common/js/select2.min.js')}}"></script>

            <x-media-upload.js/>
            <x-summernote.js/>
            <x-product::variant-info.js :colors="$data['product_colors']" :sizes="$data['product_sizes']"
                                        :all-attributes="$data['all_attribute']"/>

            <script>
                $(document).ready(function() {
                    $('.select2').select2({
                        placeholder: 'Select an option'
                    });
                });

                $(document).on('change','select[name="lang"]',function (e){
                    $(this).closest('form').trigger('submit');
                    $('input[name="lang"]').val($(this).val());
                });


                let temp = false;
                $(document).on("change",".dashboard-products-add .form--control", function (){
                    $(".dashboard-products-add .form--control").each(function (){
                        if($(this).val() != ''){
                            temp = true;
                            return false;
                        }else{
                            temp = false;
                        }
                    })
                })

                $(document).ready(function () {
                    String.prototype.capitalize = String.prototype.capitalize || function () {
                        return this.charAt(0).toUpperCase() + this.slice(1);
                    }

                    function convertToSlug(text) {
                        return text
                            .toLowerCase()
                            .replace(/ /g, '-')
                            .replace(/[^\w-]+/g, '');
                    }

                    $('#product-name , #product-slug').on('keyup', function () {
                        let title_text = $(this).val();
                        $('#product-slug').val(convertToSlug(title_text))
                    });

                    $(document).on("submit", "#product-create-form", function (e) {
                        e.preventDefault();

                        send_ajax_request("post", new FormData(e.target), $(this).attr("data-request-route"), function () {
                            toastr.warning("{{__('Request sent successfully')}}");
                        }, function (data) {
                            if (data.success) {
                                toastr.success("{{__('Product Created Successfully')}}");
                                toastr.success("{{__('You are redirected to product list page')}}");

                                $("#product-create-form").trigger("reset");
                                temp = false;
                                setTimeout(function () {
                                    window.location.href = "{{ route("tenant.admin.product.all") }}";
                                }, 1000);
                            } else if (data.restricted) {
                                toastr.error("{{__('Sorry you can not upload more products due to your product upload limit')}}");

                                let nav_product = $('.product-limits-nav');
                                nav_product.find('span').css({'color': 'red', 'font-weight': 'bold'});
                                nav_product.effect("shake", {direction: "up left", times: 2, distance: 3}, 500);
                            }
                        }, function (xhr) {
                            console.log(xhr)
                            ajax_toastr_error_message(xhr);
                        });
                    })

                    let inventory_item_id = 0;
                    $(document).on("click", ".delivery-item", function () {
                        $(this).toggleClass("active");
                        $(this).effect("shake", {direction: "up", times: 1, distance: 2}, 500);
                        let delivery_option = "";
                        $.each($(".delivery-item.active"), function () {
                            delivery_option += $(this).data("delivery-option-id") + " , ";
                        })

                        delivery_option = delivery_option.slice(0, -3)

                        $(".delivery-option-input").val(delivery_option);
                    });

                    $(document).on("change", "#category", function () {
                        let data = new FormData();
                        data.append("_token", "{{ csrf_token() }}");
                        data.append("category_id", $(this).val());

                        send_ajax_request("post", data, '{{ route('tenant.admin.category.sub-category') }}', function () {
                            $("#sub_category").html("<option value=''>Select Sub Category</option>");
                            $("#child_category").html("<option value=''>Select Child Category</option>");
                            $("#select2-child_category-container").html('');
                        }, function (data) {
                            $("#sub_category").html(data.html);
                        }, function () {

                        });
                    });

                    $(document).on("change", "#sub_category", function () {
                        let data = new FormData();
                        data.append("_token", "{{ csrf_token() }}");
                        data.append("sub_category_id", $(this).val());

                        send_ajax_request("post", data, '{{ route('tenant.admin.category.child-category') }}', function () {
                            $("#child_category").html("<option value=''>Select Child Category</option>");
                            $("#select2-child_category-container").html('');
                        }, function (data) {
                            $("#child_category").html(data.html);
                        }, function () {

                        });
                    });

                    $(document).on('click', '.badge-item', function (e) {
                        if ($(this).hasClass("active"))
                        {
                            $(this).removeClass("active")
                            $("#badge_id_input").val('');
                        } else {
                            $(".badge-item").removeClass("active");
                            $(this).addClass("active");
                            $("#badge_id_input").val($(this).attr("data-badge-id"));
                        }

                        $(this).effect("shake", {direction: "up", times: 1, distance: 2}, 500);
                    });

                    $(document).on("click", ".close-icon", function () {
                        $('#media_upload_modal').modal('hide');
                    });


                    function send_ajax_request(request_type, request_data, url, before_send, success_response, errors) {
                        $.ajax({
                            url: url,
                            type: request_type,
                            headers: {
                                'X-CSRF-TOKEN': "{{csrf_token()}}",
                            },
                            beforeSend: (typeof before_send !== "undefined" && typeof before_send === "function") ? before_send : () => {
                                return "";
                            },
                            processData: false,
                            contentType: false,
                            data: request_data,
                            success: (typeof success_response !== "undefined" && typeof success_response === "function") ? success_response : () => {
                                return "";
                            },
                            error: (typeof errors !== "undefined" && typeof errors === "function") ? errors : () => {
                                return "";
                            }
                        });
                    }

                    function prepare_errors(data, form, msgContainer, btn) {
                        let errors = data.responseJSON;

                        if (errors.success != undefined) {
                            toastr.error(errors.msg.errorInfo[2]);
                            toastr.error(errors.custom_msg);
                        }

                        $.each(errors.errors, function (index, value) {
                            console.log(value)
                            toastr.error(value[0]);
                        });
                    }


                    function ajax_toastr_error_message(xhr) {
                        $.each(xhr.responseJSON.errors, function (key, value) {
                            toastr.error((key.capitalize()).replace("-", " ").replace("_", " "), value);
                        });
                    }

                    function ajax_toastr_success_message(data) {
                        if (data.success) {
                            toastr.success(data.msg)
                        } else {
                            toastr.warning(data.msg);
                        }
                    }
                });

                $(window).bind('beforeunload', function(){
                    if(temp)
                    {
                        return '{{__('Are you sure you want to leave?')}}';
                    }
                });
            </script>
@endsection
