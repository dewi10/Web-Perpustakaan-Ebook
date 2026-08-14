<?php
include '../../inc/koneksi.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data_buku_' . date('Ymd_His') . '.csv');

$out = fopen('php://output', 'w');
fwrite($out, "\xEF\xBB\xBF");
fputcsv($out, ['id_buku', 'kode_buku', 'seri_buku', 'judul_buku', 'pengarang', 'penerbit', 'rak', 'th_terbit', 'jumlah']);

$sql = mysqli_query($koneksi, "SELECT id_buku, kode_buku, seri_buku, judul_buku, pengarang, penerbit, rak, th_terbit, jumlah FROM tb_buku ORDER BY id_buku");
while ($row = mysqli_fetch_assoc($sql)) {
    fputcsv($out, $row);
}

fclose($out);
exit;
