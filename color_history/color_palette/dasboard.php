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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ColorFlow</title>
    <link rel="stylesheet" href="../../style.css">
</head>
<body class="auth-page auth-dashboard">
    <main class="auth-shell">
        <aside class="auth-intro">
            <a class="auth-brand" href="../../index.php">Color<span>Flow</span></a>
            <div class="auth-intro-content">
                <p class="auth-eyebrow">DASHBOARD COLOR PALETTE</p>
                <h1>Kreasikan warna versimu.</h1>
                <p>Semua inspirasi warna dan alat kreatifmu, tersedia dari satu tempat.</p>
            </div>
            <div class="auth-color-art" aria-hidden="true">
                <span></span><span></span><span></span><span></span>
            </div>
            <p class="auth-caption">Buat. Simpan. Kreasikan.</p>
        </aside>

        <section class="auth-form-panel auth-dashboard-panel" aria-labelledby="dashboard-title">
            <a class="auth-mobile-brand" href="../../index.php">Color<span>Flow</span></a>
            <div class="auth-heading">
                <p class="auth-eyebrow">BERHASIL MASUK</p>
                <h2 id="dashboard-title">
                    Halo, <?= htmlspecialchars($_SESSION["nama"], ENT_QUOTES, "UTF-8") ?>!
                </h2>
                <p>Senang melihatmu kembali. Mau mulai dari mana hari ini?</p>
            </div>

            <div class="auth-account">
                <span class="auth-account-icon" aria-hidden="true">
                    <?= htmlspecialchars(strtoupper(substr($_SESSION["nama"], 0, 1)), ENT_QUOTES, "UTF-8") ?>
                </span>
                <span>
                    <span class="auth-account-label">Akun aktif</span>
                    <strong><?= htmlspecialchars($_SESSION["email"], ENT_QUOTES, "UTF-8") ?></strong>
                </span>
            </div>

            <nav class="auth-dashboard-actions" aria-label="Menu dashboard">
                <a class="auth-dashboard-link auth-dashboard-link-primary" href="../../fitur_warna/color_picker.php">
                    <span>
                        <strong>Color Picker &amp; History</strong>
                        <small>Pilih warna dan lihat warna tersimpan</small>
                    </span>
                    <span class="auth-dashboard-arrow" aria-hidden="true">→</span>
                </a>
                <a class="auth-dashboard-link" href="../../fitur_warna/indexwarna.php">
                    <span>
                        <strong>Kelola fitur warna</strong>
                        <small>Atur koleksi palet warnamu</small>
                    </span>
                    <span class="auth-dashboard-arrow" aria-hidden="true">→</span>
                </a>
                <a class="auth-dashboard-link" href="../../index.php">
                    <span>
                        <strong>Halaman utama</strong>
                        <small>Kembali ke Gradient Generator</small>
                    </span>
                    <span class="auth-dashboard-arrow" aria-hidden="true">→</span>
                </a>
            </nav>

            <a class="auth-dashboard-logout" href="logout.php">Keluar dari akun</a>
        </section>
    </main>
</body>
</html>
