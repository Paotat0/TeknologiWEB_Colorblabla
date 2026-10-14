<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h2>Dashboard Color Palette</h2>
    <h3>Selamat datang,
        <?= htmlspecialchars($_SESSION["nama"]) ?>!
    </h3>

    <p>Login berhasil. Kamu sekarang berada di halaman dashboard.</p>
    
    <p>Email:
        <?= htmlspecialchars($_SESSION["email"]) ?>
    </p>

    <a href="logout.php">Logout</a>

</body>
</html>
