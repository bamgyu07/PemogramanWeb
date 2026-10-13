-- Jobsheet 12: tabel peminjaman, menghubungkan buku, anggota, dan users
-- Jalankan: psql -d simpus_mini -f sql/03_peminjaman.sql

CREATE TABLE IF NOT EXISTS peminjaman (
    id SERIAL PRIMARY KEY,
    id_anggota INT NOT NULL,
    id_buku INT NOT NULL,
    tanggal_pinjam DATE NOT NULL DEFAULT CURRENT_DATE,
    tanggal_jatuh_tempo DATE, -- (Latihan 6.4 No. 2)
    status VARCHAR(20) NOT NULL DEFAULT 'dipinjam',
    CONSTRAINT fk_anggota FOREIGN KEY (id_anggota) REFERENCES anggota(id) ON DELETE CASCADE,
    CONSTRAINT fk_buku FOREIGN KEY (id_buku) REFERENCES buku(id) ON DELETE CASCADE
);