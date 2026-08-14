<?php

require __DIR__ . '/../inc/koneksi.php';
require __DIR__ . '/../inc/buku_helpers.php';

$result = mysqli_query($koneksi, "SELECT id_buku, seri_buku, judul_buku FROM tb_buku ORDER BY id_buku ASC");

if (!$result) {
    fwrite(STDERR, "Gagal membaca data buku.\n");
    exit(1);
}

while ($row = mysqli_fetch_assoc($result)) {
    $idBuku = mysqli_real_escape_string($koneksi, $row['id_buku']);
    $kodeBuku = mysqli_real_escape_string(
        $koneksi,
        generate_kode_buku($koneksi, $row['seri_buku'], $row['judul_buku'], $row['id_buku'], '')
    );

    $updated = mysqli_query(
        $koneksi,
        "UPDATE tb_buku SET kode_buku='" . $kodeBuku . "' WHERE id_buku='" . $idBuku . "'"
    );

    if (!$updated) {
        fwrite(STDERR, "Gagal update kode buku untuk " . $row['id_buku'] . ".\n");
        exit(1);
    }
}

echo "Kode buku berhasil digenerate.\n";
