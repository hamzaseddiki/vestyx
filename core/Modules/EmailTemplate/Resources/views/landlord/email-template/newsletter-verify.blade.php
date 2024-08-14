@extends('backend.admin-master')
@section('site-title')
    {{__('Newsletter Verify Template')}}
@endsection
@section('style')
    <link rel="stylesheet" href="{{asset('assets/backend/css/summernote-bs4.css')}}">
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
                <x-msg.success/>
                <x-msg.error/>
            </div>
            <div class="col-lg-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <div class="header-wrapp d-flex justify-content-between">
                            <h4 class="header-title">{{__('Newsletter Verify  Template')}}</h4>
                            <a class="btn btn-info" href="{{route('admin.email.template.all')}}">{{__('All Email Templates')}}</a>
                        </div>
                        <form action="{{route('admin.email.template.newsletter.verify')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    @foreach($all_languages as $key => $lang)
                                        <a class="nav-item nav-link @if($key == 0) active @endif" id="nav-home-tab" data-toggle="tab" href="#nav-home-{{$lang->slug}}" role="tab" aria-controls="nav-home" aria-selected="true">{{$lang->name}}</a>
                                    @endforeach
                                </div>
                            </nav>
                            <div class="tab-content margin-top-30" id="nav-tabContent">
                                @foreach($all_languages as $key => $lang)
                                    <div class="tab-pane fade @if($key == 0) show active @endif" id="nav-home-{{$lang->slug}}" role="tabpanel" aria-labelledby="nav-home-tab">
                                        <div class="form-group">
                                            <label for="newsletter_verify_{{$lang->slug}}_subject">{{__('Subject')}}</label>
                                            <input type="text" name="newsletter_verify_{{$lang->slug}}_subject"  class="form-control" value="{{get_static_option('newsletter_verify_'.$lang->slug.'_subject')}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="newsletter_verify_{{$lang->slug}}_message">{{__('Message')}}</label>
                                            <input type="hidden" name="newsletter_verify_{{$lang->slug}}_message"  class="form-control" value="{{get_static_option('newsletter_verify_'.$lang->slug.'_message')}}" >
                                            <div class="summernote" data-content='{{get_static_option('newsletter_verify_'.$lang->slug.'_message')}}'></div>

                                            <small class="form-text text-muted text-danger"><code>@verify_code</code> {{__('will be replaced by dynamically with password reset url.')}}</small>
                                            <small class="form-text text-muted text-danger"><code>@site_title</code> {{__('will be replaced by dynamically with password reset url.')}}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Save Changes')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            var SummerNoteSelector = $('.summernote');
            SummerNoteSelector.summernote({
                height: 200,   //set editable area's height
                codemirror: { // codemirror options
                    theme: 'monokai'
                },
                callbacks: {
                    onChange: function(contents, $editable) {
                        $(this).prev('input').val(contents);
                    },
                    onPaste: function (e) {
                        var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                        e.preventDefault();
                        document.execCommand('insertText', false, bufferText);
                    }
                },
                toolbar: [
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                ],

            });
            if(SummerNoteSelector.length > 0){
                SummerNoteSelector.each(function(index,value){
                    $(this).summernote('code', $(this).data('content'));
                });
            }
        });
    </script>
    <script src="{{asset('assets/backend/js/summernote-bs4.js')}}"></script>
@endsection
