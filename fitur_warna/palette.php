<?php
session_start();
require_once "../color_history/color_palette/koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../color_history/color_palette/login.php");
    exit;
}

if (isset($_POST['simpan'])) {
    $nama = trim($_POST['nama']);
    $warna = $_POST['kode_warna'];

    if ($nama !== "" &&
        preg_match('/^#[0-9a-fA-F]{6}$/', $warna)) {

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO palette (nama, kode_warna) VALUES (?, ?)"
        );

        mysqli_stmt_bind_param($stmt, "ss", $nama, $warna);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("Location: palette.php");
        exit;
    }
}

$query = mysqli_query(
    $conn,
    "SELECT * FROM palette ORDER BY id DESC"
);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Palette</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>


            <td>
                <a href="edit_pallete.php?id=<?= (int)$row['id'] ?>">
                    Edit
                </a>

                |

                <form method="POST" action="delete_palette.php"
                      class="form-hapus" style="display:inline">
                    <input type="hidden" name="id"
                           value="<?= (int)$row['id'] ?>">
                    <button type="submit" class="btn-hapus">
                        Hapus
                    </button>
                </form>
            </td>
        </tr>
        <?php?>
    </table>

    <a href="indexwarna.php" class="back">Kembali</a>
</div>

<script src="../script.js"></script>
</body>
</html>