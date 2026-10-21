
<?php
require_once '../includes/auth.php';
require_once '../includes/koneksi.php';

$page_title = 'Pengembalian Buku';

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

include __DIR__ . '/../includes/header.php';
?>

<style>
    .loan-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 16px;
    }

    .loan-btn {
        display: inline-block;
        padding: 9px 14px;
        border: none;
        border-radius: 4px;
        color: white;
        text-decoration: none;
        font-size: 14px;
        cursor: pointer;
    }

    .loan-btn:hover {
        color: white;
        text-decoration: none;
        opacity: 0.9;
    }

    .loan-btn-primary {
        background: #1d5b8a;
    }

    .loan-btn-secondary {
        background: #6c757d;
    }

    .loan-btn-return {
        background: #1d5b8a;
        padding: 6px 10px;
        font-size: 12px;
    }

    .loan-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .loan-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        font-size: 14px;
    }

    .loan-table th,
    .loan-table td {
        padding: 10px 8px;
        border: 1px solid #d9dee3;
        text-align: left;
        vertical-align: middle;
    }

    .loan-table thead {
        background: #1d5b8a;
        color: white;
    }

    .loan-table thead th {
        white-space: nowrap;
    }

    .loan-table tbody tr:nth-child(even) {
        background: #f2f4f6;
    }

    .loan-table .empty-row {
        text-align: center;
        padding: 18px;
        background: #f2f4f6;
    }

    .loan-status {
        display: inline-block;
        padding: 4px 7px;
        border-radius: 4px;
        color: white;
        font-size: 12px;
        font-weight: bold;
        white-space: nowrap;
    }

    .loan-status-late {
        background: #c9302c;
    }

    .loan-status-safe {
        background: #1d5b8a;
    }

    .loan-table form {
        margin: 0;
    }
</style>

<section>
    <h2 style="color: #2b2b2b;">
        Daftar Peminjaman Aktif &amp; Jatuh Tempo
    </h2>

    <div class="loan-actions">
        <a href="tambah.php"
           class="loan-btn loan-btn-primary">
            + Peminjaman Baru
        </a>

        <a href="riwayat.php"
           class="loan-btn loan-btn-secondary">
            Lihat Riwayat
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="flash flash-success">
            <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="flash flash-error">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="loan-table-wrap">
        <table class="loan-table">
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
                    <tr>
                        <td colspan="8" class="empty-row">
                            Tidak ada transaksi peminjaman aktif.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($daftar_peminjaman as $index => $row): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>

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
                                <?php if ($row['is_terlambat']): ?>
                                    <span class="loan-status loan-status-late">
                                        Terlambat
                                    </span>
                                <?php else: ?>
                                    <span class="loan-status loan-status-safe">
                                        Tepat Waktu
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <form
                                    action="proses_kembali.php"
                                    method="POST"
                                    onsubmit="return confirm('Kembalikan buku ini?');"
                                >
                                    <input
                                        type="hidden"
                                        name="id_peminjaman"
                                        value="<?= (int) $row['id_peminjaman'] ?>"
                                    >

                                    <button
                                        type="submit"
                                        class="loan-btn loan-btn-return"
                                    >
                                        Kembalikan
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
