$('#checkout-form').on('submit', function (event) {
    event.preventDefault();

    fuel_set_csrf_token(this)

    clearErrors('checkout-form')
    
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: new FormData($(this)[0]),
        processData: false,
        contentType: false,
        success: function (response) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                text: response.message,
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true
            }).then(() => {
                location.href = '/checkout/complete'
            });
        },
        error: function (xhr) {
            const response = xhr.responseJSON
    
            if (response.status == 422) {
                const errors = response.data
    
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    text: 'Please check the form fields and try again.',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true
                });
    
                setErrors(errors, 'checkout-form')
            } else {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true
                });
            }
        }
    });
})