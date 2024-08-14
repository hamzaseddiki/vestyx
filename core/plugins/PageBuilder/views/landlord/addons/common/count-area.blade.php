@php
    $current_lang = \App\Facades\GlobalLanguage::user_lang_slug();
@endphp

<div class="countDown" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="countDownWrapper">
            <div class="row">
                @foreach($data['repeater_data']['repeater_title_'.$current_lang] ?? [] as $key=> $ti)
                    @php
                        $title = $ti ?? '';
                        $number = (int) $data['repeater_data']['repeater_number_'.$current_lang][$key] ?? '';
                        $symbol =  $data['repeater_data']['repeater_symbol_'.$current_lang][$key] ?? '';
                    @endphp
                    <div class="col-xl-3 col-lg-6 col-md-4 col-sm-6">
                        <div class="single mb-24 wow fadeInLeft" data-wow-delay="0.0s" >
                            <div class="single-counter">
                                <span class="counter odometer" data-count="{{$number}}"></span>

                                <p class="icon">{{$symbol}}</p>
                            </div>
                            <div class="pera-count">
                                <p class="pera">{{$title}}</p>
                            </div>
                        </div>
                    </div>
                 @endforeach
            </div>
        </div>
    </div>
</div>
