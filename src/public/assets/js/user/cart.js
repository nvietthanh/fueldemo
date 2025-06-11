function goToShopping() {
    location.href = '/products'
}

function goToCheckout() {
    location.href = '/checkout'
}

function handleRemoveCart(productId) {
    const formData = new FormData()
    formData.append('product_id', productId)
    formData.append('fuel_csrf_token', fuel_csrf_token()); 

    $.ajax({
        url: '/cart/remove',
        method: 'DELETE',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            location.reload()
        },
        error: function(xhr) {
            const response = xhr.responseJSON

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
    });
}

function handleChangeCart(productId, type) {
    const quantity = Number ($(`.cart-item[data-id=${productId}] .quantity-number`).text())

    if (type == 'increase') {
        handleIncreaseCart(productId, quantity)
    } else {
        handleDecreaseCart(productId, quantity)
    }
}

function handleIncreaseCart(productId, quantity) {
        if (quantity === 1) {
        Swal.fire({
            title: 'Remove this product?',
            text: "Do you want to remove this item from your cart?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, remove it',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                handleRemoveCart(productId)
            }
        });
    } else {
    handleCallAjax(productId, quantity - 1)
    }
}

function handleDecreaseCart(productId, quantity) {
        handleCallAjax(productId, quantity + 1)
}

function handleCallAjax(productId, quantity) {
    const formData = new FormData()
    formData.append('product_id', productId)
    formData.append('quantity', quantity)
    formData.append('fuel_csrf_token', fuel_csrf_token()); 

    $.ajax({
        url: '/cart/update',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                text: response.message,
                showConfirmButton: false,
                timer: 700,
                timerProgressBar: true
            }).then(() => {
                window.location.reload()
            });
        },
        error: function(xhr) {
            const response = xhr.responseJSON

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
    });
}

