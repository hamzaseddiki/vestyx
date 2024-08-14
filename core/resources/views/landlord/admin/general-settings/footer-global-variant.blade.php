@extends(route_prefix().'admin.admin-master')

@section('title')
    {{__('Footer Global Variant Settings')}}
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                <x-error-msg/>
                <x-flash-msg/>
                <div class="card">
                    <div class="card-body ">
                        <h4 class="header-title mb-4">{{__("Footer Global Vartiant Settings")}}</h4>
                        <form action="{{route(route_prefix().'admin.general.global.footer.settings')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card mt-5">
                                <div class="card-body bg-secondary">
                                    <div class="form-group">
                                        <input type="hidden" class="form-control" id="global_footer_variant" value="{{ get_static_option('global_footer_variant') }}" name="global_footer_variant">
                                    </div>
                                    <div class="row">
                                        @for($i = 1; $i < 5; $i++)
                                            <div class="col-lg-6 my-2">
                                                <div class="img-select selected">
                                                    <div class="img-wrap">
                                                        <img src="{{global_asset('assets/tenant/frontend/footer-variant/footer-0'.$i.'.png')}}" data-home_id="0{{$i}}" alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>

                            <button type="submit" id="update" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        (function($){
            "use strict";
            $(document).ready(function(){

                //For Footer
                var imgSelect = $('.img-select');
                var id = $('#global_footer_variant').val();
                imgSelect.removeClass('selected');
                $('img[data-home_id="'+id+'"]').parent().parent().addClass('selected');
                $(document).on('click','.img-select img',function (e) {
                    e.preventDefault();
                    imgSelect.removeClass('selected');
                    $(this).parent().parent().addClass('selected').siblings();
                    $('#global_footer_variant').val($(this).data('home_id'));
                })

            });
        }(jQuery));
    </script>
@endsection
