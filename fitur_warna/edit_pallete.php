<?php
session_start();
require_once "../color_history/color_palette/koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../color_history/color_palette/login.php");
    exit;
}
?>

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    exit("ID tidak valid.");
}

$stmt = mysqli_prepare($conn, "SELECT * FROM palette WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$data) {
    exit("Data warna tidak ditemukan.");
}

$error = "";

if (isset($_POST['update'])) {
    $nama = trim($_POST['nama']);
    $warna = $_POST['kode_warna'];

    if ($nama === "" ||
        !preg_match('/^#[0-9a-fA-F]{6}$/', $warna)) {
        $error = "Nama atau kode warna tidak valid.";
    } else {
        $stmt = mysqli_prepare(
            $conn,
            "UPDATE palette SET nama = ?, kode_warna = ? WHERE id = ?"
        );

        mysqli_stmt_bind_param($stmt, "ssi", $nama, $warna, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("Location: palette.php");
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Palette</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="container">
    <h1>Edit Palette</h1>

    <?php if ($error !== "") { ?>
        <p><?= htmlspecialchars($error) ?></p>
    <?php } ?>

    <form method="POST">
        <label>Nama Warna</label>
        <input type="text" name="nama"
               value="<?= htmlspecialchars($data['nama']) ?>" required>

        <label>Kode Warna</label>
        <input type="color" name="kode_warna"
               value="<?= htmlspecialchars($data['kode_warna']) ?>">

        <button type="submit" name="update">Simpan Perubahan</button>
    </form>

    <a href="palette.php" class="back">Kembali</a>
</div>

<script src="../script.js"></script>
</body>
</html>