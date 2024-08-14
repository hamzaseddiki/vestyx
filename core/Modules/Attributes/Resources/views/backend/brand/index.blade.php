@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('Brand Manage')}}
@endsection
@section('style')
    <x-datatable.css />
    <x-bulk-action.css />
    <x-media-upload.css/>
@endsection
@section('content')
    @php
        $lang_slug = request()->get('lang') ?? \App\Facades\GlobalLanguage::default_slug();
    @endphp
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="">
                    <x-flash-msg/>
                    <x-error-msg/>
                </div>
            </div>
            <div class="col-lg-12 mt-2">
                <div class="card">
                    <div class="card-body">

                        <div class="header-wrap d-flex flex-wrap justify-content-between">
                            <div class="start">
                                <h4 class="header-title mb-4">{{__('All Brand Manages')}}</h4>
                                @can('product-brand-manage-create')
                                    <a href="#"
                                       data-bs-toggle="modal"
                                       data-bs-target="#brand_manage_create_modal"
                                       class="btn btn-sm btn-info btn-xs mb-3 mr-1 text-light">{{__('Create New Brand')}}</a>
                                @endcan
                            </div>
                            <div class="left">
                                @can('product-delivery-manage-delete')
                                    <x-bulk-action.dropdown />
                                @endcan
                            </div>

                            <div class="right">
                                <form action="{{route('tenant.admin.product.brand.manage.all')}}" method="get">
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
                                <th>{{__('SL NO:')}}</th>
                                <th>{{__('Logo')}}</th>
                                <th>{{__('Banner')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Title')}}</th>
                                <th>{{__('Description')}}</th>
                                <th>{{__('URL')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                @foreach($delivery_manages as $item)
                                    <tr>
                                        <td width="80px">{{$loop->iteration}}</td>
                                        <td>
                                            <div class="attachment-preview">
                                                <div class="img-wrap">
                                                    {!! render_image_markup_by_attachment_id($item->image_id) !!}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="attachment-preview">
                                                <div class="img-wrap">
                                                    {!! render_image_markup_by_attachment_id($item->banner_id) !!}
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{$item->getTranslation('name',$default_lang)}}</td>
                                        <td>{{$item->getTranslation('title',$default_lang)}}</td>
                                        <td>{{$item->getTranslation('description',$default_lang)}}</td>
                                        <td>{{$item->url}}</td>
                                        <td>
                                            @php
                                                $logo = get_attachment_image_by_id($item->image_id);
                                                $logo_url = !empty($logo) ? $logo['img_url'] : '';

                                                $banner = get_attachment_image_by_id($item->banner_id);
                                                $banner_url = !empty($banner) ? $banner['img_url'] : '';
                                            @endphp

                                            @can('product-delivery_manage-delete')
                                                <x-table.btn.swal.delete :route="route('tenant.admin.product.brand.manage.delete', $item->id)" />
                                            @endcan
                                            @can('product-delivery_manage-edit')
                                                <a href="#"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#brand_manage_edit_modal"
                                                   class="btn btn-primary btn-sm btn-xs mb-3 mr-1 brand_manage_edit_btn"
                                                   data-id="{{$item->id}}"
                                                   data-name="{{$item->getTranslation('name',$default_lang)}}"
                                                   data-slug="{{$item->slug}}"
                                                   data-title="{{$item->getTranslation('title',$default_lang)}}"
                                                   data-description="{{$item->getTranslation('description',$default_lang)}}"
                                                   data-logo-id="{{ $item->image_id }}"
                                                   data-logo="{{ $logo_url }}"
                                                   data-banner-id="{{ $item->banner_id }}"
                                                   data-banner="{{ $banner_url }}"
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
    @can('brand-manage-edit')
        <div class="modal fade" id="brand_manage_edit_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('Update Brand')}}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <div class="card-body">
                        <form action="{{route('tenant.admin.product.brand.manage.update')}}" method="post">
                            @csrf
                            <input type="hidden" value="" id="edit-brand-id" name="id">
                            <input type="hidden" name="lang" value="{{$default_lang}}">
                            <div class="form-group">
                                <label for="name">{{__('Name')}}</label>
                                <input type="text" class="form-control"  id="edit-name" name="name" placeholder="{{__('Brand Name')}}">
                            </div>

                            <div class="form-group">
                                <label for="name">{{__('Slug')}}</label>
                                <input type="text" class="form-control"  id="edit-slug" name="slug" placeholder="{{__('Brand Slug')}}">
                            </div>

                            <div class="form-group">
                                <label for="name">{{__('Title')}}</label>
                                <input type="text" class="form-control"  id="edit-title" name="title" placeholder="{{__('Title')}}">
                            </div>

                            <div class="form-group">
                                <label for="name">{{__('Description')}}</label>
                                <input type="text" class="form-control"  id="edit-description" name="description" placeholder="{{__('Brand Description Optional')}}">
                            </div>

                            <div class="form-group">
                                <label for="edit-url">{{__('URL')}}</label>
                                <input type="text" class="form-control"  id="edit-url" name="url" placeholder="{{__('Brand URL Optional')}}">
                            </div>

                            <x-fields.media-upload-two :title="'Logo'" :name="'image_id'" :dimentions="'200x200'"/>
                            <x-fields.media-upload-two :title="'Banner'" :name="'banner_id'" :dimentions="'200x200'"/>

                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Brand')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    @can('brand-manage-create')
        <div class="modal fade" id="brand_manage_create_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('Create Brand')}}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <div class="card-body">
                        <form action="{{route('tenant.admin.product.brand.manage.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="lang" value="{{$default_lang}}">
                            <div class="form-group">
                                <label for="name">{{__('Name')}}</label>
                                <input type="text" class="form-control"  id="create-name" name="name" placeholder="{{__('Brand Name')}}">
                            </div>

                            <div class="form-group">
                                <label for="name">{{__('Slug')}} <span style="font-size: 10px">{{__('Optional')}}</span></label>
                                <input type="text" class="form-control"  id="create-slug" name="slug" placeholder="{{__('Brand Slug')}}">
                            </div>

                            <div class="form-group">
                                <label for="name">{{__('Title')}}</label>
                                <input type="text" class="form-control"  id="title" name="title" placeholder="{{__('Title')}}">
                            </div>

                            <div class="form-group">
                                <label for="name">{{__('Description')}}</label>
                                <input type="text" class="form-control"  id="description" name="description" placeholder="{{__('Brand Description Optional')}}">
                            </div>

                            <div class="form-group">
                                <label for="url">{{__('URL')}}</label>
                                <input type="text" class="form-control"  id="url" name="url" placeholder="{{__('Brand URL Optional')}}">
                            </div>

                            <x-fields.media-upload-two :title="'Logo'" :name="'image_id'" :dimentions="'200x200'"/>
                            <x-fields.media-upload-two :title="'Banner'" :name="'banner_id'" :dimentions="'200x200'"/>

                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Add New')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endcan
    <x-media-upload.markup/>
@endsection
@section('scripts')
    <x-datatable.js />
    <x-table.btn.swal.js />
    <x-media-upload.js/>

    <script>
        <x-icon-picker/>
        $(document).ready(function () {

            $(document).on('change','select[name="lang"]',function (e){
                $(this).closest('form').trigger('submit');
                $('input[name="lang"]').val($(this).val());
            });

            $(document).on('click','.brand_manage_edit_btn',function(){
                let el = $(this);
                let id = el.data('id');
                let name = el.data('name');
                let slug = el.data('slug');
                let title = el.data('title');
                let description = el.data('description');
                let modal = $('#brand_manage_edit_modal');

                modal.find('#edit-brand-id').val(id);
                modal.find('#edit-title').val(title);
                modal.find('#edit-description').val(description);
                modal.find('#edit-name').val(name);
                modal.find('#edit-slug').val(slug);

                let logo = el.data('logo');
                let banner = el.data('banner');
                let logo_id = el.data('logo-id');
                let banner_id = el.data('banner-id');

                if (logo_id != '') {
                    modal.find('#image_id_section .img-wrap').html('<div class="attachment-preview"><div class="thumbnail"><div class="centered"><img class="avatar user-thumb" src="'+ logo +'" > </div></div></div>');
                    modal.find('#image_id_section input').val(logo_id);
                    modal.find('#image_id_section .media_upload_form_btn').text('Change Image');
                }

                if (banner_id != '') {
                    modal.find('#banner_id_section .img-wrap').html('<div class="attachment-preview"><div class="thumbnail"><div class="centered"><img class="avatar user-thumb" src="'+ banner +'" > </div></div></div>');
                    modal.find('#banner_id_section input').val(banner_id);
                    modal.find('#banner_id_section .media_upload_form_btn').text('Change Image');
                }

                modal.show();
            });
        });

        $('#create-name , #create-slug').on('keyup', function () {
            let title_text = $(this).val();
            $('#create-slug').val(convertToSlug(title_text))
        });

        $('#edit-name , #edit-slug').on('keyup', function () {
            let title_text = $(this).val();
            $('#edit-slug').val(convertToSlug(title_text))
        });
    </script>
@endsection
