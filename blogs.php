<?php
session_start();
include 'config.php';
$is_logged_in = isset($_SESSION['id']) && $_SESSION['id'];
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Блог - Онлайн Магазин за Цветя</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .blog-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            justify-content: center;
            padding: 30px;
        }

        .blog-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            text-align: center;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            width: 280px;
            transition: transform 0.2s ease-in-out;
            cursor: pointer;
        }

        .blog-card:hover {
            transform: scale(1.05);
        }

        .blog-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .blog-card h3 {
            padding: 15px;
            font-size: 1.3rem;
            background-color: var(--cream);
            color: #c85a6a;
        }

        .blog-card a {
            text-decoration: none;
            color: inherit;
        }

        @media (max-width: 600px) {
            .blog-card {
                width: 90%;
            }
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<main>
    <h1 style="text-align:center; margin:30px 0; color:#c85a6a;">Нашият Блог</h1>
    <div class="blog-grid">
        <?php
        $query = "SELECT * FROM blogs ORDER BY created_at DESC";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="blog-card">';
                echo '<a href="blog.php?id=' . $row['id'] . '">';
                echo '<img src="' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['title']) . '">';
                echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
                echo '</a>';
                echo '</div>';
            }
        } else {
            echo '<p style="text-align:center;">Няма публикувани блогове.</p>';
        }
