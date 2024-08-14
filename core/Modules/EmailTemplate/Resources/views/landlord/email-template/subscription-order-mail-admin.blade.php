@extends(route_prefix().'admin.admin-master')

@section('title')
    {{__('Subscription Order Mail Admin')}}
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
                            <h4 class="header-title">{{__('Subscription Order Mail Admin')}}</h4>
                            <a class="btn btn-info btn-sm" href="{{route(route_prefix().'admin.email.template.all')}}">{{__('All Email Templates')}}</a>
                        </div>
                        <form action="{{route('landlord.admin.subscription.order.mail.admin')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <x-lang-tab>
                                @foreach(\App\Facades\GlobalLanguage::all_languages(1) as $lang)
                                    @php $slug = $lang->slug; @endphp
                                    <x-slot :name="$slug">
                                        <div class="form-group">
                                            <label for="subscription_order_mail_admin_{{$lang->slug}}_subject">{{__('Subject')}}</label>
                                            <input type="text" name="subscription_order_mail_admin_{{$lang->slug}}_subject"  class="form-control" value="{{get_static_option('subscription_order_mail_admin_'.$lang->slug.'_subject')}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="user_email_verify_{{$lang->slug}}_message">{{__('Message')}}</label>
                                            <input type="hidden" name="subscription_order_mail_admin_{{$lang->slug}}_message"  class="form-control" value="{{get_static_option('subscription_order_mail_admin_'.$lang->slug.'_message')}}" >
                                            <div class="summernote" data-content='{{get_static_option('subscription_order_mail_admin_'.$lang->slug.'_message')}}'></div>
                                            <br>
                                            <small class="text-primary">{{__('You can use these placeholders given bellow, in to the message body')}}</small>
                                            <br>
                                            <br>
                                            <small class="form-text text-muted text-danger margin-top-20"><br><code>@user_name</code> {{__('will be replaced by dynamically with user name.')}}</small>
                                            <small class="form-text text-muted text-danger"><br><code>@user_email</code> {{__('will be replaced by dynamically with user email.')}}</small>
                                            <small class="form-text text-muted text-danger"><br><code>@order_id</code> {{__('will be replaced by dynamically with order id.')}}</small>
                                            <small class="form-text text-muted text-danger"><br><code>@order_amount</code> {{__('will be replaced by dynamically with order amount.')}}</small>
                                            <small class="form-text text-muted text-danger"><br><code>@package_name</code> {{__('will be replaced by dynamically with package name.')}}</small>
                                            <small class="form-text text-muted text-danger"><br><code>@payment_gateway</code> {{__('will be replaced by dynamically with payment gateway.')}}</small>
                                            <small class="form-text text-muted text-danger"><br><code>@payment_status</code> {{__('will be replaced by dynamically with payment status')}}</small>
                                            <small class="form-text text-muted text-danger"><br><code>@order_date</code> {{__('will be replaced by dynamically with order date')}}</small>
                                            <small class="form-text text-muted text-danger"><br><code>@domain</code> {{__('will be replaced by dynamically with given domain.')}}</small>
                                            <small class="form-text text-muted text-danger"><br><code>@subscription_heading_info</code> {{__('will be replaced by dynamically with dynamic heading info with renew or assign data.')}}</small>
                                            <small class="form-text text-muted text-danger"><br><code>@subscription_details_info</code> {{__('will be replaced by dynamically with table view block')}}</small>
                                            <small class="form-text text-muted text-danger margin-top-20"><br><code>@start_date</code> {{__('will be replaced with package start date')}}</small>
                                            <small class="form-text text-muted text-danger margin-top-20"><br><code>@expire_date</code> {{__('will be replaced with package expire date')}}</small>
                                            <small class="form-text text-muted text-danger margin-top-20"><br><code>@user_dashboard</code> {{__('will be replaced and redirect to user dashboard.')}}</small>
                                            <small class="form-text text-muted text-danger margin-top-20"><br><code>@site_title</code> {{__('will be replaced by dynamically with site title.')}}</small>
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
                height: 300,   //set editable area's height
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

@endsection
