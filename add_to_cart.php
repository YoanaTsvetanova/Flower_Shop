<?php
session_start();

if (isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $name = $_POST['product_name'];
    $price = floatval($_POST['product_price']);
    $image = $_POST['product_image'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$product_id] = [
            'name' => $name,
            'price' => $price,
            'image' => $image,
            'quantity' => 1
        ];
    }


    header("Location: cart.php");
    exit;
} else {
    header("Location: index.php");
    exit;
}
?>
