
<script>
    (function($){
        "use strict";
        $(document).ready(function(){
            @php
                $lang_name = current(explode('(',\App\Facades\GlobalLanguage::user_lang()?->name ?? __('English')));
            @endphp

            let default_lang = '{{ $lang_name }}';

            $('.tenant_languages_selector .current').text(default_lang)
            $(document).on('click','.tenant_languages_selector ul li',function(e){
                var el = $(this);
                el.find('.current').text(el.text());
                console.log('hello')
                $.ajax({
                    url : "{{route('tenant.frontend.langchange')}}",
                    type: "GET",
                    data:{
                        'lang' : el.data('value')
                    },
                    success:function (data) {
                        location.reload();
                    }
                })
            });
        });
    }(jQuery));
</script>
