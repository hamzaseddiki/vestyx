@extends(route_prefix().'admin.admin-master')
@section('title') {{__('All Pages')}} @endsection
@section('style')
<x-datatable.css/>
@endsection
@section('content')
    @php
        $lang_slug = request()->get('lang') ?? \App\Facades\GlobalLanguage::default_slug();

    @endphp
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <x-error-msg/>
                <x-flash-msg/>

                <x-admin.header-wrapper>
                    <x-slot name="left">
                        <h4 class="card-title mb-5">{{__('All Pages')}}</h4>
                    </x-slot>
                    <x-slot name="right" class="d-flex">
                        <form action="{{route(route_prefix().'admin.pages')}}" method="get">
                            <x-fields.select name="lang" title="{{__('Language')}}">
                                @foreach(\App\Facades\GlobalLanguage::all_languages(1) as $lang)
                                    <option value="{{$lang->slug}}" @if($lang->slug === $lang_slug) selected @endif>{{$lang->name}}</option>
                                @endforeach
                            </x-fields.select>
                        </form>
                        <p></p>
                        <x-link-with-popover permissions="page-create" url="{{route(route_prefix().'admin.pages.create')}}" extraclass="ml-3">
                            {{__('Create New Page')}}
                        </x-link-with-popover>
                    </x-slot>
                </x-admin.header-wrapper>

                <x-datatable.table>
                    <x-slot name="th">
                        <th>{{__('ID')}}</th>
                        <th>{{__('Title')}}</th>
                        <th>{{__('Status')}}</th>
                        <th>{{__('Created')}}</th>
                        <th>{{__('Action')}}</th>
                    </x-slot>
                    <x-slot name="tr">
                        @foreach($all_pages as $page)
                            <tr>
                                <td>{{ $page->id  }}</td>
                                <td>
                                    {!! $page->getTranslation('title',$lang_slug) !!}

                                    @if(get_static_option('home_page') == $page->id)
                                        <span class="badge badge-info">{{__('Current Home')}}</span>
                                    @endif

                                </td>
                                <td>{{ \App\Enums\StatusEnums::getText($page->status)  }}</td>
                                <td>{{$page->created_at->format('D, d-m-y')}}</td>
                                <td>
                                    <x-delete-popover permissions="page-delete" url="{{route(route_prefix().'admin.pages.delete', $page->id)}}"/>
                                    <x-link-with-popover permissions="page-edit" url="{{route(route_prefix().'admin.pages.edit', $page->id)}}">
                                        <i class="mdi mdi-pencil"></i>
                                    </x-link-with-popover>
                                    @if($page->page_builder === 1)
                                    <x-link-with-popover class="secondary" url="{{route(route_prefix().'admin.pages.builder', $page->id)}}" popover="{{__('edit with page builder')}}">
                                        <i class="mdi mdi-settings"></i>
                                    </x-link-with-popover>
                                    @endif
                                    <x-link-with-popover  target="_blank" class="info" url="{{route(route_prefix().'dynamic.page', $page->slug)}}" popover="{{__('view item in frontend')}}">
                                        <i class="mdi mdi-eye"></i>
                                    </x-link-with-popover>

                                    @if(tenant())
                                        <x-link-with-popover class="success"
                                                             url="{{route(route_prefix().'admin.pages.download', $page->id)}}"
                                                             popover="{{__('Download Page Layout')}}">
                                            <i class="mdi mdi-download"></i>
                                        </x-link-with-popover>

                                        <x-modal.button :target="'upload-modal'" dataid="{{$page->id}}"
                                                        :type="'secondary upload-layout upload-layout-btn'">
                                            <i class="mdi mdi-upload"></i>
                                        </x-modal.button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-datatable.table>

            </div>
        </div>
    </div>

    <x-modal.markup :target="'upload-modal'" :title="'Upload Page Layout'">
        <form action="{{route('tenant.admin.pages.upload')}}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="page_id" value="">
            <div class="form-group">
                <label for="json_file">{{__('Upload JSON File')}}</label>
                <input type="file" class="form-control" id="json_file" name="page_layout">
            </div>

            <div class="form-group text-end">
                <button type="submit" class="btn btn-success btn-sm">{{__('Upload')}}</button>
            </div>
        </form>
    </x-modal.markup>
@endsection
@section('scripts')
    <x-datatable.js/>
    <script>
        $(document).ready(function ($) {
            "use strict";

            $(document).on('change', 'select[name="lang"]', function (e) {
                $(this).closest('form').trigger('submit');
                $('input[name="lang"]').val($(this).val());
            });

            $(document).on('click', '.upload-layout-btn', function (){
                let el = $(this);
                let page_id = el.data('id');

                let modal = $('#upload-modal');
                modal.find('input[name=page_id]').val(page_id);
            })


            $(document).on('click', '#upload-modal button[type=submit]', function (e){
                e.preventDefault();

                Swal.fire({
                    title: '<strong class="text-danger">{{ __('Are you sure?') }}</strong>',
                    text: '{{ __('Previous layout along with its data will be removed permanently if you upload this new layout!') }}',
                    icon: 'warning',
                    iconColor: 'red',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, upload it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).closest('form').trigger('submit');
                    }
                });
            })
        });
    </script>
@endsection
