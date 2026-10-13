<?php
require_once '../includes/koneksi.php';
require_once '../includes/auth.php';

// --- LATIHAN 6.4 NO. 2 & NO. 4: Query JOIN lengkap (no_hp & tanggal_jatuh_tempo) ---
$sql = "SELECT p.id AS id_peminjaman, 
               a.nama AS nama_anggota, 
               a.no_hp, 
               b.judul AS judul_buku, 
               p.tanggal_pinjam, 
               p.tanggal_jatuh_tempo,
               CURRENT_DATE > p.tanggal_jatuh_tempo AS is_terlambat
        FROM peminjaman p
        JOIN anggota a ON p.id_anggota = a.id
        JOIN buku b ON p.id_buku = b.id
        WHERE p.status = 'dipinjam'
        ORDER BY p.tanggal_pinjam ASC";

$stmt = $pdo->query($sql);
$daftar_peminjaman = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengembalian Buku - Latihan 6.4</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2>Daftar Peminjaman Aktif & Jatuh Tempo</h2>
        <a href="tambah.php" class="btn btn-primary mb-3">+ Peminjaman Baru</a>
        <a href="riwayat.php" class="btn btn-secondary mb-3">Lihat Riwayat</a>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <table class="table table-bordered table-striped bg-white shadow-sm">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Anggota</th>
                    <th>No. HP (JOIN)</th>
                    <th>Judul Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Jatuh Tempo</th>
                    <th>Status Tempo</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($daftar_peminjaman)): ?>
                    <tr><td colspan="8" class="text-center">Tidak ada transaksi peminjaman aktif.</td></tr>
                <?php else: ?>
                    <?php foreach ($daftar_peminjaman as $index => $row): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($row['nama_anggota']) ?></td>
                        <td><?= htmlspecialchars($row['no_hp'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($row['judul_buku']) ?></td>
                        <td><?= $row['tanggal_pinjam'] ?></td>
                        <td><?= $row['tanggal_jatuh_tempo'] ?></td>
                        <td>
                            <?php if ($row['is_terlambat']): ?>
                                <span class="badge bg-danger">Terlambat (> 14 Hari)</span>
                            <?php else: ?>
                                <span class="badge bg-success">Tepat Waktu</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form action="proses_kembali.php" method="POST" onsubmit="return confirm('Kembalikan buku ini?');">
                                <input type="hidden" name="id_peminjaman" value="<?= $row['id_peminjaman'] ?>">
                                <button type="submit" class="btn btn-sm btn-success">Kembalikan</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>