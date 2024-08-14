@extends(route_prefix().'admin.admin-master')

@section('style')
    <x-datatable.css/>
    <x-media-upload.css/>
    <style>
        .all_donation_info_column li{
            list-style-type: none;
        }
    </style>
@endsection

@section('title')
    {{__('All Knowledgebase')}}
@endsection

@section('content')
    @php
        $lang_slug = request()->get('lang') ?? \App\Facades\GlobalLanguage::default_slug();
    @endphp
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <x-error-msg/>
                        <x-flash-msg/>
                        <x-admin.header-wrapper>
                            <x-slot name="left">
                                <h4 class="card-title mb-5">{{__('All Knowledgebase')}}</h4>
                                <x-bulk-action permissions="donation-delete"/>
                            </x-slot>
                            <x-slot name="right" class="d-flex">
                                <form action="{{route('tenant.admin.knowledgebase')}}" method="get">
                                    <x-fields.select name="lang" title="{{__('Language')}}">
                                        @foreach(\App\Facades\GlobalLanguage::all_languages() as $lang)
                                            <option value="{{$lang->slug}}" @if($lang->slug === $lang_slug) selected @endif>{{$lang->name}}</option>
                                        @endforeach
                                    </x-fields.select>
                                </form>
                                <p></p>
                                <a href="{{route('tenant.admin.knowledgebase.new')}}"
                                   class="btn btn-info btn-sm mb-3">{{__('Add New')}}</a>
                            </x-slot>
                        </x-admin.header-wrapper>

                        <div class="table-wrap table-responsive">
                            <table class="table table-default table-striped table-bordered">
                                <thead class="text-white">
                                <th class="no-sort">
                                    <div class="mark-all-checkbox">
                                        <input type="checkbox" class="all-checkbox">
                                    </div>
                                </th>
                                    <th>{{__('ID')}}</th>
                                    <th>{{__('Info')}}</th>
                                    <th>{{__('Image')}}</th>
                                    <th>{{__('Category')}}</th>
                                    <th>{{__('Status')}}</th>
                                    <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


   <x-media-upload.markup/>
@endsection

@section('scripts')
    @include('components.datatable.yajra-scripts',['only_js' => true])
    <script src="{{asset('assets/backend/js/dropzone.js')}}"></script>
    <script>
        (function ($) {
            "use strict";
            $(document).ready(function () {
                <x-bulk-action-js :url="route('tenant.admin.knowledgebase.bulk.action')"/>
            })
        })(jQuery)
    </script>
    <x-media-upload.js/>

    <script type="text/javascript">
        $(function () {
            $(document).ready(function () {
                $('.table-wrap > table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('tenant.admin.knowledgebase',['lang' => $default_lang]) }}",
                    columns: [
                        {data: 'checkbox', name: '', orderable: false, searchable: false},
                        {data: 'id', name: 'id'},
                        {data: 'info', name: '', orderable: false, searchable: false},
                        {data: 'image', name: '', orderable: false, searchable: false},
                        {data: 'category', name: ''},
                        {data: 'status', name: ''},
                        {data: 'action', name: '', orderable: false, searchable: false},
                    ]
                });
            });

            $(document).on('change','select[name="lang"]',function (e){
                $(this).closest('form').trigger('submit');
                $('input[name="lang"]').val($(this).val());
            });

        });
    </script>
@endsection
