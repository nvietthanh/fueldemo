function showProductDetail(productId) {
    $('#loading-spinner').show();
    $('#product-details').hide();

    $.ajax({
        url: '/products/' + productId,
        method: 'GET',
        processData: false,
        contentType: false,
        success: function(response) {
            const product = response.data;

            const formattedPrice = new Intl.NumberFormat('en-US').format(product.price);

            $('#product-details .product-image').attr('src', product.image_path);
            $('#product-details .product-name').text(product.name);
            $('#product-details .product-description').text(product.description || 'No description available.');
            $('#product-details .product-price').text(formattedPrice + ' VND');
            $('#product-details .product-category').text(product.category.name || 'N/A');
            $('#product-details .btn-buy').attr('href', `/checkout/${product.id}`);

            $('#loading-spinner').hide();
            $('#product-details').show();

            $('#product-details .btn-buy').off('click').on('click', function () {
                addProductCart(product.id)
            })
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

function addProductCart(productId) {
    const formData = new FormData()
    formData.append('product_id', productId)
    formData.append('quantity', 1)

    $.ajax({
        url: '/cart/add',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            location.href = '/cart'
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
