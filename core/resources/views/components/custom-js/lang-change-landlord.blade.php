<script>
    (function($){
        "use strict";
        $(document).ready(function(){
            @php
                $lang_name = current(explode('(',\App\Facades\GlobalLanguage::user_lang()->name));
            @endphp

            let default_lang = '{{ $lang_name }}';

            $('.landlord_languages_selector .current').text(default_lang)
            $(document).on('click','.landlord_languages_selector ul li',function(e){
                var el = $(this);
                el.find('.current').text(el.text());

                $.ajax({
                    url : "{{route('landlord.langchange')}}",
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
