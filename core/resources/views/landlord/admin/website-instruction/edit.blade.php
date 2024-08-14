@extends(route_prefix().'admin.admin-master')

@section('title')
    {{__('Edit Instruction')}}
@endsection

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
                        <h4 class="card-title mb-5">{{__('Edit Instruction')}}</h4><br>

                    </x-slot>
                    <x-slot name="right" class="d-flex">
                        <form action="" method="get">
                            <x-fields.select name="lang" title="{{__('Language')}}">
                                @foreach(\App\Models\Language::all() as $lang)
                                    <option value="{{$lang->slug}}"
                                            @if($lang->slug === $lang_slug) selected @endif>{{$lang->name}}</option>
                                @endforeach
                            </x-fields.select>
                        </form>
                        <p></p>
                        <x-link-with-popover url="{{route(route_prefix().'admin.tenant.website.instruction.all')}}" extraclass="ml-3">
                            {{__('All Instruction')}}
                        </x-link-with-popover>
                    </x-slot>
                </x-admin.header-wrapper>
                <x-error-msg/>
                <x-flash-msg/>

                <form class="forms-sample" method="post" action="{{route(route_prefix().'admin.tenant.website.instruction.update',$instruction->id)}}" enctype="multipart/form-data">
                    @csrf

                    <x-fields.input type="hidden" name="lang" value="{{$lang_slug}}"/>
                    <x-fields.input name="title" label="{{__('Title')}}" value="{{ $instruction->getTranslation('title',$lang_slug) }}"/>

                    <div class="form-group">
                        <label for="message">{{__('Description')}}</label>
                        <input type="hidden" name="description" value="{{ $instruction->getTranslation('description',$lang_slug) }}">
                        <div class="summernote">{{ $instruction->getTranslation('description',$lang_slug) }}</div>
                    </div>

                    <x-landlord-others.edit-media-upload-image :label="'Service Image'" :name="'image'" :value="$instruction->image" :size="'200*200'" />

                    <x-fields.select name="status" title="{{__('Status')}}">
                        <option value="1" {{ $instruction->status == 1 ? 'selected' : '' }}>{{__('Publish')}}</option>
                        <option value="0" {{ $instruction->status == 0 ? 'selected' : '' }}>{{__('Draft')}}</option>
                    </x-fields.select>

                    @php
                        $all_repeater_data =  !empty($instruction->repeater_data) ? unserialize($instruction->repeater_data,['class' => false]) : ['title' => ['']];
                    @endphp

                    @forelse($all_repeater_data['button_text'] ?? [] as $button_text)

                        <div class="iconbox-repeater-wrapper">
                            <div class="all-field-wrap">
                                <div class="form-group">
                                    <label for="faq">{{__('Button Text')}}</label>
                                    <input type="text" name="repeater_data[button_text][]" class="form-control" value="{{$button_text ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label for="faq_desc">{{__('Button Url')}}</label>
                                    <input type="text" name="repeater_data[button_url][]" class="form-control" value="{{$all_repeater_data['button_url'][$loop->index] ?? ''}}">
                                </div>
                                <div class="action-wrap">
                                    <span class="add"><i class="las la-plus"></i></span>
                                    <span class="remove"><i class="las la-trash"></i></span>
                                </div>
                            </div>
                        </div>

                     @empty
                       <div class="iconbox-repeater-wrapper">
                                <div class="all-field-wrap">
                                    <div class="form-group">
                                        <label for="faq">{{__('Button Text')}}</label>
                                        <input type="text" name="repeater_data[button_text][]" class="form-control"
                                               placeholder="{{__('Button Text')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="faq_desc">{{__('Button Url')}}</label>
                                        <input type="text" name="repeater_data[button_url][]" class="form-control"
                                               placeholder="{{__('Button Url')}}">
                                    </div>
                                    <div class="action-wrap">
                                        <span class="add"><i class="las la-plus"></i></span>
                                        <span class="remove"><i class="las la-trash"></i></span>
                                    </div>
                                </div>
                            </div>
                    @endforelse

                    <div class="form-group">
                        <br>
                        <small class="text-primary">{{__('You can use these placeholders given bellow, into your desired button url')}}</small>
                        <br>
                        <small class="form-text text-muted text-danger margin-top-20"><br><code>@url</code> {{__('will be redirected dynamically with base url settings.')}}</small>
                        <small class="form-text text-muted text-danger margin-top-20"><br><code>@color_settings</code> {{__('will be redirected dynamically with color settings.')}}</small>
                        <small class="form-text text-muted text-danger"><br><code>@logo_settings</code> {{__('will be redirected dynamically with logo settings')}}</small>
                        <small class="form-text text-muted text-danger"><br><code>@basic_settings</code> {{__('will be redirected dynamically with basic settings')}}</small>
                        <small class="form-text text-muted text-danger"><br><code>@page_settings</code> {{__('will be redirected dynamically with page settings')}}</small>
                        <small class="form-text text-muted text-danger"><br><code>@email_settings</code> {{__('will be redirected dynamically with email settings')}}</small>
                        <small class="form-text text-muted text-danger"><br><code>@edit_forms</code> {{__('will be redirected dynamically with edit forms settings')}}</small>
                        <small class="form-text text-muted text-danger"><br><code>@edit_pages</code> {{__('will be redirected dynamically with edit pages settings')}}</small>
                        <small class="form-text text-muted text-danger"><br><code>@customize_menu</code> {{__('will be redirected dynamically with customize menu settings')}}</small>
                        <small class="form-text text-muted text-danger"><br><code>@customize_footer</code> {{__('will be redirected dynamically with customize footer settings')}}</small>
                        <small class="form-text text-muted text-danger"><br><code>@connect_domain</code> {{__('will be redirected dynamically with custom domain settings')}}</small>
                        <small class="form-text text-muted text-danger"><br><code>@edit_seo</code> {{__('will be redirected dynamically with edit seo settings')}}</small>
                        <small class="form-text text-muted text-danger"><br><code>@edit_profile</code> {{__('will be redirected dynamically with edit profile settings')}}</small>
                        <small class="form-text text-muted text-danger"><br><code>@set_language</code> {{__('will be redirected dynamically with language settings')}}</small>
                    </div>


                    <button type="submit" class="btn btn-gradient-primary me-2 mt-5">{{__('Save Changes')}}</button>
                </form>
            </div>
        </div>
    </div>
    <x-media-upload.markup/>

@endsection

@section('scripts')
    <x-repeater/>
    <x-media-upload.js/>
    <x-summernote.js/>


    <script>
        <x-bulk-action-js :url="route( route_prefix().'admin.testimonial.bulk.action')" />


            $(document).on('change','select[name="lang"]',function (e){
                $(this).closest('form').trigger('submit');
                $('input[name="lang"]').val($(this).val());
            });

        $('.summernote').summernote({
            height: 130,   //set editable area's height
            codemirror: { // codemirror options
                theme: 'monokai'
            },
            callbacks: {
                onChange: function(contents, $editable) {
                    $(this).prev('input').val(contents);
                }
            }


        });
    </script>


@endsection
