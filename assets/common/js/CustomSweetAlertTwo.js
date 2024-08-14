class CustomSweetAlertTwo{

    static success(msg = 'Item Added'){
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: msg,
            showConfirmButton: false,
            timer: 1500,
            toast: true,
        })
        return msg;
    }

    static error(msg = 'Something went wrong'){
        Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: msg,
            showConfirmButton: false,
            timer: 1500,
            toast: true,
        })
        return msg;
    }

    static warning(msg = 'Notice something')
    {
        Swal.fire({
            position: 'top-end',
            icon: 'warning',
            title: msg,
            showConfirmButton: false,
            timer: 1500,
            toast: true,
        })

        return msg;
    }



}