<script>
    (function ($){
        $(document).ready(function (){
            $(document).on('click', '.payment-gateway-wrapper li', function (){
                let el = $(this);
                let payment_gateway_wrapper = $('.payment-gateway-wrapper');
                let selected_payment_gateway = $('input[name=selected_payment_gateway]');

                payment_gateway_wrapper.find('li').removeClass('selected');
                payment_gateway_wrapper.find('li').css('opacity', '0.7');
                selected_payment_gateway.val('');

                el.addClass('selected');
                el.css('opacity', '1');
                selected_payment_gateway.val(el.data('gateway'));

                if (el.data('gateway') === 'manual_payment_')
                {
                    payment_gateway_wrapper.append('<input class="form-control manual_payment_image mt-4" type="file" name="manual_payment_image" accept="image/*"></input>')
                } else{
                    $('.manual_payment_image').remove();
                }
            });
        });
    })(jQuery);
</script>
