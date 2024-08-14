@extends('landlord.admin.admin-master')
@section('title')
    {{__('Service Demo Data')}}
@endsection

@section('style')
    <x-datatable.css/>
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
                <x-error-msg/>
                <x-flash-msg/>
            </div>
            <div class="col-lg-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__('Service Demo Data')}}</h4>
                        <div class="table-wrap table-responsive">
                            <table class="table table-default table-striped table-bordered">

                                <thead>
                                <th>{{__('SL#')}}</th>
                                <th>{{__('Title')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>

                                <tbody>

                                <tr>
                                    <td>1</td>
                                    <td>{{__('All Service Category')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.service.category.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>2</td>
                                    <td>{{__('All Services')}}</td>
                                    <td>
                                         <x-edit-icon :url="route('landlord.admin.seeder.service.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>


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
    <x-datatable.js/>
@endsection
