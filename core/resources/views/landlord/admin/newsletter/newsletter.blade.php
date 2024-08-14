@extends(route_prefix().'admin.admin-master')
@section('title') {{__('All Newsletter')}} @endsection

@section('style')
    <x-datatable.css/>
    <x-summernote.css/>
@endsection

@section('content')

<div class="row">
    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <x-admin.header-wrapper>
                    <x-slot name="left">
                        <h4 class="card-title mb-5">{{__('All Newsletter Subscriber')}}</h4>
                        <x-bulk-action/>
                    </x-slot>
                </x-admin.header-wrapper>
                <x-error-msg/>
                <x-flash-msg/>
                <x-datatable.table>
                    <x-slot name="th">
                        <th class="no-sort">
                            <div class="mark-all-checkbox">
                                <input type="checkbox" class="all-checkbox">
                            </div>
                        </th>
                        <th>{{__('ID')}}</th>
                        <th>{{__('Email')}}</th>
                        <th>{{__('Status')}}</th>
                        <th>{{__('Action')}}</th>
                    </x-slot>
                    <x-slot name="tr">
                        @foreach($all_newsletter as $data)
                            <tr>
                                <td>
                                    <div class="bulk-checkbox-wrapper">
                                        <input type="checkbox" class="bulk-checkbox" name="bulk_delete[]" value="{{$data->id}}">
                                    </div>
                                </td>
                                <td>{{$data->id}}</td>
                                <td>{{$data->email}}</td>

                                <td>
                                    @if($data->verified == 1)
                                        <span class="badge badge-info">{{__('Subscribed')}}</span>
                                     @else
                                        <span class="badge badge-danger">{{__('Not Subscribed')}}</span>
                                     @endif
                                </td>

                                <td>

                               <x-delete-popover permissions="newsletter-delete" :url="route(route_prefix().'admin.newsletter.delete',$data->id)"/>

                                @can('newsletter-edit')
                                    <a class="btn btn-lg btn-primary btn-sm mb-3 mr-1 send_mail_modal_btn"
                                       href="#"
                                       data-bs-toggle="modal"
                                       data-bs-target="#send_mail_to_subscriber_modal"
                                       data-id="{{$data->id}}"
                                       data-email="{{$data->email}}"
                                    >
                                        <i class="mdi mdi-email"></i>
                                    </a>
                                    @endcan
                                    @if($data->verified < 1)
                                        <form action="{{route(route_prefix().'admin.newsletter.verify.mail.send')}}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$data->id}}">
                                            <input type="hidden" name="email" value="{{$data->email}}">
                                            <button class="btn btn-secondary btn-sm" type="submit">{{__('Send Verify Mail')}}</button>
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

    @can('newsletter-create')
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">{{__('Add New Subscriber')}}</h4>
                <form action="{{route(route_prefix().'admin.newsletter.new.add')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="email">{{__('Email')}}</label>
                        <input type="text" class="form-control"  id="email" name="email" placeholder="{{__('Email')}}">
                    </div>
                    <button id="submit" type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                </form>
            </div>
        </div>
    </div>
        @endcan
</div>


        <div class="modal fade" id="send_mail_to_subscriber_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('Send Mail To Subscriber')}}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="{{route(route_prefix().'admin.newsletter.single.mail')}}" id="send_mail_to_subscriber_edit_modal_form"  method="post">
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <label for="email">{{__('Email')}}</label>
                                <input type="text" readonly class="form-control"  id="email" name="email" placeholder="{{__('Email')}}">
                                <input type="hidden" id="newsletter_id" name="id">
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
        (function ($){
            "use strict";
            $(document).ready(function () {
                <x-bulk-action-js :url="route(route_prefix().'admin.newsletter.bulk.action')" />

                    $(document).on('click','.send_mail_modal_btn',function(){
                        var el = $(this);
                        var id = el.data('id');
                        var email = el.data('email');
                        var form = $('#send_mail_to_subscriber_edit_modal_form');
                        form.find('#newsletter_id').val(id);
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
