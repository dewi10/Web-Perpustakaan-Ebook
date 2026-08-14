<?php
include '../../inc/koneksi.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data_anggota_' . date('Ymd_His') . '.csv');

$out = fopen('php://output', 'w');
fwrite($out, "\xEF\xBB\xBF");
fputcsv($out, ['id_anggota', 'nama', 'jekel', 'kelas', 'pangkat_gol', 'nip', 'no_hp']);

$sql = mysqli_query($koneksi, "SELECT id_anggota, nama, jekel, kelas, pangkat_gol, nip, no_hp FROM tb_anggota ORDER BY id_anggota");
while ($row = mysqli_fetch_assoc($sql)) {
    fputcsv($out, $row);
}

fclose($out);
exit;
