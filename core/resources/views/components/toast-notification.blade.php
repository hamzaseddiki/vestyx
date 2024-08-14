<script>
    @if(\Illuminate\Support\Facades\Session::has('toastr_success'))
        toastr.options =
        {
            "closeButton" : true,
            "progressBar" : true
        }
    toastr.success("{{ session('toastr_success') }}");
    @endif

        @if(\Illuminate\Support\Facades\Session::has('toastr_error'))
        toastr.options =
        {
            "closeButton" : true,
            "progressBar" : true
        }
    toastr.error("{{ session('toastr_error') }}");
    @endif

        @if(\Illuminate\Support\Facades\Session::has('info'))
        toastr.options =
        {
            "closeButton" : true,
            "progressBar" : true
        }
    toastr.info("{{ session('info') }}");
    @endif

        @if(\Illuminate\Support\Facades\Session::has('warning'))
        toastr.options =
        {
            "closeButton" : true,
            "progressBar" : true
        }
    toastr.warning("{{ session('warning') }}");
    @endif
</script>
