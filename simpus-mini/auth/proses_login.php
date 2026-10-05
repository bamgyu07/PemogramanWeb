<?php
session_start();
require_once '../includes/db.php';

if (is_string($db)) {
    $db = pg_connect($db);
}

if (!$db) {
    header('Location: login.php?error=Koneksi database gagal');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = pg_prepare($db, "get_user", "SELECT * FROM users WHERE username = $1");
    $result = pg_execute($db, "get_user", [$username]);

    if ($row = pg_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            // Set Sesi Login Normal
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['nama']    = $row['nama'];
            $_SESSION['role']    = $row['role'];

            // Jika checkbox "Ingat Saya" dicentang
            if (isset($_POST['remember_me'])) {
                // Simpan username di Cookie selama 7 hari (86400 * 7 detik)
                // Parameter httponly = true (argumen terakhir) untuk keamanan dari XSS
                setcookie('remember_user', $row['username'], time() + (86400 * 7), "/", "", false, true);
            }

            header('Location: ../index.php');
            exit;
        }
    }
    $_SESSION['login_attempts'] += 1;
    $sisa = 3 - $_SESSION['login_attempts'];
    
    header('Location: login.php?error=Username atau password salah&login_attempts=' . $sisa);
    exit;
}