@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('Create Price Plan')}}
@endsection

@section('style')
    <style>
        .all-field-wrap .action-wrap {
            position: absolute;
            right: 0;
            top: 0;
            background-color: #f2f2f2;
            height: 100%;
            width: 60px;
            text-align: center;
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
        }

        .f_desc {
            height: 100px;
        }

        .payment_gateway_container {
            display: flex;
            align-items: center;
            gap: 0 12px;
            flex-wrap: wrap;
        }

    </style>
@endsection

@section('content')
    @php
        $lang_slug = request()->get('lang') ?? \App\Facades\GlobalLanguage::default_slug();
        $features = \App\Enums\PricePlanTypEnums::getFeatureList();
    @endphp

    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="alert alert-warning text-primary">{{__('There are so many options are here, if you select any feature and you are not able to find required information against the feature then dont get panic and please check-uncheck again then scroll down and you will get it....!')}}</div>
                <div class="alert alert-warning text-primary">{{__('If you want to set any package to trial then you dont need to also set this as zero price, because there will be an option for user that, is he/she will go for only trial or with payment')}}</div>
                <x-admin.header-wrapper>
                    <x-slot name="left">
                        <h4 class="card-title mb-5">{{__('Create Price Plan')}}</h4><br>
                    </x-slot>
                    <x-slot name="right" class="d-flex">
                        <form action="{{route(route_prefix().'admin.price.plan.create')}}" method="get">
                            <x-fields.select name="lang" title="{{__('Language')}}">
                                @foreach(\App\Facades\GlobalLanguage::all_languages(1) as $lang)
                                    <option value="{{$lang->slug}}"
                                            @if($lang->slug === $lang_slug) selected @endif>{{$lang->name}}</option>
                                @endforeach
                            </x-fields.select>
                        </form>
                        <p></p>
                        <x-link-with-popover url="{{route(route_prefix().'admin.price.plan')}}" extraclass="ml-3">
                            {{__('All Price Plan')}}
                        </x-link-with-popover>
                    </x-slot>
                </x-admin.header-wrapper>
                <x-error-msg/>
                <x-flash-msg/>
                <form class="forms-sample" method="post" action="{{route(route_prefix().'admin.price.plan.create')}}">
                    @csrf

                    <x-fields.input type="hidden" name="lang" value="{{$lang_slug}}"/>
                    <x-fields.input name="title" label="{{__('Title')}}"/>
                    <x-fields.input name="subtitle" label="{{__('Subtitle')}}"/>

                    @if(!tenant())
                        <div class="form-group landlord_price_plan_feature">
                            <h4>{{__('Select Features')}}</h4>
                            <div class="feature-section">
                                <ul>
                                    @foreach($features as $key => $feat)
                                        <li class="d-inline">
                                            <input type="checkbox" name="features[]"
                                                   id="{{$key}}" class="exampleCheck1" value="{{$feat}}"
                                                   data-feature="{{$feat}}">
                                            <label class="ml-1" for="{{$key}}">
                                                @if($feat != 'e_commerce')
                                                    {{__(str_replace('_', ' ', ucfirst($feat)))}}
                                                @else
                                                    {{__(str_replace('_', '-', ucfirst($feat)))}}
                                                @endif
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>


                        <div class="row">
                            @include('landlord.admin.price-plan.partials.ecommerce-markup.ecommerce-data-create')
                        </div>

                        <div class="form-group blog_permission_box"></div>
                        <div class="form-group appointment_permission_box"></div>
                        <div class="form-group page_permission_box"></div>
                        <div class="form-group service_permission_box"></div>
                        <div class="form-group donation_permission_box"></div>
                        <div class="form-group job_permission_box"></div>
                        <div class="form-group event_permission_box"></div>
                        <div class="form-group knowledgebase_permission_box"></div>
                        <div class="form-group portfolio_permission_box"></div>
                        <div class="form-group storage_permission_box"></div>

                        <div class="theme_container d-none">
                            @include('landlord.admin.price-plan.partials.theme-markup.add-data')

                        </div>
                        <div class="payment_gateway_container d-inline-flex d-none">
                            @include('landlord.admin.price-plan.partials.payment-markup.add-data')
                        </div>

                        <x-fields.select name="type" class="package_type" title="{{__('Type')}}">
                            <option value="">{{__('Select')}}</option>
                            <option value="0">{{__('Monthly')}}</option>
                            <option value="1">{{__('Yearly')}}</option>
                            <option value="2">{{__('Lifetime')}}</option>
                            <option value="3">{{__('Custom')}}</option>
                        </x-fields.select>

                        <div class="d-flex justify-content-start">
                            <x-fields.switcher name="has_trial" label="{{__('Free Trial')}}"/>

                            <div class="form-group trial_date_box mx-4">
                                <label for="">{{__('Trial Days')}}</label>
                                <input type="number" class="form-control" name="trial_days" placeholder="Days..">
                            </div>
                        </div>
                    @endif

                    <div class="zero_price_container">
                        <x-fields.switcher name="zero_price" label="{{__('Zero Price')}}"/>
                    </div>

                    <div class="price_container">
                        <x-fields.input type="number" name="price" label="{{__('Price')}}"/>
                    </div>

                    <x-fields.select name="status" title="{{__('Status')}}">
                        <option value="1">{{__('Publish')}}</option>
                        <option value="0">{{__('Draft')}}</option>
                    </x-fields.select>

                    @if(!tenant())
                        <div class="iconbox-repeater-wrapper">
                            <div class="all-field-wrap">
                                <div class="form-group">
                                    <label for="faq">{{__('Faq Title')}}</label>
                                    <input type="text" name="faq[title][]" class="form-control"
                                           placeholder="{{__('faq title')}}">
                                </div>
                                <div class="form-group">
                                    <label for="faq_desc">{{__('Faq Description')}}</label>
                                    <textarea name="faq[description][]" class="form-control f_desc"
                                              placeholder="{{__('faq description')}}"></textarea>
                                </div>
                                <div class="action-wrap">
                                    <span class="add"><i class="las la-plus"></i></span>
                                    <span class="remove"><i class="las la-trash"></i></span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <button type="submit" class="btn btn-gradient-primary me-2 mt-5">{{__('Save Changes')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')


    <script src="{{global_asset('assets/common/js/select2.min.js')}}"></script>

    <script>
        $(document).ready(function() {
            $('.price_plan_themes').select2({
                placeholder: "Select Themes"
            });

        });
    </script>

    <script>
        //Date Picker
        flatpickr('.date', {
            enableTime: false,
            dateFormat: "d-m-Y",
            minDate: "today"
        });



        $(document).on('change', 'input[name=zero_price]', function (e) {
            let el = $(this);
            if (el.prop('checked') == true) {
                $('.price_container').fadeOut(500);
                $('.price_container').find('input[name="price"]').val('0');
            } else {
                $('.price_container').find('input[name="price"]').val('');
                $('.price_container').fadeIn(500);
            }
        });


        $(document).on('change', 'select[name="lang"]', function (e) {
            $(this).closest('form').trigger('submit');
            $('input[name="lang"]').val($(this).val());
        });

        $('.trial_date_box').hide();
        $(document).on('change', 'input[name=has_trial]', function (e) {
            $('.trial_date_box').toggle(500);
        });

        $(document).on('change', '.exampleCheck1', function (e) {
            let feature = $('.exampleCheck1').data('feature');
            let el = $(this).val();

            if (el == 'page') {
                var page = `<label for="">{{__('Page Create Permission')}}</label>
                            <input type="number" min="1" class="form-control" name="page_permission_feature" value="">
                            <small class="text-primary">{{__('If your leave this field empty then it will be set as unlimited...!')}}</small>`;

                if (el == 'page' && this.checked) {
                    $('.page_permission_box').append(page).hide();
                    $('.page_permission_box').slideDown();
                } else {
                    $('.page_permission_box').slideUp().html('');
                }
            }


            if (el == 'blog') {
                var blog = `<label for="">{{__('Blog Create Permission')}}</label>
                            <input type="number" min="1" class="form-control" name="blog_permission_feature" value="">
                            <small class="text-primary">{{__('If your leave this field empty then it will be set as unlimited...!')}}</small>`;

                if (el == 'blog' && this.checked) {
                    $('.blog_permission_box').append(blog).hide();
                    $('.blog_permission_box').slideDown();

                } else {
                    $('.blog_permission_box').slideUp().html('');
                }

            }

            if (el == 'appointment') {
                var appointment = `<label for="">{{__('Appointment Create Permission')}}</label>
                            <input type="number" min="1" class="form-control" name="appointment_permission_feature" value="">
                            <small class="text-primary">{{__('If your leave this field empty then it will be set as unlimited...!')}}</small>`;

                if (el == 'appointment' && this.checked) {
                    $('.appointment_permission_box').append(appointment).hide();
                    $('.appointment_permission_box').slideDown();

                } else {
                    $('.appointment_permission_box').slideUp().html('');
                }

            }


            if (el == 'service') {
                var service = `<label for="">{{__('Service Create Permission')}}</label>
                            <input type="number" min="1" class="form-control" name="service_permission_feature" value="">
                                   <small class="text-primary">{{__('If your leave this field empty then it will be set as unlimited...!')}}</small>`;

                if (el == 'service' && this.checked) {
                    $('.service_permission_box').append(service).hide();
                    $('.service_permission_box').slideDown();
                } else {
                    $('.service_permission_box').slideUp().html('');
                }
            }

            if (el == 'donation') {
                var donation = `<label for="">{{__('Donation Create Permission')}}</label>
                            <input type="number" min="1" class="form-control" name="donation_permission_feature"value="">
                            <small class="text-primary">{{__('If your leave this field empty then it will be set as unlimited...!')}}</small>`;

                if (el == 'donation' && this.checked) {
                    $('.donation_permission_box').append(donation).hide();
                    $('.donation_permission_box').slideDown();
                } else {
                    $('.donation_permission_box').slideUp().html('');
                }
            }

            if (el == 'job') {
                var job = `<label for="">{{__('Job Create Permission')}}</label>
                            <input type="number" min="1" class="form-control" name="job_permission_feature"value="">
                            <small class="text-primary">{{__('If your leave this field empty then it will be set as unlimited...!')}}</small>`;

                if (el == 'job' && this.checked) {
                    $('.job_permission_box').append(job).hide();
                    $('.job_permission_box').slideDown();
                } else {
                    $('.job_permission_box').slideUp().html('');
                }
            }

            if (el == 'event') {
                var event = `<label for="">{{__('Event Create Permission')}}</label>
                            <input type="number" min="1" class="form-control" name="event_permission_feature" value="">
                            <small class="text-primary">{{__('If your leave this field empty then it will be set as unlimited...!')}}</small>`;

                if (el == 'event' && this.checked) {
                    $('.event_permission_box').append(event).hide();
                    $('.event_permission_box').slideDown();
                } else {
                    $('.event_permission_box').slideUp().html('');
                }
            }

            if (el == 'knowledgebase') {
                var knowledgebase = `<label for="">{{__('Knowledgebase Create Permission')}}</label>
                            <input type="number" min="1" class="form-control" name="knowledgebase_permission_feature"value="">
                            <small class="text-primary">{{__('If your leave this field empty then it will be set as unlimited...!')}}</small>`;

                if (el == 'knowledgebase' && this.checked) {
                    $('.knowledgebase_permission_box').append(knowledgebase).hide();
                    $('.knowledgebase_permission_box').slideDown();
                } else {
                    $('.knowledgebase_permission_box').slideUp().html('');
                }
            }


            if (el == 'portfolio') {
                var portfolio = `<label for="">{{__('Portfolio Create Permission')}}</label>
                            <input type="number" min="1" class="form-control" name="portfolio_permission_feature"value="">
                            <small class="text-primary">{{__('If your leave this field empty then it will be set as unlimited...!')}}</small>`;

                if (el == 'portfolio' && this.checked) {
                    $('.portfolio_permission_box').append(portfolio).hide();
                    $('.portfolio_permission_box').slideDown();
                } else {
                    $('.portfolio_permission_box').slideUp().html('');
                }
            }

            if (el == 'storage') {
                var storage = `<label for="">{{__('Storage Manage Permission')}}</label>
                            <input type="number" min="1" class="form-control" name="storage_permission_feature"value="">
                             <small class="text-primary">Storage will count as per (MB)</small>
                            `;

                if (el == 'storage' && this.checked) {
                    $('.storage_permission_box').append(storage).hide();
                    $('.storage_permission_box').slideDown();
                } else {
                    $('.storage_permission_box').slideUp().html('');
                }
            }

            //eCommerce gateways coded
            if (el == 'eCommerce') {
                let ecom_container = $('.ecommerce_data');
                if (this.checked) {
                    $('.exampleCheck1[value=brand]').prop("checked", true);
                    ecom_container.removeClass('d-none');
                } else {
                    $('.exampleCheck1[value=brand]').prop("checked", false);
                    ecom_container.addClass('d-none');
                    ecom_container.find('input[type="text"]').val('');
                    ecom_container.find('input[type="checkbox"]').val(null);
                }
            }


            //payment gateways coded
            if (el == 'payment_gateways') {
                    let payment_container = $('.payment_gateway_container');
                if (el == 'payment_gateways' && this.checked) {
                    payment_container.removeClass('d-none');
                } else {
                    payment_container.addClass('d-none');
                    payment_container.find('input[type="checkbox"]').val(null);
                }
            }


            //theme coded
            if (el == 'themes') {
                let theme_container = $('.theme_container');
                if (el == 'themes' && this.checked) {
                    theme_container.removeClass('d-none');
                } else {
                    theme_container.addClass('d-none');
                  //  theme_container.find('.price_plan_themes"]').val(null);
                }
            }

        });


    </script>
    <x-repeater/>


    <script>

        $('.ecommerce_data').addClass('d-none');


        $('.product_section_child').hide();
        $(document).on('change', '.product_section_parent_switcher', function () {
            $('.product_section_child').toggle(500);
        });


        $('.inventory_section_child').hide();
        $(document).on('change', '.inventory_section_parent_switcher', function () {
            $('.inventory_section_child').toggle(500);
        });


        $('.campaign_section_child').hide();
        $(document).on('change', '.campaign_section_parent_switcher', function () {
            $('.campaign_section_child').toggle(500);
        });

    </script>
@endsection
