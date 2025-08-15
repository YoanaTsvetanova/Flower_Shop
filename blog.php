<?php
session_start();
include 'config.php';

$is_logged_in = isset($_SESSION['id']) && $_SESSION['id'];

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

$message = '';
if ($is_logged_in && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['item_id']) && $_POST['item_type'] === 'blog') {
        $blog_id = intval($_POST['item_id']);
        $user_id = $_SESSION['id'];

        $check_query = "SELECT * FROM favourites_blogs WHERE user_id = $user_id AND blog_id = $blog_id";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $message = 'Този пост вече е в любими.';
        } else {
            $insert_query = "INSERT INTO favourites_blogs (user_id, blog_id, created_at) VALUES ($user_id, $blog_id, NOW())";
            if (mysqli_query($conn, $insert_query)) {
                $message = 'Постът беше добавен в любими ❤️';
            } else {
                $message = 'Грешка при добавянето. Опитайте отново.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($post['title']); ?> - Блог</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .blog-page {
            max-width: 700px;
            margin: 50px auto;
            padding: 30px;
            background-color: var(--cream);
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .blog-page img {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 10px;
        }

        .blog-page h1 {
            color: #c85a6a;
            font-size: 2rem;
            margin: 0;
        }

        .blog-page p {
            font-size: 1.1rem;
            line-height: 1.6;
            margin: 0;
        }

        .fav-btn {
            align-self: flex-start;
            padding: 10px 15px;
            font-size: 1rem;
            background-color: var(--light-mint-green);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
            margin-top: 10px;
        }

        .fav-btn:hover {
            background-color: var(--dark-mint-green);
        }

        .message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 8px;
            margin-top: 10px;
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

        <?php if ($is_logged_in): ?>
            <form action="" method="POST">
                <input type="hidden" name="item_id" value="<?php echo $post['id']; ?>">
                <input type="hidden" name="item_type" value="blog">
                <button type="submit" class="fav-btn">Добави в любими ❤️</button>
            </form>
        <?php endif; ?>

        <?php if (!empty($message)): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
    </div>
</main>

</body>
</html>
