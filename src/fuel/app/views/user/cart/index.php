<div class="container mb-5">
    <h2 class="mb-4">🛒 Your Shopping Cart</h2>
    <?php if (empty($cartItems)): ?>
        <div class="alert alert-info">Your cart is currently empty.</div>
    <?php else: ?>
        <?php $grandTotal = 0; ?>
        <?php foreach ($cartItems as $item): ?>
            <?php $subtotal = $item->price * $item->quantity; ?>
            <?php $grandTotal += $subtotal; ?>

            <div class="cart-item" data-id="<?= $item->product_id ?>">
                <div class="cart-wrapper">
                    <div class="product-info">
                        <img src="<?= $item->image_path ?>" alt="<?= htmlspecialchars($item->name) ?>">
                        <div>
                            <div class="product-name"><?= htmlspecialchars($item->name) ?></div>
                            <div class="product-price"><?= number_format($item->price) ?> VND</div>
                        </div>
                    </div>
                    <div class="quantity-controls">
                        <button type="submit" class="btn btn-outline-secondary"
                            onclick="handleChangeCart(<?= $item->product_id ?>, 'increase')">–</button>
                        <div class="quantity-number"><?= $item->quantity ?></div>
                        <button type="submit" class="btn btn-outline-secondary"
                            onclick="handleChangeCart(<?= $item->product_id ?>, 'decrease')">+</button>
                    </div>
                    <div class="remove-btn ms-3">
                        <button class="btn btn-outline-danger" onclick="handleRemoveCart(<?= $item->product_id ?>)">
                            Remove
                        </button>
                    </div>
                </div>
                <div class="subtotal"><?= number_format($subtotal) ?> VND</div>
            </div>
        <?php endforeach; ?>
        <div class="cart-total mb-4 pt-2">
            <div class="text-primary fw-bold fs-5">
                Total: <?= number_format($grandTotal) ?> VND
            </div>
        </div>
    <?php endif; ?>
    <div class="mt-5 d-flex justify-content-center align-items-center" style="gap: 18px;">
        <button class="btn btn-outline-secondary" onclick="goToShopping()">
            ← Continue Shopping
        </button>
        <?php if (!empty($cartItems)): ?>
            <button class="btn btn-success" onclick="goToCheckout()">
                Checkout
            </button>
        <?php endif; ?>
    </div>
</div>