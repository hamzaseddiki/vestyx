<script>

$(document).ready(function(){

    $(document).on('click','.category_item_in_sidebar',function(){
        let el = $(this);
        let route = el.data('route');
        window.location = route;
    });

})
</script>
