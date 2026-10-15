<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

function respond(int $status, array $data): void
{
    http_response_code($status);
    echo json_encode($data);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    respond(401, ['success' => false, 'message' => 'Sesi login berakhir. Silakan login kembali.']);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    respond(405, ['success' => false, 'message' => 'Metode permintaan tidak diizinkan.']);
}

$csrfToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
if (
    !isset($_SESSION['color_picker_csrf_token']) ||
    !hash_equals($_SESSION['color_picker_csrf_token'], $csrfToken)
) {
    respond(403, ['success' => false, 'message' => 'Token keamanan tidak valid. Muat ulang halaman dan coba lagi.']);
}

$request = json_decode(file_get_contents('php://input'), true);
if (!is_array($request)) {
    respond(400, ['success' => false, 'message' => 'Data permintaan tidak valid.']);
}

$action = $request['action'] ?? '';
$color = $request['color'] ?? null;
if (!in_array($action, ['list', 'add_history', 'add_library', 'remove_library'], true)) {
    respond(400, ['success' => false, 'message' => 'Aksi tidak dikenal.']);
}

if ($action !== 'list' && (!is_string($color) || !preg_match('/^#[0-9a-fA-F]{6}$/', $color))) {
    respond(400, ['success' => false, 'message' => 'Kode warna HEX tidak valid.']);
}

$color = is_string($color) ? strtoupper($color) : null;
$userId = (int) $_SESSION['user_id'];

try {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    require_once "../color_history/color_palette/koneksi.php";
    mysqli_set_charset($conn, 'utf8mb4');

    if ($action === 'list') {
        $history = [];
        $stmt = mysqli_prepare(
            $conn,
            "SELECT hex FROM color_history WHERE user_id = ? ORDER BY created_at DESC, id DESC LIMIT 25"
        );
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            $history[] = $row['hex'];
        }
        mysqli_stmt_close($stmt);

        $library = [];
        $stmt = mysqli_prepare(
            $conn,
            "SELECT hex FROM color_library WHERE user_id = ? ORDER BY created_at DESC, id DESC"
        );
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            $library[] = $row['hex'];
        }
        mysqli_stmt_close($stmt);

        respond(200, ['success' => true, 'history' => $history, 'library' => $library]);
    }

    if ($action === 'add_history') {
        mysqli_begin_transaction($conn);
        try {
            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO color_history (user_id, hex) VALUES (?, ?)"
            );
            mysqli_stmt_bind_param($stmt, "is", $userId, $color);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $stmt = mysqli_prepare(
                $conn,
                "DELETE FROM color_history
                 WHERE user_id = ?
                   AND id NOT IN (
                       SELECT id FROM (
                           SELECT id FROM color_history
                           WHERE user_id = ?
                           ORDER BY created_at DESC, id DESC
                           LIMIT 25
                       ) AS recent_colors
                   )"
            );
            mysqli_stmt_bind_param($stmt, "ii", $userId, $userId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            mysqli_commit($conn);
        } catch (Throwable $error) {
            mysqli_rollback($conn);
            throw $error;
        }

        $history = [];
        $stmt = mysqli_prepare(
            $conn,
            "SELECT hex FROM color_history WHERE user_id = ? ORDER BY created_at DESC, id DESC LIMIT 25"
        );
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            $history[] = $row['hex'];
        }
        mysqli_stmt_close($stmt);

        respond(200, ['success' => true, 'history' => $history]);
    }

    if ($action === 'add_library') {
        $stmt = mysqli_prepare(
            $conn,
            "INSERT IGNORE INTO color_library (user_id, hex) VALUES (?, ?)"
        );
        mysqli_stmt_bind_param($stmt, "is", $userId, $color);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } elseif ($action === 'remove_library') {
        $stmt = mysqli_prepare(
            $conn,
            "DELETE FROM color_library WHERE user_id = ? AND hex = ?"
        );
        mysqli_stmt_bind_param($stmt, "is", $userId, $color);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    $library = [];
    $stmt = mysqli_prepare(
        $conn,
        "SELECT hex FROM color_library WHERE user_id = ? ORDER BY created_at DESC, id DESC"
    );
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $library[] = $row['hex'];
    }
    mysqli_stmt_close($stmt);

    respond(200, ['success' => true, 'library' => $library]);
} catch (Throwable $error) {
    error_log("Color picker database error: " . $error->getMessage());
    respond(500, ['success' => false, 'message' => 'Terjadi kesalahan saat mengakses database. Pastikan koneksi dan tabel warna sudah disiapkan.']);
}
