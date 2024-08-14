@php
    $user_lang = get_user_lang();
@endphp

@forelse($allJob as $item)
    <div class="col-xl-{{$col ?? '4'}} col-lg-6 col-md-6">
        <div class="singleJob mb-24">
            <div class="company">
                <!-- Clients -->
                <div class="companyCap">
                    <div class="companyLogo">
                        <a href="{{route('tenant.frontend.job.single',$item->slug)}}">
                               {!! render_image_markup_by_attachment_id($item->image) !!}
                        </a>
                    </div>
                    <div class="companyTitle">
                        <span class="title"> {{ \Modules\Job\Enums\WorkingTypeEnum::getText($item->working_type) }}</span>
                        <p class="pera">{{ $item->getTranslation('job_location',$user_lang) }}</p>
                    </div>
                </div>
                <div class="jobCat">
                    <a href="{{ route('tenant.frontend.job.category',['id' => $item->category_id, 'any'=> \Illuminate\Support\Str::slug($item->category?->getTranslation('title',get_user_lang()))]) }}">
                        <span class="title">{!! $item->category?->getTranslation('title',$user_lang) !!}</span>
                    </a>

                </div>
            </div>
            <div class="productCap">
                <h5><a href="{{route('tenant.frontend.job.single',$item->slug)}}" class="title">{{ $item->getTranslation('title',$user_lang) }}</a></h5>
                <p class="pera">{!! Str::words(purify_html($item->getTranslation('description',$user_lang)),15) !!}</p>
            </div>
            <div class="d-flex align-items-center flex-wrap justify-content-between">
                <div class="productPrice">
                    <strong class="regularPrice">{{amount_with_currency_symbol($item->salary_offer)}}</strong>
                    <span class="offerPrice"> /{{__('Month')}}</span>
                </div>
                <div class="btn-wrapper mb-10">
                    <a href="{{route('tenant.frontend.job.payment',$item->slug)}}" class="cmn-btn3">{{__('Apply Now')}}</a>
                </div>
            </div>
        </div>
    </div>
    @empty
        <div class="col-lg-12">
            <div class="alert alert-warning event_filter_top_message">
                <h4 class="text-center">{!! __('No Job Available In ') .' : ' . purify_html($searchTerm) ?? '' !!}</h4>
            </div>
        </div>
    @endforelse
