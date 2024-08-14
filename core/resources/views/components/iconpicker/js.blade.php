<script src="{{global_asset('assets/common/js/fontawesome-iconpicker.min.js')}}"></script>
<script>
    (function ($){
        "use strict";
        /*------------------------------------------
        *   ICON PICKET INIT
        * ----------------------------------------*/
        $('.icp-dd').iconpicker();
        $('body').on('iconpickerSelected','.icp-dd', function (e) {
            var selectedIcon = e.iconpickerValue;
            $(this).parent().parent().children('input').val(selectedIcon);
            $('body .dropdown-menu.iconpicker-container').removeClass('show');
        });
    })(jQuery);
</script>
