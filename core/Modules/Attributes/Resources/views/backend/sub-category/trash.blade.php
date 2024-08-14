@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('Product Sub Category')}}
@endsection
@section('style')
    <x-media-upload.css/>
    <x-datatable.css/>
    <x-bulk-action.css/>

    <style>
        .img-wrap img{
            width: 100%;
        }
    </style>
@endsection

@php
    $all_status = \App\Models\Status::all();
@endphp

@section('content')
    <div class="col-lg-12 col-ml-12">
        <x-error-msg/>
        <x-flash-msg/>
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="header-wrap d-flex flex-wrap justify-content-between">
                            <h4 class="header-title mb-4">{{__('All Trashed Products Sub Categories')}}</h4>
                            @can('product-category-create')
                                <a href="{{route('tenant.admin.product.subcategory.all')}}" class="btn btn-sm btn-info btn-xs mb-3 mr-1 text-light">{{__('Back')}}</a>
                            @endcan
                        </div>
                        @can('product-category-delete')
                            <x-bulk-action.dropdown/>
                        @endcan

                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                <x-bulk-action.th/>
                                <th>{{__('ID')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Image')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>

                                @foreach($all_subcategory as $key => $category)
                                    <tr>
                                        <x-bulk-action.td :id="$category->id"/>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$category->name}}</td>
                                        <td>
                                            <div class="attachment-preview">
                                                <div class="img-wrap">
                                                    {!! render_image_markup_by_attachment_id($category->image_id) !!}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @can('product-category-edit')
                                                <a class="btn btn-success" href="{{route('tenant.admin.product.subcategory.trash.restore', $category->id)}}">
                                                    {{__('Restore')}}
                                                </a>
                                            @endcan

                                            @can('product-category-delete')
                                                <a class="btn btn-danger" href="{{route('tenant.admin.product.subcategory.trash.delete', $category->id)}}">
                                                    {{__('Delete')}}
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
    <div class="body-overlay-desktop"></div>
    <x-media-upload.markup/>
@endsection

@section('scripts')
    <x-datatable.js/>
    <x-media-upload.js/>
    <x-table.btn.swal.js/>
    @can('product-category-delete')
        <x-bulk-action.js :route="route('tenant.admin.product.subcategory.trash.delete.bulk')"/>
    @endcan
@endsection
