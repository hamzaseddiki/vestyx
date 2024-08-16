@extends(route_prefix() . 'admin.admin-master')
@section('title')
    {{ __('ChargilyPay Payment Gateway Settings') }}
@endsection
@section('content')
    <div class="col-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">{{ __('ChargilyPay Payment Gateway Settings') }}</h4>
                <x-error-msg />
                <x-flash-msg />
                <form class="forms-sample" method="post" action="{{ route('sitepaymentgateway.' . route_prefix() . 'admin.settings') }}">
                    @csrf
                    <x-fields.input name="public" type="text" value="{{ $gateway->credentials->public }}" label="{{ __('Pulic Key') }}" />
                    <x-fields.input name="secret" type="text" value="{{ $gateway->credentials->secret }}" label="{{ __('Secret Key') }}" />
                    <x-fields.input name="dzd_rate" type="text" value="{{ $gateway->credentials->dzd_rate }}" label="{{ __('DZD to USD rate') }}" />

                    <x-fields.switcher name="chargilypay_status" value="{{ $sitesways->status }}" label="{{ __('ChargilyPay Enable/Disable Landlord & Tenant Both') }}" />
                    @if (is_null(tenant()))
                        <x-fields.switcher name="chargilypay_landlord_status" value="{{ $sitesways->admin_settings->show_admin_landlord }}" label="{{ __('ChargilyPay Enable/Disable Landlord Websites') }}" />
                        <x-fields.switcher name="chargilypay_tenant_status" value="{{ $sitesways->admin_settings->show_admin_tenant }}" label="{{ __('ChargilyPay Enable/Disable Tenant Websites') }}" />
                    @endif

                    <button class="btn btn-gradient-primary me-2 mt-5" type="submit">{{ __('Save Changes') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
