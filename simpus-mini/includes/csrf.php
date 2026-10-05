<?php
// Proteksi CSRF (Cross-Site Request Forgery) dasar berbasis token per-session.
// Membutuhkan session yang sudah dimulai (lihat header.php / auth.php).

function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

function csrf_verify()
{
    $token = $_POST['csrf_token'] ?? '';
    if ($token === '' || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        die('Permintaan ditolak: token CSRF tidak valid atau kedaluwarsa.');
    }
}

// Versi GET: mengembalikan true/false (tidak die()), karena dipanggil
// setelah header.php sudah mengeluarkan HTML sehingga status 403
// tidak bisa dikirim lagi.
function csrf_verify_get()
{
    $token = $_GET['csrf_token'] ?? '';
    return $token !== '' && hash_equals($_SESSION['csrf_token'] ?? '', $token);
}