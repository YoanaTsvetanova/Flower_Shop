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
if ($is_logged_in && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_type'])) {
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

        .product-page img {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 10px;
        }

        .product-page h1 {
            color: #c85a6a;
            font-size: 2rem;
            margin: 0;
        }

        .product-page p.description {
            font-size: 1.1rem;
            line-height: 1.6;
            margin: 0;
        }

        .product-page p.price {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--dark-gray);
            margin: 10px 0 0 0;
        }

        .buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 15px;
        }

        .buttons form button {
            padding: 12px;
            font-size: 1rem;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            color: white;
            transition: background-color 0.3s ease;
        }

        .fav-btn { background-color: var(--light-mint-green); }
        .fav-btn:hover { background-color: var(--dark-mint-green); }

        .cart-btn { background-color: var(--light-pink); }
        .cart-btn:hover { background-color: var(--dark-pink); }

        .message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 8px;
            margin-top: 10px;
        }

        .reviews-section {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #eee;
        }

        .reviews-section h2 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 15px;
        }

        .reviews-section form textarea {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            resize: vertical;
        }

        .reviews-section form button {
            margin-top: 10px;
            padding: 10px 15px;
            background: #c85a6a;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .reviews-section form button:hover {
            background: #a84553;
        }

        .review {
            margin-bottom: 15px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 6px;
        }

        .review strong {
            color: #c85a6a;
        }

        .review small {
            color: #777;
        }
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

                <form action="add_to_cart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <button type="submit" class="cart-btn">Добави в кошницата 🛒</button>
                </form>
            <?php else: ?>
                <p>Моля <a href="login.php">влезте</a>, за да добавяте продукти в любими или кошницата.</p>
            <?php endif; ?>
        </div>

        <?php if (!empty($message)): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <div class="reviews-section">
            <h2>Отзиви</h2>

            <?php if ($is_logged_in): ?>
                <form action="add_review.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <textarea name="review_text" rows="4" required placeholder="Вашият отзив..."></textarea>
                    <button type="submit">Изпрати отзив</button>
                </form>
            <?php else: ?>
                <p>Моля <a href="login.php">влезте</a>, за да оставите отзив.</p>
            <?php endif; ?>

            <?php
            $reviews_query = "SELECT r.review_text, r.created_at, u.username 
                              FROM reviews r 
                              JOIN users u ON r.user_id = u.id 
                              WHERE r.product_id = {$product['id']} 
                              ORDER BY r.created_at DESC";
            $reviews_result = mysqli_query($conn, $reviews_query);

            if (mysqli_num_rows($reviews_result) > 0):
                while ($review = mysqli_fetch_assoc($reviews_result)):
            ?>
                <div class="review">
                    <p><strong><?php echo htmlspecialchars($review['username']); ?></strong> 
                       <small>(<?php echo $review['created_at']; ?>)</small></p>
                    <p><?php echo nl2br(htmlspecialchars($review['review_text'])); ?></p>
                </div>
            <?php endwhile; else: ?>
                <p>Все още няма отзиви за този продукт.</p>
            <?php endif; ?>
        </div>
    </div>
</main>

</body>
</html>
