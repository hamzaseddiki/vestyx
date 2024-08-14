@extends(route_prefix().'admin.admin-master')
@section('title') {{__('All Blogs')}} @endsection

@section('style')
    <x-media-upload.css/>
    <x-datatable.css/>
@endsection

@section('content')
    @php
        $lang_slug = request()->get('lang') ?? \App\Facades\GlobalLanguage::default_slug();
    @endphp
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="card">
            <div class="card-body">
                <x-admin.header-wrapper>
                    <x-slot name="left">
                        <h4 class="header-title">{{__('All Blog Items')}} </h4>
                            <x-bulk-action/>
                    </x-slot>

                    <x-slot name="right" class="d-flex">
                        <form action="{{route(route_prefix().'admin.blog')}}" method="get">
                            <x-fields.select name="lang" title="{{__('Language')}}">
                                @foreach(\App\Facades\GlobalLanguage::all_languages(1) as $lang)
                                    <option value="{{$lang->slug}}" @if($lang->slug === $lang_slug) selected @endif>{{$lang->name}}</option>
                                @endforeach
                            </x-fields.select>
                        </form>

                        <p></p>

                        <x-link-with-popover permissions="blog-create" url="{{route(route_prefix().'admin.blog.new')}}" extraclass="ml-3">
                            {{__('Add New')}}
                        </x-link-with-popover>



                    </x-slot>
                </x-admin.header-wrapper>

            <div class="msg-content">
                <x-error-msg/>
                <x-flash-msg/>
            </div>

                <div class="table-wrap table-responsive ">
                    <table class="table table-default table-striped table-bordered" id="all_blog_table" >
                        <thead class="text-white">
                        <th class="no-sort">
                            <div class="mark-all-checkbox">
                                <input type="checkbox" class="all-checkbox">
                            </div>
                        </th>
                        <th>{{__('ID')}}</th>
                        <th>{{__('Author')}}</th>
                        <th>{{__('Views')}}</th>
                        <th>{{__('Title')}}</th>
                        <th>{{__('Image')}}</th>
                        <th>{{__('Category')}}</th>
                        <th>{{__('Status')}}</th>
                        <th>{{__('Date')}}</th>
                        <th>{{__('Action')}}</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-media-upload.markup/>
@endsection

@section('scripts')
    @include('components.datatable.yajra-scripts',['only_js' => true])
    <script>
        (function ($) {
            "use strict";
            <x-bulk-action-js :url="route(route_prefix().'admin.blog.bulk.action')" />
            $(document).ready(function () {
                $(document).on('change','select[name="lang"]',function (e){
                    $(this).closest('form').trigger('submit');
                    $('input[name="lang"]').val($(this).val());
                });

                $('.table-wrap > table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route(route_prefix().'admin.blog',['lang' => $default_lang]) }}",
                    columns: [
                        {data: 'checkbox', name: '', orderable: false, searchable: false},
                        {data: 'id', name: 'id'},
                        {data: 'author', name: ''},
                        {data: 'views', name: ''},
                        {data: 'title', name: '', orderable: false, searchable: false},
                        {data: 'image', name: '', orderable: false, searchable: false},
                        {data: 'category_id', name: ''},
                        {data: 'status', name: ''},
                        {data: 'date', name: ''},
                        {data: 'action', name: '', orderable: false, searchable: true},
                    ]
                });
            });
        })(jQuery);
    </script>
    <x-media-upload.js/>

@endsection
