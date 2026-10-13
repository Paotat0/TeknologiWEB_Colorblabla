<?php
require_once "koneksi.php";

$pesan = "";
$jenis_pesan = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = trim($_POST["nama"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($nama === "" || $email === "" || $password === "") {
        $pesan = "Semua kolom wajib diisi.";
        $jenis_pesan = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $pesan = "Format email tidak valid.";
        $jenis_pesan = "error";

    } elseif (strlen($password) < 8) {
        $pesan = "Password minimal 8 karakter.";
        $jenis_pesan = "error";

    } else {
        $stmt = mysqli_prepare(
            $conn,
            "SELECT id FROM users WHERE email = ?"
        );
         mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $pesan = "Email sudah terdaftar.";
            $jenis_pesan = "error";
        } else {
            mysqli_stmt_close($stmt);

            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO users (nama, email, password) VALUES (?, ?, ?)"
            );
            mysqli_stmt_bind_param(
                $stmt,
                "sss",
                $nama,
                $email,
                $password_hash
            );

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header("Location: login.php?daftar=berhasil");
                exit;
            } else {
                $pesan = "Registrasi gagal. Silakan coba lagi.";
                $jenis_pesan = "error";
            }
        }

        if ($stmt instanceof mysqli_stmt) {
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Color Palette</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f2f4f8;
            font-family: Arial, sans-serif;
        }

        .container {
            width: 90%;
            max-width: 400px;
            padding: 30px;
            background: white;
            border-radius: 14px;
            box-shadow: 0 5px 25px #00000012;
        }

        h1 {
            margin-top: 0;
            color: #222;
            text-align: center;
        }

        p {
            color: #666;
            text-align: center;
        }

        label {
            display: block;
            margin: 15px 0 6px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 7px;
        }

        button {
            width: 100%;
            margin-top: 22px;
            padding: 12px;
            border: none;
            border-radius: 7px;
            background: #5b55e7;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .pesan {
            padding: 10px;
            border-radius: 7px;
            background: #fff0f0;
            color: #b42318;
        }

        .link {
            margin-top: 20px;
            text-align: center;
        }

        a {
            color: #5b55e7;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Buat Akun</h1>
        <p>Daftar untuk mulai mengelola palet warna.</p>

        <?php if ($pesan !== ""): ?>
            <div class="pesan">
                <?= htmlspecialchars($pesan, ENT_QUOTES, "UTF-8") ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="nama">Nama</label>
            <input
                type="text"
                id="nama"
                name="nama"
                maxlength="100"
                required
                value="<?= htmlspecialchars($_POST["nama"] ?? "", ENT_QUOTES, "UTF-8") ?>"
            >

            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                maxlength="100"
                required
                value="<?= htmlspecialchars($_POST["email"] ?? "", ENT_QUOTES, "UTF-8") ?>"
            >

            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                minlength="8"
                required
            >

            <button type="submit">Daftar</button>
        </form>

        <div class="link">
            Sudah punya akun?
            <a href="login.php">Login di sini</a>
        </div>
    </div>
</body>
</html>