@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('Product Child-Category')}}
@endsection
@section('style')
    <x-media-upload.css/>
    <x-datatable.css/>
    <x-bulk-action.css/>

    <style>
        .img-wrap img {
            width: 100%;
        }
    </style>
@endsection

@php
    $statuses = \App\Models\Status::all();
    $lang_slug = request()->get('lang') ?? \App\Facades\GlobalLanguage::default_slug();
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
                            <h4 class="header-title mb-4">{{__('All Products Child-Categories')}}</h4>
                            <div class="btn-wrap">
                                @can('product-child-category-create')
                                    <a href="#"
                                       data-bs-toggle="modal"
                                       data-bs-target="#child-category_create_modal"
                                       class="btn btn-sm btn-info btn-xs mb-3 mr-1 text-light">New Child Category</a>
                                @endcan
                                @can('product-category-delete')
                                    <a class="btn btn-sm btn-danger btn-xs mb-3 mr-1 text-light" href="{{route('tenant.admin.product.child-category.trash.all')}}">{{__('Trash')}}</a>
                                @endcan
                            </div>
                            <div class="lang">
                                <form action="{{route('tenant.admin.product.child-category.all')}}" method="get">
                                    <x-fields.select name="lang" title="{{__('Language')}}">
                                        @foreach(\App\Facades\GlobalLanguage::all_languages() as $lang)
                                            <option value="{{$lang->slug}}" @if($lang->slug === $lang_slug) selected @endif>{{$lang->name}}</option>
                                        @endforeach
                                    </x-fields.select>
                                </form>
                            </div>
                        </div>
                        @can('product-child-category-delete')
                            <x-bulk-action.dropdown/>
                        @endcan

                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                <x-bulk-action.th/>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Category Name') }}</th>
                                <th>{{ __('Sub Category Name') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Image') }}</th>
                                <th>{{ __('Action') }}</th>
                                </thead>
                                <tbody>
                                @foreach($data['all_child_category'] as $child_category)
                                    @php
                                        $category = $child_category->category?->getTranslation('name',$default_lang);
                                        $sub_category = $child_category->sub_category?->getTranslation('name',$default_lang);
                                    @endphp
                                    <tr>
                                        <x-bulk-action.td :id="$child_category->id"/>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{ $category }}</td>
                                        <td>{{ $sub_category }}</td>
                                        <td>{{$child_category->getTranslation('name',$default_lang)}}</td>
                                        <td>
                                            <x-status-span :status="$child_category->status?->name"/>
                                        </td>
                                        <td>
                                            <div class="attachment-preview">
                                                <div class="img-wrap">
                                                    {!! render_image_markup_by_attachment_id($child_category->image_id) !!}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @can('product-child-category-delete')
                                                <x-table.btn.swal.delete
                                                    :route="route('tenant.admin.product.child-category.delete', $child_category->id)"/>
                                            @endcan
                                            @can('product-child-category-edit')
                                                @php
                                                    $image = get_attachment_image_by_id($child_category->image_id, null, true);
                                                    $img_path = $image['img_url'];
                                                @endphp

                                                <a href="javascript:void(0)"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#child-category_edit_modal"
                                                   class="btn btn-sm btn-primary btn-xs mb-3 mr-1 child-category_edit_btn"
                                                   data-id="{{$child_category->id}}"
                                                   data-name="{{$child_category->getTranslation('name',$default_lang)}}"
                                                   data-slug="{{$child_category->slug}}"
                                                   data-status="{{ $child_category->status_id }}"
                                                   data-imageid="{!! $child_category->image_id !!}"
                                                   data-image="{{ $img_path }}"
                                                   data-category-id="{{$child_category->category_id}}"
                                                   data-sub-category-id="{{$child_category->sub_category_id}}"
                                                >
                                                    <i class="mdi mdi-lead-pencil"></i>
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

    @can('product-child-category-edit')
        <div class="modal fade" id="child-category_edit_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('Update Child-Category')}}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="{{route('tenant.admin.product.child-category.update')}}" method="post">
                        <input type="hidden" name="id" id="child-category_id">
                        <input type="hidden" name="lang" value="{{$default_lang}}">
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <label for="edit_name">{{__('Name')}}</label>
                                <input type="text" class="form-control" id="edit_name" name="name"
                                       placeholder="{{__('Name')}}">
                            </div>
                            <div class="form-group">
                                <label for="edit_slug">{{__('Slug')}}</label>
                                <input type="text" class="form-control" id="edit_slug" name="slug"
                                       placeholder="{{__('Slug')}}">
                            </div>
                            <div class="form-group edit-category-wrapper">
                                <label for="category">{{__('Category')}}</label>
                                <select class="form-control" id="edit_category_id" name="category_id">
                                    <option>{{ __('Select Category') }}</option>
                                    @foreach ($data['all_category'] as $category)
                                        <option value="{{ $category->id }}">{{ $category->getTranslation('name',$default_lang) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group edit-sub-category-wrapper">
                                <label for="category">{{__('Sub Category')}}</label>
                                <select class="form-control" id="edit_sub_category" name="sub_category_id">
                                    <option>{{ __('Select Sub Category') }}</option>
                                </select>
                            </div>

                            <div class="form-group edit-status-wrapper">
                                <label for="edit_status">{{__('Status')}}</label>
                                <select name="status_id" class="form-control" id="edit_status">
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <x-fields.media-upload :title="__('Image')" :name="'image_id'" :dimentions="'200x200'"/>
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

    @can('product-child-category-create')
        <div class="modal fade" id="child-category_create_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('Add Child-Category')}}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="{{route('tenant.admin.product.child-category.new')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="lang" value="{{$default_lang}}">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="create-name">{{__('Name')}}</label>
                                <input type="text" class="form-control" id="create-name" name="name"
                                       placeholder="{{ __('Name') }}">
                            </div>

                            <div class="form-group">
                                <label for="create-slug">{{__('Slug')}}</label>
                                <input type="text" class="form-control" id="create-slug" name="slug"
                                       placeholder="{{ __('Slug') }}">
                            </div>

                            <div class="form-group category-wrapper">
                                <label for="category_id">{{__('Category')}}</label>
                                <select class="form-control" id="create_category_id" name="category_id">
                                    <option>{{ __('Select Category') }}</option>
                                    @foreach ($data['all_category'] as $category)
                                        <option value="{{ $category->id }}">{{ $category->getTranslation('name',$default_lang) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group create-sub-category-wrapper">
                                <label for="category">{{__('Sub Category')}}</label>
                                <select class="form-control" id="create_sub_category" name="sub_category_id">
                                    <option>{{ __('Select Sub Category') }}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="status">{{__('Status')}}</label>
                                <select name="status_id" class="form-control" id="status">
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <x-fields.media-upload :title="__('Image')" :name="'image_id'" :dimentions="'200x200'"/>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-secondary"
                                    data-bs-dismiss="modal">{{__('Close')}}</button>
                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Add New')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
    <x-media-upload.markup/>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {


            $(document).on('change','select[name="lang"]',function (e){
                $(this).closest('form').trigger('submit');
                $('input[name="lang"]').val($(this).val());
            });


            $(document).on("change", "#create_category_id", function () {
                let category_id = $(this).val();

                $.ajax({
                    url: '{{ route("tenant.admin.product.subcategory.all") }}/of-category/select/' + category_id,
                    type: 'GET',
                    data: {
                        _token: '<?php echo csrf_token(); ?>',
                        "category_id": category_id
                    },
                    success: function (data) {
                        console.log(data.option)
                        $("#create_sub_category").html(data.option);
                        $(".create-sub-category-wrapper .list").html(data.list);
                        $(".create-sub-category-wrapper span.current").html('{{__('Select Sub Category')}}');
                    },
                    error: function (err) {

                    }
                });
            });

            $(document).on("change", "#edit_sub_category_id", function () {
                let category_id = $(this).val();

                $.ajax({
                    url: '{{ route("tenant.admin.product.subcategory.all") }}/of-category/select/' + category_id,
                    type: 'GET',
                    data: {
                        _token: '<?php echo csrf_token(); ?>',
                        "category_id": category_id
                    },
                    success: function (data) {
                        $("#edit_sub_category").html(data.option);
                        $(".edit-sub-category-wrapper .list").html(data.list);
                        $(".edit-sub-category-wrapper span.current").html('{{__('Select Sub Category')}}');
                    },
                    error: function (err) {
                        toastr.error('<?php echo __("An error occurred"); ?>');
                    }
                });
            });

            $('#create-name , #create-slug').on('keyup', function () {
                let title_text = $(this).val();
                $('#create-slug').val(convertToSlug(title_text))
            });

            $('#edit_name , #edit_slug').on('keyup', function () {
                let title_text = $(this).val();
                $('#edit_slug').val(convertToSlug(title_text))
            });
            $(document).on('click', '.child-category_edit_btn', function () {
                $("#edit_sub_category_id").attr("id", "edit_category_id");
                let el = $(this);
                let id = el.data('id');
                let name = el.data('name');
                let slug = el.data('slug');
                let status = el.data('status');
                let category_id = el.data('category-id');
                let sub_category_id = el.data('sub-category-id');
                let modal = $('#child-category_edit_modal');

                $.ajax({
                    url: '{{ route("tenant.admin.product.subcategory.all") }}/of-category/select/' + category_id,
                    type: 'GET',
                    data: {
                        _token: '<?php echo csrf_token(); ?>',
                        "category_id": category_id
                    },
                    success: function (data) {
                        $("#edit_sub_category").html(data.option);
                        $(".edit-sub-category-wrapper .list").html(data.list);

                        modal.find(".edit-sub-category-wrapper .list li[data-value='" + sub_category_id + "']").click();
                        modal.find(".modal-footer").click();
                        $("#edit_category_id").attr("id", "edit_sub_category_id");
                    },
                    error: function (err) {

                    }
                });

                modal.find('#child-category_id').val(id);
                modal.find('#edit_status option[value=' + status + ']').attr('selected', true);
                modal.find('#edit_name').val(name);
                modal.find('#edit_slug').val(slug);
                modal.find('#edit_category').val(category_id);
                modal.find(".edit-category-wrapper select option[value=" + category_id + "]").attr('selected', true);
                modal.find(".edit-status-wrapper select option[value=" + status + "]").attr('selected', true);

                modal.find(".modal-footer").click();

                let image = el.data('image');
                let imageid = el.data('imageid');

                if (imageid != '') {
                    modal.find('.media-upload-btn-wrapper .img-wrap').html('<div class="attachment-preview"><div class="thumbnail"><div class="centered"><img class="avatar user-thumb" src="' + image + '" > </div></div></div>');
                    modal.find('.media-upload-btn-wrapper input').val(imageid);
                    modal.find('.media-upload-btn-wrapper .media_upload_form_btn').text('{{__('Change Image')}}');
                }
            });
        });
    </script>
    <x-datatable.js/>
    <x-media-upload.js/>
    <x-table.btn.swal.js/>
    @can('product-child-category-delete')
        <x-bulk-action.js :route="route('tenant.admin.product.child-category.bulk.action')"/>
    @endcan

@endsection
