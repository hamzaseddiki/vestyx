@extends(route_prefix().'admin.admin-master')
@section('title') {{__('Login Register Settings')}} @endsection

@section('style')
    <x-media-upload.css/>
@endsection

@section('content')

    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-5">{{__('Login Register Settings')}}</h4>
                <x-error-msg/>
                <x-flash-msg/>
                <form class="forms-sample" method="post" action="{{route(route_prefix().'admin.login.register.settings')}}" enctype="multipart/form-data">
                    @csrf
                    <x-lang-tab>
                        @foreach(\App\Facades\GlobalLanguage::all_languages() as $lang)
                            @php $slug = $lang->slug; @endphp
                            <x-slot :name="$slug">
                                <x-fields.input type="text" value="{{get_static_option('landlord_user_login_'.$lang->slug.'_title')}}" name="landlord_user_login_{{ $lang->slug}}_title" label="{{__('Login Title')}}" info="{{__('To show the highlighted text, place your word between this code {h}YourText{/h]')}}"/>
                                <x-fields.input type="text" value="{{get_static_option('landlord_user_register_'.$lang->slug.'_title')}}" name="landlord_user_register_{{ $lang->slug}}_title" label="{{__('Register Title')}}" info="{{__('To show the highlighted text, place your word between this code {h}YourText{/h]')}}"/>
                                <br>
                                <x-fields.input type="text" value="{{get_static_option('landlord_user_register_feature_'.$lang->slug.'_title_one')}}" name="landlord_user_register_feature_{{ $lang->slug}}_title_one" label="{{__('Feature Title One')}}"/>
                                <x-fields.input type="text" value="{{get_static_option('landlord_user_register_feature_'.$lang->slug.'_description_one')}}" name="landlord_user_register_feature_{{ $lang->slug}}_description_one" label="{{__('Feature Description One')}}"/>

                                <x-fields.input type="text" value="{{get_static_option('landlord_user_register_feature_'.$lang->slug.'_title_two')}}" name="landlord_user_register_feature_{{ $lang->slug}}_title_two" label="{{__('Feature Title Two')}}"/>
                                <x-fields.input type="text" value="{{get_static_option('landlord_user_register_feature_'.$lang->slug.'_description_two')}}" name="landlord_user_register_feature_{{ $lang->slug}}_description_two" label="{{__('Feature Description Two')}}"/>

                                <x-fields.input type="text" value="{{get_static_option('landlord_user_register_feature_'.$lang->slug.'_title_three')}}" name="landlord_user_register_feature_{{ $lang->slug}}_title_three" label="{{__('Feature Title Three')}}"/>
                                <x-fields.input type="text" value="{{get_static_option('landlord_user_register_feature_'.$lang->slug.'_description_three')}}" name="landlord_user_register_feature_{{ $lang->slug}}_description_three" label="{{__('Feature Description Three')}}"/>
                            </x-slot>
                        @endforeach
                    </x-lang-tab>

                    <x-fields.media-upload name="landlord_user_register_feature_image_one" title="{{__('Register Feature Image One')}}"/>
                    <x-fields.media-upload name="landlord_user_register_feature_image_two" title="{{__('Register Feature Image Two')}}"/>
                    <x-fields.media-upload name="landlord_user_register_feature_image_three" title="{{__('Register Feature Image Three')}}"/>


                    <x-fields.switcher name="landlord_frontend_login_facebook_show_hide" value="{{get_static_option('landlord_frontend_login_facebook_show_hide')}}"  label="{{__('Enable/Disable Facebook Login')}}"/>
                    <x-fields.switcher name="landlord_frontend_login_google_show_hide" value="{{get_static_option('landlord_frontend_login_google_show_hide')}}"  label="{{__('Enable/Disable Google Login')}}"/>
                    <x-fields.switcher name="landlord_frontend_register_feature_show_hide" value="{{get_static_option('landlord_frontend_register_feature_show_hide')}}"  label="{{__('Enable/Disable Regsiter Features')}}"/>

                    <button type="submit" class="btn btn-gradient-primary me-2">{{__('Save Changes')}}</button>
                </form>
            </div>
        </div>
    </div>
    <x-media-upload.markup/>
@endsection


@section('scripts')
    <x-media-upload.js/>
@endsection
