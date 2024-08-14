@extends('landlord.admin.admin-master')
@section('title')
    {{__('Price Plan Settings')}}
@endsection

@section('style')
    <link rel="stylesheet" href="{{global_asset('assets/common/css/select2.min.css')}}">
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

                        <h4 class="header-title my-2">{{__("Price Plan Settings")}}</h4>
                        <form action="{{route('landlord.admin.price.plan.settings')}}" method="POST" enctype="multipart/form-data">
                          @csrf
                            @php
                                $fileds = [1 =>'One Day', 2 => 'Two Day', 3 => 'Three Day', 4 => 'Four Day', 5 => 'Five Day', 6 => 'Six Day', 7=> 'Seven Day'];
                            @endphp
                               <div class="form-group  mt-3">
                                   <label for="site_logo">{{__('Select How many days earlier expiration mail alert will be send')}}</label>
                                   <select name="package_expire_notify_mail_days[]" class="form-control expiration_dates" multiple="multiple">

                                       @foreach($fileds as $key => $field)
                                           @php
                                               $package_expire_notify_mail_days = get_static_option('package_expire_notify_mail_days');
                                               $decoded = json_decode($package_expire_notify_mail_days) ?? [];
                                           @endphp
                                         <option value="{{$key}}"
                                         @foreach($decoded as  $day)
                                                {{$day == $key ? 'selected' : ''}}
                                          @endforeach
                                         >{{__($field)}}</option>
                                       @endforeach
                                   </select>
                               </div>

                            <x-fields.input name="how_many_times_can_user_take_free_or_zero_package" value="{{get_static_option('how_many_times_can_user_take_free_or_zero_package')}}" label="{{__('How many times user can take free packages')}}"/>
                            <x-fields.switcher value="{{get_static_option_central('cancel_subscription_status')}}" name="cancel_subscription_status" label="{{__('Enable/Disable Cancel Subscription')}}"/>
                            <x-fields.switcher value="{{get_static_option_central('subscription_free_package_auto_approve_status')}}" name="subscription_free_package_auto_approve_status" label="{{__('Enable/Disable free package auto approve')}}"/>

                            <div class="form-group">
                                @php
                                    $themes = getAllThemeDataForAdmin();
                                    $languages = \App\Models\Language::all();
                                @endphp
                                <label for="default-theme">{{__('Default Theme Set')}}</label>
                                <select name="landlord_default_theme_set" id="default-theme" class="form-control">
                                    @foreach($themes as $theme)
                                        @php
                                            $lang_suffix = '_'.default_lang();
                                            $theme_name = get_static_option_central($theme->slug.'_theme_name'.$lang_suffix);
                                        @endphp
                                        <option value="{{$theme->slug}}" {{get_static_option_central('landlord_default_theme_set') == $theme->slug ? 'selected' : ''}}>{{ $theme_name }}</option>
                                    @endforeach
                                </select>

                                <small class="text-primary">{{__('If subscription plan have themes and user does not select any theme while ordering then this default theme will work..!')}}</small>
                            </div>


                            <div class="form-group">
                                <label for="">{{__('Default logo for tenant')}}</label><br>
                                <input type="file" class="btn btn-info btn-sm" name="landlord_default_tenant_admin_logo_set">

                                <div class="old my-3">
                                    <img src="{{global_asset('assets/tenant/seeder-demo-assets/logo1673525067.png')}}" alt="" style="height: 50px">
                                </div>
                            </div>



                            <x-fields.input name="landlord_default_tenant_admin_username_set" value="{{get_static_option_central('landlord_default_tenant_admin_username_set')}}"
                                            label="{{__('Default Admin Username Set')}}" info="{{__('If you dont set any username here, then default username will be set (super_admin)')}}"/>

                            <x-fields.switcher value="{{get_static_option_central('tenant_seeding_password_status')}}" name="tenant_seeding_password_status" extra="tenant_seeding_password_status" label="{{__('Enable/Disable Auto Generate Password')}}"/>

                            <x-fields.input name="landlord_default_tenant_admin_password_set" class="landlord_default_tenant_admin_password_set" extra="tenant_password_field"
                              value="{{get_static_option_central('landlord_default_tenant_admin_password_set')}}" label="{{__('Default Admin Password Set')}}" info="{{__('If you dont set any password here, then default password will be set (12345678)')}}"/>

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
    <x-media-upload.js/>
    <script src="{{global_asset('assets/common/js/select2.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('.expiration_dates').select2();

            let password_status = '{{ get_static_option_central('tenant_seeding_password_status') }}'

            if(password_status == 'on'){
                $('.tenant_password_field').hide();
            }else{
                $('.tenant_password_field').show();
            }

            $(document).on('change','.tenant_seeding_password_status',function(){
                let el = $(this);

                if(el.prop('checked') == true){
                    $('.landlord_default_tenant_admin_password_set').val('');
                    $('.tenant_password_field').hide();
                }else{
                    $('.tenant_password_field').show();
                }

            });
        });
    </script>
@endsection
