@extends('tenant.admin.admin-master')

@section('title')
    {{__('All Donations Settings')}}
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12">
                <x-flash-msg/>
                <x-error-msg/>
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">{{__("Donation Settings")}}</h4>
                        <form action="{{route('tenant.admin.donation.settings')}}" method="POST"
                              enctype="multipart/form-data">
                            @csrf

                    <x-lang-tab>
                        @foreach(\App\Facades\GlobalLanguage::all_languages() as $lang)
                            @php $slug = $lang->slug; @endphp
                            <x-slot :name="$slug">

                            <div class="form-group">
                                <label for="donation_raised_text">{{__('Raised Text')}}</label>
                                <input type="text" name="donation_raised_{{$slug}}_text" class="form-control"
                                       value="{{get_static_option('donation_raised_'.$slug.'_text')}}" id="donation_raised_text">
                            </div>
                            <div class="form-group">
                                <label for="donation_goal_text">{{__('Goal Text')}}</label>
                                <input type="text" name="donation_goal_{{$slug}}_text" class="form-control"
                                  value="{{get_static_option('donation_goal_'.$slug.'_text')}}" id="donation_goal_text">
                            </div>

                                <div class="form-group">
                                    <label for="donation_goal_text">{{__('Footer Contact Title ')}}</label>
                                    <input type="text" name="footer_contact_{{$slug}}_title" class="form-control"
                                     value="{{get_static_option('footer_contact_'.$slug.'_title')}}">
                                </div>

                                <div class="form-group">
                                    <label for="donation_goal_text">{{__('Footer Contact Description ')}}</label>
                                    <input type="text" name="footer_contact_{{$slug}}_description" class="form-control"
                                           value="{{get_static_option('footer_contact_'.$slug.'_description')}}">
                                </div>

                                <div class="form-group">
                                    <label for="donation_goal_text">{{__('Footer Contact Left Button Text ')}}</label>
                                    <input type="text" name="footer_contact_left_button_{{$slug}}_text" class="form-control"
                                     value="{{get_static_option('footer_contact_left_button_'.$slug.'_text')}}">
                                </div>

                                <div class="form-group">
                                    <label for="donation_goal_text">{{__('Footer Contact Right Button Text')}}</label>
                                    <input type="text" name="footer_contact_right_button_{{$slug}}_text" class="form-control"
                                     value="{{get_static_option('footer_contact_right_button_'.$slug.'_text')}}">
                                </div>

                                <div class="form-group">
                                    <label for="donation_goal_text">{{__('Footer Contact Left Button URL ')}}</label>
                                    <input type="text" name="footer_contact_left_button_{{$slug}}_url" class="form-control"
                                           value="{{get_static_option('footer_contact_left_button_'.$slug.'_url')}}">
                                </div>

                                <div class="form-group">
                                    <label for="donation_goal_text">{{__('Footer Contact Right Button URL')}}</label>
                                    <input type="text" name="footer_contact_right_button_{{$slug}}_url" class="form-control"
                                           value="{{get_static_option('footer_contact_right_button_'.$slug.'_url')}}">
                                </div>

                            </x-slot>
                        @endforeach
                    </x-lang-tab>

                            <div class="form-group">
                                <label for="donation_goal_text">{{__('Donation Receiving alert Mail')}}</label>
                                <input type="text" name="donation_alert_receiving_mail" class="form-control"
                                       value="{{get_static_option('donation_alert_receiving_mail')}}" id="donation_alert_receiving_mail">
                            </div>

                            <div class="form-group">
                                <label for="donation_custom_amount">{{__('Custom Donation Amount')}}</label>
                                <input type="text" name="donation_custom_amount"  class="form-control" value="{{get_static_option('donation_custom_amount')}}" id="donation_custom_amount_once">
                                <p>{{__('Separate amount by comma (,)')}}</p>
                            </div>

                            <div class="form-group">
                                <label for="donation_default_amount">{{__('Default Donation Amount')}}</label>
                                <input type="number" name="donation_default_amount"  class="form-control" value="{{get_static_option('donation_default_amount')}}" id="donation_default_amount">
                            </div>

                            <div class="form-group">
                                <label for="navbar_button">{{__('Show/Hide Donation Countdown')}}</label>
                                <label class="switch">
                                    <input type="checkbox" name="donation_single_page_countdown_status"
                                           @if(!empty(get_static_option('donation_single_page_countdown_status'))) checked @endif >
                                    <span class="slider"></span>
                                </label>
                            </div>

                            <div class="form-group">
                                <label for="navbar_button">{{__('Show/Hide Donation FAQ')}}</label>
                                <label class="switch">
                                    <input type="checkbox" name="donation_faq_show_hide"
                                           @if(!empty(get_static_option('donation_faq_show_hide'))) checked @endif >
                                    <span class="slider"></span>
                                </label>
                            </div>


                            <div class="form-group">
                                <label for="navbar_button">{{__('Show/Hide Donation Comments')}}</label>
                                <label class="switch">
                                    <input type="checkbox" name="donation_comments_show_hide"
                                           @if(!empty(get_static_option('donation_comments_show_hide'))) checked @endif >
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="navbar_button">{{__('Show/Hide Social Share ')}}</label>
                                <label class="switch">
                                    <input type="checkbox" name="donation_social_icons_show_hide"
                                           @if(!empty(get_static_option('donation_social_icons_show_hide'))) checked @endif >
                                    <span class="slider"></span>
                                </label>
                            </div>

                            <div class="form-group">
                                <label for="navbar_button">{{__('Show/Hide Recent Donors')}}</label>
                                <label class="switch">
                                    <input type="checkbox" name="donation_recent_donors_show_hide"
                                           @if(!empty(get_static_option('donation_recent_donors_show_hide'))) checked @endif >
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <button id="update" type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
