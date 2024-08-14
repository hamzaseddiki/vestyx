<div class="donationList">
    <div class="small-tittle mb-40">
        <h3 class="tittle">{{__('People have made a Donation')}}</h3>
    </div>

    @foreach($allDonors as $data)
          <div class="singleDonation">
            <div class="singleDonation">
                <!-- Left -->
                <div class="donationPostUser mb-15">
                    <img src="{{global_asset('assets/tenant/frontend/themes/img/gallery/donation-commentsUser1.jpg')}}" alt="images">
                    <div class="cap">
                        <a href="#" class="tittle">{{$data->name}}</a>
                        <p>{{ amount_with_currency_symbol($data->amount) }}</p>
                    </div>
                </div>
                <!-- Right -->
                <div class="donationPostUser mb-15">
                    <div class="cap">
                        <a href="#" class="tittle"></a>
                        <p>{{ $data->created_at?->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

