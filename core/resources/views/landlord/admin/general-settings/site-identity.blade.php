
@extends(route_prefix().'admin.admin-master')
@section('title') {{__('Site Identity')}} @endsection
@section('style')
    <x-media-upload.css/>
@endsection
@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-5">{{__('Site Identity')}}</h4>
                <x-error-msg/>
                <x-flash-msg/>
                <form class="forms-sample" method="post" action="{{route(route_prefix().'admin.general.site.identity')}}">
                    @csrf
                    <x-fields.media-upload name="site_logo" title="{{__('Site Logo')}}" dimentions="{{__('100*100')}}"/>
                    <x-fields.media-upload name="site_white_logo" title="{{__('Site White Logo')}}" dimentions="{{__('100*100')}}"/>
                    <x-fields.media-upload name="site_favicon" title="{{__('Site Favicon')}}" dimentions="{{__('40*40')}}"/>

                    @if(!tenant())
                        <x-fields.media-upload name="breadcrumb_left_image" title="{{__('Breadcrumb Left Image')}}" dimentions="{{__('146*264')}}"/>
                        <x-fields.media-upload name="breadcrumb_right_image" title="{{__('Breadcrumb Right Image')}}" dimentions="{{__('146*264')}}"/>
                     @else
                        <x-fields.media-upload name="site_breadcrumb_image" title="{{__('Breadcrumb Image')}}" dimentions="{{__('1903*336')}}"/>
                    @endif
                    <button type="submit" class="btn btn-gradient-primary me-2">{{__('Save Changes')}}</button>
                </form>
            </div>
        </div>
    </div>
    <x-media-upload.markup/>
@endsection

@section('scripts')
    <x-media-upload.js/>
@endsection
