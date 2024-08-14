@php
    $userlang = get_user_lang();
@endphp
<div class="contactArea section-padding" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row justify-content-center">

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

                    {!! \App\Helpers\FormBuilderCustom::render_form(optional($form_details)->id,null,null,'btn-default') !!}

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


