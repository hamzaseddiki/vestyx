@extends(route_prefix().'admin.admin-master')
@section('style')
    <x-datatable.css/>
@endsection
@section('title')
    {{__('All Unpaid Applications')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12 ">
                            <div class="card">
                                <div class="card-body">
                                    <x-error-msg/>
                                    <x-flash-msg/>
                                    <h4 class="header-title">{{__('All Unpaid Applications')}}</h4>
                                    <div class="bulk-delete-wrapper">
                                        <div class="select-box-wrap">
                                            <select name="bulk_option" id="bulk_option">
                                                <option value="">{{{__('Bulk Action')}}}</option>
                                                <option value="delete">{{{__('Delete')}}}</option>
                                            </select>
                                            <button class="btn btn-primary btn-sm" id="bulk_delete_btn">{{__('Apply')}}</button>
                                        </div>
                                    </div>
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
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('components.datatable.yajra-scripts',['only_js' => true])
    <script type="text/javascript">
        $(function () {
            <x-bulk-action-js :url="route('tenant.admin.job.payment.log.bulk.action')"/>

            $(document).ready(function (){
                $('.table-wrap > table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('tenant.admin.job.unpaid.payment.logs') }}",
                    columns: [
                        {data: 'checkbox', name: '', orderable: false, searchable: false},
                        {data: 'id', name: 'id'},
                        {data: 'info', name: '' ,orderable: false, searchable: false},
                        {data: 'status'},
                        {data: 'action', name: '', orderable: false, searchable: false},
                    ]
                });
            });

        });
    </script>
@endsection

