@extends(route_prefix().'admin.admin-master')

@section('title') {{__('All Users')}} @endsection

@section('style')
    <x-datatable.css/>
    <x-summernote.css/>
@endsection

@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-5">{{__('All users')}}</h4>
                <x-error-msg/>
                <x-flash-msg/>

                <x-datatable.table>
                    <x-slot name="th">
                        <th>{{__('ID')}}</th>
                        <th>{{__('Name')}}</th>
                        <th>{{__('Email')}}</th>
                        <th>{{__('Plan')}}</th>
                        <th>{{__('Action')}}</th>
                    </x-slot>
                    <x-slot name="tr">
                       @foreach($all_users as $user)
                           <tr>
                               <td>{{$user->id}}</td>
                               <td>{{$user->name}}</td>
                               <td>{{$user->email}}
                                   @if($user->email_verified === 0)
                                    <i class="text-danger mdi mdi-close-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Email Not Verified')}}"></i>
                                   @else
                                    <i class="text-success mdi mdi-check-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Email  Verified')}}"></i>
                                   @endif
                               </td>
                               <td>show which plan user using</td>
                               <td>
                                   <x-delete-popover url="{{route('tenant.admin.user.delete',$user->id)}}" popover="{{__('Delete')}}"/>
                                   <x-link-with-popover url="{{route('tenant.admin.user.edit.profile',$user->id)}}" popover="{{__('Edit')}}">
                                       <i class="mdi mdi-account-edit"></i>
                                   </x-link-with-popover>


                                <x-modal.button target="tenant_password_change" extra="user_change_password_btn" type="info" dataid="{{$user->id}}">
                                    {{__('Change Password')}}
                                </x-modal.button>

                                   <x-modal.button target="send_mail_to_tenant_modal" extra="send_mail_to_tenant_btn" type="success" dataid="{{$user->email}}">
                                       {{__('Send Mail')}}
                                   </x-modal.button>


                                   @if($user->email_verified < 1)
                                       <form action="{{route(route_prefix().'admin.user.resend.verify.mail')}}" method="post" enctype="multipart/form-data">
                                           @csrf
                                           <input type="hidden" name="id" value="{{$user->id}}">
                                           <button class="btn btn-secondary mb-3 mr-1 btn-sm " type="submit">{{__('Send Verify Mail')}}</button>
                                       </form>
                                   @endif
                               </td>
                           </tr>
                       @endforeach
                    </x-slot>
                </x-datatable.table>
            </div>
        </div>
    </div>


{{--Change Password Modal--}}
<div class="modal fade" id="tenant_password_change" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Change Admin Password')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                </div>

                <form action="{{route('tenant.admin.user.change.password')}}" id="user_password_change_modal_form" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="ch_user_id" id="ch_user_id">
                        <div class="form-group">
                            <label for="password">{{__('Password')}}</label>
                            <input type="password" class="form-control" name="password" placeholder="{{__('Enter Password')}}">
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">{{__('Confirm Password')}}</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="{{__('Confirm Password')}}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Change Password')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
{{--Send Mail Modal--}}
<div class="modal fade" id="send_mail_to_tenant_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Send Mail To Subscriber')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                </div>
                <form action="{{route(route_prefix().'admin.user.send.mail')}}" id="send_mail_to_subscriber_edit_modal_form"  method="post">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="email">{{__('Email')}}</label>
                            <input type="text" readonly class="form-control"  id="email" name="email" placeholder="{{__('Email')}}">
                        </div>
                        <div class="form-group">
                            <label for="edit_icon">{{__('Subject')}}</label>
                            <input type="text" class="form-control"  id="subject" name="subject" placeholder="{{__('Subject')}}">
                        </div>
                        <div class="form-group">
                            <label for="message">{{__('Message')}}</label>
                            <input type="hidden" name="message" >
                            <div class="summernote"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button id="submit" type="submit" class="btn btn-primary">{{__('Send Mail')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <x-datatable.js/>
    <x-summernote.js/>

    <script>
        $(document).ready(function(){
            $(document).on('click','.user_change_password_btn',function(e){
                e.preventDefault();
                var el = $(this);

                console.log(el.data('id'));
                var form = $('#user_password_change_modal_form');
                form.find('#ch_user_id').val(el.data('id'));
            });
        });
    </script>

    <script>
        (function ($){
            "use strict";
            $(document).ready(function () {

                    $(document).on('click','.send_mail_to_tenant_btn',function(){
                        var el = $(this);
                        var email = el.data('id');

                        console.log(email)
                        var form = $('#send_mail_to_subscriber_edit_modal_form');
                        form.find('#email').val(email);
                    });
                $('.summernote').summernote({
                    height: 300,   //set editable area's height
                    codemirror: { // codemirror options
                        theme: 'monokai'
                    },
                    callbacks: {
                        onChange: function(contents, $editable) {
                            $(this).prev('input').val(contents);
                        }
                    }
                });
            });

        })(jQuery)
    </script>
@endsection

