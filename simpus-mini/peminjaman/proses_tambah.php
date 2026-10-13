<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/csrf.php';
require __DIR__ . '/../includes/koneksi.php';

csrf_verify();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_anggota = $_POST['id_anggota'];
    $id_buku    = $_POST['id_buku'];

    try {
        $pdo->beginTransaction();

        // --- LATIHAN 6.4 NO. 1: Validasi Bisnis Keterlambatan > 14 Hari ---
        $sql_check = "SELECT CURRENT_DATE - tanggal_pinjam AS selisih 
                      FROM peminjaman 
                      WHERE id_anggota = :id_anggota 
                        AND status = 'dipinjam' 
                        AND (CURRENT_DATE - tanggal_pinjam) > 14
                      LIMIT 1";
        
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute(['id_anggota' => $id_anggota]);
        $terlambat = $stmt_check->fetch();

        if ($terlambat) {
            $pdo->rollBack();
            $_SESSION['error'] = "Transaksi ditolak! Anggota memiliki peminjaman aktif yang terlambat lebih dari 14 hari.";
            header("Location: tambah.php");
            exit;
        }

        // Kunci baris buku menggunakan FOR UPDATE (Latihan 6.4 No. 3 - Uji Race Condition)
        $stmt_buku = $pdo->prepare("SELECT stok FROM buku WHERE id = :id FOR UPDATE");
        $stmt_buku->execute(['id' => $id_buku]);
        $buku = $stmt_buku->fetch();

        if (!$buku || $buku['stok'] < 1) {
            $pdo->rollBack();
            $_SESSION['error'] = "Stok buku tidak tersedia.";
            header("Location: tambah.php");
            exit;
        }

        // --- LATIHAN 6.4 NO. 2: Insert dengan Tanggal Jatuh Tempo Otomatis (+14 hari) ---
        $sql_insert = "INSERT INTO peminjaman (id_anggota, id_buku, tanggal_pinjam, tanggal_jatuh_tempo, status) 
                       VALUES (:id_anggota, :id_buku, CURRENT_DATE, CURRENT_DATE + INTERVAL '14 days', 'dipinjam')";
        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->execute([
            'id_anggota' => $id_anggota,
            'id_buku'    => $id_buku
        ]);

        // Kurangi stok buku
        $stmt_update = $pdo->prepare("UPDATE buku SET stok = stok - 1 WHERE id = :id");
        $stmt_update->execute(['id' => $id_buku]);

        $pdo->commit();

        $_SESSION['success'] = "Peminjaman berhasil dicatat.";
        header("Location: kembali.php");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
        header("Location: tambah.php");
        exit;
    }
}