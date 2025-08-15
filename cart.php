<?php
session_start();
include 'config.php';

$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Кошница</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .cart-container {
            max-width: 900px;
            margin: 50px auto;
            padding: 30px;
            background-color: var(--cream);
            border-radius: 10px;
        }
        .cart-item {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }
        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }
        .cart-item-details {
            flex: 1;
        }
        .cart-item-details h3 {
            margin: 0 0 5px 0;
        }
        .cart-item-details p {
            margin: 0;
        }
        .quantity-form {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-top: 10px;
        }
        .quantity-form input {
            width: 50px;
            padding: 5px;
            text-align: center;
        }
        .quantity-form button {
            padding: 5px 10px;
            background-color: #6cbf84;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        .cart-summary {
            text-align: right;
            font-size: 1.3rem;
            font-weight: bold;
        }
        .remove-btn {
            padding: 8px 12px;
            background-color: #c85a6a;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<main>
    <div class="cart-container">
        <h1>Вашата кошница</h1>

        <?php if (empty($cart)): ?>
            <p>Кошницата е празна.</p>
        <?php else: ?>
            <?php foreach ($cart as $id => $item): 
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
            ?>
                <div class="cart-item">
                    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                    <div class="cart-item-details">
                        <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                        <p>Цена: <?php echo number_format($item['price'], 2); ?> лв.</p>
                        <p>Сума: <?php echo number_format($subtotal, 2); ?> лв.</p>

                        <form action="update_cart.php" method="POST" class="quantity-form">
                            <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
                            <button type="submit">Актуализирай</button>
                        </form>
                    </div>
                    <form action="remove_from_cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                        <button type="submit" class="remove-btn">Премахни</button>
                    </form>
                </div>
            <?php endforeach; ?>

            <div class="cart-summary">
                Общо: <?php echo number_format($total, 2); ?> лв.
            </div>
        <?php endif; ?>
    </div>
</main>

</body>
</html>
