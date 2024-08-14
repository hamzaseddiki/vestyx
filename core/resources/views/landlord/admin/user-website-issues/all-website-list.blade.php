
@extends('landlord.admin.admin-master')

@section('style')
    <x-datatable.css/>
    <style>
        .all_donation_info_column li{
            list-style-type: none;
        }
    </style>
@endsection

@section('title')
    {{__('All User Website List')}}
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
                                <h3 class="card-title mb-5">{{__('All User Website List')}}</h3>
                                <h5 class="text-danger">{{__('( If you delete any website and if it\'s associated with any package than everything regarding with this website will be deleted )')}}</h5>
                            </x-slot>

                            <x-slot name="right">
                            </x-slot>

                        </x-admin.header-wrapper>
                        <div class="table-wrap table-responsive">
                            <table class="table table-default table-striped table-bordered">
                                <thead class="text-white">
                                <th>{{__('User Name')}}</th>
                                <th>{{__('Subdomain')}}</th>
                                <th>{{__('Domain')}}</th>
                                <th>{{__('Browse')}}</th>
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
@endsection

@section('scripts')
    @include('components.datatable.yajra-scripts',['only_js' => true])

    <script type="text/javascript">
        $(function () {
            $(document).ready(function () {
                $('.table-wrap > table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('landlord.admin.tenant.website.list',['lang' => $default_lang]) }}",
                    columns: [
                        {data: 'username', name: '', orderable: true, searchable: true},
                        {data: 'subdomain', name: '', orderable: true, searchable: true},
                        {data: 'domain', name: '',searchable: true},
                        {data: 'browse', name: ''},
                        {data: 'action', name: '', orderable: false, searchable: false},
                    ]
                });
            });
        });
    </script>
@endsection
