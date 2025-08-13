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
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<main style="padding: 20px;">
   
</main>

</body>
</html>
