@extends('backend.admin-master')
@section('site-title')
    {{__('Donation Page Settings')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                @include('backend.partials.message')
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__("Donation Page Settings")}}</h4>
                        <form action="{{route('admin.donations.page.settings')}}" method="POST" enctype="multipart/form-data">
                            @csrf


                            <div class="form-group">
                                <label for="donation_button_text">{{__('Donation Button Text')}}</label>
                                <input type="text" name="donation_button_text"  class="form-control" value="{{get_static_option('donation_button_text')}}" id="donation_button_text">
                            </div>
                            <div class="form-group">
                                <label for="donation_raised_text">{{__('Raised Text')}}</label>
                                <input type="text" name="donation_raised_text"  class="form-control" value="{{get_static_option('donation_raised_text')}}" id="donation_raised_text">
                            </div>
                            <div class="form-group">
                                <label for="donation_goal_text">{{__('Goal Text')}}</label>
                                <input type="text" name="donation_goal_text"  class="form-control" value="{{get_static_option('donation_goal_text')}}" id="donation_goal_text">
                            </div>

                            <div class="form-group">
                                <label for="site_events_post_items">{{__('Donation Items')}}</label>
                                <input type="text" name="donor_page_post_items"  class="form-control" value="{{get_static_option('donor_page_post_items')}}" id="donor_page_post_items">
                            </div>

                            <button id="update" type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
 <script>
     <x-btn.update/>
 </script>
@endsection
