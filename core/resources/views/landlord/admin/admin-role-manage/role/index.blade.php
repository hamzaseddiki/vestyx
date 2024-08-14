@extends(route_prefix().'admin.admin-master')
@section('style')
   <x-media-upload.css/>
    <x-datatable.css/>
@endsection
@section('title')
    {{__('All Admin Roles')}}
@endsection
@section('content')

    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12 mt-5">
                            <x-error-msg/>
                            <x-flash-msg/>
                            <div class="card">
                                <div class="card-body">

                                   <div class="header-wrap d-flex justify-content-between margin-bottom-40">
                                       <h4 class="header-title">{{__('All Admin Roles')}}</h4>
                                       <div class="btn-wrapper">
                                           <a href="{{route(route_prefix().'admin.role.new')}}" class="btn btn-primary mb-3">{{__("New Role")}}</a>
                                       </div>
                                   </div>

                                    <div class="table-wrap table-responsive">
                                        <table class="table table-default table-striped table-bordered">
                                            <thead class="text-white">
                                            <tr>
                                                <th>{{__('ID')}}</th>
                                                <th>{{__('Name')}}</th>
                                                <th>{{__('Action')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($roles as $role)
                                                <tr>
                                                    <td>{{$role->id}}</td>
                                                    <td>{{$role->name}}</td>
                                                    <td >
                                                        @if($role->name != 'Super Admin')
                                                        <x-edit-icon :url="route(route_prefix().'admin.user.role.edit',$role->id)"/>
                                                        <x-delete-popover :url="route(route_prefix().'admin.user.role.delete',$role->id)"/>
                                                        @else
                                                            <span class="badge badge-danger text-capitalize mb-3">{{__('super admin has all access')}}</span>
                                                        @endif
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
            </div>
        </div>
    </div>
<x-media-upload.markup/>
@endsection

@section('scripts')
  <x-media-upload.js/>
  <x-datatable.js/>
@endsection
