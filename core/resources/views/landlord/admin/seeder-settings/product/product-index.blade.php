@extends('landlord.admin.admin-master')
@section('title')
    {{__('Product Demo Data')}}
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
                        <h4 class="header-title">{{__('Product Demo Data')}}</h4>
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
                                    <td>{{__('Category')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.product.category.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>2</td>
                                    <td>{{__('Sub-category')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.product.subcategory.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>3</td>
                                    <td>{{__('Child-category')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.product.childcategory.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>4</td>
                                    <td>{{__('Colors')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.product.color.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>5</td>
                                    <td>{{__('Sizes')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.product.size.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>6</td>
                                    <td>{{__('Delivery Option')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.product.delivery.option.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>7</td>
                                    <td>{{__('Badges')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.product.badge.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>8</td>
                                    <td>{{__('Campaign')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.product.campaign.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>9</td>
                                    <td>{{__('Shipping & Return Policy')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.product.return.policy.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>10</td>
                                    <td>{{__('All Products')}}</td>
                                    <td>
                                         <x-edit-icon :url="route('landlord.admin.seeder.product.data.settings').'?lang=en_US'"/>
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
