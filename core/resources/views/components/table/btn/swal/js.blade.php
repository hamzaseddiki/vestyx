<script>
    var type = "{{ $type ??  'Delete' }}";
console.log(type);
    (function ($) {
        "use strict"
        $(document).ready(function () {
            $(document).on('click', '.swal-delete', function () {
                Swal.fire({
                    title: "{{ __($message ?? 'Do you want to delete this item?') }}",
                    showCancelButton: true,
                    confirmButtonText: type,
                    confirmButtonColor: '#dd3333',
                }).then((result) => {
                    if (result.isConfirmed) {
                        let route = $(this).data('route');
                        $.post(route, {_token: '{{ csrf_token() }}'}).then(function (data) {
                            if (data) {
                                Swal.fire(type+'ed!', '', 'success');
                                setTimeout(function () {
                                    location.reload();
                                }, 1000);
                            }
                        });
                    }
                });
            });
        });
    })(jQuery)
</script>
