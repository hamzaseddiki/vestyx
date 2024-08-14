@php
    $user_lang = get_user_lang();
@endphp

@forelse($allDonation as $item)
    <div class="col-lg-4 col-md-6">
        <div class="singleCases mb-40">
            <div class="cases-img">
                <a href="{{route('tenant.frontend.donation.single',$item->slug)}}">
                    {!! render_image_markup_by_attachment_id($item->image) !!}
                </a>
            </div>
            <div class="casesCaption">
                <h3><a href="{{route('tenant.frontend.donation.single',$item->slug)}}"
                       class="tittle">{{ $item->getTranslation('title',$user_lang) }} </a></h3>
                <p class="pera">{!! \Illuminate\Support\Str::words(purify_html_raw($item->getTranslation('description',$user_lang)),18) !!}</p>

                @php
                    $deadline = $item->deadline;
                    $remaining_days = \Illuminate\Support\Carbon::parse($deadline)->diffInDays(now());
                    $count = \Modules\Donation\Entities\DonationPaymentLog::where('donation_id',$item->id)->count();
                @endphp

                <div class="prices mb-10 mt-10">
                    <span class="price">{{__('Goal')}}: {{amount_with_currency_symbol($item->amount)}}</span>
                    <span class="dates"> {{$remaining_days}} {{__('days left')}}</span>
                </div>
                <!-- Progress Bar -->
                <div class="singleProgres mb-15 donation-progress"
                     data-percentage="{{get_percentage($item->amount,$item->raised)}}">
                    <div class="bar-1"></div>
                </div>
                <!-- /End progress -->
                <div class="donateMember">

                    <div class="donateMemberList mb-10">
                        <span class="memberCounter2">{{$count}} {{__('Donated')}}</span>
                    </div>

                    <div class="btn-wrapper mb-10">
                        <a href="{{route('tenant.frontend.donation.payment',$item->id)}}"
                           class="cmn-btn4">{{__('Donate Now')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="col-lg-12">
        <div class="alert alert-warning donation_filter_top_message">
            <h4 class="text-center">{{__('No Donation Available In ') .' : ' .$searchTerm ?? ''}}</h4>
        </div>
    </div>
    @endforelse
    </div>
