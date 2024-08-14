<script>

    function removeTags(str) {
        if ((str === null) || (str === '')) {
            return false;
        }
        str = str.toString();
        return str.replace(/(<([^>]+)>)/ig, '');
    }
    let register_payment_form = $('.register_page_payment_hidden_form');
    $(document).on('keyup paste change', 'input[name="{{$name}}"]', function (e) {
        var value = '';
        if ($(this).val() != '') {
            value = removeTags($(this).val()).toLowerCase().replace(/\s/g, "-");
            let newText = value.replace(/([@#%&"';><,`~.*+?^=!:${}()|\[\]\/\\])/g, "-");
            $(this).val(newText)
        }
        if (value.length < 1) {
            $('#subdomain-wrap').html('');
            return;
        }
        let msgWrap = $('#subdomain-wrap');
        msgWrap.html('');
        msgWrap.append('<span class="text-warning">{{__('availability checking..')}}</span>');
        axios({
            url: "{{route('landlord.subdomain.check')}}",
            method: 'post',
            responseType: 'json',
            data: {
                subdomain: value
            }
        }).then(function (res) {
            let site_name = '{{env('APP_NAME')}}';
            let restricted_words = [
                'https', 'http', 'www', 'subdomain', 'domain', 'primary-domain', 'central-domain',
                'landlord', 'landlords', 'tenant', 'tenants', 'multi-store', 'multistore', 'admin',
                'user', 'user', site_name];
            let newestText = restricted_words.includes(value);
            if(newestText != true)
            {
                msgWrap.html('');

                let root_domain = '{{ env('CENTRAL_DOMAIN') }}';
                let subdomain_with_root = value + '.' + root_domain;
                msgWrap.append('<span class="text-success"> (' + subdomain_with_root + ') {{__('is available')}}</span>');
                $('#login_button').attr('disabled', false)
            } else {
                msgWrap.html('');
                msgWrap.append('<span class="text-danger">{{__('This subdomain is not available')}}</span>');
            }
        }).catch(function (error) {
            var responseData = error.response.data.errors;
            msgWrap.html('');
            msgWrap.append('<span class="text-danger"> ' + responseData.subdomain + '</span>');
            $('#login_button').attr('disabled', true)
        });
    });


</script>
