@php
    $route_name ='landlord';
@endphp
@extends($route_name.'.admin.admin-master')

@section('title')
    {{__('Account Settings')}}
@endsection

@section('style')
    <link rel="stylesheet" href="{{global_asset('assets/common/css/select2.min.css')}}">
@endsection

@section('content')
    <div class="col-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <x-admin.header-wrapper>
                <x-slot name="left">
                <h4 class="card-title mb-4"> {{__('Account Settings')}}</h4>
                </x-slot>

                <x-slot name="right">
                    <a href="{{route('landlord.admin.tenant')}}" class="btn btn-info btn-sm">{{__('All Users')}}</a>
                </x-slot>

                </x-admin.header-wrapper>
                <x-error-msg/>
                <x-flash-msg/>

                <form class="forms-sample"  action="{{route('landlord.admin.tenant.settings')}}" method="post">
                    @csrf
                    @php
                        $fileds = [ 1 =>'One Day', 2 => 'Two Day', 3 => 'Three Day', 4 => 'Four Day', 5 => 'Five Day', 6 => 'Six Day', 7=> 'Seven Day'];
                    @endphp
                    <div class="form-group  mt-3">
                        <label for="site_logo">{{__('Select How many days earlier after expiration  account deleted mail alert will be send')}}</label>
                        <select name="tenant_account_delete_notify_mail_days[]" class="form-control expiration_dates" multiple="multiple">
                            @foreach($fileds as $key => $field)
                                @php
                                    $account_expiry = get_static_option('tenant_account_delete_notify_mail_days');
                                    $decoded = json_decode($account_expiry) ?? [];
                                @endphp
                                <option value="{{$key}}"
                                @foreach($decoded as  $day)
                                    {{$day == $key ? 'selected' : ''}}
                                    @endforeach
                                >{{__($field)}}</option>
                            @endforeach

                        </select>
                    </div>

                    <x-fields.input type="number" min="1" name="account_remove_day_within_expiration" class="form-control"
                                    value="{{get_static_option('account_remove_day_within_expiration')}}" label="{{__('Account will be removed after expiration this Day')}}"/>

                    <button type="submit" class="btn btn-gradient-primary mt-4">{{__('Submit')}}</button>
                </form>


            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{global_asset('assets/common/js/select2.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('.expiration_dates').select2();
        });
    </script>
@endsection
