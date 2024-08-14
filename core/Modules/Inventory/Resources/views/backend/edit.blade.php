@extends('tenant.admin.admin-master')
@section('title')
    {{__('Product Inventory - ').$product->getTranslation('name',default_lang())}}
@endsection
@section('style')
    <link rel="stylesheet" href="{{ global_asset('assets/landlord/admin/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{global_asset('assets/landlord/admin/css/module-fix-style.css')}}">
    <x-media-upload.css/>
    <x-product::variant-info.css />
    <style>
        .singleFlexitem {
            background: #FFFFFF;
            -webkit-box-shadow: 0px 1px 80px 12px rgba(26, 40, 68, 0.06);
            box-shadow: 0px 1px 80px 12px rgba(26, 40, 68, 0.06);
            padding: 20px;
            padding-bottom: 0;
            border-radius: 12px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: start;
            -ms-flex-align: start;
            align-items: start;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            -webkit-transition: 0.4s;
            transition: 0.4s;
            cursor: pointer;
        }
        @media (max-width: 1199px) {
            .singleFlexitem {
                -ms-flex-wrap: wrap;
                flex-wrap: wrap;
            }
        }
        .singleFlexitem .listCap {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            /*-webkit-box-align: center;*/
            /*-ms-flex-align: center;*/
            /*align-items: center;*/
            -webkit-transition: 0.4s;
            transition: 0.4s;
            margin-bottom: 20px;
            cursor: pointer;
            left: auto;
        }
        @media only screen and (min-width: 1200px) and (max-width: 1399.99px) {
            .singleFlexitem .listCap {
                padding: 10px;
            }
        }
        @media (max-width: 991px) {
            .singleFlexitem .listCap {
                -ms-flex-wrap: wrap;
                flex-wrap: wrap;
                margin-bottom: 10px;
            }
        }
        .singleFlexitem .listCap .recentImg {
            margin-right: 20px;
        }
        @media (max-width: 575px) {
            .singleFlexitem .listCap .recentImg {
                margin-bottom: 15px;
            }
        }
        @media only screen and (min-width: 1200px) and (max-width: 1399.99px) {
            .singleFlexitem .listCap .recentImg {
                width: 29%;
                margin-right: 9px;
            }
        }
        .singleFlexitem .listCap .recentImg img {
            border-radius: 12px;
            margin-bottom: 16px;
        }
        @media only screen and (min-width: 1200px) and (max-width: 1399.99px) {
            .singleFlexitem .listCap .recentImg img {
                width: 100%;
            }
        }
        .singleFlexitem .listCap .recentCaption .featureTittle {
            font-family: var(--heading-font);
            line-height: 1.5;
            color: #00d8ff;
            font-weight: 500;
            font-size: 20px;
            display: block;
        }
        .singleFlexitem .listCap .recentCaption .featureTittle:hover {
            color: var(--heading-color);
        }
        @media only screen and (min-width: 1200px) and (max-width: 1399.99px) {
            .singleFlexitem .listCap .recentCaption .featureTittle {
                font-size: 15px;
            }
        }
        @media only screen and (min-width: 992px) and (max-width: 1199px) {
            .singleFlexitem .listCap .recentCaption .featureTittle {
                font-size: 21px;
            }
        }
        @media only screen and (min-width: 768px) and (max-width: 991px) {
            .singleFlexitem .listCap .recentCaption .featureTittle {
                font-size: 18px;
            }
        }
        @media (max-width: 575px) {
            .singleFlexitem .listCap .recentCaption .featureTittle {
                font-size: 18px;
            }
        }
        .singleFlexitem .listCap .recentCaption .featureCap {
            font-family: var(--heading-font);
            font-size: 15px;
            color: var(--heading-font);
        }
        @media only screen and (min-width: 1200px) and (max-width: 1399.99px) {
            .singleFlexitem .listCap .recentCaption .featureCap {
                font-size: 12px;
                margin-bottom: 7px;
            }
        }
        .singleFlexitem .listCap .recentCaption .featureCap .subCap {
            font-family: var(--heading-font);
            font-family: var(--heading-font);
            color: #00ff65;
            font-weight: 400;
        }
        @media only screen and (min-width: 1200px) and (max-width: 1399.99px) {
            .singleFlexitem .listCap .recentCaption .featureCap .subCap {
                font-size: 12px;
            }
        }
        .cat label {
            font-weight: 500;
            color: #be3300;
        }
        .singleFlexitem .featurePricing {
            margin-bottom: 18px;
            font-family: var(--heading-font);
            color: #ff2600;
            font-weight: 500;
            font-size: 22px;
            display: block;
            text-align: center;
        }
        .singleFlexitem .featurePricing del{
            font-weight: 400;
            font-size: 18px;
        }
        @media only screen and (min-width: 1200px) and (max-width: 1399.99px) {
            .singleFlexitem .featurePricing {
                font-size: 17px;
                margin-bottom: 7px;
            }
        }
        .singleFlexitem:hover .cat-caption .product-price i {
            color: var(--main-color-two);
            font-size: 16px;
        }



        .pro-btn1 {
            font-family: var(--heading-font);
            -webkit-transition: 0.4s;
            -moz-transition: 0.4s;
            transition: 0.4s;
            border: 1px solid transparent;
            background: rgba(255,205,2, 0.1);
            color: red;
            text-transform: capitalize;
            padding: 1px 8px;
            font-size: 15px;
            font-weight: 400;
            display: inline-block;
            border-radius: 6px;

        }
        .featureCap_category_item:not(:first-child) {
            margin-top: 30px;
        }
        .pro-btn2 {
            font-family: var(--heading-font);
            -webkit-transition: 0.4s;
            -moz-transition: 0.4s;
            transition: 0.4s;
            border: 1px solid transparent;
            background: rgba(82, 78, 183, 0.1);
            color: blue;
            text-transform: uppercase;
            padding: 4px 8px;
            font-size: 14px;
            font-weight: 400;
            display: inline-block;
            border-radius: 6px;

        }

        .pro-btn2:hover {
            background: red;
            color: #fff;
        }
        .pro-btn1:hover {
            background: blue;
            color: #fff;
        }

        .recentImg{
            width: 360px;
        }
    </style>
