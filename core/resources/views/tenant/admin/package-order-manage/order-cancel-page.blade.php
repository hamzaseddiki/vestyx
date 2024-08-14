@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('Order Cancel Page Settings')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                <x-error-msg/>
                <x-flash-msg/>
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__("Order Cancel Page Settings")}}</h4>
                        <form action="{{route(route_prefix().'admin.package.order.cancel.page')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <x-lang-tab>
                                @foreach(\App\Helpers\LanguageHelper::all_languages() as $lang)
                                    <x-slot :name="$lang->slug">
                                        <div class="form-group">
                                            <label for="site_order_cancel_page_{{$lang->slug}}_title">{{__('Main Title')}}</label>
                                            <input type="text" name="site_order_cancel_page_{{$lang->slug}}_title"  class="form-control" value="{{get_static_option('site_order_cancel_page_'.$lang->slug.'_title')}}" id="site_order_cancel_page_{{$lang->slug}}_title">
                                        </div>
                                        <div class="form-group">
                                            <label for="site_order_cancel_page_{{$lang->slug}}_subtitle">{{__('Subtitle')}}</label>
                                            <input type="text" name="site_order_cancel_page_{{$lang->slug}}_subtitle"  class="form-control" value="{{get_static_option('site_order_cancel_page_'.$lang->slug.'_subtitle')}}" id="site_order_cancel_page_{{$lang->slug}}_subtitle">
                                            <small class="info-text">{{__('{pkname} will be replaced by package name')}}</small>
                                        </div>
                                        <div class="form-group">
                                            <label for="site_order_cancel_page_{{$lang->slug}}_description">{{__('Description')}}</label>
                                            <textarea name="site_order_cancel_page_{{$lang->slug}}_description" class="form-control" id="site_order_cancel_page_{{$lang->slug}}_description" cols="30" rows="10">{{get_static_option('site_order_cancel_page_'.$lang->slug.'_description')}}</textarea>
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
