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

if ($is_logged_in && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_text'])) {
    $comment_text = mysqli_real_escape_string($conn, trim($_POST['comment_text']));
    if (!empty($comment_text)) {
        $user_id = $_SESSION['id'];
        $insert = "INSERT INTO comments (user_id, blog_id, comment_text) 
                   VALUES ($user_id, $id, '$comment_text')";
        mysqli_query($conn, $insert);
    }
}


$comments_query = "SELECT c.*, u.username 
                   FROM comments c 
                   JOIN users u ON c.user_id = u.id 
                   WHERE c.blog_id = $id 
                   ORDER BY c.created_at DESC";
$comments_result = mysqli_query($conn, $comments_query);
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
        }
        .blog-page img {
            width: 100%;
            max-height: 250px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .blog-page h1 {
            color: #c85a6a;
            margin-bottom: 15px;
        }
        .blog-page p {
            font-size: 1.1rem;
            line-height: 1.6;
        }
        .comments {
            margin-top: 40px;
        }
        .comments h2 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: #333;
        }
        .comment-form textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
        }
        .comment-form button {
            padding: 10px 15px;
            background-color: var(--light-mint-green);
            border: none;
            border-radius: 8px;
            color: white;
            cursor: pointer;
        }
        .comment-form button:hover {
            background-color: var(--dark-mint-green);
        }
        .comment {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .comment strong {
            color: #c85a6a;
        }
        .comment small {
            color: #777;
            font-size: 0.85rem;
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

        <div class="comments">
            <h2>Коментари</h2>

            <?php if ($is_logged_in): ?>
                <form method="POST" class="comment-form">
                    <textarea name="comment_text" placeholder="Напишете вашия коментар..." required></textarea>
                    <button type="submit">Публикувай</button>
                </form>
            <?php else: ?>
                <p>Моля, <a href="login.php">влезте</a>, за да оставите коментар.</p>
            <?php endif; ?>

            <?php while ($comment = mysqli_fetch_assoc($comments_result)): ?>
                <div class="comment">
                    <strong><?php echo htmlspecialchars($comment['username']); ?></strong>
                    <small> • <?php echo $comment['created_at']; ?></small>
                    <p><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</main>

</body>
</html>
