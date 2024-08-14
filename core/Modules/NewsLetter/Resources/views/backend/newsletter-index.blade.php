@extends('backend.admin-master')
@section('style')
    <x-summernote.css/>
    <link rel="stylesheet" href="{{asset('assets/admin/css/dropzone.css')}}">
    <x-datatable.css/>
@endsection
@section('site-title')
    {{__('All Newsletter')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40 m-0">
                    <x-flash-msg/>
                    <x-error-msg/>
                </div>
            </div>
            <div class="col-lg-12 mt-2">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">{{__('All Newsletter Subscriber')}}</h4>
                        <div class="row py-0 mb-3">
                            <div class="col-md-6">
                                @can('newsletter-delete')
                                    <div class="bulk-delete-wrapper">
                                        <div class="select-box-wrap d-flex">
                                            <select name="bulk_option" id="bulk_option">
                                                <option value="">{{{__('Bulk Action')}}}</option>
                                                <option value="delete">{{{__('Delete')}}}</option>
                                            </select>
                                            <button class="btn btn-primary btn-sm px-5" id="bulk_delete_btn">{{__('Apply')}}</button>
                                        </div>
                                    </div>
                                @endcan
                            </div>
                            <div class="col-md-6 d-flex justify-content-end">
                                <div>
                                    <button class="btn btn-sm btn-info text-white py-2" data-bs-toggle="modal" data-bs-target="#new_subscribe_model">Add New Subscribe</button>
                                </div>
                            </div>
                        </div>

                        <div class="table-wrap">
                            <table class="table table-default data-table-style newsLetterTable">
                                <thead>
                                <th class="no-sort">
                                    <div class="mark-all-checkbox">
                                        <input type="checkbox" class="all-checkbox">
                                    </div>
                                </th>
                                <th>{{__('ID')}}</th>
                                <th>{{__('Email')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                @foreach($all_subscriber as $data)
                                    <tr>
                                        <td>
                                            <div class="bulk-checkbox-wrapper">
                                                <input type="checkbox" class="bulk-checkbox" name="bulk_delete[]" value="{{$data->id}}">
                                            </div>
                                        </td>
                                        <td>{{$data->id}}</td>
                                        <td>{{$data->email}} @if($data->verified > 0) <i class="fas fa-check-circle"></i>@endif</td>
                                        <td>
                                            @can('newsletter-delete')
                                            <x-delete-popover :url="route('admin.newsletter.delete',$data->id)"/>
                                            @endcan
                                            @can('newsletter-mail-send')
                                            <a class="btn btn-lg btn-primary btn-sm mb-3 mr-2 send_mail_modal_btn ml-2"
                                               href="#"
                                               data-bs-toggle="modal"
                                               data-bs-target="#send_mail_to_subscriber_modal"
                                               data-email="{{$data->email}}"
                                            >
                                                <i class="ti-email"></i>
                                            </a>
                                            @if($data->verified <1)
                                                <form class="mb-3 mr-1 d-inline float-left mr-2" action="{{route('admin.newsletter.verify.mail.send')}}" method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$data->id}}">
                                                    <button class="btn btn-sm btn-secondary" type="submit" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __("Send Verify Mail") }}">
                                                        <i class="las la-plus"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @can('newsletter-mail-send')
    <div class="modal fade" id="new_subscribe_model" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content px-4">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Add New Subscriber')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                </div>
                <form action="{{route('admin.newsletter.new.add')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="email">{{__('Email')}}</label>
                        <input type="text" class="form-control"  id="email" name="email" placeholder="{{__('Email')}}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button id="submit" type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="send_mail_to_subscriber_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Send Mail To Subscriber')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                </div>
                <form action="{{route('admin.newsletter.single.mail')}}" id="send_mail_to_subscriber_edit_modal_form"  method="post">
                    <div class="modal-body">
                        @csrf
                        <div class="d-none form-group">
                            <label for="email">{{__('Email')}}</label>
                            <input type="email" class="form-control"  id="email" name="email" placeholder="{{__('Email')}}">
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
    @endcan
   <x-media.markup/>
@endsection

@section('script')
    <x-datatable.js/>
    <script src="{{asset('assets/admin/js/summernote-bs4.js')}}"></script>
    <script src="{{asset('assets/admin/js/dropzone.js')}}"></script>

    <script>
        (function ($){
            "use strict";
            $(document).ready(function () {
                <x-bulk-action-js :url="route('admin.newsletter.bulk.action')" />
                    <x-btn.submit />
                $(document).on('click','.send_mail_modal_btn',function(){
                    var el = $(this);
                    var email = el.data('email');
                    var form = $('#send_mail_to_subscriber_edit_modal_form');
                    form.find('#email').val(email);
                });

                $(document).on('click', '.swal_delete_button', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '{{ __('Are you sure?') }}',
                        text: '{{ __('You would not be able to revert this item!') }}',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $(this).next().find('.swal_form_submit_btn').trigger('click');
                        }
                    });
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
