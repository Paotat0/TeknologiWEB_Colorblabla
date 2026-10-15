<?php
session_start();
require_once "../color_history/color_palette/koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../color_history/color_palette/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: palette.php");
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    exit("ID tidak valid.");
}

$stmt = mysqli_prepare($conn, "DELETE FROM palette WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header("Location: palette.php");
exit;