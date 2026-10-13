<?php
require __DIR__ . '/../includes/auth.php';
$page_title = "Peminjaman Baru";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

// Ambil data anggota (sekaligus menampilkan No HP)
$stmt_anggota = $pdo->query("SELECT * FROM anggota ORDER BY nama ASC");
$daftar_anggota = $stmt_anggota->fetchAll();

// Ambil data buku yang stoknya > 0 (untuk dropdown stok > 0)
$stmt_buku = $pdo->query("SELECT * FROM buku WHERE stok > 0 ORDER BY judul ASC");
$daftar_buku = $stmt_buku->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Peminjaman Baru - Latihan 6.4</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2>Form Peminjaman Buku Baru</h2>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form action="proses_tambah.php" method="POST" class="card p-4 shadow-sm bg-white">
            <div class="mb-3">
                <label for="id_anggota" class="form-label">Pilih Anggota</label>
                <select name="id_anggota" id="id_anggota" class="form-control" required>
                    <option value="">-- Pilih Anggota --</option>
                    <?php foreach ($daftar_anggota as $anggota): ?>
                        <option value="<?= $anggota['id'] ?>"><?= htmlspecialchars($anggota['nama']) ?> (No HP: <?= htmlspecialchars($anggota['no_hp'] ?? '-') ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="id_buku" class="form-label">Pilih Buku (Stok > 0)</label>
                <select name="id_buku" id="id_buku" class="form-control" required>
                    <option value="">-- Pilih Buku --</option>
                    <?php foreach ($daftar_buku as $buku): ?>
                        <option value="<?= $buku['id'] ?>"><?= htmlspecialchars($buku['judul']) ?> (Stok: <?= $buku['stok'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Peminjaman</button>
            <a href="kembali.php" class="btn btn-secondary mt-2">Kembali ke Daftar Pengembalian</a>
        </form>
    </div>
</body>
</html>