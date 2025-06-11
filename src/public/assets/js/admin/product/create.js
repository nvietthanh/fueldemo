function handleUploadImage (event) {
    event.preventDefault();

    $('#image').click()
}

$(document).ready(function () {
    $('#image').on('change', function(event) {
        var file = event.target.files[0];

        if (file && file.type.startsWith('image')) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                $('#previewImg').attr('src', e.target.result);
                $('#previewImg').show();
            };

            reader.readAsDataURL(file);
        }
    });

    $('#product-form').on('submit', function (event) {
        event.preventDefault();

        clearErrors('product-form')

        fuel_set_csrf_token(this)

        const form = $(this)[0];
        const formData = new FormData();

        $(form).serializeArray().forEach(({ name, value }) => {
            formData.append(name, value);
        });

        const fileInput = form.querySelector('input[name="image_file"]');
        if (fileInput && fileInput.files.length > 0) {
            formData.append('image_file', fileInput.files[0]);
        }

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
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
                    window.location.href = '/admin/products';
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

                    setErrors(errors, 'product-form')
                }
            }
        });
    });
});
