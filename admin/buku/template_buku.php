<?php
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=template_import_buku.csv');

$out = fopen('php://output', 'w');
fwrite($out, "\xEF\xBB\xBF");
fputcsv($out, ['seri_buku', 'judul_buku', 'pengarang', 'penerbit', 'rak', 'th_terbit', 'jumlah']);
fputcsv($out, ['Kursus Kepemimpinan dan Manajemen Pertahanan', 'Doktrin Pertahanan', 'Nama Pengarang', 'Nama Penerbit', 'A-01', '2024', '3']);
fclose($out);
exit;
