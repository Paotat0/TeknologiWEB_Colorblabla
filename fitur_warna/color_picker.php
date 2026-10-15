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
    <title>Color Picker</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="container">
    <h1>Color Picker</h1>
    <p>Pilih warna dan salin kode HEX yang dihasilkan.</p>

    <label for="pilihWarna">Pilih Warna:</label>
    <input type="color" id="pilihWarna" value="#3498db">

    <div id="previewWarna"></div>

    <p>Kode HEX: <strong id="kodeWarna">#3498DB</strong></p>

    <button type="button" id="salinWarna">Salin Kode</button>
    <p id="pesan"></p>

    <a href="index.php" class="back">Kembali</a>
</div>

<script src="../script.js"></script>
</body>
</html>