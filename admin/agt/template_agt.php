<?php
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=template_import_anggota.csv');

$out = fopen('php://output', 'w');
fwrite($out, "\xEF\xBB\xBF");
fputcsv($out, ['nama', 'jekel', 'kelas', 'pangkat_gol', 'nip', 'no_hp']);
fputcsv($out, ['Nama Anggota', 'Laki-laki', 'Jabatan atau Unit', 'Penata Tk.I III/d', '198901012010011001', '081234567890']);
fclose($out);
exit;
