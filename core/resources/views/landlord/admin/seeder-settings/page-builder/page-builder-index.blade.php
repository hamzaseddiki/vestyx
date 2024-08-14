@extends('landlord.admin.admin-master')
@section('title')
    {{__('Page Builder Demo Data List')}}
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
                        <h4 class="header-title"> {{__('Page Builder Demo Data List')}}</h4>
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
                                    <td>{{__('Header Area (Builder Data)')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.donation.home.page.header.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>2</td>
                                    <td>{{__('Brand Area (Builder Data)')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.donation.home.page.brand.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>3</td>
                                    <td>{{__('Campaign Area (Builder Data)')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.donation.home.page.campaign.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>4</td>
                                    <td>{{__('About Area (Builder Data)')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.donation.home.page.about.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>5</td>
                                    <td>{{__('Campaign Area Two (Builder Data)')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.donation.home.page.campaign.two.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>6</td>
                                    <td>{{__('Activities Area (Builder Data)')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.donation.home.page.activities.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>7</td>
                                    <td>{{__('Testimonial Area (Builder Data)')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.donation.home.page.testimonial.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>8</td>
                                    <td>{{__('Blog Area (Builder Data)')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.donation.home.page.blog.data.settings').'?lang=en_US'"/>
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
