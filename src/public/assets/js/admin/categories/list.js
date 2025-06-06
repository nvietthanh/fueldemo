
$('#create-category-form').on('submit', function (event) {
    event.preventDefault();
    
    const formData = new FormData($(this)[0])
    
    handleCallAjax('/admin/categories', formData, 'create-category-form')
})

$('#edit-category-form').on('submit', function (event) {
    event.preventDefault();
    
    const formData = new FormData($(this)[0])
    const id = $('#editCategoryModal').attr('data-id')
    
    handleCallAjax(`/admin/categories/${id}`, formData, 'edit-category-form')
})

function handleCallAjax(url, formData, formId) {
    $.ajax({
        url: url,
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
                window.location.reload()
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
    
                setErrors(errors, formId)
            }
        }
    });
}

function handleOpenFormEdit(id) {
    $.ajax({
        url: '/admin/categories/' + id,
        method: 'GET',
        processData: false,
        contentType: false,
        success: function (response) {
            const data = response.data

            $('#editCategoryModal [name]').val(data.name)
            $('#editCategoryModal').attr('data-id', id)
            
            const modal = new bootstrap.Modal(document.getElementById('editCategoryModal'))
            modal.show()
        },
    })
}

function handleDeleteCategory(id) {
    Swal.fire({
        title: 'Are you sure you want to delete?',
        text: "This data cannot be recovered!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'Cancel',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/admin/categories/' + id,
                method: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    if (response.status == 200) {
                        Swal.fire( 'Deleted!', 'The record has been successfully deleted.', 'success').then(() => {
                            location.reload()
                        });
                    } else {
                        Swal.fire( 'Error!', 'Failed to delete: ' + response.message, 'error');
                    }
                },
                error: function(res) {
                    const response = res.responseJSON

                    Swal.fire('Error!', response.message ?? 'Unable to connect to the server.', 'error');
                }
            });
        }
    });
}