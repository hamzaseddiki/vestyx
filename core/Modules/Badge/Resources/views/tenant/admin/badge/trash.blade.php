@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('Trash Badges')}}
@endsection
@section('style')
    <x-datatable.css/>
    <x-bulk-action.css/>
    <x-media-upload.css/>

    <style>
        .badge_image {
            width: 50px;
            height: auto;
        }
    </style>
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="">
                    <x-error-msg/>
                    <x-flash-msg/>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">{{__('Trash Badges')}}</h4>
                        <div class="d-flex justify-content-between flex-wrap align-items-center">
                            @can('badge-delete')
                                <x-bulk-action.dropdown/>
                            @endcan

                            <a class="btn btn-info btn-sm" href="{{route('tenant.admin.badge.all')}}">Back</a>
                        </div>
                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                <x-bulk-action.th/>
                                <th>{{__('ID')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Image')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                @foreach($badges as $badge)
                                    <tr>
                                        <x-bulk-action.td :id="$badge->id"/>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$badge->name}}</td>
                                        <td>
                                            {!! render_image_markup_by_attachment_id($badge->image, 'badge_image') !!}
                                        </td>
                                        <td>{{$badge->status}}</td>
                                        <td>
                                            @can('badge-edit')
                                                <a class="btn btn-info btn-sm btn-xs mb-3"
                                                   href="{{route('tenant.admin.badge.trash.restore', $badge->id)}}">
                                                    {{__('Restore')}}
                                                </a>
                                            @endcan
                                            @can('badge-delete')
                                                    <x-delete-popover permissions="badge-delete" url="{{route('tenant.admin.badge.trash.delete', $badge->id)}}"/>
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

    <x-media-upload.markup/>
@endsection
@section('scripts')
    <x-datatable.js/>
    <x-table.btn.swal.js/>
    <x-media-upload.js/>
    @can('badge-delete')
        <x-bulk-action.js :route="route('tenant.admin.badge.trash.bulk.action.delete')"/>
    @endcan
@endsection
