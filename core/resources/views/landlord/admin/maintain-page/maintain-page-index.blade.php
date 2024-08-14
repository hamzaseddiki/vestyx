@extends(route_prefix().'admin.admin-master')

@section('title')
    {{__('Maintain Page Settings')}}
@endsection
@section('style')
    <x-media-upload.css/>
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
                <x-error-msg/>
                <x-flash-msg/>
            </div>
            <div class="col-lg-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__('Maintain Page Settings')}}</h4>
                        <form action="{{route(route_prefix().'admin.maintains.page.settings')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <x-lang-tab>
                                @foreach(\App\Facades\GlobalLanguage::all_languages() as $lang)
                                    @php
                                        $slug = $lang->slug;
                                    @endphp
                                    <x-slot :name="$slug">
                                        <x-fields.input type="text" value="{{get_static_option('maintains_page_'.$lang->slug.'_title')}}" name="maintains_page_{{$lang->slug}}_title" label="{{__('Title')}}"/>
                                        <x-fields.textarea type="text" value="{{get_static_option('maintains_page_'.$lang->slug.'_description')}}" name="maintains_page_{{ $lang->slug}}_description" label="{{__('Description')}}"/>
                                    </x-slot>
                                @endforeach
                            </x-lang-tab>

                           <div class="form-group">
                               <label for="">{{__('Comming Back Date')}}</label>
                               <input type="date" name="mentenance_back_date" class="form-control mt-2 date" value="{{ get_static_option('mentenance_back_date') ?? ''}}" id="maintenance_date">
                           </div>

                            <x-fields.media-upload name="maintenance_logo" title="{{__('Maintenance Logo')}}"/>
                            <x-fields.media-upload name="maintenance_bg_image" title="{{__('Maintenance Background Image')}}"/>

                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Settings')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media-upload.markup/>
@endsection

@section('scripts')
    <x-media-upload.js/>
    <script>
        //Date Picker
        flatpickr('#maintenance_date', {
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d H:i",
        });
    </script>
@endsection

