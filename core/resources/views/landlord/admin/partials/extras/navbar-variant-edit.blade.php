
        <h4 class="header-title text-white">{{__('Navbar Variant')}}</h4>
        <div class="form-group">
            <input type="hidden" class="form-control" id="navbar_variant" value="{{$page->navbar_variant}}" name="navbar_variant">
        </div>
        <div class="row">
            @for($i = 1; $i < 7; $i++)
                <div class="col-lg-6">
                    <div class="img-select img-select-nav @if($page->navbar_variant == $i ) selected @endif">
                        <div class="img-wrap">
                            <img src="{{global_asset('assets/tenant/frontend/navbar-variant/navbar-0'.$i.'.png')}}" data-nav_id="0{{$i}}" alt="">
                        </div>
                    </div>
                </div>
            @endfor
        </div>
