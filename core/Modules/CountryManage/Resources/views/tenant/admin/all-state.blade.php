@extends('tenant.admin.admin-master')
@section('title')
    {{__('State')}}
@endsection
@section('site-title')
    {{__('State')}}
@endsection
@section('style')
    <x-datatable.css/>
    <x-bulk-action.css/>
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <x-flash-msg/>
        <x-error-msg/>

        <div class="row g-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="header-wrap d-flex flex-wrap justify-content-between">
                            <h4 class="header-title mb-4">{{__('All States')}}</h4>
                            <button class="btn btn-sm btn-info" data-bs-target="#state_create_modal" data-bs-toggle="modal">
                                {{__('New State')}}
                            </button>
                        </div>
                        @can('state-delete')
                            <x-bulk-action.dropdown/>
                        @endcan


                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                <x-bulk-action.th/>
                                <th>{{__('ID')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Country')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                @foreach($all_states as $state)
                                    <tr>
                                        <x-bulk-action.td :id="$state->id"/>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $state->name }}</td>
                                        <td>{{ optional($state->country)->name }}</td>
                                        <td>
                                            <x-status-span :status="$state->status"/>
                                        </td>
                                        <td>
                                            @can('state-delete')
                                                <x-table.btn.swal.delete
                                                        :route="route('tenant.admin.state.delete', $state->id)"/>
                                            @endcan
                                            @can('state-edit')
                                                <a href="#"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#state_edit_modal"
                                                   class="btn btn-primary btn-sm btn-xs mb-3 mr-1 state_edit_btn"
                                                   data-id="{{$state->id}}"
                                                   data-name="{{$state->name}}"
                                                   data-country_id="{{$state->country_id}}"
                                                   data-status="{{$state->status}}"
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
                        <div class="pagination">
                            {!! $all_states->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @can('state-edit')
        <div class="modal fade" id="state_edit_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('Update state')}}</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="{{route('tenant.admin.state.update')}}" method="post">
                        <input type="hidden" name="id" id="state_id">
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <label for="edit_name">{{__('Name')}}</label>
                                <input type="text" class="form-control" id="edit_name" name="name"
                                       placeholder="{{__('Name')}}">
                            </div>
                            <div class="form-group">
                                <label for="edit_country_id">{{__('Country')}}</label>
                                <select name="country_id" class="form-control" id="edit_country_id">
                                    @foreach ($all_countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
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
                            <button type="submit" class="btn btn-sm btn-primary">{{__('Save Change')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
    @can('state-create')
        <div class="modal fade" id="state_create_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('Update state')}}</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="{{route('tenant.admin.state.new')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body p-4">
                            <div class="form-group">
                                <label for="name">{{__('Name')}}</label>
                                <input type="text" class="form-control" id="name" name="name"
                                       placeholder="{{__('Name')}}">
                            </div>

                            <div class="form-group">
                                <label for="country_id">{{__('Country')}}</label>
                                <select name="country_id" class="form-control" id="country_id">
                                    @foreach ($all_countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="status">{{__('Status')}}</label>
                                <select name="status" class="form-control" id="status">
                                    <option value="publish">{{__("Publish")}}</option>
                                    <option value="draft">{{__("Draft")}}</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Add New')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
@endsection
@section('scripts')
    <x-table.btn.swal.js/>
    <x-bulk-action.js :route="route('tenant.admin.state.bulk.action')"/>

    <script>
        $(document).ready(function () {
            $(document).on('click', '.state_edit_btn', function () {
                let el = $(this);
                let id = el.data('id');
                let name = el.data('name');
                let country_id = el.data('country_id');
                let status = el.data('status');
                let modal = $('#state_edit_modal');

                modal.find('#state_id').val(id);
                modal.find('#edit_status option[value="' + status + '"]').attr('selected', true);
                modal.find('#edit_name').val(name);
                modal.find('#edit_country_id').val(country_id);
            });
        });
    </script>

@endsection
