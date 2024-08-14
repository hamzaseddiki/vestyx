<script src="{{global_asset('assets/landlord/common/js/spectrum.min.js')}}"></script>
<script>
    (function(){
       "use strict";

   var spectrum_color_picker =  $('.spectrum_color_picker');
    $.each(spectrum_color_picker,function(){
        var el = $(this);
        el.spectrum({
            showAlpha: true,
            preferredFormat: "hex",
            showPalette: true,
            cancelText : '',
            showInput: true,
            allowEmpty:true,
            chooseText : '',
            maxSelectionSize: 2,
            color: el.next('input').val(),
            change: function(color) {
                el.next('input').val( color ? color.toRgbString() : '');
                el.css({
                    'background-color' : color ? color.toRgbString() : ''
                });
            },
            move: function(color) {
                el.next('input').val( color ? color.toRgbString() : '');
                el.css({
                    'background-color' : color ? color.toRgbString() : ''
                });
            },
            palette: [
                [
                    "{{get_static_option('site_color')}}",
                    "{{get_static_option('site_main_color_two')}}",
                    "{{get_static_option('site_secondary_color')}}",
                    "{{get_static_option('site_heading_color')}}",
                    "{{get_static_option('site_paragraph_color')}}",
                ]
            ]
        });

        el.on("dragstop.spectrum", function(e, color) {
            el.next('input').val( color.toRgbString());
            el.css({
                'background-color' : color.toHexString()
            });
        });
    });


    })(jQuery);
</script>
