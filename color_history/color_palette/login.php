<?php
session_start();
include "koneksi.php";

$pesan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $hasil = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($hasil)) {
        if (password_verify($password, $user["password"])) {
            session_regenerate_id(true);

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["nama"] = $user["nama"];
            $_SESSION["email"] = $user["email"];

            header("Location: dasboard.php");
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ColorFlow</title>
    <link rel="stylesheet" href="../../style.css">
</head>
<body class="auth-page">
    <main class="auth-shell">
        <aside class="auth-intro">
            <a class="auth-brand" href="../../index.php">Color<span>Flow</span></a>
            <div class="auth-intro-content">
                <p class="auth-eyebrow">RUANG KREATIF WARNA</p>
                <h1>Warna indah, dimulai dari satu ide.</h1>
                <p>Masuk untuk menyimpan warna favorit dan melanjutkan eksplorasi paletmu.</p>
            </div>
            <div class="auth-color-art" aria-hidden="true">
                <span></span><span></span><span></span><span></span>
            </div>
            <p class="auth-caption">Buat. Simpan. Kreasikan.</p>
        </aside>

        <section class="auth-form-panel" aria-labelledby="login-title">
            <a class="auth-mobile-brand" href="../../index.php">Color<span>Flow</span></a>
            <div class="auth-heading">
                <p class="auth-eyebrow">SELAMAT DATANG KEMBALI</p>
                <h2 id="login-title">Masuk ke akunmu</h2>
                <p>Lanjutkan perjalanan kreatifmu bersama ColorFlow.</p>
            </div>

            <?php if (isset($_GET["daftar"])): ?>
                <p class="auth-message auth-message-success" role="status">
                    Registrasi berhasil! Silakan masuk dengan akunmu.
                </p>
            <?php endif; ?>

            <?php if ($pesan !== ""): ?>
                <p class="auth-message auth-message-error" role="alert">
                    <?= htmlspecialchars($pesan, ENT_QUOTES, "UTF-8") ?>
                </p>
            <?php endif; ?>

            <form class="auth-form" method="POST">
                <label for="email">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    placeholder="nama@email.com"
                    autocomplete="email"
                    required
                >

                <label for="password">Password</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="Masukkan password"
                    autocomplete="current-password"
                    required
                >

                <button type="submit">Masuk ke akun <span aria-hidden="true">→</span></button>
            </form>

            <p class="auth-switch">
                Belum punya akun?
                <a href="register.php">Daftar sekarang</a>
            </p>

            <a class="auth-home-link" href="../../index.php">← Kembali ke halaman utama</a>
        </section>
    </main>
</body>
</html>