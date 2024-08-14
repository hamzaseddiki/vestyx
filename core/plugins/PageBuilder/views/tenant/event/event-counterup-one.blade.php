<div class="countDownArea section-padding2">
    <div class="container">
        <div class="row justify-content-center ">
            <div class="col-lg-12 col-md-12">
                <div class="countDown-wrapper">
                    <div class="row justify-content-between">
                        @foreach($data['repeater_data']['repeater_title_'.get_user_lang()] ?? [] as $key => $title)
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                <div class="singleCounter mb-30 wow ladeInRight" data-wow-delay="0.1s">
                                    <div class="counting">
                                        <span class="count odometer" data-odometer-final="{{$data['repeater_data']['repeater_number_'.get_user_lang()][$key] ?? 0}}"></span>
                                    </div>
                                    <div class="countPera">
                                        <p class="pera">{{$title ?? ''}}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
