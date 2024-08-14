@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('Product Tag')}}
@endsection
@section('style')
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
                <div>
                    <x-error-msg/>
                    <x-flash-msg/>
                </div>
            </div>
            <div class="col-xl-7 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">{{__('All Product Tags')}}</h4>
                        <div class="d-flex justify-content-between">
                            <div class="left">
                                @can('product-tag-delete')
                                    <x-bulk-action.dropdown />
                                @endcan
                            </div>

                            <div class="right">
                                <form action="{{route('tenant.admin.product.tag.all')}}" method="get">
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
                                    <th>{{__('Name')}}</th>
                                    <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                    @foreach($all_tag as $tag)
                                    <tr>
                                        <x-bulk-action.td :id="$tag->id" />
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$tag->getTranslation('tag_text',$default_lang)}}</td>
                                        <td>
                                            @can('product-tag-delete')
                                            <x-table.btn.swal.delete :route="route('tenant.admin.product.tag.delete', $tag->id)" />
                                            @endcan
                                            @can('product-tag-edit')
                                            <a href="javascript_void(0)"
                                                data-bs-toggle="modal"
                                                data-bs-target="#tag_edit_modal"
                                                class="btn btn-sm btn-primary btn-xs mb-3 mr-1 tag_edit_btn"
                                                data-id="{{$tag->id}}"
                                                data-name="{{$tag->getTranslation('tag_text',$default_lang)}}"
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
            @can('product-tag-create')
            <div class="col-xl-5 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">{{__('Add New Tag')}}</h4>
                        <form action="{{route('tenant.admin.product.tag.new')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="lang" value="{{$default_lang}}">
                            <div class="form-group">
                                <label for="name">{{__('Name')}}</label>
                                <input type="text" class="form-control"  id="name" name="title" placeholder="{{__('Name')}}">
                            </div>
                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Add New')}}</button>
                        </form>
                    </div>
                </div>
            </div>
            @endcan
        </div>
    </div>
    @can('product-tag-edit')
    <div class="modal fade" id="tag_edit_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Update Tag')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                </div>
                <form action="{{route('tenant.admin.product.tag.update')}}"  method="post">
                    <input type="hidden" name="id" id="tag_id">
                    <input type="hidden" name="lang" value="{{$default_lang}}">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="edit_name">{{__('Name')}}</label>
                            <input type="text" class="form-control"  id="edit_name" name="title" placeholder="{{__('Name')}}">
                        </div>
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
    <x-datatable.js />
    <x-table.btn.swal.js />
    @can('product-tag-delete')
    <x-bulk-action.js :route="route('tenant.admin.product.tag.bulk.action')" />
    @endcan

    <script>
        $(document).ready(function () {

            $(document).on('change','select[name="lang"]',function (e){
                $(this).closest('form').trigger('submit');
                $('input[name="lang"]').val($(this).val());
            });


            $(document).on('click','.tag_edit_btn',function(){
                let el = $(this);
                let id = el.data('id');
                let name = el.data('name');
                let modal = $('#tag_edit_modal');

                modal.find('#tag_id').val(id);
                modal.find('#edit_name').val(name);

                modal.show();
            });
        });
    </script>

@endsection
