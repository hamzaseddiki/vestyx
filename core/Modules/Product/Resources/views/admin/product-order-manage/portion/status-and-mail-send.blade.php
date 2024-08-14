<div class="modal fade" id="user_edit_modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('Send Mail To Order Sender')}}</h5>
                <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
            </div>
            <form action="{{route(route_prefix().'admin.product.order.manage.send.mail')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="name">{{__('Name')}}</label>
                        <input type="text" class="form-control order_manage_user_name" name="name" placeholder="{{__('Enter name')}}">
                    </div>
                    <div class="form-group">
                        <label for="email">{{__('Email')}}</label>
                        <input type="text" class="form-control order_manage_user_email" name="email" placeholder="{{__('Email')}}">
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
            <form action="{{route(route_prefix().'admin.product.order.manage.change.status')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="order_id" id="order_id">
                    <div class="form-group">
                        <label for="order_status">{{__('Order Status')}}</label>
                        <select name="order_status" class="form-control" id="order_status">
                            <option value="pending">{{__('Pending')}}</option>
                            <option value="in_progress">{{__('In Progress')}}</option>
                            <option value="cancel">{{__('Cancel')}}</option>
                            <option value="complete">{{__('Complete')}}</option>
                        </select>
                        <sub class="text-primary">{{__('If you cancel order then stock will go to it\'s previous state')}}</sub>
                    </div>

                    <div class="form-group">
                        <label for="order_status">{{__('Payment Status')}}</label>
                        <select name="payment_status" class="form-control" id="payment_status">
                            <option value="pending">{{__('Pending')}}</option>
                            <option value="success">{{__('Success')}}</option>
                        </select>
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
