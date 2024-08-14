@extends('landlord.admin.admin-master')

@section('title')
    {{__('Custom Domain  Settings')}}
@endsection

@section('style')
    <x-summernote.css/>
    <x-media-upload.css/>
@endsection

@section('content')

    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                <x-error-msg/>
                <x-flash-msg/>
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title my-2">{{__("Custom Domain Settings")}}</h4>
                        <form action="{{route('landlord.admin.custom.domain.requests.settings')}}" method="POST" enctype="multipart/form-data">
                            @csrf

                    <x-lang-tab>
                        @foreach(\App\Facades\GlobalLanguage::all_languages(1) as $lang)
                            @php $slug = $lang->slug; @endphp
                            <x-slot :name="$slug">

                            <div class="form-group  mt-3">
                                <label>{{__('Title')}}</label>
                                <input type="text" name="custom_domain_settings_{{$lang->slug}}_title"class="form-control" value="{{get_static_option_central('custom_domain_settings_'.$lang->slug.'_title')}}">
                            </div>

                            <div class="form-group">
                                <label>{{__('Description')}}</label>
                                <textarea name="custom_domain_settings_{{$lang->slug}}_description" class="form-control" cols="30" rows="8">{{get_static_option_central('custom_domain_settings_'.$lang->slug.'_description')}}</textarea>
                            </div>

                            <div class="form-group  mt-3">
                                <label>{{__('Table Info Data Title')}}</label>
                                <input type="text" name="custom_domain_table_{{$lang->slug}}_title" class="form-control" value="{{get_static_option_central('custom_domain_table_'.$lang->slug.'_title')}}">
                            </div>

                            </x-slot>
                        @endforeach
                    </x-lang-tab>

                            <div class="row">
                                <div class="form-group col-md-3 mt-3">
                                    <label>{{__('Type One')}}</label>
                                    <input type="text" readonly class="form-control" value="CNAME Record">
                                </div>
                                <div class="form-group col-md-3 mt-3">
                                    <label>{{__('Host One')}}</label>
                                    <input type="text" readonly class="form-control" value="www">
                                </div>

                                <div class="form-group col-md-3 mt-3">
                                    <label>{{__('Value One')}}</label>
                                    <input type="text" readonly class="form-control" value="{{env('CENTRAL_DOMAIN')}}">
                                </div>

                                <div class="form-group col-md-3 mt-3">
                                    <label>{{__('TTL One')}}</label>
                                    <input type="text" readonly class="form-control" value="Automatic">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-3 mt-3">
                                    <label>{{__('Type Two')}}</label>
                                    <input type="text" readonly class="form-control" value="CNAME Record">
                                </div>
                                <div class="form-group col-md-3 mt-3">
                                    <label>{{__('Host Two')}}</label>
                                    <input type="text" readonly class="form-control" value="@">
                                </div>

                                <div class="form-group col-md-3 mt-3">
                                    <label>{{__('Value Two')}}</label>
                                    <input type="text" readonly class="form-control" value="{{env('CENTRAL_DOMAIN')}}">
                                </div>
                                <div class="form-group col-md-3 mt-3">
                                    <label>{{__('TTL Two')}}</label>
                                    <input type="text" readonly class="form-control" value="Automatic">
                                </div>

                                <div class="form-group col-md-3 mt-3">
                                    <label>{{__('IP Address')}}</label>
                                    <input type="text" readonly class="form-control" value="{{$_SERVER['SERVER_ADDR']}}">
                                </div>

                            </div>


                            <x-fields.media-upload name="custom_domain_settings_show_image" title="{{__('Screen Shot Example')}}" dimentions="{{__('100*100')}}"/>

                            <button type="submit" id="update" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media-upload.markup/>
@endsection

@section('scripts')
    <x-summernote.js/>
    <x-media-upload.js/>
@endsection
