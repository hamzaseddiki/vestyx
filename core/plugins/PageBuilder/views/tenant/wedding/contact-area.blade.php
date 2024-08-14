<section class="weddingContact_area position-relative padding-top-50 padding-bottom-100">
    <div class="gradient_bg gradient_center">
        <span></span>
        <span></span>
    </div>

    <div class="wedding_contact__shape">
        <img src="{{global_asset('assets/tenant/frontend/themes/img/wedding/contact/wedding_flower2.png')}}" alt="">
    </div>
    <div class="container">
        <div class="row g-5 align-items-center justify-content-between">
            <div class="col-lg-6">
                <div class="weddingContact__thumb">
                    <div class="weddingContact__thumb__main">
                        {!! render_image_markup_by_attachment_id($data['left_image']) !!}
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="weddingContact">
                    <div class="weddingContact__single">
                        <div class="wedding_sectionTitle text-left">
                            <h2 class="title">{{$data['title']}}</h2>
                        </div>
                        <div class="weddingContact__form mt-4">
                            <form action="#" class="wedding_home_contact_form">
                                <div class="form-message-show mb-3"></div>
                                <div class="weddingContact__form__input">
                                    <input type="text" name="name" class="form--control" placeholder="Your Name">
                                </div>
                                <div class="weddingContact__form__input">
                                    <input type="text" name="email" class="form--control" placeholder="Your Email">
                                </div>
                                <div class="weddingContact__form__input">
                                    <textarea name="message" cols="30" rows="8" type="text" class="form--control" placeholder="Your Message"> </textarea>
                                </div>
                            </form>
                        </div>
                        <div class="btn-wrapper mt-4">
                            <a href="" class="wedding_cmn_btn btn_gradient_main extra_width radius-30 wedding_contact_btn">{{$data['button_text']}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



@section('scripts')
    <script>

        $(document).on('click', '.wedding_contact_btn', function (e) {
            e.preventDefault();

            let form = $('.wedding_home_contact_form');
            var name = form.find('input[name=name]').val();
            var email = form.find('input[name=email]').val();
            var message = form.find('textarea[name=message]').val();

            var button =$(this);
            var errrContaner = $('.form-message-show');

            $.ajax({
                url: "{{route('tenant.frontend.wedding.message.store.ajax')}}",
                type: "POST",
                data: {
                    _token: "{{csrf_token()}}",
                    name: name,
                    email: email,
                    message: message,
                },

                beforeSend: function() {
                    button.text('Submitting...');
                },
                success: function (data) {
                    button.text('Submit');
                    form.find('input[name=name]').val('');
                    form.find('input[name=email]').val('');
                    form.find('textarea[name=message]').val('');

                    errrContaner.html('<div class="alert alert-'+data.type+'">' + data.msg + '</div>');
                },
                error: function (data) {
                    button.text('Submit');
                    var errors = data.responseJSON.errors;
                    errrContaner.html('<ul class="sohan p-2"></ul>');
                    $.each(errors,function(key,val){
                        errrContaner.append('<li class=".sohan ul aler alert-danger px-3 py-1">' + val + '</li>');
                    })


                }
            });
        });
    </script>
@endsection
