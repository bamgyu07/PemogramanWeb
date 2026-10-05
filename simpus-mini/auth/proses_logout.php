<?php
session_start();

// Hapus semua data Sesi
session_unset();
session_destroy();

// Hapus Cookie "remember_user" dengan menyet tanggal kedaluwarsa ke masa lalu
if (isset($_COOKIE['remember_user'])) {
    setcookie('remember_user', '', time() - 3600, "/");
}

header('Location: login.php');
exit;