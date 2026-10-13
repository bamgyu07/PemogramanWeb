<?php
require_once '../includes/koneksi.php';
require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_peminjaman = $_POST['id_peminjaman'];

    try {
        $pdo->beginTransaction();

        // Ambil id_buku dari peminjaman aktif & lock barisnya
        $stmt_peminjaman = $pdo->prepare("SELECT id_buku FROM peminjaman WHERE id = :id AND status = 'dipinjam' FOR UPDATE");
        $stmt_peminjaman->execute(['id' => $id_peminjaman]);
        $peminjaman = $stmt_peminjaman->fetch();

        if (!$peminjaman) {
            $pdo->rollBack();
            $_SESSION['error'] = "Data peminjaman tidak ditemukan atau sudah dikembalikan.";
            header("Location: kembali.php");
            exit;
        }

        $id_buku = $peminjaman['id_buku'];

        // Update status peminjaman menjadi 'kembali'
        $stmt_update_peminjaman = $pdo->prepare("UPDATE peminjaman SET status = 'kembali' WHERE id = :id");
        $stmt_update_peminjaman->execute(['id' => $id_peminjaman]);

        // Tambahkan kembali stok buku (+1)
        $stmt_update_buku = $pdo->prepare("UPDATE buku SET stok = stok + 1 WHERE id = :id");
        $stmt_update_buku->execute(['id' => $id_buku]);

        $pdo->commit();

        $_SESSION['success'] = "Buku berhasil dikembalikan dan stok bertambah.";
        header("Location: kembali.php");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
        header("Location: kembali.php");
        exit;
    }
}