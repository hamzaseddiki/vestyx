<script>
    $(document).on('click', '.newsletter-submit-btn', function (e) {
        e.preventDefault();

        var email = $(this).parent().find('.email').val();
        var errrContaner = $(this).parent().parent().find('.form-message-show');
        errrContaner.html('');
        var paperIcon = 'fab-paper-plane';
        var spinnerIcon = 'fab-spinner fa-spin';
        var el = $(this);

        el.find('i').removeClass(paperIcon).addClass(spinnerIcon);
        $.ajax({
            url: "{{route('tenant.frontend.subscribe.newsletter')}}",
            type: "POST",
            data: {
                _token: "{{csrf_token()}}",
                email: email
            },

            beforeSend: function() {
                el.text('Submiting..');
            },

            success: function (data) {
                 el.text('Subscribe');
                $('.email').val('');
                errrContaner.html('<div class="alert alert-'+data.type+'">' + data.msg + '</div>');
                el.find('i').addClass(paperIcon).removeClass(spinnerIcon);
            },
            error: function (data) {
                el.text('Subscribe');
                el.find('i').addClass(paperIcon).removeClass(spinnerIcon);
                var errors = data.responseJSON.errors;
                errrContaner.html('<div class="alert alert-danger">' + errors.email[0] + '</div>');
            }
        });
    });
</script>
