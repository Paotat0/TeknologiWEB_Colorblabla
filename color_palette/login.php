<?php
session_start();
include "koneksi.php";

$pesan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($koneksi, $sql);

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $hasil = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($hasil)) {
        if (password_verify($password, $user["password"])) {
            session_regenerate_id(true);

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["nama"] = $user["nama"];
            $_SESSION["email"] = $user["email"];

            header("Location: dashboard.php");
            exit;
        } else {
            $pesan = "Password salah!";
        }
    } else {
        $pesan = "Email tidak ditemukan!";
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h2>Login Color Palette</h2>

    <p style="color:green;">
        <?php
        if (isset($_GET["daftar"])) {
            echo "Registrasi berhasil! Silakan login.";
        }
        ?>
    </p>

    <p style="color:red;">
        <?= htmlspecialchars($pesan) ?>
    </p>

    <form method="POST">
        <label>Email:</label><br>
        <input type="email" name="email" required>
        <br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required>
        <br><br>

        <button type="submit">Login</button>
    </form>

    <p>Belum punya akun?
        <a href="register.php">Daftar di sini</a>
    </p>
</body>
</html>