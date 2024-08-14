@extends('tenant.admin.admin-master')
@section('title')
    {{ __('All Products') }}
@endsection

@section('style')
    <link rel="stylesheet" href="{{global_asset('assets/landlord/admin/css/module-fix-style.css')}}">
    <x-summernote.css/>
    <x-product::variant-info.css/>
    <style>
        .out_of_stock{
            background-color: #ff000014;
        }
    </style>
@endsection
@section('content')
    @php
        $lang_slug = request()->get('lang') ?? \App\Facades\GlobalLanguage::default_slug();
    @endphp
    <div class="dashboard-recent-order">
        <div class="row">
            <x-flash-msg/>
            <div class="col-md-12">
                <div class="recent-order-wrapper dashboard-table bg-white">
                    <div id="product-list-title-flex" class="product-list-title-flex d-flex flex-wrap align-items-center justify-content-between">
                        <h3 class="cursor-pointer">{{__('Search Product Module')}} <i class="las la-angle-down"></i></h3>
                        <button id="product-search-button" type="submit" class="btn btn-info btn-sm">{{__('Search')}}</button>
                    </div>

                    <form id="product-search-form" class="row" action="{{ route("tenant.admin.product.search") }}" method="get">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="label-1" for="search-name">{{__('Name')}}</label>
                                <input name="name" class="form--control input-height-1" id="search-name" value="{{ request()->name ?? old("name") }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="label-1" for="search-sku">{{__('SKU')}}</label>
                                <input name="sku" class="form--control input-height-1" id="search-sku" value="{{ request()->sku ?? old("sku") }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="label-1" for="search-brand">{{__('Brand')}}</label>
                                <input name="brand" class="form--control input-height-1" id="search-brand" value="{{ request()->brand ?? old("brand") }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="label-1" for="search-category">{{__('Category')}}</label>
                                <input name="category" class="form--control input-height-1" id="search-category" value="{{ old("category") }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="label-1" for="search-sub_category">{{__('Sub Category')}}</label>
                                <input name="sub_category" class="form--control input-height-1" id="search-brand" value="{{ old("sub_category") }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="label-1" for="search-category">{{__('Child Category')}}</label>
                                <input name="child_category" class="form--control input-height-1" id="search-category" value="{{ old("child_category") }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="label-1" for="search-color">{{__('Color Name')}}</label>
                                <input name="color" class="form--control input-height-1" id="search-color" value="{{ old("color") }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="label-1" for="search-size">{{__('Size Name')}}</label>
                                <input name="size" class="form--control input-height-1" id="search-size" value="{{ old("size") }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="search-is_inventory_warn_able" class="checkbox-label-1"><input type="checkbox" name="is_inventory_warn_able" class="form--checkbox-1" id="search-is_inventory_warn_able" value="{{ old("is_inventory_warn_able") }}" /> {{__('Inventory Warning')}}</label>
                            </div>

                            <div class="form-group">
                                <label for="search-refundable" class="checkbox-label-1"> <input type="checkbox" name="refundable" class="form--checkbox-1" id="search-refundable" value="{{ old("refundable") }}" /> {{__('Refundable')}}</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="label-1" for="search-from_price">{{__('From Price')}}</label>
                                        <input name="from_price" class="form--control input-height-1" id="search-from_price" value="{{ old("from_price") }}" />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="label-1" for="search-to_price">{{__('TO Price')}}</label>
                                        <input name="to_price" class="form--control input-height-1" id="search-to_price" value="{{ old("to_price") }}" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="label-1" for="search-date_range">{{__('Created Date Range')}}</label>
                                <input name="date_range" class="form--control input-height-1" id="search-date_range" value="{{ old("date_range") }}" />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="label-1" for="search-order_by">{{__('Order By')}}</label>
                                <select name="order_by" class="form--control input-height-1" id="search-order_by" value="{{ old("order_by") }}">
                                    <option value="">{{__('Select Order By Option')}}</option>
                                    <option value="asc">{{__('ASC')}}</option>
                                    <option value="desc">{{__('DESC')}}</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-12 mt-4">
                <div class="recent-order-wrapper dashboard-table bg-white">
                    <div class="product-list-title-flex d-flex flex-wrap align-items-center justify-content-between">
                        <h3>{{__('Product List')}}</h3>
                    </div>
                    <div class="header-wrap d-flex flex-wrap justify-content-between">
                        <x-bulk-action/>
                        <div class="d-flex bulk-delete-wrapper gap-2">
                            <div class="bulk-delete-select-rows d-flex gap-2 me-4">
                                <label for="number-of-item">{{__('Number Of Rows')}}</label>
                                <select name="count" id="number-of-item">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                            <div class="btn-wrapper-trash">
                                <a class="btn btn-danger btn-sm" href="{{route('tenant.admin.product.trash.all')}}">{{__('Trash')}}</a>
                            </div>
                            <a class="btn btn-info btn-sm" href="{{route('tenant.admin.product.create')}}">{{__('Add New Product')}}</a>
                            <div class="right">
                                <form action="{{route('tenant.admin.product.all')}}" method="get">
                                    <x-fields.select name="lang" title="{{__('Language')}}">
                                        @foreach(\App\Facades\GlobalLanguage::all_languages() as $lang)
                                            <option value="{{$lang->slug}}" @if($lang->slug === $lang_slug) selected @endif>{{$lang->name}}</option>
                                        @endforeach
                                    </x-fields.select>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="table-wrap mt-4" id="product-table-body">
                        {!! view("product::search", compact("products","statuses","default_lang")) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <x-product::table.status-js />
    <x-product::table.bulk-action-js :url="route('tenant.admin.product.bulk.destroy')"/>
    <script>
        $(function (){
            $(document).on('change','select[name="lang"]',function (e){
                $(this).closest('form').trigger('submit');
                $('input[name="lang"]').val($(this).val());
            });

            $("#search-date_range").flatpickr({
                mode: "range",
                dateFormat: "Y-m-d",
            });

            $("#product-search-form").fadeOut();
            $(document).on("click","#product-list-title-flex h3", function (){
                $("#product-search-form").slideToggle();
            })

            $(document).ready(function (){
                $(".loader").hide();
            })

            $(document).on("click","#product-search-button", function (){
                $("#product-search-form").trigger("submit");
            });

            $(document).on("submit","#product-search-form", function (e){
                e.preventDefault();
                let form_data = $("#product-search-form").serialize().toString();
                form_data += "&count=" + $("#number-of-item").val();

                // product-table-body
                send_ajax_request("GET",null,$(this).attr("action") + "?" + form_data, () => {
                    // before send request
                    $(".loader").fadeIn();
                }, (data) => {
                    $("#product-table-body").html(data);
                    $(".loader").fadeOut();
                }, (data) => {
                    prepare_errors(data);
                });
            });

            $(document).on("change","#number-of-item", function (e){
                e.preventDefault();
                let form_data = $("#product-search-form").serialize().toString()
                form_data += "&count=" + $(this).val();

                // product-table-body
                send_ajax_request("GET",null,$("#product-search-form").attr("action") + "?" + form_data, () => {
                    // before send request
                    $(".loader").show();
                }, (data) => {
                    $("#product-table-body").html(data);
                    $(".loader").hide();
                }, (data) => {
                    prepare_errors(data);
                });
            });

            /*
            ========================================
                Row Remove Click Delete
            ========================================
            */
            $(document).on("click", ".pagination-list li a", function(e) {
                e.preventDefault();

                $(".pagination-list li a").removeClass("current");
                $(this).addClass("current");

                // product-table-body
                send_ajax_request("GET",null,$(this).attr("href"), () => {
                    // before send request
                    $(".loader").show();
                }, (data) => {
                    $("#product-table-body").html(data);
                    $(".loader").hide();
                }, (data) => {
                    prepare_errors(data);
                });
            });

            $(document).on("click", ".delete-row", function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        send_ajax_request("GET",null,$(this).data("product-url"), () => {
                            // before send request
                            toastr.warning("Request send please wait while");
                        }, (data) => {
                            Swal.fire(
                                '{{__('Deleted!')}}',
                                '{{__('Your file has been deleted.')}}',
                                '{{__('success')}}'
                            );

                            let product = $(this).parent().parent().parent();
                            product.fadeOut();

                            setTimeout(() => {
                                product.remove();
                                $(".tenant_info").load(location.href + " .tenant_info");
                                ajax_toastr_success_message(data);
                            }, 800)

                        }, (data) => {
                            prepare_errors(data);
                        })
                    }
                });
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
    </script>
@endsection
