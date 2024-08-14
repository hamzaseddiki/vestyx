@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('User Reset Password Email Template')}}
@endsection
@section('style')
    <x-summernote.css/>
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
                        <div class="header-wrapp d-flex justify-content-between">
                            <h4 class="header-title">{{__('User Reset Password Email Template')}}</h4>
                            <a class="btn btn-info" href="{{route(route_prefix().'admin.email.template.all')}}">{{__('All Email Templates')}}</a>
                        </div>
                        <form action="{{route(route_prefix().'admin.email.template.user.password.reset')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <x-lang-tab>
                                @foreach(\App\Facades\GlobalLanguage::all_languages(1) as $lang)
                                    @php $slug = $lang->slug; @endphp
                                    <x-slot :name="$slug">
                                        <div class="form-group">
                                            <label for="user_reset_password_{{$lang->slug}}_subject">{{__('Subject')}}</label>
                                            <input type="text" name="user_reset_password_{{$lang->slug}}_subject"  class="form-control" value="{{get_static_option('user_reset_password_'.$lang->slug.'_subject')}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="user_reset_password_{{$lang->slug}}_message">{{__('Message')}}</label>
                                            <input type="hidden" name="user_reset_password_{{$lang->slug}}_message"  class="form-control" value="{{get_static_option('user_reset_password_'.$lang->slug.'_message')}}" >
                                            <div class="summernote" data-content='{{get_static_option('user_reset_password_'.$lang->slug.'_message')}}'></div>

                                            <br>
                                            <small class="text-primary">{{__('You can use these placeholders given bellow, in to the message body')}}</small>
                                            <br>
                                            <small class="form-text text-muted text-danger margin-top-20"><br><code>@name</code> {{__('will be replaced by dynamically with  name.')}}</small>
                                            <small class="form-text text-muted text-danger"><br><code>@username</code>{{__('will be replaced by dynamically with username.')}}</small>
                                            <small class="form-text text-muted text-danger"><br><code>@reset_url</code> {{__('will be replaced by dynamically with password reset url.')}}</small>
                                        </div>
                                    </x-slot>
                                @endforeach
                            </x-lang-tab>
                            <button type="submit" class="btn btn-primary pr-4 pl-4">{{__('Save Changes')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <x-summernote.js/>
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

@endsection
