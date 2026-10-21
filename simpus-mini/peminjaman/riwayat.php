
<?php
require_once '../includes/auth.php';
require_once '../includes/koneksi.php';

$page_title = 'Riwayat Peminjaman';

$sql = "SELECT p.*,
               a.nama AS nama_anggota,
               a.no_hp,
               b.judul AS judul_buku
        FROM peminjaman p
        JOIN anggota a ON p.id_anggota = a.id
        JOIN buku b ON p.id_buku = b.id
        ORDER BY p.id DESC";

$stmt = $pdo->query($sql);
$riwayat = $stmt->fetchAll();

include __DIR__ . '/../includes/header.php';
?>

<style>
    .history-actions {
        margin-bottom: 16px;
    }

    .history-btn {
        display: inline-block;
        padding: 9px 14px;
        border-radius: 4px;
        background: #6c757d;
        color: white;
        text-decoration: none;
        font-size: 14px;
    }

    .history-btn:hover {
        color: white;
        text-decoration: none;
        opacity: 0.9;
    }

    .history-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .history-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        font-size: 14px;
    }

    .history-table th,
    .history-table td {
        padding: 10px 8px;
        border: 1px solid #d9dee3;
        text-align: left;
        vertical-align: middle;
    }

    .history-table thead {
        background: #1d5b8a;
        color: white;
    }

    .history-table thead th {
        white-space: nowrap;
    }

    .history-table tbody tr:nth-child(even) {
        background: #f2f4f6;
    }

    .history-table .empty-row {
        text-align: center;
        padding: 18px;
        background: #f2f4f6;
    }

    .history-status {
        display: inline-block;
        padding: 4px 7px;
        border-radius: 4px;
        color: white;
        font-size: 12px;
        font-weight: bold;
        white-space: nowrap;
    }

    .history-status-borrowed {
        background: #f0ad4e;
        color: #252525;
    }

    .history-status-returned {
        background: #6c757d;
    }
</style>

<section>
    <h2 style="color: #2b2b2b;">
        Riwayat Peminjaman Buku
    </h2>

    <div class="history-actions">
        <a href="kembali.php" class="history-btn">
            &laquo; Kembali ke Transaksi Aktif
        </a>
    </div>

    <div class="history-table-wrap">
        <table class="history-table">
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
                    <tr>
                        <td colspan="7" class="empty-row">
                            Belum ada riwayat peminjaman.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($riwayat as $i => $row): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>

                            <td>
                                <?= htmlspecialchars($row['nama_anggota']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['no_hp'] ?? '-') ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['judul_buku']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['tanggal_pinjam']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $row['tanggal_jatuh_tempo'] ?? '-'
                                ) ?>
                            </td>

                            <td>
                                <?php if ($row['status'] === 'dipinjam'): ?>
                                    <span class="history-status history-status-borrowed">
                                        Dipinjam
                                    </span>
                                <?php else: ?>
                                    <span class="history-status history-status-returned">
                                        Dikembalikan
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
