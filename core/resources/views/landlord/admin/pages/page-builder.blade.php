@extends(route_prefix().'admin.admin-master')
@section('title') {{__('Edit With Page Builder')}} @endsection
@section('style')
    <x-media-upload.css/>
    <x-summernote.css/>
    <x-pagebuilder::css/>
    <link rel="stylesheet" href="{{global_asset('assets/common/css/fontawesome-iconpicker.min.css')}}">
@endsection
@section('content')
    @php
        $lang_slug = request()->get('lang') ?? \App\Facades\GlobalLanguage::default_slug();
    @endphp
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body pagebuilderouterwarp">
                <x-admin.header-wrapper>
                    <x-slot name="left">
                        <h4 class="card-title mb-5">{{$page->getTranslation('title',$lang_slug)}} <br> <small class="text-small">{{__('Edit With Page Builder')}}</small></h4>

                    </x-slot>
                    <x-slot name="right" class="d-flex">

                        <p></p>
                        <x-link-with-popover url="{{route(route_prefix().'admin.pages')}}" extraclass="ml-3">
                            {{__('All Pages')}}
                        </x-link-with-popover>
                        <x-link-with-popover  class="info" target="_blank" url="{{route(route_prefix().'dynamic.page', $page->slug)}}" popover="{{__('view item in frontend')}}">
                            <i class="mdi mdi-eye"></i>
                        </x-link-with-popover>
                        <x-link-with-popover url="{{route(route_prefix().'admin.pages.edit', $page->id)}}">
                            <i class="mdi mdi-pencil"></i>
                        </x-link-with-popover>

                    </x-slot>
                </x-admin.header-wrapper>

            </div>
        </div>
    </div>
   <div class="row">
       <div class="col-lg-8">
           <div class="card pagebuilderouterwarp">
               <div class="card-body">
                   <x-pagebuilder::draggable location="dynamic_page" :page="$page"/>
               </div>
           </div>

       </div>
       <div class="col-lg-4">
           <div class="card page_builder_sidebar">
               <div class="card-body">
                   <x-pagebuilder::widgets type="landlord"/>
               </div>
           </div>
       </div>
   </div>
    <x-media-upload.markup/>
@endsection

@section('scripts')
    <script src="{{global_asset('assets/common/js/fontawesome-iconpicker.min.js')}}"></script>
    <script src="{{global_asset('assets/common/js/jquery.nice-select.min.js')}}"></script>
    <x-media-upload.js/>
    <x-summernote.js/>
    <x-pagebuilder::js/>
    <x-pagebuilder::script :page="$page"/>
    <script>
        $(document).ready(function(){
            //additional js code
        });
    </script>
@endsection
