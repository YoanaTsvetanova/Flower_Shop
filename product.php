<?php
session_start();
include 'config.php';

$is_logged_in = isset($_SESSION['id']) && $_SESSION['id'];

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Продуктът не е намерен.";
    exit;
}

$id = intval($_GET['id']);

$query = "SELECT * FROM products WHERE id = $id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    echo "Продуктът не е намерен.";
    exit;
}

$product = mysqli_fetch_assoc($result);

$message = '';
if ($is_logged_in && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'])) {
    if ($_POST['item_type'] === 'product') {
        $product_id = intval($_POST['item_id']);
        $user_id = $_SESSION['id'];

        $check_query = "SELECT * FROM favourites_products WHERE user_id = $user_id AND product_id = $product_id";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $message = 'Този продукт вече е в любими.';
        } else {
            $insert_query = "INSERT INTO favourites_products (user_id, product_id, created_at) VALUES ($user_id, $product_id, NOW())";
            if (mysqli_query($conn, $insert_query)) {
                $message = 'Продуктът беше добавен в любими ❤️';
            } else {
                $message = 'Грешка при добавянето. Опитайте отново.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['name']); ?> - Онлайн Магазин за Цветя</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .product-page {
            max-width: 700px;
            margin: 50px auto;
            padding: 30px;
            background-color: var(--cream);
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .product-page img { width: 100%; max-height: 300px; object-fit: cover; border-radius: 10px; }
        .product-page h1 { color: #c85a6a; font-size: 2rem; margin: 0; }
        .product-page p.description { font-size: 1.1rem; line-height: 1.6; margin: 0; }
        .product-page p.price { font-size: 1.5rem; font-weight: bold; color: var(--dark-gray); margin: 10px 0 0 0; }
        .buttons { display: flex; flex-direction: column; gap: 10px; margin-top: 15px; }
        .buttons form button { padding: 12px; font-size: 1rem; border-radius: 8px; border: none; cursor: pointer; color: white; transition: background-color 0.3s ease; }
        .fav-btn { background-color: var(--light-mint-green); }
        .fav-btn:hover { background-color: var(--dark-mint-green); }
        .cart-btn { background-color: var(--light-pink); }
        .cart-btn:hover { background-color: var(--dark-pink); }
        .message { background-color: #d4edda; color: #155724; padding: 10px; border-radius: 8px; margin-top: 10px; }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<main>
    <div class="product-page">
        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
        <p class="description"><?php echo htmlspecialchars($product['description']); ?></p>
        <p class="price"><?php echo number_format($product['price'], 2); ?> лв.</p>

        <div class="buttons">
            <?php if ($is_logged_in): ?>
                <form action="" method="POST">
                    <input type="hidden" name="item_id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="item_type" value="product">
                    <button type="submit" class="fav-btn">Добави в любими ❤️</button>
                </form>
            <?php endif; ?>

            <form action="add_to_cart.php" method="POST">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
                <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
                <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($product['image']); ?>">
                <button type="submit" class="cart-btn">Добави в кошницата 🛒</button>
            </form>
        </div>

        <?php if (!empty($message)): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
    </div>
</main>

</body>
</html>
