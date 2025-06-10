<div class="container my-5">
    <h2 class="text-center mb-5 text-primary">Checkout</h2>
    <div class="row mb-5">
        <div class="col-md-12">
            <h4 class="mb-4">Product Details</h4>
            <?php $grandTotal = 0; ?>
            <?php foreach ($cartItems as $product): ?>
                <?php $grandTotal += $product->total; ?>
                <div class="card shadow-sm mb-3">
                    <div class="card-wrapper">
                        <div class="cart-image">
                            <img src="<?= get_file_url($product->image_path) ?>" class="img-fluid rounded-start" alt="<?= $product->name ?>">
                        </div>
                        <div class="cart-info">
                            <div class="card-body">
                                <h5 class="card-title"><?= $product->name ?></h5>
                                <p class="card-text mb-1"><strong>Price:</strong> <?= number_format($product->price) ?> VND</p>
                                <p class="card-text"><strong>Quantity:</strong> <?= $product->quantity ?></p>
                            </div>
                        </div>
                        <div class="cart-subtotal">
                            <div class="card-body pt-0">
                                <p class="card-text text-danger fw-bold"><?= number_format($product->total) ?> VND</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="d-flex justify-content-end">
                <h5 class="text-primary">Total: <?= number_format($grandTotal) ?> VND</h5>
            </div>
        </div>
    </div>
    <div class="row pb-5">
        <h4 class="mb-4">Shipping Information</h4>
        <form id="checkout-form" class="needs-validation" novalidate>
            <div class="col-md-6 mb-3">
                <label for="full-name" class="form-label">Full Name</label>
                <input type="text" name="fullname" class="form-control" id="full-name">
                <span id="error-fullname" class="error-message"></span>
            </div>
            <div class="col-md-6 mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" name="address" class="form-control" id="address">
                <span id="error-address" class="error-message"></span>
            </div>
            <div class="col-md-6 mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" name="phone_number" class="form-control" id="phone">
                <span id="error-phone_number" class="error-message"></span>
            </div>
            <div class="col-md-12 text-center mt-5">
                <button type="submit" class="btn btn-success">Place Order</button>
            </div>
        </form>
    </div>
</div>
