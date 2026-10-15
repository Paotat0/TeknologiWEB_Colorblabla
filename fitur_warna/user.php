<?php
session_start();
require_once "../color_history/color_palette/koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../color_history/color_palette/login.php");
    exit;
}

$query = mysqli_query($conn, "SELECT id, nama, email FROM users");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>User Management</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="container">
    <h1>User Management</h1>

    <table>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
        </tr>

        <?php while ($user = mysqli_fetch_assoc($query)) { ?>
        <tr>
            <td><?= htmlspecialchars($user['id']) ?></td>
            <td><?= htmlspecialchars($user['nama']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
        </tr>
        <?php } ?>
    </table>

    <a href="indexwarna.php" class="back">Kembali</a>
</div>

<script src="../script.js"></script>
</body>
</html>