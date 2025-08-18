<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, full_name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профил</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #fff9f7, #f9cccf);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #444;
        }
        .container {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
        h1 {
            font-size: 2.5em;
            color: #e8aeb7;
            margin-bottom: 10px;
        }
        h2 {
            font-size: 1.5em;
            color: #6cbf84;
            margin-bottom: 5px;
        }
        p {
            font-size: 1.1em;
            color: #555;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Здравей, <?php echo htmlspecialchars($user['username']); ?>!</h1>
        <h2>Пълно име: <?php echo htmlspecialchars($user['full_name']); ?></h2>
        <p>Имейл: <?php echo htmlspecialchars($user['email']); ?></p>
    </div>
</body>
</html>
