
        <h4 class="header-title text-white">{{__('Navbar Variant')}}</h4>
        <div class="form-group">
            <input type="hidden" class="form-control" id="navbar_variant" value="01" name="navbar_variant">
        </div>
        <div class="row">
            @for($i = 1; $i < 7; $i++)
                <div class="col-lg-6 col-md-6 ">
                    <div class="img-select selected">
                        <div class="img-wrap">
                            <img src="{{global_asset('assets/tenant/frontend/navbar-variant/navbar-0'.$i.'.png')}}" data-home_id="0{{$i}}" alt="">
                        </div>
                    </div>
                </div>
            @endfor
        </div>


