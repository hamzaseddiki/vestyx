@extends('tenant.frontend.frontend-page-master')

@section('title')
     {{__('Support Ticket Create')}}
@endsection
@section('page-title')
   {{__('Support Ticket Create')}}
@endsection

@section('meta-data')
    <meta name="description" content="{{get_static_option('support_ticket_page_'.$user_select_lang_slug.'_meta_description')}}">
    <meta name="tags" content="{{get_static_option('support_ticket_page_'.$user_select_lang_slug.'_meta_tags')}}">
    {!! render_og_meta_image_by_attachment_id(get_static_option('support_ticket_page_'.$user_select_lang_slug.'_meta_image')) !!}
@endsection

@section('style')

@endsection

@section('content')


   @if(auth()->guard('web')->check())


       <form action="{{route('tenant.frontend.support.ticket.store')}}" method="post" class="support-ticket-form-wrapper" enctype="multipart/form-data">
           @csrf
           <input type="hidden" name="via" value="{{__('website')}}">
         <div class="PaymentArea section-padding">
            <div class="container">
                <div class="row justify-content-center">

                    <div class="col-xl-8 col-lg-9">

                        <div class="applyDetails mb-24">
                            <div class="section-tittle text-center mb-30">
                                <h2 class="tittle">{{get_static_option('support_ticket_'.$user_select_lang_slug.'_form_title')}}</h2>
                            </div>
                            <x-error-msg/>
                            <x-flash-msg/>
                            <form>
                                <div class="row">

                                    <div class="col-lg-12 col-md-12">
                                        <label class="catTittle"> {{__('Title')}}</label>
                                        <div class="input-form input-form2">
                                            <input type="text" name="title" placeholder="Enter your ticket Title">
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12">
                                        <label class="catTittle"> {{__('Ticket Subject')}}</label>
                                        <div class="input-form input-form2">
                                            <input type="text" name="subject" placeholder="Enter your ticket subject">
                                        </div>
                                    </div>


                                    <div class="col-lg-12 col-md-12">
                                        <label class="catTittle">{{__('Ticket Description ')}}</label>
                                        <div class="input-form input-form2">
                                            <textarea name="description" placeholder="Description"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12">
                                        <label class="catTittle">{{__('Priority ')}}</label>
                                        <select name="priority" class="form-control h-50">
                                            <option value="low">{{__('Low')}}</option>
                                            <option value="medium">{{__('Medium')}}</option>
                                            <option value="high">{{__('High')}}</option>
                                            <option value="urgent">{{__('Urgent')}}</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-12 col-md-12 my-3">
                                        <label class="catTittle">{{__('Departments')}}</label>
                                        <select name="departments" class="form-control h-50" >
                                            @foreach($departments as $dep)
                                                <option value="{{$dep->id}}">{{$dep->getTranslation('name',$user_select_lang_slug)}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-12 col-md-12">
                                        <div class="btn-wrapper mt-50">
                                            <button type="submit" class="cmn-btn4"> {{__('Submit a Ticket')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   @else
    <div class="PaymentArea section-padding">
       <div class="container">
           <div class="row justify-content-center">

               <div class="col-xl-8 col-lg-9">
                    @include('tenant.frontend.partials.ajax-login-form',['title' => get_static_option('support_ticket_'.$user_select_lang_slug.'_login_notice')])
               </div>
           </div>
       </div>
    </div>
   @endif



@endsection

@section('scripts')
    <script>
      $(document).ready(function(){
         // $('.nice-select').remove();
      })
    </script>
    @include('tenant.frontend.partials.ajax-login-form-js')
@endsection
