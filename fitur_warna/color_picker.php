<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../color_history/color_palette/login.php");
    exit;
}

if (!isset($_SESSION['color_picker_csrf_token'])) {
    $_SESSION['color_picker_csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Color Picker &amp; Library</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="color-picker-page">

<main class="container" data-csrf-token="<?= htmlspecialchars($_SESSION['color_picker_csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
    <header class="page-header">
        <div>
            <p class="eyebrow">COLOR TOOLS</p>
            <h1>Color Picker</h1>
            <p>Pilih warna, simpan favorit, dan akses kembali warna yang pernah dibuat.</p>
        </div>
        <a href="../index.php" class="back-link">Kembali ke halaman utama</a>
    </header>

    <section class="picker-panel" aria-labelledby="picker-title">
        <div class="picker-control">
            <label for="pilihWarna" id="picker-title">Pilih warna</label>
            <input type="color" id="pilihWarna" value="#3498db">
        </div>
        <div class="current-color">
            <div id="previewWarna" class="color-preview" aria-hidden="true"></div>
            <div class="color-details">
                <span class="label">Kode HEX</span>
                <strong id="kodeWarna">#3498DB</strong>
            </div>
            <div class="current-actions">
                <button type="button" id="salinWarna" class="button button-secondary">Salin kode</button>
                <button type="button" id="simpanWarna" class="button button-primary">Simpan ke library</button>
            </div>
        </div>
        <p id="pesan" class="status-message" role="status" aria-live="polite"></p>
    </section>

    <div class="color-sections">
        <section class="list-panel" aria-labelledby="library-title">
            <div class="section-heading">
                <div>
                    <h2 id="library-title">Color Library</h2>
                    <p>Warna favorit yang kamu simpan.</p>
                </div>
                <span id="jumlahLibrary" class="count-badge">0 warna</span>
            </div>
            <div id="libraryWarna" class="color-list"></div>
        </section>

        <section class="list-panel" aria-labelledby="history-title">
            <div class="section-heading">
                <div>
                    <h2 id="history-title">History Log</h2>
                    <p>Maksimal 25 warna terbaru. Pilih warna untuk menggunakannya kembali.</p>
                </div>
                <span id="jumlahHistory" class="count-badge">0 / 25</span>
            </div>
            <div id="riwayatWarna" class="color-list"></div>
        </section>
    </div>
</main>

<script src="color_picker.js" defer></script>
</body>
</html>