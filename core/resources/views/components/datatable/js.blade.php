<!-- Start datatable js -->
<script src="{{global_asset('assets/common/js/jquery.dataTables.js')}}"></script>
<script src="{{global_asset('assets/common/js/jquery.dataTables.min.js')}}"></script>
<script src="{{global_asset('assets/common/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{global_asset('assets/common/js/dataTables.responsive.min.js')}}"></script>
<script src="{{global_asset('assets/common/js/responsive.bootstrap.min.js')}}"></script>

@php
    $condition_of_orderable = empty(!get_static_option('table_list_data_orderable_status')) ? 'true' : 'false';
@endphp
<script>

    (function($){
        "use strict";
        $(document).ready(function() {
            $('.table-wrap > table').DataTable( {
                "ordering": {{$condition_of_orderable}},
                columnDefs: [{
                    orderable: {{$condition_of_orderable}},
                    targets: "no-sort"
                }],
                // 'language': translatedDataTable()
            } );
        } );

    })(jQuery)
</script>
