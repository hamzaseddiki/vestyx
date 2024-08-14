@extends(route_prefix().'admin.admin-master')

@section('title')
    {{__('Ticket Page Settings')}}
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                <x-flash-msg/>
                <x-error-msg/>
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__("Ticket Page Settings")}}</h4>
                        <form action="{{route(route_prefix().'admin.support.ticket.page.settings')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <x-lang-tab>
                                @foreach(\App\Facades\GlobalLanguage::all_languages() as $lang)
                                    <x-slot :name="$lang->slug">
                                        <div class="form-group">
                                            <label for="support_ticket_{{$lang->slug}}_login_notice">{{__('Login Notice')}}</label>
                                            <input type="text" name="support_ticket_{{$lang->slug}}_login_notice"  class="form-control" value="{{get_static_option('support_ticket_'.$lang->slug.'_login_notice')}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="support_ticket_{{$lang->slug}}_form_title">{{__('Form Title')}}</label>
                                            <input type="text" name="support_ticket_{{$lang->slug}}_form_title"  class="form-control" value="{{get_static_option('support_ticket_'.$lang->slug.'_form_title')}}" >
                                        </div>
                                        <div class="form-group">
                                            <label for="support_ticket_{{$lang->slug}}_button_text">{{__('Button Text')}}</label>
                                            <input type="text" name="support_ticket_{{$lang->slug}}_button_text"  class="form-control" value="{{get_static_option('support_ticket_'.$lang->slug.'_button_text')}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="support_ticket_{{$lang->slug}}_success_message">{{__('Success Message')}}</label>
                                            <input type="text" name="support_ticket_{{$lang->slug}}_success_message"  class="form-control" value="{{get_static_option('support_ticket_'.$lang->slug.'_success_message')}}">
                                        </div>
                                    </x-slot>
                                @endforeach
                            </x-lang-tab>

                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
