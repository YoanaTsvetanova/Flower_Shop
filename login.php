<?php 
session_start();
include 'config.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $user['username'];
                $_SESSION['id'] = $user['id'];
                header('Location: index.php');
                exit;
                
            } else {
                $error = "Грешно потребителско име или парола. <br><a href='register.php'>Регистрирайте се тук</a>";
            }
        } else {
            $error = "Потребителят не съществува. <br><a href='register.php'>Регистрирайте се тук</a>";
        }
        $stmt->close();
    } else {
        $error = "Моля, попълнете всички полета!";
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <style>
      
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #fff9f7, #f9cccf);
            color: #444444;
        }

        .container {
            text-align: center;
            padding: 40px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            font-size: 2em;
            margin-bottom: 20px;
            color: #e8aeb7;
            text-align: center;
            width: 100%;
            max-width: 100%;
        }

        form {
            text-align: left;
        }

        label {
            font-size: 1em;
            margin-bottom: 8px;
            display: block;
        }

        input[type="text"],
        input[type="password"],
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1em;
            background-color: #fff9f7;
        }

        input[type="submit"] {
            background-color: #a1d8bb;
            color: #ffffff;
            font-weight: bold;
            border: none;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #7db89e;
        }

        p {
            font-size: 0.95em;
            margin-top: 12px;
        }

        p.error {
            color: #ff4d4d;
        }

        p.success {
            color: #28a745;
        }

        a {
            color: #e8aeb7;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .container {
                padding: 30px 20px;
            }

            h1 {
                font-size: 1.6em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Вход</h1>
        <form method="post" action="login.php">
            <label for="username">Потребителско име:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Парола:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Влез">
        </form>
         <a href='register.php'>Регистрирация</a>

        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <?php if (isset($_SESSION['logout_message'])): ?>
            <p class="success"><?php echo $_SESSION['logout_message']; unset($_SESSION['logout_message']); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>