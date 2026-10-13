<?php
require_once '../includes/koneksi.php';
require_once '../includes/auth.php';

$sql = "SELECT p.*, a.nama AS nama_anggota, a.no_hp, b.judul AS judul_buku 
        FROM peminjaman p
        JOIN anggota a ON p.id_anggota = a.id
        JOIN buku b ON p.id_buku = b.id
        ORDER BY p.id DESC";
$stmt = $pdo->query($sql);
$riwayat = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Peminjaman - Latihan 6.4</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2>Riwayat Peminjaman Buku</h2>
        <a href="kembali.php" class="btn btn-secondary mb-3">&laquo; Kembali ke Transaksi Aktif</a>

        <table class="table table-striped table-bordered bg-white shadow-sm">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Anggota</th>
                    <th>No. HP</th>
                    <th>Judul Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Jatuh Tempo</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($riwayat)): ?>
                    <tr><td colspan="7" class="text-center">Belum ada riwayat peminjaman.</td></tr>
                <?php else: ?>
                    <?php foreach ($riwayat as $i => $row): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($row['nama_anggota']) ?></td>
                        <td><?= htmlspecialchars($row['no_hp'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($row['judul_buku']) ?></td>
                        <td><?= $row['tanggal_pinjam'] ?></td>
                        <td><?= $row['tanggal_jatuh_tempo'] ?? '-' ?></td>
                        <td>
                            <?php if ($row['status'] === 'dipinjam'): ?>
                                <span class="badge bg-warning text-dark">Dipinjam</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Dikembalikan</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>