<?php
session_start();
include 'config.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['blog_id'], $_POST['comment_text'])) {
    $user_id = $_SESSION['id'];
    $blog_id = intval($_POST['blog_id']);
    $comment_text = mysqli_real_escape_string($conn, $_POST['comment_text']);

    $insert_query = "INSERT INTO comments (user_id, blog_id, comment_text, created_at) 
                     VALUES ($user_id, $blog_id, '$comment_text', NOW())";

    if (mysqli_query($conn, $insert_query)) {
        header("Location: blog.php?id=$blog_id");
        exit;
    } else {
        echo "Грешка при добавянето на коментар.";
    }
} else {
    echo "Невалидни данни.";
}
?>
