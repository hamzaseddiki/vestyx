@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('Country')}}
@endsection
@section('site-title')
    {{__('Country')}}
@endsection
@section('style')
    <x-datatable.css/>
    <x-bulk-action.css/>
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <x-error-msg/>
        <x-flash-msg/>
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="header-wrap d-flex flex-wrap justify-content-between">
                            <h4 class="header-title mb-4">{{__('All Countries')}}</h4>

                            @if(!tenant())
                                <a href="{{ route(route_prefix().'admin.country.demo.all.country.insert') }}" class="btn btn-info btn-sm">{{__('Insert all countries')}}</a>
                            @endif

                            <button class="btn btn-sm btn-info"
                                    data-bs-toggle="modal"
                                    data-bs-target="#country_new_modal">{{__('Add Country')}}
                            </button>
                        </div>
                        @can('country-delete')
                            <x-bulk-action.dropdown/>
                        @endcan

                        <div class="table-wrap table-responsive">
                            <table class="table table-default table-striped table-bordered">
                                <thead>
                                <x-bulk-action.th/>
                                <th>{{__('ID')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                @foreach($all_countries as $country)
                                    <tr>
                                        <x-bulk-action.td :id="$country->id"/>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$country->name}}</td>
                                        <td>
                                            <x-status-span :status="$country->status"/>
                                        </td>
                                        <td>
                                            @can('country-delete')
                                                <x-table.btn.swal.delete
                                                        :route="route(route_prefix().'admin.country.delete', $country->id)"/>
                                            @endcan
                                            @can('country-edit')
                                                <a href="#"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#country_edit_modal"
                                                   class="btn btn-primary btn-sm btn-xs mb-3 mr-1 country_edit_btn"
                                                   data-id="{{$country->id}}"
                                                   data-name="{{$country->name}}"
                                                   data-status="{{$country->status}}"
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
        </div>
    </div>
    @can('country-edit')
        <div class="modal fade" id="country_edit_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('Update country')}}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="{{route(route_prefix().'admin.country.update')}}" method="post">
                        <input type="hidden" name="id" id="country_id">
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <label for="edit_name">{{__('Name')}}</label>
                                <input type="text" class="form-control" id="edit_name" name="name"
                                       placeholder="{{__('Name')}}">
                            </div>
                            <div class="form-group">
                                <label for="edit_status">{{__('Status')}}</label>
                                <select name="status" class="form-control" id="edit_status">
                                    <option value="publish">{{__("Publish")}}</option>
                                    <option value="draft">{{__("Draft")}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-secondary"
                                    data-bs-dismiss="modal">{{__('Close')}}</button>
                            <button type="submit" class="btn btn-primary">{{__('Save Change')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan


    @can('country-create')
        <div class="modal fade" id="country_new_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('Add new country')}}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <div class="model-body p-4">
                        <form action="{{route(route_prefix().'admin.country.new')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="name">{{__('Name')}}</label>
                                <input type="text" class="form-control" id="name" name="name"
                                       placeholder="{{__('Name')}}">
                            </div>
                            <div class="form-group">
                                <label for="status">{{__('Status')}}</label>
                                <select name="status" class="form-control" id="status">
                                    <option value="publish">{{__("Publish")}}</option>
                                    <option value="draft">{{__("Draft")}}</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Add New')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection
@section('scripts')
    <x-datatable.js/>
    <x-table.btn.swal.js/>
    <x-bulk-action.js :route="route(route_prefix().'admin.country.bulk.action')"/>

    <script>
        $(document).ready(function () {
            $(document).on('click', '.country_edit_btn', function () {
                let el = $(this);
                let id = el.data('id');
                let name = el.data('name');
                let status = el.data('status');
                let modal = $('#country_edit_modal');

                modal.find('#country_id').val(id);
                modal.find('#edit_status option[value="' + status + '"]').attr('selected', true);
                modal.find('#edit_name').val(name);
            });
        });
    </script>

@endsection
