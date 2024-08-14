@php
    $userlang = get_user_lang();
@endphp
<div class="contactArea section-padding" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-7">
                <div class="row">
                    @php
                        $user_lang = default_lang();
                    @endphp



                    @foreach($data['repeater_data']['repeater_title_'.$user_lang] ?? [] as $key => $title)
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="singleContact text-center mb-24 wow fadeInUp" data-wow-delay="0.0s">
                                <div class="icon-wrap">
                                    <div class="icon"><i class="{{$data['repeater_data']['repeater_icon_'.$user_lang][$key] ?? ''}}"></i> </div>
                                </div>
                                <div class="cat-cap">
                                    <h5><a href="#" class="tittle">{{$title}}</a></h5>
                                    <p class="pera">{{$data['repeater_data']['repeater_info_'.$user_lang][$key] ?? ''}}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="footer-social mb-40 mt-20">
                            @if(!empty($data['facebook_url']))
                                <a href="{{$data['facebook_url'] ?? ''}}" class="wow ladeInUp social" data-wow-delay="0.0s"><i class="fab fa-facebook-f icon"></i></a>
                            @endif

                            @if(!empty($data['instagram_url']))
                                <a href="{{$data['instagram_url'] ?? ''}}" class="wow ladeInUp social" data-wow-delay="0.1s"><i class="fab fa-instagram icon"></i></a>
                            @endif

                            @if(!empty($data['linkedin_url']))
                                <a href="{{$data['linkedin_url'] ?? ''}}" class="wow ladeInUp social" data-wow-delay="0.2s"><i class="fab fa-linkedin-in icon"></i></a>
                            @endif

                            @if(!empty($data['twitter_url']))
                                <a href="{{$data['twitter_url'] ?? ''}}" class="wow ladeInUp social" data-wow-delay="0.3s"><i class="fab fa-twitter icon"></i></a>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <!-- Contact Map -->
                        <div class="mapArea">
                            <div class="mapWrapper-contact">
                                {!! $data['location'] !!}
                            </div>
                        </div>
                        <!-- End-of Map -->
                    </div>
                </div>
            </div>

            @php
                $default_theme = get_static_option('tenant_default_theme');
                $class_condition = '';

                switch ($default_theme){
                    case 'job-find';
                    case 'article-listing';
                    $class_condition = '2';
                    break;
                    default;
                }
            @endphp

            <div class="col-xl-7 col-lg-5">
                <div class="simplePresentCart{{$class_condition}} cart mb-24 contact_form">

                    <h2 class="tittle">{{$data['title']}}</h2>

                    @if(!empty($data['custom_form_id']))
                        @php
                            $form_details = \App\Models\FormBuilder::find($data['custom_form_id']);
                        @endphp
                    @endif

                    {!! \App\Helpers\FormBuilderCustom::render_form(optional(@$form_details)->id,null,null,'btn-default') !!}

                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script>
        $(document).on('submit', '.custom-form-builder-ten', function (e) {
            e.preventDefault();
            var btn = $('#contact_form_btn');
            var form = $(this);
            var formID = form.attr('id');
            var msgContainer =  form.find('.error-message');
            var formSelector = document.getElementById(formID);
            var formData = new FormData(formSelector);
            msgContainer.html('');
            $.ajax({
                url: "{{route(route_prefix().'frontend.form.builder.custom.submit')}}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{csrf_token()}}",
                },
                beforeSend:function (){
                    btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> {{__("Submiting..")}}');
                },
                processData: false,
                contentType: false,
                data:formData,
                success: function (data) {
                    form.find('.ajax-loading-wrap').removeClass('show').addClass('hide');
                    msgContainer.html('<div class="alert alert-'+data.type+'">' + data.msg + '</div>');
                    btn.text('{{__("Submit Message")}}');
                    form[0].reset();

                },
                error: function (data) {

                    form.find('.ajax-loading-wrap').removeClass('show').addClass('hide');
                    var errors = data.responseJSON.errors;
                    var markup = '<ul class="alert alert-danger">';

                    $.each(errors,function (index,value){
                        markup += '<li>'+value+'</li>';})
                    markup += '</ul>';


                    msgContainer.html(markup);
                    btn.text('{{__("Submit Message")}}');
                }
            });
        });
    </script>

@endsection


