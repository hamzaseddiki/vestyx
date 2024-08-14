@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('All Email Templates')}}
@endsection

@section('style')
    <x-datatable.css/>
    <x-summernote.css/>
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
                        <h4 class="header-title">{{__('All Email Templates')}}</h4>
                        <h6 class="text-primary my-3">({{__('If you dont use or keep these dynamic email templates empty, then the mail will go as default template system..!')}})</h6>
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
                                    <td>{{__(' Verify Mail to Admin')}}</td>
                                    <td>
                                        <x-edit-icon :url="route(route_prefix().'admin.email.template.admin.email.verify')"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>2</td>
                                    <td>{{__(' Verify Mail to User')}}</td>
                                    <td>
                                        <x-edit-icon :url="route(route_prefix().'admin.email.template.user.email.verify')"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>{{__('Reset Password Mail to Admin')}}</td>
                                    <td>
                                        <x-edit-icon :url="route(route_prefix().'admin.email.template.admin.password.reset')"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>{{__('Reset password Mail to User')}}</td>
                                    <td>
                                        <x-edit-icon :url="route(route_prefix().'admin.email.template.user.password.reset')"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>5</td>
                                    <td>{{__('Subscription Order Mail to Admin')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.subscription.order.mail.admin')"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>{{__('Subscription Order Mail to User')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.subscription.order.mail.user')"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>7</td>
                                    <td>{{__('Subscription Credential Mail to User (Without Trial)')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.subscription.order.credential.mail.user')"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>8</td>
                                    <td>{{__('Subscription Trial with Credential Mail to User')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.subscription.order.trial.mail.user')"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>9</td>
                                    <td>{{__('Subscription Manual Payment Approved Mail to User')}}</td>
                                    <td>
                                        <x-edit-icon :url="route('landlord.admin.subscription.order.manual.payment.approved.mail')"/>
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
