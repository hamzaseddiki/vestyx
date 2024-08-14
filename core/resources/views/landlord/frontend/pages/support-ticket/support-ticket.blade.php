@extends('landlord.frontend.frontend-page-master')

@section('title')

 {{__('New Support Ticket')}}
@endsection
@section('page-title')
   {{__('New Support Ticket')}}
@endsection

@section('meta-data')
    <meta name="description" content="{{get_static_option('support_ticket_page_'.$user_select_lang_slug.'_meta_description')}}">
    <meta name="tags" content="{{get_static_option('support_ticket_page_'.$user_select_lang_slug.'_meta_tags')}}">
    {!! render_og_meta_image_by_attachment_id(get_static_option('support_ticket_page_'.$user_select_lang_slug.'_meta_image')) !!}
@endsection

@section('content')
    <section class="support-ticket-page-area" data-padding-bottom="100"data-padding-top="100">
        <div class="container">
            <div class="row justify-content-center">
               <div class="col-lg-8">
                   <div class="support-ticket-wrapper">
                       @if(auth()->guard('web')->check())
                           <a href="{{route('landlord.user.home')}}" class="btn btn-secondary mb-3">{{__('Go to Dashboard')}}</a>
                           <h3 class="title mb-4">{{get_static_option('support_ticket_'.$user_select_lang_slug.'_form_title')}}</h3>
                           <x-error-msg/>
                           <x-flash-msg/>
                           <form action="{{route('landlord.frontend.support.ticket.store')}}" method="post" class="support-ticket-form-wrapper" enctype="multipart/form-data">
                               @csrf
                               <input type="hidden" name="via" value="{{__('website')}}">
                                <div class="form-group">
                                    <label>{{__('Title')}}</label>
                                    <input type="text" class="form-control" name="title" placeholder="{{__('Title')}}">
                                </div>
                               <div class="form-group">
                                   <label>{{__('Subject')}}</label>
                                    <input type="text" class="form-control" name="subject" placeholder="{{__('Subject')}}">
                                </div>
                               <div class="form-group">
                                   <label>{{__('Priority')}}</label>
                                   <select name="priority" class="form-control">
                                       <option value="low">{{__('Low')}}</option>
                                       <option value="medium">{{__('Medium')}}</option>
                                       <option value="high">{{__('High')}}</option>
                                       <option value="urgent">{{__('Urgent')}}</option>
                                   </select>
                               </div>
                               <div class="form-group">
                                   <label>{{__('Departments')}}</label>
                                   <select name="departments" class="form-control">
                                       @foreach($departments as $dep)
                                       <option value="{{$dep->id}}">{{$dep->getTranslation('name',$user_select_lang_slug)}}</option>
                                       @endforeach
                                   </select>
                               </div>
                               <div class="form-group">
                                   <label>{{__('Description')}}</label>
                                   <textarea name="description"class="form-control" cols="30" rows="5" placeholder="{{__('Description')}}"></textarea>
                               </div>
                              <div class="btn-wrapper">
                                  <button type="submit" class="cmn-btn cmn-btn-bg-1">{{get_static_option('support_ticket_'.$user_select_lang_slug.'_button_text')}}</button>
                              </div>
                           </form>
                       @else
                           @include('tenant.frontend.partials.ajax-login-form',['title' => get_static_option('support_ticket_'.$user_select_lang_slug.'_login_notice')])
                       @endif
                   </div>
               </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    @include('landlord.frontend.partials.ajax-login-form-js')
@endsection
