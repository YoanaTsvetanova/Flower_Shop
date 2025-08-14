<?php
session_start();
include 'config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Постът не е намерен.";
    exit;
}

$id = intval($_GET['id']);

$query = "SELECT * FROM blogs WHERE id = $id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    echo "Постът не е намерен.";
    exit;
}

$post = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($post['title']); ?> - Блог</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .blog-page {
            max-width: 900px;
            margin: 50px auto;
            padding: 30px;
            background-color: var(--cream);
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .blog-page img {
            width: 100%;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .blog-page h1 {
            color: #c85a6a;
            margin-bottom: 20px;
        }
        .blog-page p {
            font-size: 1.2rem;
            line-height: 1.6;
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<main>
    <div class="blog-page">
        <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
        <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
    </div>
</main>

</body>
</html>
