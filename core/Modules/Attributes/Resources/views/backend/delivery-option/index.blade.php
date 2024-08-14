@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('Product Delivery Manage')}}
@endsection
@section('style')
    <link href="{{ global_asset('assets/common/css/fontawesome-iconpicker.min.css') }}" rel="stylesheet">
    <x-datatable.css />
    <x-bulk-action.css />
@endsection
@section('content')
    @php
        $lang_slug = request()->get('lang') ?? \App\Facades\GlobalLanguage::default_slug();
    @endphp
    <div class="col-lg-12 col-ml-12">
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="">
                    <x-flash-msg/>
                    <x-error-msg/>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                            <x-bulk-action.dropdown />
                        <div class="header-wrap d-flex flex-wrap justify-content-between">
                            <h4 class="header-title mb-4">{{__('All Delivery Manages')}}</h4>
                            <div class="left">
                                @can('product-delivery-manage-delete')

                                    <a class="btn btn-danger btn-sm" href="{{route('tenant.admin.product.delivery.option.trash.all')}}">{{__('Trash')}}</a>
                                @endcan
                            </div>
                            <div class="right">
                                <form action="{{route('tenant.admin.product.delivery.option.all')}}" method="get">
                                    <x-fields.select name="lang" title="{{__('Language')}}">
                                        @foreach(\App\Facades\GlobalLanguage::all_languages() as $lang)
                                            <option value="{{$lang->slug}}" @if($lang->slug === $lang_slug) selected @endif>{{$lang->name}}</option>
                                        @endforeach
                                    </x-fields.select>
                                </form>
                            </div>
                        </div>

                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                <x-bulk-action.th />
                                <th>{{__('ID')}}</th>
                                <th>{{__('Icon')}}</th>
                                <th>{{__('Title')}}</th>
                                <th>{{__('Sub Title')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                @foreach($delivery_manages as $item)
                                    <tr>
                                        <x-bulk-action.td :id="$item->id" />
                                        <td>{{$loop->iteration}}</td>
                                        <td>
                                            <i class="{{$item->icon}}"></i>
                                        </td>
                                        <td>{{$item->getTranslation('title',$default_lang)}}</td>
                                        <td>{{$item->getTranslation('sub_title',$default_lang)}}</td>
                                        <td>
                                            @can('product-delivery_manage-delete')
                                                <x-table.btn.swal.delete :route="route('tenant.admin.product.delivery.option.delete', $item->id)" />
                                            @endcan
                                            @can('product-delivery_manage-edit')
                                                <a href="javascript:void(0)"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#delivery_manage_edit_modal"
                                                   class="btn btn-primary btn-sm btn-xs mb-3 mr-1 delivery_manage_edit_btn"
                                                   data-id="{{$item->id}}"
                                                   data-title="{{$item->getTranslation('title',$default_lang)}}"
                                                   data-sub-title="{{$item->getTranslation('sub_title',$default_lang)}}"
                                                   data-icon="{{$item->icon}}"
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
            @can('product-delivery_manage-create')
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mb-4">{{__('Add New Delivery Manage')}}</h4>
                            <form action="{{route('tenant.admin.product.delivery.option.store')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="lang" value="{{$default_lang}}">
                                <div class="form-group">
                                    <label for="name">{{__('Title')}}</label>
                                    <input type="text" class="form-control"  id="title" name="title" placeholder="{{__('Title')}}">
                                </div>
                                <div class="form-group">
                                    <label for="name">{{__('Sub Title')}}</label>
                                    <input type="text" class="form-control"  id="sub_title" name="sub_title" placeholder="{{__('Sub Title')}}">
                                </div>
                                <div class="form-group">
                                    <x-fields.icon-picker/>
                                </div>
                                <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Add New')}}</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>
    @can('product-delivery_manage-edit')
        <div class="modal fade" id="delivery_manage_edit_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('Update Delivery Manage')}}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="{{route('tenant.admin.product.delivery.option.update')}}"  method="post">
                        @csrf
                        <input type="hidden" name="id" id="delivery_manage_id">
                        <input type="hidden" name="lang" value="{{$default_lang}}">
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <label for="name">{{__('Title')}}</label>
                                <input type="text" class="form-control"  id="edit-title" name="title" placeholder="{{__('Title')}}">
                            </div>
                            <div class="form-group">
                                <label for="name">{{__('Name')}}</label>
                                <input type="text" class="form-control"  id="edit-sub-title" name="sub_title" placeholder="{{__('Sub Title')}}">
                            </div>
                            <x-fields.icon-picker/>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                            <button type="submit" class="btn btn-primary">{{__('Save Change')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
@endsection
@section('scripts')
    <script src="{{global_asset('assets/common/js/fontawesome-iconpicker.min.js')}}"></script>
    <x-datatable.js />
    <x-table.btn.swal.js />

    @can('product-delivery_manage-delete')
        <x-bulk-action.js :route="route('tenant.admin.product.delivery.option.bulk.action')" />
    @endcan
    <script>
        $(document).ready(function () {

            $(document).on('change','select[name="lang"]',function (e){
                $(this).closest('form').trigger('submit');
                $('input[name="lang"]').val($(this).val());
            });

            <x-icon-picker/>
            $(document).on('click','.delivery_manage_edit_btn',function(){
                let el = $(this);
                let id = el.data('id');
                let title = el.data('title');
                let sub_title = el.data('sub-title');
                let modal = $('#delivery_manage_edit_modal');

                modal.find('#delivery_manage_id').val(id);
                modal.find('#edit-title').val(title);
                modal.find('#edit-sub-title').val(sub_title);
                // modal.find('#edit-icon').val(icon);
                modal.find('.icp-dd').attr('data-selected', el.data('icon'));
                modal.find('.iconpicker-component i').attr('class', el.data('icon'));

                modal.show();
            });
        });
    </script>
@endsection
