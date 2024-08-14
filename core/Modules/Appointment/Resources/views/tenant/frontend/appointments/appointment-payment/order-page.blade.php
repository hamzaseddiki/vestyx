@extends('tenant.frontend.frontend-page-master')

@section('title')
   {{__('Order')}} : {{ $order_details->getTranslation('title',get_user_lang())}}
@endsection

@section('page-title')
    {{__('Order')}} : {{ $order_details->getTranslation('title',get_user_lang())}}
@endsection

@section('style')
    <link href="{{ global_asset('assets/common/css/flatpickr.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <main>
        <!-- Banner area Starts -->
        <div class="barberShop_banner_area barberShop_banner__appointment barberShop-bg-main">
            <div class="barberShop_banner__appointment__shape"></div>
            <div class="container">
                <div class="row align-items-center justify-content-between">
                    <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-9">
                        <div class="barberShop_banner">
                            <div class="barberShop_banner__content">

                                @php
                                    $original_title = $order_details->title;
                                    $explode_title = explode(' ',$original_title) ?? [];
                                    $last_word = array_slice($explode_title,-1,1) ?? [];
                                    $first_words = array_diff($explode_title,$last_word) ?? [];
                                @endphp
                                <h2 class="barberShop_banner__title">{{ !empty($first_words) ? implode(' ',$first_words) : '' }}  <span class="barberShop_banner__titleColor">{{ !empty($last_word) ? implode(' ',$last_word) : '' }}</span></h2>
                                <p class="barberShop_banner__para mt-3">{!! \Illuminate\Support\Str::words(purify_html($order_details->description),45) !!}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-9">
                        <div class="barberShop_banner__appointment__right text-center">
                            <div class="barberShop_banner__appointment__right__thumb">
                                {!! render_image_markup_by_attachment_id($order_details->image) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('tenant.frontend.appointment.payment.page') }}" method="get">

            @if(count($order_details->sub_appointments) > 0)
                <section class="select_service_area padding-top-100 padding-bottom-50">
                <div class="container">
                    <div class="barberShop_sectionTitle">
                        {!! get_modified_title_barber_two(get_static_option('appointment_order_page_sub_appointment_'.get_user_lang().'_heading')) !!}
                    </div>

                    <div class="row g-4 mt-4">
                        @foreach($order_details->sub_appointments ?? [] as $key=> $sub_appointment)
                            <div class="col-md-6 col-sm-12">
                                <label class="barberShop__pricing barberShop-section-bg" for="{{$key}}">

                                    <div class="barberShop__pricing__flex">
                                        <div class="barberShop_service__single__icon">
                                            <a href="{{ route('tenant.frontend.sub.appointment.single',$sub_appointment->slug) }}">
                                                {!! render_image_markup_by_attachment_id($sub_appointment->image) !!}
                                            </a>
                                        </div>
                                        <div class="barberShop__pricing__contents">
                                            <a href="{{ route('tenant.frontend.sub.appointment.single',$sub_appointment->slug) }}">
                                                 <h4 class="barberShop__pricing__contents__title">{{ $sub_appointment->title }}</h4>
                                            </a>
                                            <p class="barberShop__pricing__contents__para mt-2">
                                                <span class="barberShop__pricing__contents__para__price">{{ amount_with_currency_symbol($sub_appointment->price) }}</span> - {{ \App\Enums\AppointmentEnums::getText($sub_appointment->person ?? 1) }}</p>
                                        </div>
                                        <div class="barberShop__pricing__btn">
                                            <div class="checkBox">
                                                <input type="checkbox" class="checkBox__input" id="{{$key}}" name="sub_appointment_ids[]" value="{{ $sub_appointment->id }}">
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
            @endif

            @php
                $padding_condition = count($order_details->sub_appointments) > 0 ? '50' : '100';
            @endphp
            <section class="barberShop_schedule_area padding-top-{{$padding_condition}} padding-bottom-100">
                <div class="container">
                    <div class="barberShop_sectionTitle">
                        {!! get_modified_title_barber_two(get_static_option('appointment_order_page_date_section_'.get_user_lang().'_heading')) !!}
                    </div>
                    <div class="row g-4 mt-4">
                        <x-error-msg/>
                        <div class="col-lg-12">
                            <div class="barberShop_schedule__flex">
                                <div class="barberShop_schedule">
                                    <div class="barberShop_schedule__calendar">
                                        <input type="hidden" name="appointment_date" class="input_calendar d-none appointment_date">
                                    </div>
                                    <div class="btn-wrapper mt-4">
                                        <button type="submit" class="barberShop_cmn_btn btn_bg_1">{{get_static_option('appointment_order_page_date_selection_button_'.get_user_lang().'_text')}}</button>
                                    </div>
                                </div>
                                <div class="barberShop_scheduleTabs">
                                    <ul class="tabs barberShop_scheduleTabs__list">
                                        @foreach($day_types as $key=> $type)
                                            <li data-tab="{{$type->id}}" data-id="{{ $type->id }}" class="{{ $loop->index == 0 ? 'active' : '' }}">{{ $type->title }}</li>
                                        @endforeach
                                    </ul>
                                </div>


                                {{--Passing hidden data--}}
                                <input type="hidden" name="appointment_id" value="{{$order_details->id}}">
                                <input type="hidden" name="schedule_time" class="schedule_time">

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </form>

    </main>
@endsection


@section('scripts')
    <script src="{{global_asset('assets/tenant/frontend/themes/js/flatpickr.js')}}"></script>
    <script>

        $(document).ready(function(){

            //For selected one schedule date
            $(document).on('click','.barberShop_scheduleTabs__time', function(){
                let el = $(this);
                el.find('.barberShop_scheduleTabs__time__item').addClass('active');
                el.siblings().find('.barberShop_scheduleTabs__time__item').removeClass('active');
            });

            //For selected one schedule time data pass
            $(document).on('click','.barberShop_scheduleTabs__contents .tab_content_item .barberShop_scheduleTabs__time', function(){
                let time = $(this).data('time');
                $('.barberShop_schedule__flex').find('.schedule_time').val(time);
            });


            //Select date method
             $(document).on('change','.appointment_date',function(){
                let selected_date = $('.appointment_date').val();
                let el = $(this);
                let tab_id = el.data('id');

                 $.ajax({
                     url: '{{ route('tenant.frontend.appointment.schedule.via.time.data.ajax') }}',
                     type: 'get',
                     data: {
                         date:selected_date,
                         tab_id:tab_id
                     },
                     beforeSend: function (){
                         CustomLoader.start();
                     },
                     success: function (data){
                         $('.barberShop_scheduleTabs').html(data);
                         CustomLoader.end();
                     },
                     error: function (error){
                         console.log(error)
                     }
                 })
            });

        });
    </script>
@endsection


