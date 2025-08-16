<?php
session_start();
include 'config.php';

if (!isset($_SESSION['id'])) {
    die("Трябва да сте влезли в профила си, за да добавите отзив.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['id'];
    $product_id = intval($_POST['product_id']);
    $review_text = mysqli_real_escape_string($conn, $_POST['review_text']);

    if (!empty($review_text)) {
        $query = "INSERT INTO reviews (user_id, product_id, review_text, created_at) 
                  VALUES ($user_id, $product_id, '$review_text', NOW())";
        mysqli_query($conn, $query);
    }
}

header("Location: product.php?id=" . $product_id);
exit;
