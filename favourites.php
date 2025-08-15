<?php
session_start();
include 'config.php';

if (!isset($_SESSION['id'])) {
    echo "Моля, влезте в профила си.";
    exit;
}

$user_id = $_SESSION['id'];

$products_query = "
    SELECT p.* 
    FROM favourites_products fp
    JOIN products p ON fp.product_id = p.id
    WHERE fp.user_id = ?
    ORDER BY fp.created_at DESC
";
$stmt = $conn->prepare($products_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$products_result = $stmt->get_result();

$blogs_query = "
    SELECT b.* 
    FROM favourites_blogs fb
    JOIN blogs b ON fb.blog_id = b.id
    WHERE fb.user_id = ?
    ORDER BY fb.created_at DESC
";
$stmt = $conn->prepare($blogs_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$blogs_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="bg">
<head>
<meta charset="UTF-8">
<title>Любими</title>
<link rel="stylesheet" href="styles.css">
<style>
    .favourites-section { max-width: 1000px; margin: 50px auto; }
    h2 { color: #c85a6a; margin-bottom: 20px; }
    .item { display: flex; gap: 20px; margin-bottom: 20px; }
    .item img { width: 150px; height: 150px; object-fit: cover; border-radius: 10px; }
    .item-info { flex: 1; }
</style>
</head>
<body>

<?php include 'navbar.php'; ?>

<main class="favourites-section">

<h2>Любими продукти</h2>
<?php if ($products_result->num_rows > 0): ?>
    <?php while($product = $products_result->fetch_assoc()): ?>
        <div class="item">
            <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            <div class="item-info">
                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                <p><?php echo number_format($product['price'], 2); ?> лв.</p>
                <a href="product.php?id=<?php echo $product['id']; ?>">Виж продукта</a>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>Нямате добавени любими продукти.</p>
<?php endif; ?>

<h2>Любими блог постове</h2>
<?php if ($blogs_result->num_rows > 0): ?>
    <?php while($post = $blogs_result->fetch_assoc()): ?>
        <div class="item">
            <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
            <div class="item-info">
                <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                <a href="blog.php?id=<?php echo $post['id']; ?>">Прочети повече</a>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>Нямате добавени любими блог постове.</p>
<?php endif; ?>

</main>
</body>
</html>
