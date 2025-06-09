<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            background-color: #f7f7f7;
            padding: 20px;
            margin: 0;
        }

        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            margin: 0;
        }

        .order-info {
            margin-top: 20px;
        }

        .order-info h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: #333;
        }

        .product-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .product-table th, .product-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .product-table th {
            background-color: #f8f8f8;
        }

        .product-table td {
            font-size: 1rem;
        }

        .total {
            text-align: right;
            font-size: 1.25rem;
            font-weight: bold;
            margin-top: 10px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 0.9rem;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="order-info">
            <h3>Dear <?php echo e($customer['fullname']); ?>,</h3>
            <p>Thank you for your order! We're happy to inform you that your order has been successfully processed. Below are the details of your order:</p>

            <table class="product-table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0; ?>
                    <?php foreach ($products as $product): ?>
                    <?php $sub_total = $product->quantity * $product->price; ?>
                    <?php $total += $sub_total; ?>
                    <tr>
                        <td><?php echo e($product['name']); ?></td>
                        <td><?php echo e($product['quantity']); ?></td>
                        <td><?php echo e(number_format($product['price'], 0, ',', '.')); ?> VND</td>
                        <td><?php echo e(number_format($sub_total, 0, ',', '.')); ?> VND</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <p class="total">Total: <?php echo e(number_format($total, 0, ',', '.')); ?> VND</p>

            <p>We will notify you once your order has been shipped. In the meantime, feel free to track your order status in your account.</p>

            <p>If you have any questions, please don't hesitate to contact our customer support at <a href="mailto:support@example.com">support@example.com</a>.</p>
        </div>

        <div class="footer">
            <p>Thank you for shopping with us! We look forward to serving you again.</p>
            <p>Best regards, <br>Your Brand Team</p>
        </div>
    </div>
</body>
</html>
