<?php
session_start();
include 'config.php';
$is_logged_in = isset($_SESSION['id']) && $_SESSION['id'];

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'name_asc';
switch ($sort) {
    case 'name_asc':
        $order_by = "name ASC";
        break;
    case 'price_low':
        $order_by = "price ASC";
        break;
    case 'price_high':
        $order_by = "price DESC";
        break;
    default:
        $order_by = "name ASC";
}

$query = "SELECT * FROM products ORDER BY $order_by";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Всички продукти - Онлайн Магазин за Цветя</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .products-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .sort-menu {
            margin-bottom: 20px;
            text-align: right;
        }

        .sort-menu select {
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }

        .product-list-item {
            display: flex;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .product-list-item img {
            width: 200px;
            height: 200px;
            object-fit: cover;
        }

        .product-details {
            flex: 1;
            padding: 15px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .product-details h3 {
            margin: 0 0 10px 0;
            font-size: 1.5rem;
            color: #c85a6a;
        }

        .product-details p.price {
            font-size: 1.2rem;
            font-weight: bold;
            margin: 0 0 15px 0;
            color: #444;
        }

        .view-button {
            padding: 10px 15px;
            background: #c85a6a;
            color: white;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-size: 1rem;
            width: fit-content;
            transition: background 0.2s ease;
        }

        .view-button:hover {
            background: #a44856;
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="products-container">
    <div class="sort-menu">
        <form method="GET" action="products.php">
            <label for="sort">Сортирай по: </label>
            <select name="sort" id="sort" onchange="this.form.submit()">
                <option value="name_asc" <?php if ($sort == 'name_asc') echo 'selected'; ?>>Име (A–Z)</option>
                <option value="price_low" <?php if ($sort == 'price_low') echo 'selected'; ?>>Цена (Ниска към Висока)</option>
                <option value="price_high" <?php if ($sort == 'price_high') echo 'selected'; ?>>Цена (Висока към Ниска)</option>
            </select>
        </form>
    </div>

    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="product-list-item">';
            echo '<img src="' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '">';
            echo '<div class="product-details">';
            echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
            echo '<p class="price">' . number_format($row['price'], 2) . ' лв.</p>';
            echo '<a href="product.php?id=' . $row['id'] . '" class="view-button">Виж повече</a>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<p>Няма налични продукти.</p>';
    }
    ?>
</div>

</body>
</html>
