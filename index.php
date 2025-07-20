<?php 
session_start();
include 'config.php'; 

$is_logged_in = isset($_SESSION['id']) && $_SESSION['id'];
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Онлайн Магазин за Цветя</title>
</head>
<body>
</body>
</html>