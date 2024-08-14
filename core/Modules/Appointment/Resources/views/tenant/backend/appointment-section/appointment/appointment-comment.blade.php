@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__(' Appointment Comments')}}
@endsection

@section('style')
    <x-datatable.css/>
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>

            </div>
            <div class="col-lg-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h4 class="header-title">{{__('Appointment Comment of:')}} <span class="text-primary">{{ $donation->getTranslation('title',get_user_lang()) }}</span></h4>
                            <a class="btn btn-success btn-sm pull-right" href="{{route('tenant.admin.appointment')}}">{{__('All Appointments')}}</a>
                        </div>

                        <div class="bulk-delete-wrapper">
                            <x-bulk-action/>
                        </div>

                        <div class="table-wrap table-responsive">
                            <table class="table table-default table-striped table-bordered">
                                <thead>
                                <th class="no-sort">
                                    <div class="mark-all-checkbox">
                                        <input type="checkbox" class="all-checkbox">
                                    </div>
                                </th>
                                <th>{{__('ID')}}</th>
                                <th>{{__('Cause Title')}}</th>
                                <th>{{__('Commented By')}}</th>
                                <th>{{__('Commented Content')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>

                                @foreach($donation->comments as $data)
                                    <tr>
                                        <td>
                                            <x-bulk-delete-checkbox :id="$data->id"/>
                                        </td>
                                        <td>{{$data->id}}</td>
                                        <td>{{ $data->appointment?->getTranslation('title',get_user_lang()) }}</td>
                                        <td>{{$data->commented_by ?? __('User')}}</td>
                                        <td>{{$data->comment_content ?? ''}}</td>

                                        <td>
                                            <x-delete-popover :url="route('tenant.admin.appointment.comments.delete.all.lang',$data->id)"/>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

@endsection
@section('scripts')
    <x-datatable.js/>
    <script src="{{asset('assets/backend/js/dropzone.js')}}"></script>
    <script>
        <x-btn.submit/>
        <x-btn.update/>
        (function ($) {
            "use strict";
            $(document).ready(function () {
                <x-bulk-action-js :url="route('tenant.admin.appointment.comments.bulk.action')"/>

            });
        })(jQuery)
    </script>

@endsection
