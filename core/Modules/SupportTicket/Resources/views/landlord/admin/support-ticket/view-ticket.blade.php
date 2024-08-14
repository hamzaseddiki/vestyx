@extends(route_prefix().'admin.admin-master')

@section('title') {{__('View Ticket')}} @endsection

@section('style')
    <x-summernote.css/>
    <x-media-upload.css/>
    <style>
        span.low,
        span.status-open {
            display: inline-block;
            background-color: #6bb17b;
            padding: 3px 10px;
            border-radius: 4px;
            color: #fff;
            text-transform: capitalize;
            border: none;
            font-weight: 600;
            font-size: 10px;
            margin: 3px;
        }

        span.high,
        span.status-close {
            display: inline-block;
            background-color: #c66060;
            padding: 3px 10px;
            border-radius: 4px;
            color: #fff;
            text-transform: capitalize;
            border: none;
            font-weight: 600;
            font-size: 10px;
            margin: 3px;
        }

        span.medium {
            display: inline-block;
            background-color: #70b9ae;
            padding: 3px 10px;
            border-radius: 4px;
            color: #fff;
            text-transform: capitalize;
            border: none;
            font-weight: 600;
            font-size: 10px;
            margin: 3px;
        }

        span.urgent {
            display: inline-block;
            background-color: #bfb55a;
            padding: 3px 10px;
            border-radius: 4px;
            color: #fff;
            text-transform: capitalize;
            border: none;
            font-weight: 600;
            font-size: 10px;
            margin: 3px;
        }

        /* support ticket  */

        .reply-message-wrap {
            padding: 40px;
            background-color: #fbf9f9;
        }

        .gig-message-start-wrap {
            margin-top: 60px;
            margin-bottom: 60px;
            background-color: #fbf9f9;
            padding: 40px;
        }

        .single-message-item {
            background-color: #e7ebec;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            margin-right: 80px;
        }

        .reply-message-wrap .title {
            font-size: 22px;
            line-height: 32px;
            margin-bottom: 40px;
            font-weight: 600;
        }

        .single-message-item.customer {
            background-color: #dadde0;
            text-align: left;
            margin-right: 0;
        }

        .reply-message-wrap .title {
            font-size: 22px;
            line-height: 32px;
            margin-bottom: 40px;
            font-weight: 600;
        }

        .gig-message-start-wrap .boxed-btn {
            padding: 8px 10px;
        }

        .reply-message-wrap .boxed-btn {
            padding: 8px 20px;
            font-size: 14px;
            font-weight: 500;
        }

        .reply-message-wrap textarea:focus {
            outline: none;
            box-shadow: none;
        }

        .reply-message-wrap textarea {
            border: 1px solid #e2e2e2;
        }

        .gig-message-start-wrap .title {
            font-size: 20px;
            line-height: 30px;
            margin-bottom: 40px;
            font-weight: 600;
        }

        .single-message-item .thumb .title {
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            width: 40px;
            height: 40px;
            line-height: 40px;
            background-color: #c7e5ec;
            display: inline-block;
            border-radius: 5px;
            text-align: center;
        }

        .single-message-item .title {
            font-size: 16px;
            line-height: 20px;
            margin: 10px 0 0px 0;
        }

        .single-message-item .time {
            display: block;
            font-size: 13px;
            margin-bottom: 20px;
            font-weight: 500;
            font-style: italic;
        }

        .single-message-item .thumb i {
            display: block;
            width: 100%;
        }

        .single-message-item.customer .thumb .title {
            background-color: #efd2d2;
        }

        .single-message-item .top-part {
            display: flex;
            margin-bottom: 25px;
        }

        .single-message-item .top-part .content {
            flex: 1;
            margin-left: 15px;
        }


        .anchor-btn {
            border-bottom: 1px solid var(--main-color-one);
            color: var(--main-color-one);
            display: inline-block;
        }

        .all-message-wrap.msg-row-reverse {
            display: flex;
            flex-direction: column-reverse;
            position: relative;
        }

        .load_all_conversation:focus {
            outline: none;
        }

        .load_all_conversation {
            border: none;
            background-color: #111D5C;
            border-radius: 30px;
            font-size: 14px;
            line-height: 20px;
            padding: 10px 30px;
            color: #fff;
            cursor: pointer;
            text-transform: capitalize;
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            font-weight: 500;
        }

        .single-message-item ol, .single-message-item ul {
            padding-left: 15px;
        }

        .anchor-btn {
            color: #345990;
            text-decoration: underline;
            margin: 5px 0;
        }
    </style>
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
            </div>
            <div class="col-lg-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <div class="gig-chat-message-heading">
                            <div class="header-wrap d-flex justify-content-between">
                                <h4 class="header-title">{{__('Support Ticket Details')}}</h4>
                                <a class="btn btn-primary btn-xs" href="{{route('landlord.admin.support.ticket.all')}}">{{__('All Tickets')}}</a>
                            </div>
                            <div class="gig-order-info">
                                <ul>
                                    <li><strong>{{__('Ticket ID:')}}</strong> #{{$ticket_details->id}}</li>
                                    <li><strong>{{__('Title:')}}</strong> {{$ticket_details->title}}</li>
                                    <li><strong>{{__('Subject:')}}</strong> {{$ticket_details->subject}}</li>
                                    <li><strong>{{__('Description:')}}</strong> {{$ticket_details->description}}</li>
                                    <li><strong>{{__('Status:')}}</strong> <span class="status-{{$ticket_details->status}}">{{$ticket_details->status}}</span></li>
                                    <li><strong>{{__('Priority:')}}</strong> <span class="{{$ticket_details->priority}}">{{$ticket_details->priority}}</span></li>
                                    <li><strong>{{__('User:')}}</strong> {{optional($ticket_details->user)->name ?? __('anonymous')}}</li>
                                    <li><strong>{{__('Department:')}}</strong> {{optional($ticket_details->department)->name ?? __('anonymous')}}</li>
                                    @if($ticket_details->admin_id)
                                        <li><strong>{{__('Admin:')}}</strong> {{optional($ticket_details->admin)->name ?? __('anonymous')}}</li>
                                    @endif
                                </ul>
                            </div>
                            <div class="gig-message-start-wrap">
                                <h2 class="title">{{__('All Conversation')}}</h2>
                                <div class="all-message-wrap @if($q == 'all') msg-row-reverse @endif">
                                    @if($q == 'all' && count($all_ticket_messages) > 1)
                                        <form action="" method="get">
                                            <input type="hidden" value="all" name="q">
                                            <button class="load_all_conversation" type="submit">{{__('load all message')}}</button>
                                        </form>
                                    @endif
                                        @forelse($all_ticket_messages as $msg)
                                            <div class="single-message-item @if($msg->type == 'customer') customer @endif">
                                                <div class="top-part">
                                                    <div class="thumb">
                                                <span class="title">
                                                        {{substr($msg->user_info()->name ?? 'Unknown',0,1)}}
                                                </span>
                                                        @if($msg->notify == 'on')
                                                            <i class="fas fa-envelope mt-2"
                                                               title="{{__('Notified by email')}}"></i>
                                                        @endif
                                                    </div>
                                                    <div class="content">
                                                        <h6 class="title">
                                                            {{$msg->user_info()->name ?? 'Unknown'}}
                                                        </h6>
                                                        <span class="time">{{date_format($msg->created_at,'d M Y H:i:s')}} | {{$msg->created_at->diffForHumans()}}</span>
                                                    </div>
                                                </div>
                                                <div class="content">
                                                    <div class="message-content">
                                                        {!! $msg->message !!}
                                                    </div>
                                                    @if(file_exists('assets/uploads/ticket/'.$msg->attachment))
                                                        <a href="{{asset('assets/uploads/ticket/'.$msg->attachment)}}"
                                                           download class="anchor-btn">{{$msg->attachment}}</a>
                                                    @endif
                                                </div>
                                            </div>
                                        @empty
                                            <p class="alert alert-warning">{{__('no message found')}}</p>
                                        @endforelse
                                </div>
                            </div>
                            <div class="reply-message-wrap ">
                                <h5 class="title">{{__('Replay To Message')}}</h5>
                               <x-error-msg/>
                                <x-flash-msg/>
                                <form action="{{route('landlord.admin.support.ticket.send.message')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" value="{{$ticket_details->id}}" name="ticket_id">
                                    <input type="hidden" value="admin" name="user_type">
                                    <div class="form-group">
                                        <label for="">{{__('Message')}}</label>
                                        <textarea name="message" class="form-control d-none" cols="30" rows="5" ></textarea>
                                        <div class="summernote"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="file">{{__('File')}}</label>
                                        <input type="file" name="file" accept="zip">
                                        <small class="info-text d-block text-danger">{{__('max file size 200mb, only zip file is allowed')}}</small>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" name="send_notify_mail" id="send_notify_mail">
                                        <label for="send_notify_mail">{{__('Notify Via Mail')}}</label>
                                    </div>
                                    <button class="btn-primary btn btn-md" type="submit">{{__('Send Message')}}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media-upload.markup/>
@endsection
@section('scripts')
    <x-media-upload.js/>
    <x-summernote.js/>
    <script>
        $(document).ready(function () {

            $('.summernote').summernote({
                height: 200,   //set editable area's height
                codemirror: { // codemirror options
                    theme: 'monokai'
                },
                callbacks: {
                    onChange: function(contents, $editable) {
                        $(this).prev('textarea').val(contents);
                    }
                }
            });

        });
    </script>
@endsection
