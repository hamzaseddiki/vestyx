@extends('tenant.admin.admin-master')
@section('title')
    {{__('Shipping Zones')}}
@endsection
@section('style')
    <x-datatable.css />
    <x-bulk-action.css />
    <link rel="stylesheet" href="{{global_asset('assets/landlord/admin/css/nice-select.css')}}">
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="margin-top-40">
                   <x-flash-msg/>
                    <x-error-msg/>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">{{__('All Shipping Zones')}}</h4>
                        @can('shipping-zone-delete')
                        <x-bulk-action.dropdown />
                        @endcan
                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                    <x-bulk-action.th />
                                    <th>{{__('ID')}}</th>
                                    <th>{{__('Name')}}</th>
                                    <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                    @foreach($all_zones as $zone)
                                    <tr>
                                        <x-bulk-action.td :id="$zone->id" />
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$zone->name}}</td>
                                        <td>
                                            @can('shipping-zone-delete')
                                            <x-table.btn.swal.delete :route="route('tenant.admin.shipping.zone.delete', $zone->id)" />
                                            @endcan
                                            @can('shipping-zone-edit')
                                            <a href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#shipping_zone_edit_modal"
                                                class="btn btn-primary btn-xs mb-3 mr-1 shipping_zone_edit_btn"
                                                data-id="{{$zone->id}}"
                                                data-name="{{$zone->name}}"
                                                data-country="{{optional($zone->region)->country}}"
                                                data-state="{{optional($zone->region)->state}}"
                                                data-bs-toggle="tooltip"
                                                data-placement="right"
                                                title="Edit"
                                            >
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @can('shipping-zone-create')
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__('Add New Shipping Zone')}}</h4>
                        <form action="{{route('tenant.admin.shipping.zone.new')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="name">{{__('Name')}}</label>
                                <input type="text" class="form-control"  id="name" name="name" placeholder="{{__('Name')}}">
                            </div>
                            <div class="form-group" id="country_id_wrapper">
                                <label for="country">{{ __('Country') }}</label>
                                <select name="country[]" id="country" class="form-control nice-select wide" multiple>
                                    <option value="">{{ __('Select Country') }}</option>
                                    @foreach ($all_countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <small class="text-warning">{{__('First select all the desired countries.')}}</small>

                            <div class="form-group mt-4">
                                <label for="state">{{ __('State') }}</label>
                                <select name="state[]" id="state" class="form-control nice-select wide" multiple>
                                    <option value="">{{ __('Select State') }}</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Add New')}}</button>
                        </form>
                    </div>
                </div>
            </div>
            @endcan
        </div>
    </div>
    @can('shipping-zone-edit')
    <div class="modal fade" id="shipping_zone_edit_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Update Shipping Zone')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                </div>
                <form action="{{route('tenant.admin.shipping.zone.update')}}"  method="post">
                    <input type="hidden" name="id" id="shipping_zone_id">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="edit_name">{{__('Name')}}</label>
                            <input type="text" class="form-control"  id="edit_name" name="name" placeholder="{{__('Name')}}">
                        </div>
                        <div class="form-group">
                            <label for="">{{__('Country')}}</label>
                            <select name="country[]" id="edit_country" class="form-control nice-select wide" multiple>
                                 <option value="">{{ __('Select Country') }}</option>
                                @foreach ($all_countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-warning">{{__('First select all the desired countries.')}}</small>

                        <div class="form-group mt-4">
                            <label for="">{{__('State')}}</label>
                            <select name="state[]" id="edit_state_select" class="form-control nice-select wide" multiple>
                                 <option value="">{{ __('Select State') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save Change')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan
@endsection
@section('scripts')
    <script src="{{global_asset('assets/landlord/admin/js/jquery.nice-select.min.js')}}"></script>
    <x-datatable.js />
    <x-table.btn.swal.js />
    <x-bulk-action.js :route="route('tenant.admin.shipping.zone.bulk.action')" />

    <script>
    $(document).ready(function () {

        if ($('.nice-select').length) {
            $('.nice-select').niceSelect();
        }

        //todo: load state data based on selected country

        $('#country').on('change', function () {
            let id = $(this).val();
            $.post('{{ route("tenant.admin.state.by.multiple.country") }}', {id:id,_token: "{{csrf_token()}}"}).then(function (data) {
                $('#state').html('');
                for (const state of data) {
                    $('#state').append('<option value="'+state.id+'">'+state.name+'</option>');
                }
                $('.nice-select').niceSelect("update");
            });

        });

        $(document).on('click','.shipping_zone_edit_btn',function(){
            let el = $(this);
            let id = el.data('id');
            let name = el.data('name');
            let country = el.data('country');
            let selectedState = el.data('state');
            let modal = $('#shipping_zone_edit_modal');

            modal.find('#shipping_zone_id').val(id);
            modal.find('#edit_name').val(name);
            modal.find('#edit_country').val(country)

            $('#edit_state_select').html('');
            $.post('{{ route("tenant.admin.state.by.multiple.country") }}', {id:country,_token: "{{csrf_token()}}"}).then(function (data) {
                for (const state of data) {
                    let selected =  selectedState != null && selectedState.includes(state.id.toString()) ? 'selected' : '';
                    $('#edit_state_select').append('<option '+selected+' value="'+state.id+'">'+state.name+'</option>');
                }
                $('.nice-select').niceSelect("update");
            });
        });
    });
    </script>
@endsection
