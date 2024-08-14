@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('Order Cancel Page Settings')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row g-4">
            <div class="col-12">
                <x-error-msg/>
                <x-flash-msg/>
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">{{__("Order Cancel Page Settings")}}</h4>
                        <form action="{{route(route_prefix().'admin.product.order.cancel.page')}}" method="POST"
                              enctype="multipart/form-data">
                            @csrf

                        <x-lang-tab>
                            @foreach(\App\Facades\GlobalLanguage::all_languages() as $lang)
                                @php $slug = $lang->slug; @endphp
                                <x-slot :name="$slug">

                            <div class="form-group">
                                <label for="site_order_cancel_page_title">{{__('Main Title')}}</label>
                                <input type="text" name="site_order_cancel_page_{{$lang->slug}}_title" class="form-control"
                                       value="{{get_static_option('site_order_cancel_page_'.$lang->slug.'_title')}}"
                                       id="site_order_cancel_page_title">
                            </div>
                            <div class="form-group">
                                <label for="site_order_cancel_page_subtitle">{{__('Subtitle')}}</label>
                                <input type="text" name="site_order_cancel_page_{{$lang->slug}}_subtitle" class="form-control"
                                       value="{{get_static_option('site_order_cancel_page_'.$lang->slug.'_subtitle')}}"
                                       id="site_order_cancel_page_subtitle">
                                <small class="info-text">{{__('{pkname} will be replaced by package name')}}</small>
                            </div>
                            <div class="form-group">
                                <label for="site_order_cancel_page_description">{{__('Description')}}</label>
                                <textarea name="site_order_cancel_page_{{$lang->slug}}_description" class="form-control"
                                          id="site_order_cancel_page_description" cols="30"
                                          rows="10">{{get_static_option('site_order_cancel_page_'.$lang->slug.'_description')}}</textarea>
                            </div>

                                </x-slot>
                            @endforeach
                        </x-lang-tab>

                            <button type="submit"
                                    class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
