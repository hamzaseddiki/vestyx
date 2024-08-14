@extends(route_prefix().'admin.admin-master')
@section('title') {{__('Edit Theme')}} @endsection
@section('style')
    <x-media-upload.css/>
    <x-summernote.css/>
@endsection
@section('content')
    @php
        $lang_slug = request()->get('lang') ?? \App\Facades\GlobalLanguage::default_slug();
    @endphp
    <div class="col-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <x-admin.header-wrapper>
                    <x-slot name="left">
                        <h4 class="card-title mb-4">{{__('Edit Theme')}}</h4>
                    </x-slot>
                    <x-slot name="right" class="d-flex">
                        <form action="{{route(route_prefix().'admin.theme.edit',$theme->id)}}" method="get">
                            <x-fields.select name="lang" title="{{__('Language')}}">
                                @foreach(\App\Facades\GlobalLanguage::all_languages() as $lang)
                                    <option value="{{$lang->slug}}" @if($lang->slug === $lang_slug) selected @endif>{{$lang->name}}</option>

                                @endforeach
                            </x-fields.select>
                        </form>
                        <p></p>
                        <x-link-with-popover class="info" url="{{route(route_prefix().'admin.theme.create')}}" extraclass="ml-3">
                            {{__('Create New Page')}}
                        </x-link-with-popover>
                        <x-link-with-popover url="{{route(route_prefix().'admin.theme')}}" extraclass="ml-3">
                            {{__('All Theme')}}
                        </x-link-with-popover>
                    </x-slot>
                </x-admin.header-wrapper>
                <x-error-msg/>
                <x-flash-msg/>
                <form class="forms-sample" method="post" action="{{route(route_prefix().'admin.theme.update')}}">
                    @csrf
                    <x-fields.input type="hidden" name="lang"  value="{{$lang_slug}}"/>
                    <x-fields.input type="hidden" name="id"  value="{{$theme->id}}"/>

                    <div class="row">
                        <div class="col-lg-9">
                            <x-fields.input name="title" label="{{__('Title')}}" value="{{$theme->getTranslation('title',$lang_slug)}}" id="title"/>

                            <x-summernote.textarea label="{{__('Theme Description')}}" name="description" value="{!! $theme->getTranslation('description',$lang_slug) !!}"/>

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
                                            <x-fields.input name="meta_title" label="{{__('Meta Title')}}"  value="{{optional($theme->metainfo)->getTranslation('title',$lang_slug)}}" />
                                            <x-fields.textarea name="meta_description" label="{{__('Meta Description')}}"  value="{{optional($theme->metainfo)->getTranslation('description',$lang_slug)}}" />
                                            <x-fields.media-upload name="meta_image" title="{{__('Meta Image')}}" dimentions="1200x1200" id="{{optional($theme->metainfo)->image}}" />
                                        </div>
                                        <div class="tab-pane fade" id="v-facebook-meta-info" role="tabpanel" >
                                            <x-fields.input name="meta_fb_title" label="{{__('Meta Title')}}" value="{{optional($theme->metainfo)->fb_title}}" />
                                            <x-fields.textarea name="meta_fb_description" label="{{__('Meta Description')}}"  value="{{optional($theme->metainfo)->fb_description}}" />
                                            <x-fields.media-upload name="fb_image" title="{{__('Meta Image')}}" dimentions="1200x1200" id="{{optional($theme->metainfo)->fb_image}}"/>
                                        </div>
                                        <div class="tab-pane fade" id="v-twitter-meta-info" role="tabpanel" >
                                            <x-fields.input name="meta_tw_title" label="{{__('Meta Title')}}"  value="{{optional($theme->metainfo)->tw_title}}"  />
                                            <x-fields.textarea name="meta_tw_description" label="{{__('Meta Description')}}" value="{{optional($theme->metainfo)->tw_description}}"  />
                                            <x-fields.media-upload name="tw_image" title="{{__('Meta Image')}}" dimentions="1200x1200" id="{{optional($theme->metainfo)->tw_image}}"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if((bool)tenant())
                                <div class="row mt-5">
                                    <div class="col-lg-12 bg-secondary py-3">
                                        @include('landlord.admin.partials.extras.navbar-variant-edit')
                                        @include('landlord.admin.partials.extras.footer-variant-edit')
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-3">
                            <x-fields.select name="status" title="{{__('Status')}}">
                                <option  @if($theme->status === 1) selected @endif value="1">{{__('Publish')}}</option>
                                <option  @if($theme->status === 0) selected @endif value="0">{{__('Draft')}}</option>
                            </x-fields.select>

                            <x-landlord-others.edit-media-upload-image :size="__('4 : 5 ratio image recommended')" :label="'Theme Image'" :name="'image'" :value="$theme->image"/>

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
    <script>
        $(document).ready(function(){
            //For Navbar
            var imgSelect1 = $('.img-select-nav');
            var id = $('#navbar_variant').val();
            imgSelect1.removeClass('selected');
            $('img[data-nav_id="'+id+'"]').parent().parent().addClass('selected');
            $(document).on('click','.img-select-nav img',function (e) {
                e.preventDefault();
                imgSelect1.removeClass('selected');

                $(this).parent().parent().addClass('selected').siblings();
                $('#navbar_variant').val($(this).data('nav_id'));
            })

            //For Footer
            var imgSelect2 = $('.img-select-footer');
            var idi = $('#footer_variant').val();
            imgSelect2.removeClass('selected');
            $('img[data-foot_id="'+idi+'"]').parent().parent().addClass('selected');
            $(document).on('click','.img-select-footer img',function (e) {
                e.preventDefault();
                imgSelect2.removeClass('selected');
                $(this).parent().parent().addClass('selected').siblings();
                $('#footer_variant').val($(this).data('foot_id'));
            })

        });
    </script>
@endsection
