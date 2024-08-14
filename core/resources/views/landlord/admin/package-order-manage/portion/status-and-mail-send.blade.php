<div class="modal fade" id="user_edit_modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('Send Mail To Order Sender')}}</h5>
                <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
            </div>
            <form action="{{route(route_prefix().'admin.package.order.manage.send.mail')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="name">{{__('Name')}}</label>
                        <input type="text" class="form-control" name="name" placeholder="{{__('Enter name')}}">
                    </div>
                    <div class="form-group">
                        <label for="email">{{__('Email')}}</label>
                        <input type="text" class="form-control" name="email" placeholder="{{__('Email')}}">
                    </div>
                    <div class="form-group">
                        <label for="Subject">{{__('Subject')}}</label>
                        <input type="text" class="form-control" name="subject" value="{{__('Your order Replay From {site}')}}">
                        <small class="info-text">{{__('{site} will be replaced by site title')}}</small>
                    </div>
                    <div class="form-group">
                        <label>{{__('Message')}}</label>
                        <input type="hidden" name="message">
                        <div class="summernote"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                    <button type="submit" class="btn btn-primary">{{__('Send Mail')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="order_status_change_modal" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('order Status Change')}}</h5>
                <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
            </div>
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{route(route_prefix().'admin.package.order.manage.change.status')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" class="passing_status">
                    <input type="hidden" name="order_id" id="order_id">
                    <div class="form-group">
                        <label for="order_status">{{__('order Status')}}</label>
                        <select name="order_status" class="form-control" id="order_status">
                            <option value="pending">{{__('Pending')}}</option>
                            <option value="in_progress">{{__('In Progress')}}</option>
                            <option value="cancel">{{__('Cancel')}}</option>
                            <option value="complete">{{__('Complete')}}</option>
                        </select>
                    </div>

                    <div class="form-group subscription_cancel_type_parent d-none">
                        <label for="order_status">{{__('Cancel Type')}}</label>
                        <select name="subscription_cancel_type" class="form-control" id="subscription_cancel_type">
                            <option value="temporary">{{__('Temporary')}}</option>
                            <option value="permanent">{{__('Permanent')}}</option>
{{--                            <option value="permanent_with_delete">{{__('Permanent with delete')}}</option>--}}
                        </select>

                        <small class="text-primary">{{__('Temporary means user tenant will no longer to renew the package, he can use normally..!')}}</small>
                        <br><br>
                        <small class="text-info">{{__('Permanent Cancle  means you want to remove this user website..!')}}</small>
{{--                        <br> <small class="text-info">{{__('Permanent means user website will be expired immediately..!')}}</small>--}}
{{--                        <br> <small class="text-danger">{{__('Permanent with delete means the tenant will be deleted..!')}}</small>--}}
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                    <button type="submit" class="btn btn-primary">{{__('Change Status')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
