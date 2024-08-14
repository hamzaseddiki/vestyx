<script>
    function ajax_toastr_success_message(data) {
        if (data.success) {
            toastr.success(data.msg)
        } else {
            toastr.warning(data.msg);

        }
    }

    function ajax_toastr_error_message(xhr) {
        $.each(xhr.responseJSON.errors, function (key, value) {
            toastr.error((key.capitalize()).replace("-", " ").replace("_", " "), value);
        });
    }

    /*
    ========================================
        Click add Value text
    ========================================
    */

    $(document).on('click', '.status-dropdown .single-item', function(event) {
        let el = $(this);
        let value = el.data('value');
        let parentWrap = el.parent().parent();
        parentWrap.find('.add-dropdown-text').text(value);
        parentWrap.find('.add-dropdown-text').attr('value', value);
        return true;
    });

    $(document).on("click",".status-dropdown .dropdown-menu li",function (){
        let id = $(this).attr("data-id");
        let statusId = $(this).attr("data-status-id");

        let data = new FormData();
        data.append("id", id);
        data.append("status_id", statusId);
        data.append("_token", "{{ csrf_token() }}");

        function send_ajax_request(request_type,request_data,url,before_send,success_response,errors){
            $.ajax({
                url: url,
                type: request_type,
                headers: {
                    'X-CSRF-TOKEN': "4Gq0plxXAnBxCa2N0SZCEux0cREU7h4NHObiPH10",
                },
                beforeSend: (typeof before_send !== "undefined" && typeof before_send === "function") ? before_send : () => { return ""; } ,
                processData: false,
                contentType: false,
                data: request_data,
                success:  (typeof success_response !== "undefined" && typeof success_response === "function") ? success_response : () => { return ""; },
                error:  (typeof errors !== "undefined" && typeof errors === "function") ? errors : () => { return ""; }
            });
        }
        send_ajax_request("post", data, '{{ route('tenant.admin.product.update.status') }}', function () {
            toastr.success("Request sent..");
        }, function (data) {
            ajax_toastr_success_message(data);
        }, function () {
            ajax_toastr_error_message(xhr);
        });
    })
</script>
