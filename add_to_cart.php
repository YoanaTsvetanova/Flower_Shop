<?php
session_start();
include 'config.php'; // Make sure this connects to your database ($conn)

// Check if a product ID is posted
if (isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);

    // Fetch product details from the database
    $stmt = $conn->prepare("SELECT id, name, price, image FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        // Product doesn't exist
        header("Location: index.php");
        exit;
    }

    // Initialize cart if not already set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // If product already in cart, increment quantity
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += 1;
    } else {
        // Add new product to cart
        $_SESSION['cart'][$product_id] = [
            'name' => $product['name'],
            'price' => $product['price'],
            'image' => $product['image'],
            'quantity' => 1
        ];
    }

    // Redirect to cart page
    header("Location: cart.php");
    exit;

} else {
    // No product ID posted
    header("Location: index.php");
    exit;
}
?>
