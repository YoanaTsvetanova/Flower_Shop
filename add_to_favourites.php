<?php
session_start();
include 'config.php';

if (!isset($_SESSION['id'])) {
    echo "Моля, влезте в профила си.";
    exit;
}

$user_id = $_SESSION['id'];

if (!isset($_POST['item_id'], $_POST['item_type'])) {
    echo "Невалидни данни.";
    exit;
}

$item_id = intval($_POST['item_id']);
$item_type = $_POST['item_type'];

if ($item_type === 'product') {
    $stmt = $conn->prepare("INSERT IGNORE INTO favourites_products (user_id, product_id, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $user_id, $item_id);
} elseif ($item_type === 'blog') {
    $stmt = $conn->prepare("INSERT IGNORE INTO favourites_blogs (user_id, blog_id, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $user_id, $item_id);
} else {
    echo "Невалидни данни.";
    exit;
}

if ($stmt->execute()) {
    header("Location: favourites.php"); 
    exit;
} else {
    echo "Грешка при добавяне в любими.";
}
?>
