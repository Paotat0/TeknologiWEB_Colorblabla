<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../color_palette/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Color Palette</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="container">
    <h1>Color Palette Management</h1>
    <p>Kelola warna dan buat kombinasi warna sesuai kebutuhan.</p>

    <div class="menu">
        <a href="users.php">User Management</a>
        <a href="palette.php">Kelola Palette</a>
        <a href="color_picker.php">Color Picker</a>
    </div>

    <a href="../color_palette/dashboard.php" class="back">
        Kembali ke Dashboard
    </a>
</div>

<script src="../script.js"></script>
</body>
</html>