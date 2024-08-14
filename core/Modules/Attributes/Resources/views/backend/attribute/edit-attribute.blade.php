@extends('tenant.admin.admin-master')
@section('title')
    {{__('Edit Attribute')}}
@endsection
@section('site-title')
    {{__('Edit Attribute')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="margin-top-40">
                    <x-error-msg/>
                    <x-flash-msg/>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="header-wrapper d-flex justify-content-between">
                            <h4 class="header-title mb-4">{{__('Edit Attribute')}}</h4>
                            @can('product-attribute-list')
                            <a href="{{route('tenant.admin.products.attributes.all')}}" class="btn btn-primary">{{__('All Attributes')}}</a>
                            @endcan
                        </div>

                        @can('product-attribute-list')
                        <form action="{{route('tenant.admin.products.attributes.update')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{$attribute->id}}">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="title">{{__('Title')}}</label>
                                        <input type="text" class="form-control"  name="title" value="{{$attribute->title}}" placeholder="{{__('Title')}}">
                                    </div>
                                    <div class="form-group attributes-field product-attributes">
                                        <label for="attributes">{{__('Terms')}}</label>
                                        @forelse(json_decode($attribute->terms) as $terms)
                                            <div class="attribute-field-wrapper">
                                                <input type="text" class="form-control" name="terms[]" value="{{$terms}}">
                                                <div class="icon-wrapper my-3">
                                                    <span class="btn btn-sm btn-info add_attributes"><i class="las la-plus"></i></span>
                                                    <span class="btn btn-sm btn-danger remove_attributes"><i class="las la-minus"></i></span>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="attribute-field-wrapper">
                                                <input type="text" class="form-control" name="terms[]" placeholder="{{__('terms')}}">
                                                <div class="icon-wrapper my-3">
                                                    <span class="btn btn-sm btn-info add_attributes"><i class="las la-plus"></i></span>
                                                    <span class="btn btn-sm btn-danger remove_attributes"><i class="las la-minus"></i></span>
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Product')}}</button>
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
        $(document).ready(function () {
            $(document).on('click','.attribute-field-wrapper .add_attributes',function (e) {
               e.preventDefault();
                $(this).parent().parent().parent().append(' <div class="attribute-field-wrapper">\n' +
                    '<input type="text" class="form-control" name="terms[]" placeholder="{{__('terms')}}">\n' +
                    '<div class="icon-wrapper my-3">\n' +
                    '<span class="btn btn-sm btn-info add_attributes"><i class="las la-plus"></i></span>\n' +
                    '<span class="btn btn-sm btn-danger remove_attributes"><i class="las la-minus"></i></span>\n' +
                    '</div>\n' +
                    '</div>');
            });

            $(document).on('click','.attribute-field-wrapper .remove_attributes',function (e) {
                e.preventDefault();

                if($(".attribute-field-wrapper").length > 1){
                    $(this).parent().parent().remove();
                }
            });
        });
    </script>
@endsection