@endsection


@php
    $inventory_details = true;
@endphp

@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row g-4">

            <div class="col-lg-12">
                <!-- Single -->
                <div class="singleFlexitem social">
                    <div class="listCap">
                        <div class="recentImg">
                            {!! render_image_markup_by_attachment_id($product->image_id, '', 'grid', false) !!}
                            <span class="featurePricing">{{ amount_with_currency_symbol($product->price) }} <del>{{ amount_with_currency_symbol($product->sale_price) }}</del></span>
                        </div>
                        <div class="recentCaption">
                            <div class="d-flex align-items-center gap-4 mb-4">
                                <h5 class="mb-0"><a href="javascript:void(0)" class="featureTittle">{{ $product->getTranslation('name',default_lang()) }}</a></h5>
                                <div class="btn-wrapper d-flex flex-wrap gap-2">
                                    <a href="{{route('tenant.shop.product.details', $product->slug)}}" class="btn btn-success me-2"><i class="lar la-eye icon"></i> {{__('View')}}</a>  <!-- To Product View from Frontend -->
                                    <a href="{{route('tenant.admin.product.edit',$product->id)}}" class="btn btn-info"><i class="las la-pencil-alt icon"></i> {{__('Edit Product')}}</a>   <!-- To Product Edit from Admin Panel -->
                                </div>
                            </div>

                            <p class="featureCap mb-4">{{ $product?->summary }} <strong class="subCap">{{$product->updated_at?->diffForHumans() ?? ''}}</strong></p>

                            <div class="featureCap_category">
                                  <div class="featureCap_category_item d-flex gap-2">
                                      <strong> {{__('Category:')}} </strong>
                                      @if($product?->category)
                                          <div class="featureCap_category_item_flex d-flex flex-wrap gap-2">
                                              <span class="pro-btn1">{{$product?->category?->getTranslation('name',default_lang())}}</span>
                                          </div>
                                      @endif
                                  </div>
                                <div class="featureCap_category_item d-flex gap-2">
                                    <strong> {{__('Sub Category:')}} </strong>
                                    @if($product?->subCategory)
                                        <div class="featureCap_category_item_flex d-flex flex-wrap gap-2">
                                            <span class="pro-btn2">{{$product?->subCategory?->getTranslation('name',default_lang())}}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="featureCap_category_item d-flex gap-2">
                                <strong> {{__('Child Category:')}} </strong>
                                @if($product?->childCategory)
                                    @foreach($product->childCategory as $childCategory)
                                        <div class="featureCap_category_item_flex d-flex flex-wrap gap-2">
                                            <span class="pro-btn1">{{ $childCategory?->getTranslation('name',default_lang()) }}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                        </div>
                    </div>

                </div>
            </div>
            <form action="{{ route('tenant.admin.product.inventory.update') }}" method="POST" id="update-inventory-form">
                @csrf
                <input value="{{ $product->id }}" name="product_id" type="hidden">

            <div class="col-lg-12 mt-3">
                <div class="card p-5">

                    <x-product::product-inventory :inventory_page="false" :units="$data['units']" :inventory="$product?->inventory"
                                                  :uom="$product?->uom"/>
                </div>
            </div>
            @can('product-category-edit')
                <div class="col-lg-12 mt-3">

                        <x-product::product-attribute
                                :inventorydetails="$inventory?->inventoryDetails" :colors="$product_colors"
                                :sizes="$product_sizes"
                                :allAttributes="$all_attributes"/>
                </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-sm btn-info">{{__('Update Inventory')}}</button>
                    </div>
            @endcan
            </form>
        </div>
    </div>

    <x-media-upload.markup/>
@endsection
@section('scripts')
    <x-product::variant-info.js :colors="$product_colors" :sizes="$product_sizes"
                                :all-attributes="$all_attributes"/>
    <x-datatable.js />
    <x-table.btn.swal.js />
    <script src="{{ global_asset('assets/landlord/admin/js/jquery.nice-select.min.js') }}"></script>
    <x-media-upload.js/>
    <script>
        (function ($) {
            'use script'

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

            $(document).on("submit", "#update-inventory-form", function (e) {
                e.preventDefault();
                let data = new FormData(e.target);

                send_ajax_request("post", data, '{{ route('tenant.admin.product.inventory.update') }}', function () {

                }, function (data) {
                    if(data.type == 'success'){
                        toastr.success(data.msg);
                    }else{
                        toastr.error(data.msg);
                    }

                }, function () {

                });
            });

            $(document).ready(function () {
                let nice_select_el = $('.nice-select');
                if (nice_select_el.length > 0) {
                    nice_select_el.niceSelect();
                }
            });
        })(jQuery)
    </script>
@endsection
