@extends(route_prefix().'admin.admin-master')
@section('title') {{__('Edit Page')}} @endsection
@section('style')
    <x-media-upload.css/>
    <x-summernote.css/>
@endsection
@section('content')
    @php
        $lang_slug = request()->get('lang') ?? \App\Facades\GlobalLanguage::default_slug();
    @endphp
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <x-admin.header-wrapper>
                    <x-slot name="left">
                        <h4 class="card-title mb-5">{{__('Edit Page')}}</h4>
                    </x-slot>
                    <x-slot name="right" class="d-flex">
                        <form action="{{route(route_prefix().'admin.pages.edit',$page->id)}}" method="get">
                            <x-fields.select name="lang" title="{{__('Language')}}">
                                @foreach(\App\Facades\GlobalLanguage::all_languages(1) as $lang)
                                    <option value="{{$lang->slug}}" @if($lang->slug === $lang_slug) selected @endif>{{$lang->name}}</option>

                                @endforeach
                            </x-fields.select>
                        </form>
                        <p></p>
                        <x-link-with-popover class="info" url="{{route(route_prefix().'admin.pages.create')}}" extraclass="ml-3">
                            {{__('Create New Page')}}
                        </x-link-with-popover>
                        <x-link-with-popover url="{{route(route_prefix().'admin.pages')}}" extraclass="ml-3">
                            {{__('All Pages')}}
                        </x-link-with-popover>
                    </x-slot>
                </x-admin.header-wrapper>
                <x-error-msg/>
                <x-flash-msg/>
                <form class="forms-sample" method="post" action="{{route(route_prefix().'admin.pages.update')}}">
                    @csrf
                    <x-fields.input type="hidden" name="lang"  value="{{$lang_slug}}"/>
                    <x-fields.input type="hidden" name="id"  value="{{$page->id}}"/>

                    <div class="row">
                        <div class="col-lg-9">
                            <x-fields.input name="title" label="{{__('Title')}}" value="{!! $page->getTranslation('title',$lang_slug) !!}" id="title"/>

                            <x-slug.edit-markup value="{{$page->slug}}"/>

                            <x-summernote.textarea label="{{__('Page Content')}}" name="page_content" value="{!! $page->getTranslation('page_content',$lang_slug) !!}"/>

                            <div class="meta-information-wrapper">
                                <h4 class="mb-4">{{__('Meta Information For SEO')}}</h4>
                                <div class="d-flex align-items-start mb-8 metainfo-inner-wrap">
                                    <div class="nav flex-column nav-pills me-3" role="tablist" aria-orientation="vertical">
                                        <button class="nav-link active"  data-bs-toggle="pill" data-bs-target="#v-general-meta-info" type="button" role="tab"  aria-selected="true">
                                            {{__('General Meta Info')}}</button>
                                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#v-facebook-meta-info" type="button" role="tab"  aria-selected="false">
                                            {{__('Facebook Meta Info')}}</button>
                                        <button class="nav-link"  data-bs-toggle="pill" data-bs-target="#v-twitter-meta-info" type="button" role="tab"  aria-selected="false">
                                            {{__('Twitter Meta Info')}}
                                        </button>
                                    </div>
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="v-general-meta-info" role="tabpanel" >
                                            <x-fields.input name="meta_title" label="{{__('Meta Title')}}"  value="{{optional($page->metainfo)->getTranslation('title',$lang_slug)}}" />
                                            <x-fields.textarea name="meta_description" label="{{__('Meta Description')}}"  value="{{optional($page->metainfo)->getTranslation('description',$lang_slug)}}" />
                                            <x-fields.media-upload name="meta_image" title="{{__('Meta Image')}}" dimentions="1200x1200" id="{{optional($page->metainfo)->image}}" />
                                        </div>
                                        <div class="tab-pane fade" id="v-facebook-meta-info" role="tabpanel" >
                                            <x-fields.input name="meta_fb_title" label="{{__('Meta Title')}}" value="{{optional($page->metainfo)->fb_title}}" />
                                            <x-fields.textarea name="meta_fb_description" label="{{__('Meta Description')}}"  value="{{optional($page->metainfo)->fb_description}}" />
                                            <x-fields.media-upload name="fb_image" title="{{__('Meta Image')}}" dimentions="1200x1200" id="{{optional($page->metainfo)->fb_image}}"/>
                                        </div>
                                        <div class="tab-pane fade" id="v-twitter-meta-info" role="tabpanel" >
                                            <x-fields.input name="meta_tw_title" label="{{__('Meta Title')}}"  value="{{optional($page->metainfo)->tw_title}}"  />
                                            <x-fields.textarea name="meta_tw_description" label="{{__('Meta Description')}}" value="{{optional($page->metainfo)->tw_description}}"  />
                                            <x-fields.media-upload name="tw_image" title="{{__('Meta Image')}}" dimentions="1200x1200" id="{{optional($page->metainfo)->tw_image}}"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <x-fields.select name="visibility" title="{{__('Visibility')}}" info="{{__('if you select users only, then this page can only be accessable by logged in users')}}">
                                <option @if($page->visibility === 0) selected @endif value="0">{{__('Everyone')}}</option>
                                <option @if($page->visibility === 1) selected @endif value="1">{{__('Users Only')}}</option>
                            </x-fields.select>
                            <x-fields.switcher name="breadcrumb" label="{{__('Enable/Disable Breadcrumb')}}" value="{{$page->breadcrumb}}"/>
                            <x-fields.switcher name="page_builder" label="{{__('Enable/Disable Page Builder')}}" value="{{$page->page_builder}}"/>
                            <x-fields.select name="status" title="{{__('Status')}}">
                                <option  @if($page->status === 1) selected @endif value="1">{{__('Publish')}}</option>
                                <option  @if($page->status === 0) selected @endif value="0">{{__('Draft')}}</option>
                            </x-fields.select>
                            <button type="submit" class="btn btn-gradient-primary me-2 mt-5">{{__('Save Changes')}}</button>
                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>
    <x-media-upload.markup/>
@endsection
@section('scripts')
    <x-media-upload.js/>
    <x-summernote.js/>
    <x-slug.js.edit :module="'page'"/>
    <script>

        $(document).ready(function(){
            //Permalink Code

            $(document).on('change','select[name="lang"]',function (e){
                $(this).closest('form').trigger('submit');
                $('input[name="lang"]').val($(this).val());
            });

        });
    </script>
@endsection
