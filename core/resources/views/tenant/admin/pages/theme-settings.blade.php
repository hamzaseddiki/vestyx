@extends(route_prefix().'admin.admin-master')

@section('title')
    {{__('Theme Settings')}}
@endsection

@section('style')
    <x-media-upload.css/>
@endsection

@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <x-error-msg/>
                <x-flash-msg/>
                <h4 class="card-title mb-5">{{__('Theme Settings')}}</h4>
                @php
                    $package = tenant()->payment_log()->first()?->package()->first() ?? [];

                   $data_base_features = $package->plan_features?->pluck('feature_name')->toArray() ?? [];

                   $themes = [];
                   foreach ($data_base_features as $feature){
                       if(preg_match('/theme-/',$feature)){
                         $themes[] = str_replace('theme-','',$feature);
                       }
                   }
                @endphp
                <div class="info mb-3">
                    <small class="text-primary">{{__('(From now you can set theme with demo data or not with demo data, In order to do this you need to click to the theme image first..!)')}}</small>
                </div>

                <form action="" method="post" class="theme_form">
                    @csrf

                    <div class="row">
                        <div class="row">
                            @foreach($themes as $theme)
                                @php
                                    $theme_image = loadScreenshot($theme);
                                    $custom_theme_image = get_static_option_central($theme.'_theme_image');

                                    $filePath =  theme_path($theme).'/theme.json';
                                    if (file_exists($filePath) && !is_dir($filePath))
                                    {
                                        $theme_file = json_decode(file_get_contents($filePath), false);
                                        if (!empty($theme_file))
                                        {
                                           if(!$theme_file->status)
                                           {
                                               continue;
                                           }
                                        }
                                    }
                                @endphp
                                <div class="col-lg-4">
                                    <div class=" mb-4 img-select img-select-theme @if(get_static_option('tenant_default_theme') == $theme ) selected @endif">
                                        <div class="img-wrap">
                                            {{-- <img data-bs-toggle="modal" data-bs-target="#theme_import_type_modal" src="{{global_asset('assets/tenant/frontend/img/gallery/'.$theme.'.jpg')}}" data-theme="{{$theme}}" alt=""> --}}
                                            <img data-bs-toggle="modal" data-bs-target="#theme_import_type_modal" src="{{!empty($custom_theme_image) ? $custom_theme_image : $theme_image}}" data-theme="{{$theme}}" alt="">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <div class="modal fade" id="theme_import_type_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{__('Theme Import Type')}}</h5>
                </div>

                <div class="msg px-2">
                    <small class="text-primary">{{__('You can set theme by demo imported data or you can set with no imported data, If you set only (theme set) then you have to add all the addon for completing your home page from page builder section also set have to set home page from general settings/page settings, if its not previously set by you..!')}}</small>
                </div>

                <form action="{{ route('tenant.admin.theme') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" class="form-control" id="tenant_default_theme" value="{{ get_static_option('tenant_default_theme') }}" name="tenant_default_theme">

                    <div class="modal-body">
                        @csrf
                        <x-fields.select name="theme_setting_type" class="theme_setting_type" title="{{__('Theme setting type')}}">
                            <option value="">{{__('Select Type')}}</option>
                            <option value="set_theme">{{__('Set without data')}}</option>
                            <option value="set_theme_with_demo_data">{{__('Set theme with demo or old data')}}</option>
                        </x-fields.select>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Set Default')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <x-media-upload.markup/>
@endsection

@section('scripts')
    <x-media-upload.js/>

    <script>
        $(document).ready(function(){
            var imgSelect2 = $('.img-select-theme');
            var theme_name = $('#tenant_default_theme').val();
            imgSelect2.removeClass('selected');
            $('img[data-theme="'+theme_name+'"]').parent().parent().addClass('selected');
            $(document).on('click','.img-select-theme img',function (e) {
                e.preventDefault();
                imgSelect2.removeClass('selected');
                $(this).parent().parent().addClass('selected').siblings();
                $('#tenant_default_theme').val($(this).data('theme'));
            })

            $(document).on('change','.theme_setting_type',function () {
                let value =  $(this).val();
                let theme_form = $('.theme_form');
                theme_form.find('.import_type').val(value);
            })
        });



    </script>
@endsection
