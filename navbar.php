<?php
$is_logged_in = isset($_SESSION['id']) && $_SESSION['id'];
?>
<header>
    <div class="header-container">
  
        <button class="hamburger-menu" aria-label="Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

  
        <div class="nav-menu">
        
            <?php /*
            <div class="nav-menu__section">
                <a href="index.php">
                    <img src="images/logo.png" alt="Logo" class="logo">
                </a>
            </div>
            */ ?>

          
            <div class="nav-menu__section">
                <a class="nav-menu__link" href="index.php">Начало</a>
                <a class="nav-menu__link" href="products.php">Продукти</a>
                <a class="nav-menu__link" href="blogs.php">Блог</a>
                <?php if ($is_logged_in): ?>
                    <a class="nav-menu__link" href="favourites.php">Любими</a>
                    <a class="nav-menu__link" href="cart.php">Кошница</a>
                <?php endif; ?>
            </div>

        
            <div class="nav-menu__section">
                <?php if ($is_logged_in): ?>
                    <a class="nav-menu__link" href="profile.php">Профил</a>
                    <a class="nav-menu__link" href="logout.php">Изход</a>
                <?php else: ?>
                    <a class="nav-menu__link" href="login.php">Вход</a>
                    <a class="nav-menu__link" href="register.php">Регистрация</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<script src="js/header.js"></script>
