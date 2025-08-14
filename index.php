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
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Open+Sans&display=swap');

    .top-box, .bottom-box {
        background-color: var(--light-pink);
        padding: 40px;
        text-align: center;
        border-radius: 12px;
        margin-bottom: 40px;
        font-size: 1.2rem;
        line-height: 1.8;
        color: var(--dark-gray);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

   
    .top-box h2, .bottom-box h3 {
        font-family: 'Playfair Display', serif;
        font-size: 2.2rem;
        color: var(--dark-pink);
        margin-bottom: 15px;
    }

   
    .top-box p, .bottom-box p {
        font-family: 'Open Sans', sans-serif;
        font-size: 1.15rem;
        color: var(--dark-gray);
        max-width: 900px;
        margin: 0 auto;
    }

    .product-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        justify-content: center;
    }

    .product-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        text-align: center;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        transition: transform 0.2s ease-in-out;
        width: 260px;
    }

    .product-card:hover {
        transform: scale(1.05);
    }

    .product-card img {
        width: 100%;
        height: 260px;
        object-fit: cover;
    }

    .top-box h2, .bottom-box h3 {
    font-family: 'Playfair Display', serif;
    font-size: 2.2rem;
    color: #c85a6a; 
    margin-bottom: 15px;
}

</style>

</head>
<body>

<?php include 'navbar.php'; ?>

<main style="padding: 20px; max-width: 1400px; margin: auto;">


    <div class="top-box">
        <h2 style="font-size: 2rem; margin-bottom: 10px;">"Цветно вълшебство"</h2>
        <p>Изберете от нашата богата колекция от растения и цветя, подбрани с любов и внимание. </p>
    </div>

   
   <div class="product-grid">
    <?php
    $query = "SELECT * FROM products";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="product-card">';
            echo '<a href="product.php?id=' . $row['id'] . '">';
            echo '<img src="' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '">';
            echo '<h3 style="color: #c85a6a; text-decoration: none;">' . htmlspecialchars($row['name']) . '</h3>';
            echo '</a>';
            echo '</div>';
        }
    } else {
        echo '<p style="font-size: 1.2rem;">Няма налични продукти.</p>';
    }
    ?>
</div>


   
    <div class="bottom-box">
        <h3 style="font-size: 1.5rem; margin-bottom: 10px;">За нас</h3>
        <p>Нашият магазин предлага ръчно подбрани растения и цветя за всеки повод. Ние се стремим да внесем красота и свежест във вашия дом или офис.
            <br>Можете да поръчате цветя и растения онлайн, с бърза доставка до офис или адрес, както и да посетите и нашия физически магазин!<br>
            Магазин "Цветно вълшебство" - ул. Евлоги Георгиев 20, гр. Варна<br>
            Телефон за връзка - 089 765 2787 <br>
            Свържете се с нас и на имейл - cvetno_vulshebstvo@gmail.com
        </p>
    </div>

</main>

</body>
</html>
