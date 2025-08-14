<?php
session_start();
include 'config.php'; 

$is_logged_in = isset($_SESSION['id']) && $_SESSION['id'];

// Check if an ID was provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Продуктът не е намерен.";
    exit;
}

$id = intval($_GET['id']); // sanitize input

// Fetch product from database
$query = "SELECT * FROM products WHERE id = $id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    echo "Продуктът не е намерен.";
    exit;
}

$product = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['name']); ?> - Онлайн Магазин за Цветя</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .product-page {
            max-width: 1000px;
            margin: 50px auto;
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }

        .product-image {
            flex: 1 1 400px;
        }

        .product-image img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            object-fit: cover;
        }

        .product-info {
            flex: 1 1 400px;
            background-color: var(--cream);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .product-info h1 {
            color: #c85a6a;
            font-size: 2rem;
            margin-bottom: 15px;
        }

        .product-info p.description {
            font-size: 1.2rem;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .product-info p.price {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--dark-gray);
            margin-bottom: 25px;
        }

        .product-info .buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .product-info button {
            flex: 1;
            padding: 12px;
            font-size: 1rem;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .product-info .fav-btn {
            background-color: var(--light-mint-green);
            color: white;
        }

        .product-info .fav-btn:hover {
            background-color: var(--dark-mint-green);
        }

        .product-info .cart-btn {
            background-color: var(--light-pink);
            color: white;
        }

        .product-info .cart-btn:hover {
            background-color: var(--dark-pink);
        }

        @media screen and (max-width: 768px) {
            .product-page {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<main>
    <div class="product-page">
        <div class="product-image">
            <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
        </div>
        <div class="product-info">
            <h1><?php echo htmlspecialchars($product['name']); ?></h1>
            <p class="description"><?php echo htmlspecialchars($product['description']); ?></p>
            <p class="price"><?php echo number_format($product['price'], 2); ?> лв.</p>
            <div class="buttons">
                <button class="fav-btn">Добави в любими</button>
                <button class="cart-btn">Добави в кошницата</button>
            </div>
        </div>
    </div>
</main>

</body>
</html>
