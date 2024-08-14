@extends('tenant.admin.admin-master')
@section('title')
    {{__('Create Campaign')}}
@endsection
@section('style')
    <x-media-upload.css/>
    <link rel="stylesheet" href="{{ global_asset('assets/common/css/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ global_asset('assets/landlord/admin/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{global_asset('assets/landlord/admin/css/module-fix-style.css')}}">
@endsection
@section('content')
    @php
        $lang_slug = request()->get('lang') ?? \App\Facades\GlobalLanguage::default_slug();
    @endphp
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-20">
                    <x-error-msg/>
                    <x-flash-msg/>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">{{__('Create Campaign')}}</h4>
                        <div class="d-flex justify-content-between">
                            @can('campaign-list')
                                <div class="text-end">
                                    <a href="{{ route('tenant.admin.campaign.all') }}" class="btn btn-primary">{{ __('All Campaigns') }}</a>
                                </div>
                            @endcan
                            <form action="{{route('tenant.admin.campaign.new')}}" method="get">
                                <x-fields.select name="lang" title="{{__('Language')}}">
                                    @foreach(\App\Facades\GlobalLanguage::all_languages() as $lang)
                                        <option value="{{$lang->slug}}" @if($lang->slug === $lang_slug) selected @endif>{{$lang->name}}</option>
                                    @endforeach
                                </x-fields.select>
                            </form>
                        </div>
                        @can('campaign-create')
                            <form action="{{ route('tenant.admin.campaign.new') }}" method="POST">
                                @csrf
                                <input type="hidden" name="lang" value="{{$default_lang}}">
                                <div class="row new_campaign mt-5">
                                    <div class="col-md-4">
                                        <div class="card-wrapper mb-3" style="border: 1px solid #f3f3f3">
                                            <div class="card-body-wrapper">
                                                <div class="border-0 bg-transparent">
                                                    <h4 class="font-weight-bold">{{ __('Create Info') }}</h4>
                                                </div>
                                                <div class="card-body-inner mt-4">
                                                    <div class="form-group">
                                                        <label for="campaign_name">{{ __('Campaign Name') }}</label>
                                                        <input type="text" class="form-control" id="campaign_name" name="campaign_name" placeholder="Campaign Name">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="campaign_subtitle">{{ __('Campaign Subtitle') }}</label>
                                                        <input type="text" class="form-control" id="campaign_subtitle" name="campaign_subtitle" placeholder="Campaign Subtitle">
                                                    </div>
                                                    <x-fields.media-upload :title="__('Campaign Image')" :name="'image'" :dimentions="'1920x1080'"/>
                                                    <div class="form-group">
                                                        <label for="campaign_status">{{ __('Campaign Status') }}</label>
                                                        <select name="status" id="status" class="form-control">
                                                            <option value="draft">{{ __('Draft') }}</option>
                                                            <option value="publish">{{ __('Publish') }}</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group mt-5">
                                                        <input type="checkbox" id="set_fixed_percentage">
                                                        <label for="set_fixed_percentage">{{ __('Set Fixed Percentage') }}</label>
                                                        <p class="text-small">{{__('when you set fixed percentage, you have to click on sync price button, to sync price selection with all prodcuts')}}</p>
                                                        <div id="fixe_price_cut_container" style="display: none">
                                                            <input type="number" id="fixed_percentage_amount" class="form-control mb-2" placeholder="{{ __('Price Cut Percentage') }}">
                                                            <button type="button" class="btn btn-sm btn-primary mb-2" id="fixed_price_sync_all">{{ __('Sync Price') }}</button>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="checkbox" id="set_fixed_date">
                                                        <label for="set_fixed_date">{{ __('Set Fixed Date') }}</label>
                                                        <p class="text-small">{{__('when you set fixed date, you have to click on sync date button, to sync date selection with all prodcuts')}}</p>
                                                        <div id="fixed_date_container" style="display: none">
                                                            <input type="date" name="campaign_start_date" id="fixed_from_date" class="form-control mb-2 flatpickr" placeholder="{{ __('From Date') }}">
                                                            <input type="date" name="campaign_end_date" id="fixed_to_date" class="form-control mb-2 flatpickr" placeholder="{{ __('To Date') }}">
                                                            <button class="btn btn-sm btn-primary" id="fixed_date_sync_all">{{ __('Sync Date') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="product_repeater_container">
                                                    @include("campaign::backend.add_new_campaign_product")
                                                </div>
                                                <div class="text-end">
                                                    <button type="button" class="btn btn-outline-primary" id="add_product_btn">{{ __('Add Product') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 text-center">
                                        @can('campaign-create')
                                            <button type="submit" class="btn btn-primary">{{ __('Create Campaign') }}</button>
                                        @endcan
                                    </div>
                                </div>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

 <x-media-upload.markup/>

@endsection

@section('scripts')
    <x-media-upload.js/>
    <script src="{{ global_asset('assets/common/js/flatpickr.js') }}"></script>
    <script src="{{ global_asset('assets/landlord/admin/js/jquery.nice-select.min.js') }}"></script>
    <script>
        (function ($) {
            $(document).ready(function () {
                flatpickr(".flatpickr", {
                    altInput: true,
                    altFormat: "F j, Y",
                    dateFormat: "Y-m-d",
                });

                if ($('.nice-select').length > 0) {
                    $('.nice-select').niceSelect();
                }

                $(document).on('click', '.cross-btn', function () {
                    let container = $(this).closest('.card');
                    container.slideUp('slow');
                    setTimeout(() => {container.remove();}, 1000);
                });

                $(document).on('change', '.repeater_product_id', function () {
                    let stock = $(this).find('option:checked').data('stock');
                    $(this).closest('.card-body').find('.available_num_of_units').val(stock);
                });

                $(document).on('change', '.select_product select', function () {
                    let selected_product_id = $(this).val();
                    let container = $(this).closest('.card');
                    let original_price_field = container.find('.original_price');
                    let campaign_price_field = container.find('.campaign_price');
                    $(this).prev().val(selected_product_id);
                    let data = $(this).find('option:checked').data();
                    let product_price = data['sale_price'];

                    $(this).closest('.card-body').find('.available_num_of_units').val(data['stock']);

                    $(this).closest('.card-body').find('.original_price').val(product_price);

                    if ($('#set_fixed_percentage').is(':checked')) {
                        let percentage = $('#fixed_percentage_amount').val().trim();
                        let price_after_percentage = product_price - (product_price / 100 * percentage);
                        campaign_price_field.val(price_after_percentage);
                    }
                });

                $('#set_fixed_percentage').on('change', function () {
                    if ($(this).is(':checked')) {
                        $('#fixe_price_cut_container').slideDown('500')
                    } else {
                        $('#fixe_price_cut_container').slideUp('500');
                        setTimeout(function () {
                            $('#fixed_percentage_amount').val('');
                        }, 500);
                    }
                });

                $('#set_fixed_date').on('change', function () {
                    if ($(this).is(':checked')) {
                        $('#fixed_date_container').slideDown(500);
                    } else {
                        $('#fixed_date_container').slideUp(500);
                        setTimeout(function () {
                            $('#fixed_date_container input').val('');
                        }, 500);
                    }
                });

                $('#fixed_price_sync_all').on('click', function () {
                    let fixed_percentage = $('#fixed_percentage_amount').val().trim();

                    if (!fixed_percentage.length) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'warning',
                            title: '{{ __("Set percentage first") }}',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    let all_prices = $('.original_price');
                    for (let i = 0; i < all_prices.length; i++) {
                        let price_container = $(all_prices[i]).closest('.col');
                        let final_price_container = price_container.next();
                        let product_price = $(all_prices[i]).val().trim();
                        let price_after_percentage = product_price - (product_price / 100 * fixed_percentage);
                        price_after_percentage = price_after_percentage.toFixed(2);
                        console.log(price_after_percentage);
                        final_price_container.find('.campaign_price').val(price_after_percentage);
                    }
                });

                $('#fixed_date_sync_all').on('click', function (e) {
                    e.preventDefault();
                    console.log(111);
                    if ($('#set_fixed_date').is(':checked')) {
                        let from_date = $('#fixed_from_date').val();
                        let to_date = $('#fixed_to_date').val();

                        $('.start_date.flatpickr-input').val(from_date);
                        $('.end_date.flatpickr-input').val(to_date);

                        flatpickr(".flatpickr", {
                            altInput: true,
                            altFormat: "F j, Y",
                            dateFormat: "Y-m-d",
                        });
                    } else {
                        Swal.fire({
                            position: 'top-start',
                            icon: 'warning',
                            title: '{{ __("Set fixed date first") }}',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });

                $(document).on('keyup', '.campaign_price', function (x){
                    let products = $('#product_repeater_container .card');
                    products.each(function (key ,value){
                        let main_price = $(value).find('.product_original_price').val();
                        let new_price = $(value).find('.campaign_price').val();
                        let new_percentage = $(value).find('.product_percentage');

                        let percentage = 0;
                        percentage = 100-((new_price/main_price)*100);
                        new_percentage.html((percentage).toFixed(2) +'%');
                    })
                });

                $('#add_product_btn').on('click', function () {
                    let product_repeater_container = $('#product_repeater_container');
                    let from_date = undefined;
                    let to_date = undefined;
                    let new_element = product_repeater_container.find('.card').last().clone();

                    console.log(new_element)

                    if ($('#set_fixed_date').is(':checked')) {
                        from_date = $('#fixed_from_date').val();
                        to_date = $('#fixed_to_date').val();
                    }

                    if (from_date) {
                        new_element.find('.start_date.input').val(from_date);
                    }

                    if (to_date) {
                        new_element.find('.end_date.input').val(to_date);
                    }

                    let card_header = new_element.find('.campaign-card-header');

                    if (card_header.find('.cross-btn').length < 1) {
                        card_header.append('<span class="cross-btn"><i class="las la-times-circle"></i></span>');
                    }

                    new_element.find('.start_date.input').remove();
                    new_element.find('.end_date.input').remove();

                    new_element.find('.campaign_price').val('');
                    new_element.find('.units_for_sale').val('');

                    product_repeater_container.append(new_element.hide());
                    new_element.slideDown('slow');

                    flatpickr(".flatpickr", {
                        altInput: true,
                        altFormat: "F j, Y",
                        dateFormat: "Y-m-d",
                    });

                    product_repeater_container.find('.nice-select').niceSelect('destroy');
                    product_repeater_container.find('.nice-select').niceSelect();
                });
            });
        })(jQuery)
    </script>
@endsection
