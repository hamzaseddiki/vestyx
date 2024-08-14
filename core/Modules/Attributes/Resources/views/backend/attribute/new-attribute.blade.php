@extends('tenant.admin.admin-master')
@section('title')
    {{__('New Variant')}}
@endsection
@section('site-title')
    {{__('New Variant')}}
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
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="header-wrapper d-flex justify-content-between">
                            <h4 class="header-title mb-4">{{__('Add New Variant')}}</h4>
                            @can('product-attribute-list')
                            <a href="{{route('tenant.admin.products.attributes.all')}}" class="btn btn-primary">{{__('All Variants')}}</a>
                            @endcan
                        </div>

                        @can('product-attribute-create')
                        <form action="{{route('tenant.admin.products.attributes.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="title">{{__('Title')}}</label>
                                        <input type="text" class="form-control"  name="title" value="{{old('title')}}" placeholder="{{__('Title')}}">
                                    </div>
                                    <div class="form-group attributes-field product-variants">
                                        <label for="attributes">{{__('Terms')}}</label>
                                       <div class="attribute-field-wrapper">
                                           <input type="text" class="form-control" name="terms[]" placeholder="{{__('terms')}}">
                                          <div class="icon-wrapper my-3">
                                              <span class="add_attributes btn btn-success btn-sm"><i class="las la-plus"></i></span>
                                          </div>
                                       </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Save Changes')}}</button>
                                </div>
                            </div>
                        </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        (function ($) {
            $(document).ready(function () {
                $(document).on('click', '.attribute-field-wrapper .add_attributes', function (e) {
                    e.preventDefault();
                    $(this).parent().parent().parent().append(' <div class="attribute-field-wrapper">\n' +
                        '<input type="text" class="form-control" name="terms[]" placeholder="{{__('terms')}}">\n' +
                        '<div class="icon-wrapper my-3">\n' +
                        '<span class="add_attributes btn btn-success btn-sm"><i class="las la-plus"></i></span>\n' +
                        '<span class="remove_attributes btn btn-danger btn-sm"><i class="las la-minus"></i></span>\n' +
                        '</div>\n' +
                        '</div>');
                });

                $(document).on('click', '.attribute-field-wrapper .remove_attributes', function (e) {
                    e.preventDefault();
                    $(this).parent().parent().remove();
                });
            });
        })(jQuery)
    </script>
@endsection
