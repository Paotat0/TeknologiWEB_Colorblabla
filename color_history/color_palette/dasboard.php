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

    <p>
        <a href="../../index.php">Halaman utama aplikasi</a>
        |
        <a href="../../fitur_warna/color_picker.php">Color Picker &amp; History</a>
        |
        <a href="../../fitur_warna/indexwarna.php">Kelola fitur warna</a>
    </p>

    <a href="logout.php">Logout</a>

</body>
</html>
