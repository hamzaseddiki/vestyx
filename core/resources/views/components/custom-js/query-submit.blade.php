<script>
    $(document).on('click', '.query_submit_button', function (e) {
        e.preventDefault();

        var errrContaner = $('.query_form_message_show');
        errrContaner.html('');
        var paperIcon = 'fa-paper-plane';
        var spinnerIcon = 'fa-spinner fa-spin';
        var el = $(this);
        var form = $('.query_form');

        let email = form.find('input[name="email"]').val();
        let subject = form.find('input[name="subject"]').val();
        let message = form.find('textarea[name="message"]').val();

        el.find('i').removeClass(paperIcon).addClass(spinnerIcon);
        $.ajax({
            url: form.attr('action'),
            type: "POST",
            data: {
                _token: "{{csrf_token()}}",
                email: email,
                subject: subject,
                message: message,
            },

            beforeSend:function (){
                el.html('<i class="fas fa-spinner fa-spin mr-1"></i> {{__("Submitting..")}}');
            },

            success: function (data) {
                el.text('Submit Message');
                form.trigger('reset');
                errrContaner.html('<div class="alert alert-'+data.type+'">' + data.msg + '</div>');
                el.find('i').addClass(paperIcon).removeClass(spinnerIcon);
            },
            error: function (data) {
                el.find('i').addClass(paperIcon).removeClass(spinnerIcon);
                el.text('Submit Message');
                var errors = data.responseJSON.errors;

                $.each(errors,function(key,value){
                    errrContaner.append('<div class="alert alert-danger">' +value + '</div>');
                })

            }
        });
    });
</script>
