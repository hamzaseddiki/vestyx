@extends('tenant.admin.admin-master')
@section('title')
    {{__('Edit Product - '. $product->name)}}
@endsection
@section('style')
    <link rel="stylesheet" href="{{global_asset('assets/landlord/admin/css/bootstrap-taginput.css')}}">
    <link rel="stylesheet" href="{{global_asset('assets/landlord/admin/css/module-fix-style.css')}}">
    <x-media-upload.css/>
    <x-summernote.css/>
    <x-product::variant-info.css/>
@endsection
@section('content')
    @php
        $subCat = $product?->subCategory?->id ?? null;
        $childCat = $product?->childCategory?->pluck("id")->toArray() ?? null;
        $cat = $product?->category?->id ?? null;
        $selectedDeliveryOption = $product?->delivery_option?->pluck("delivery_option_id")?->toArray() ?? [];
        $lang_slug = request()->get('lang') ?? \App\Facades\GlobalLanguage::default_slug();
    @endphp
    <div class="dashboard-top-contents">
        <div class="row">
            <div class="col-lg-12">
                <div class="top-inner-contents search-area top-searchbar-wrapper">
                    <div class="dashboard-flex-contetns">
                        <div class="dashboard-left-flex">
                            <h3 class="heading-three fw-500"> {{ __("Edit Product") }} </h3>
                        </div>
                        <div class="dashboard-right-flex">
                            <div class="top-search-input">
                                <a class="btn btn-info btn-sm px-4"
                                   href="{{route('tenant.admin.product.all')}}">{{__('Back')}}</a>
                            </div>
                        </div>
                        <div class="dashboard-right-flex">
                            <form action="{{route('tenant.admin.product.edit',$product->id)}}" method="get">
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
        <div class="row">
            <div class="col-md-12">
                <div class="row g-4 d-flex align-items-start">
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
                            <button class="nav-link" id="v-pills-images-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-images-tab" type="button" role="tab" aria-controls="v-images-tab"
                                    aria-selected="false"><span
                                    style='font-size:15px; padding-right: 7px;'>&#9679;</span> {{ __("Images") }}
                            </button>
                            <button class="nav-link" id="v-pills-inventory-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-inventory-tab" type="button" role="tab"
                                    aria-controls="v-inventory-tab" aria-selected="false"><span
                                    style='font-size:15px; padding-right: 7px;'>&#9679;</span> {{ __("Inventory") }}
                            </button>
                            <button class="nav-link" id="v-pills-tags-and-label" data-bs-toggle="pill"
                                    data-bs-target="#v-tags-and-label" type="button" role="tab"
                                    aria-controls="v-tags-and-label" aria-selected="false"><span
                                    style='font-size:15px; padding-right: 7px;'>&#9679;</span> {{ __("Tags & Label") }}
                            </button>
                            <button class="nav-link" id="v-pills-attributes-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-attributes-tab" type="button" role="tab"
                                    aria-controls="v-attributes-tab" aria-selected="false"><span
                                    style='font-size:15px; padding-right: 7px;'>&#9679;</span> {{ __("Attributes") }}
                            </button>
                            <button class="nav-link" id="v-pills-categories-tab" data-bs-toggle="pill"
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
                        <form data-request-route="{{ route("tenant.admin.product.edit", $product->id) }}" method="post"
                              id="product-create-form">
                            @csrf
                            <input name="id" type="hidden" value="{{ $product?->id }}">
                            <input type="hidden" name="lang" value="{{$data['default_lang']}}">

                            <div class="form-button mb-4">
                                <button class="btn-sm btn btn-info">{{ __("Save Changes") }}</button>
                            </div>

                            <div class="tab-content margin-top-10" id="v-pills-tabContent">
                                <div class="tab-pane fade show active" id="v-general-info-tab" role="tabpanel"
                                     aria-labelledby="v-general-info-tab">
                                    <x-product::general-info :brands="$data['brands']" :product="$product" :defaultLang="$data['default_lang']"/>
                                </div>
                                <div class="tab-pane fade" id="v-price-tab" role="tabpanel"
                                     aria-labelledby="v-price-tab">
                                    <x-product::product-price :product="$product"/>
                                </div>
                                <div class="tab-pane fade" id="v-inventory-tab" role="tabpanel"
                                     aria-labelledby="v-inventory-tab">
                                    <x-product::product-inventory :units="$data['units']"
                                                                  :inventory="$product?->inventory"
                                                                  :uom="$product?->uom"/>
                                </div>
                                <div class="tab-pane fade" id="v-images-tab" role="tabpanel"
                                     aria-labelledby="v-images-tab">
                                    <x-product::product-image :product="$product"/>
                                </div>
                                <div class="tab-pane fade" id="v-tags-and-label" role="tabpanel"
                                     aria-labelledby="v-tags-and-label">
                                    <x-product::tags-and-badge :badges="$data['badges']" :tag="$product?->tag"
                                                               :singlebadge="$product?->badge_id" :defaultLang="$data['default_lang']"/>
                                </div>
                                <div class="tab-pane fade" id="v-attributes-tab" role="tabpanel"
                                     aria-labelledby="v-attributes-tab">
                                    <x-product::product-attribute
                                        :inventorydetails="$product?->inventory?->inventoryDetails"
                                        :colors="$data['product_colors']"
                                        :sizes="$data['product_sizes']"
                                        :allAttributes="$data['all_attribute']"/>
                                </div>
                                <div class="tab-pane fade" id="v-categories-tab" role="tabpanel"
                                     aria-labelledby="v-categories-tab">
                                    <x-product::categories :sub_categories="$sub_categories"
                                                           :categories="$data['categories']"
                                                           :child_categories="$child_categories"
                                                           :selected_child_cat="$childCat" :selected_sub_cat="$subCat"
                                                           :selectedcat="$cat"
                                                           :defaultLang="$data['default_lang']"
                                    />
                                </div>
                                <div class="tab-pane fade" id="v-delivery-option-tab" role="tabpanel"
                                     aria-labelledby="v-delivery-option-tab">
                                    <x-product::delivery-option :selected_delivery_option="$selectedDeliveryOption"
                                                                :deliveryOptions="$data['deliveryOptions']"
                                                                :defaultLang="$data['default_lang']"/>
                                </div>
                                <div class="tab-pane fade" id="v-meta-tag-tab" role="tabpanel"
                                     aria-labelledby="v-meta-tag-tab">
                                    <x-product::meta-seo :meta_data="$product->metaData"/>
                                </div>
                                <div class="tab-pane fade" id="v-settings-tab" role="tabpanel"
                                     aria-labelledby="v-settings-tab">
                                    <x-product::settings :product="$product"/>
                                </div>
                                <div class="tab-pane fade" id="v-policy-tab" role="tabpanel"
                                     aria-labelledby="v-policy-tab">
                                    <x-product::policy :product="$product" :defaultLang="$data['default_lang']"/>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <x-media-upload.markup/>
        @endsection
        @section('scripts')
            <script src="{{ global_asset('assets/common/js/jquery-ui.min.js') }}" rel="stylesheet"></script>
            <script src="{{global_asset('assets/landlord/admin/js/bootstrap-taginput.min.js')}}"></script>
            <x-media-upload.js/>
            <x-summernote.js/>

            <script>
                $(document).ready(function () {

                    $(document).on('change','select[name="lang"]',function (e){
                        $(this).closest('form').trigger('submit');
                        $('input[name="lang"]').val($(this).val());
                    });

                    String.prototype.capitalize = String.prototype.capitalize || function () {
                        return this.charAt(0).toUpperCase() + this.slice(1);
                    }

                    let aria_name = "{{$data['aria_name']}}";
                    if (aria_name != '') {
                        $('.nav-link').removeClass('active');
                        $('.tab-pane ').removeClass('show active');

                        $('#v-pills-' + aria_name).addClass('active');
                        $('#v-' + aria_name).addClass('show active');
                    }

                    $('.general-meta').addClass('active');
                    $('.general-meta-pane').addClass('show active');


                    $(document).on('click', '.add_item_attribute', function (e) {
                        let container = $(this).closest('.inventory_item');
                        let attribute_name_field = container.find('.item_attribute_name');
                        let attribute_value_field = container.find('.item_attribute_value');
                        let attribute_name = attribute_name_field.find('option:selected').text();
                        let attribute_value = attribute_value_field.find('option:selected').text();

                        let container_id = container.data('id');

                        if (!container_id) {
                            container_id = 0;
                        }

                        if (attribute_name_field.val().length && attribute_value_field.val().length) {
                            let attribute_repeater = '';
                            attribute_repeater += '<div class="form-row">';
                            attribute_repeater += '<input type="hidden" name="item_attribute_id[' + container_id + '][]" value="">';
                            attribute_repeater += '<div class="col">';
                            attribute_repeater += '<div class="form-group">';
                            attribute_repeater += '<input type="text" class="form-control" name="item_attribute_name[' + container_id + '][]" value="' + attribute_name + '" readonly />';
                            attribute_repeater += '</div>';
                            attribute_repeater += '</div>';
                            attribute_repeater += '<div class="col">';
                            attribute_repeater += '<div class="form-group">';
                            attribute_repeater += '<input type="text" class="form-control" name="item_attribute_value[' + container_id + '][]" value="' + attribute_value + '" readonly />';
                            attribute_repeater += '</div>';
                            attribute_repeater += '</div>';
                            attribute_repeater += '<div class="col-auto">';
                            attribute_repeater += '<button type="button" class="btn btn-danger remove_details_attribute"> x </button>';
                            attribute_repeater += '</div>';
                            attribute_repeater += '</div>';

                            container.find('.item_selected_attributes').append(attribute_repeater);

                            attribute_name_field.val('');
                            attribute_value_field.val('');
                        } else {
                            toastr.warning('@php echo e(__("Select both attribute name and value")); @endphp');
                        }
                    });

                    $(document).on('change', '.item_attribute_name', function () {
                        let terms = $(this).find('option:selected').data('terms');
                        let terms_html = '<option value=""><?php echo e(__("Select attribute value")); ?></option>';



                        terms.map(function (term) {
                            terms_html += '<option value="' + term + '">' + term + '</option>';
                        });
                        $(this).closest('.inventory_item').find('.item_attribute_value').html(terms_html);
                    })

                    $(document).on("submit", "#product-create-form", function (e) {
                        e.preventDefault();

                        send_ajax_request("post", new FormData(e.target), $(this).attr("data-request-route"), function () {
                            toastr.warning("Request sent successfully ");
                        }, function (data) {
                            if (data.success) {
                                let nav_aria_name = $('.nav-link.active').attr('aria-controls');
                                let changed_aria_name = nav_aria_name.replace('v-', '');

                                toastr.success("Product updated Successfully");
                                toastr.success("You are redirected to product list page");
                                setTimeout(function () {
                                    let url = '{{route("tenant.admin.product.edit", $product->id)}}';
                                    window.location.href = url + '/' + changed_aria_name;
                                }, 1000)
                            }
                        }, function (xhr) {
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

                    $(document).on('click', '.repeater_button .add', function (e) {
                        let inventory_item = `<x-product::variant-info.repeater :colors="$data['product_colors']" :sizes="$data['product_sizes']" :all-available-attributes="$data['all_attribute']" />`;

                        if (inventory_item_id < 1) {
                            inventory_item_id = $('.inventory_items_container .inventory_item').length;
                        }

                        $('.inventory_items_container').append(inventory_item);
                        $('.inventory_items_container .inventory_item:last-child').data('id', inventory_item_id + 1);
                    });

                    $(document).on('click', '.repeater_button .remove', function (e) {
                        if($('.repeater_button .remove').length > 1){
                            $(this).closest('.inventory_item').remove();
                        }
                    });

                    $(document).on('click', '.remove_details_attribute', function (e) {
                        $(this).parent().parent().remove();
                    });

                    $(document).on('click', '.badge-item', function (e) {
                        $(".badge-item").removeClass("active");
                        $(this).addClass("active");
                        $(this).effect("shake", {direction: "up", times: 1, distance: 2}, 500);
                        $("#badge_id_input").val($(this).attr("data-badge-id"));
                    });

                    $(document).on("click", ".close-icon", function () {
                        $('#media_upload_modal').modal('hide');
                    });


                    function send_ajax_request(request_type, request_data, url, before_send, success_response, errors) {
                        $.ajax({
                            url: url,
                            type: request_type,
                            headers: {
                                'X-CSRF-TOKEN': "4Gq0plxXAnBxCa2N0SZCEux0cREU7h4NHObiPH10",
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
                        })
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
            </script>
@endsection
