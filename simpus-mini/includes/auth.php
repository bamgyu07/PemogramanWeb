<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. DAHULUKAN GUARD CLAUSE: Jika belum login, LANGSUNG redirect (tanpa nyentuh koneksi database)
if (!isset($_SESSION['user_id'])) {
    // Tentukan lokasi file login relatif terhadap direktori saat ini
    $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
    $isSubfolder = (strpos($scriptDir, '/buku') !== false || strpos($scriptDir, '/anggota') !== false);
    
    $loginUrl = $isSubfolder ? '../auth/login.php' : 'auth/login.php';
    
    header('Location: ' . $loginUrl);
    exit;
}

// 2. LOGIKA "INGAT SAYA" (Hanya dijalankan jika butuh & koneksi database aman)
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_user'])) {
    @include_once __DIR__ . '/koneksi.php';
    
    if (isset($pdo) && $pdo instanceof PDO) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->execute(['username' => $_COOKIE['remember_user']]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['nama']    = $row['nama'];
                $_SESSION['role']    = $row['role'];
            }
        } catch (Exception $e) {
            // Abaikan jika database error/mati
        }
    }
}

// 3. HELPER CEK ROLE (Soal 1 Opsional)
function check_role($allowed_roles = []) {
    $user_role = $_SESSION['role'] ?? '';
    if (!in_array($user_role, $allowed_roles)) {
        $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
        $isSubfolder = (strpos($scriptDir, '/buku') !== false || strpos($scriptDir, '/anggota') !== false);
        
        $indexUrl = $isSubfolder ? '../index.php' : 'index.php';
        
        header('Location: ' . $indexUrl . '?error=Akses ditolak!');
        exit;
    }
}