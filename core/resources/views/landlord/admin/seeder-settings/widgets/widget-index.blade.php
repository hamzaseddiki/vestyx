@extends('landlord.admin.admin-master')
@section('title')
    {{__('Widget Demo List')}}
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
                        <h4 class="header-title">{{__('Widget Demo List')}}</h4>
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
                                    <td>{{__('About Us Widget')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.widget.about.us.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>2</td>
                                    <td>{{__('Navigation Menu Widget')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.widget.navigation.menu.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>3</td>
                                    <td>{{__('Custom Link Widget')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.widget.custom.link.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>4</td>
                                    <td>{{__('Contact Info Widget')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.widget.contact.info.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>5</td>
                                    <td>{{__('Blog Category Widget')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.widget.blog.category.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>6</td>
                                    <td>{{__('Popular Blog Widget')}}</td>
                                    <td>
                                         <x-edit-icon :url="route('landlord.admin.seeder.widget.popular.blog.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>7</td>
                                    <td>{{__('Service Category Widget')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.widget.service.category.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>8</td>
                                    <td>{{__('Popular Service Widget')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.widget.popular.service.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>9</td>
                                    <td>{{__('Query Submit Widget')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.widget.query.submit.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>10</td>
                                    <td>{{__('Donation Category Widget')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.widget.donation.category.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>11</td>
                                    <td>{{__('Recent Donation Widget')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.widget.recent.donation.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>12</td>
                                    <td>{{__('Donation Activity Category Widget')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.widget.donation.activity.category.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>13</td>
                                    <td>{{__('Recent Donation Activity Widget')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.widget.recent.donation.activities.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>14</td>
                                    <td>{{__('Footer Recent Events Widget')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.widget.footer.recent.event.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>15</td>
                                    <td>{{__('Event Category Widget')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.widget.event.category.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>16</td>
                                    <td>{{__('Sidebar Recent Events Widget')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.widget.sidebar.recent.event.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>17</td>
                                    <td>{{__('Job Category Widget')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.widget.job.category.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>18</td>
                                    <td>{{__('Recent Jobs Widget')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.widget.sidebar.recent.job.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>19</td>
                                    <td>{{__('Subscribe Newsletter Widget')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.widget.sidebar.article.newsletter.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>20</td>
                                    <td>{{__('Article Category Widget')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.widget.article.category.data.settings').'?lang=en_US'"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>21</td>
                                    <td>{{__('Recent Articles Widget')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.seeder.widget.recent.article.data.settings').'?lang=en_US'"/>
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
