
        <h4 class="header-title text-white">{{__('Footer Variant')}}</h4>
        <div class="form-group">
            <input type="hidden" class="form-control" id="footer_variant" value="{{$page->footer_variant}}" name="footer_variant">
        </div>
        <div class="row">
            @for($i = 1; $i < 5; $i++)
                <div class="col-lg-6">
                    <div class="img-select img-select-footer @if($page->footer_variant == $i ) selected @endif">
                        <div class="img-wrap">
                            <img src="{{global_asset('assets/tenant/frontend/footer-variant/footer-0'.$i.'.png')}}" data-foot_id="0{{$i}}" alt="">
                        </div>
                    </div>
                </div>
            @endfor
        </div>

