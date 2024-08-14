@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('Badges')}}
@endsection
@section('style')
    <x-datatable.css />
    <x-bulk-action.css />
    <x-media-upload.css/>

    <style>
        .badge_image{
            width: 50px;
            height: auto;
        }
    </style>
@endsection
@section('content')
    @php
        $lang_slug = request()->get('lang') ?? \App\Facades\GlobalLanguage::default_slug();
    @endphp
    <div class="col-lg-12 col-ml-12">
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="">
                    <x-error-msg/>
                    <x-flash-msg/>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__('All Badges')}}</h4>

                        <div class="delete_with_trash d-flex flex-wrap justify-content-between align-items-center">
                            @can('badge-delete')
                                <x-bulk-action.dropdown/>
                            @endcan
                            <a class="btn btn-danger btn-sm" href="{{route('tenant.admin.badge.trash')}}">{{__('Trash')}}</a>
                                <form action="{{route('tenant.admin.badge.all')}}" method="get">
                                    <x-fields.select name="lang" title="{{__('Language')}}">
                                        @foreach(\App\Facades\GlobalLanguage::all_languages() as $lang)
                                            <option value="{{$lang->slug}}" @if($lang->slug === $lang_slug) selected @endif>{{$lang->name}}</option>
                                        @endforeach
                                    </x-fields.select>
                                </form>
                        </div>

                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                <x-bulk-action.th />
                                <th>{{__('ID')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Image')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                @foreach($badges as $badge)
                                    <tr>
                                        <x-bulk-action.td :id="$badge->id" />
                                        <td>{{$loop->iteration}}</td>

                                        <td>{{$badge->getTranslation('name',$default_lang)}}</td>
                                        <td>
                                            {!! render_image_markup_by_attachment_id($badge->image, 'badge_image') !!}
                                        </td>
                                        <td>{{$badge->status}}</td>
                                        <td>
                                            @can('badge-delete')
                                                <x-delete-popover permissions="badge-delete" url="{{route('tenant.admin.badge.delete', $badge->id)}}"/>
                                            @endcan
                                            @can('badge-edit')
                                                @php
                                                    $img = get_attachment_image_by_id($badge->image);
                                                    $img_url = !empty($img) ? $img['img_url'] : '';
                                                @endphp
                                                <a href="javascript:void(0)"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#badge_edit_modal"
                                                   class="btn btn-primary btn-sm btn-xs mb-3 mr-1 badge_edit_btn"
                                                   data-id="{{$badge->id}}"
                                                   data-name="{{$badge->getTranslation('name',$default_lang)}}"
                                                   data-status="{{$badge->status}}"
                                                   data-image_id="{{$badge->image}}"
                                                   data-image_url="{{$img_url}}"
                                                   data-route="{{route('tenant.admin.badge.update', $badge->id)}}"
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
            @can('badge-create')
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">{{__('Add New Badge')}}</h4>
                            <form action="{{route('tenant.admin.badge.store')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="lang" value="{{$default_lang}}">
                                <div class="form-group">
                                    <label for="name">{{__('Name')}}</label>
                                    <input type="text" class="form-control"  id="name" name="name" placeholder="{{__('Name')}}">
                                </div>

                                <div class="form-group">
                                    <label for="status">{{__('Status')}}</label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="" selected disabled>Select a badge type</option>
                                        <option value="Active">{{__('Active')}}</option>
                                        <option value="Inactive">{{__('Inactive')}}</option>
                                    </select>
                                </div>

                                <x-fields.media-upload :name="'image'" :title="'Badge Image'"/>

                                <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Add New')}}</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>
    @can('badge-edit')
        <div class="modal fade" id="badge_edit_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('Update Unit')}}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action=""  method="post" id="badge_edit_modal_form">
                        @csrf
                        <input type="hidden" name="lang" value="{{$default_lang}}">
                        <input type="hidden" name="id" id="badge_id">
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <label for="edit_name">{{__('Name')}}</label>
                                <input type="text" class="form-control"  id="edit_name" name="name" placeholder="{{__('Name')}}">
                            </div>

                            <div class="form-group">
                                <label for="status">{{__('Status')}}</label>
                                <select class="form-control" name="status" id="edit-status">
                                    <option value="" selected disabled>{{__('Select a badge type')}}</option>
                                    <option value="Active">{{__('Active')}}</option>
                                    <option value="Inactive">{{__('Inctive')}}</option>
                                </select>
                            </div>

                            <x-fields.media-upload :name="'image'" :title="'Badge Image'"/>
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
    <x-media-upload.markup/>
@endsection

@section('scripts')
    <x-datatable.js />
    <x-table.btn.swal.js />
    <x-media-upload.js/>

    @can('badge-delete')
        <x-bulk-action.js :route="route('tenant.admin.badge.bulk.action.delete')" />
    @endcan

    <script>
        $(function (){

            $(document).on('change','select[name="lang"]',function (e){
                $(this).closest('form').trigger('submit');
                $('input[name="lang"]').val($(this).val());
            });

            $(document).on('click', '.badge_edit_btn', function () {
                var el = $(this);
                var id = el.data('id');
                var name = el.data('name');
                var image = el.data('image_url');
                var image_id = el.data('image_id');
                var status = el.data('status');
                var action = el.data('route');

                var form = $('#badge_edit_modal_form');
                form.attr('action', action);
                form.find('#badge_id').val(id);
                form.find('#edit_name').val(name);
                form.find('#edit-status option[value="' + status + '"]').attr('selected', true);
                if (image_id != '') {
                    form.find('.media-upload-btn-wrapper .img-wrap').html('<div class="attachment-preview"><div class="thumbnail"><div class="centered"><img class="avatar user-thumb" src="' + image + '" > </div></div></div>');
                    form.find('.media-upload-btn-wrapper input').val(image_id);
                    form.find('.media-upload-btn-wrapper .media_upload_form_btn').text('Change Image');
                }
            });
        });
    </script>
@endsection
