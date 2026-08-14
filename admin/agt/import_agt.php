<?php
session_start();
include '../../inc/koneksi.php';

function redirect_import($status, $count = 0, $message = '') {
    $params = [
        'page' => 'MyApp/data_agt',
        'import_status' => $status,
    ];
    if ($count > 0) {
        $params['import_count'] = $count;
    }
    if ($message !== '') {
        $params['import_message'] = $message;
    }
    header('Location: ../../index.php?' . http_build_query($params));
    exit;
}

function next_member_id($nextNumber) {
    return 'A' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
}

function normalize_gender($value) {
    $value = strtolower(trim((string) $value));
    $map = [
        'laki-laki' => 'Laki-laki',
        'pria' => 'Laki-laki',
        'lk' => 'Laki-laki',
        'l' => 'Laki-laki',
        'perempuan' => 'Perempuan',
        'wanita' => 'Perempuan',
        'pr' => 'Perempuan',
        'p' => 'Perempuan',
        '-' => '-',
        '' => '-',
        'tidak diketahui' => '-',
    ];

    return $map[$value] ?? '-';
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_import('error', 0, 'Metode upload tidak valid.');
}

if (!isset($_FILES['file_csv']) || $_FILES['file_csv']['error'] !== UPLOAD_ERR_OK) {
    redirect_import('error', 0, 'File CSV gagal diupload.');
}

$tmpFile = $_FILES['file_csv']['tmp_name'];
$handle = fopen($tmpFile, 'r');
if (!$handle) {
    redirect_import('error', 0, 'File CSV tidak bisa dibaca.');
}

$header = fgetcsv($handle);
if (!$header) {
    fclose($handle);
    redirect_import('error', 0, 'File CSV kosong.');
}

$header = array_map(function ($value) {
    $value = preg_replace('/^\xEF\xBB\xBF/', '', (string) $value);
    return strtolower(trim($value));
}, $header);

$required = ['nama', 'jekel', 'kelas', 'pangkat_gol', 'nip', 'no_hp'];
if ($header !== $required) {
    fclose($handle);
    redirect_import('error', 0, 'Header CSV anggota harus: nama,jekel,kelas,pangkat_gol,nip,no_hp');
}

$result = mysqli_query($koneksi, "SELECT MAX(CAST(SUBSTRING(id_anggota,2) AS UNSIGNED)) AS last_id FROM tb_anggota");
$row = mysqli_fetch_assoc($result);
$nextNumber = ((int) ($row['last_id'] ?? 0)) + 1;
$inserted = 0;

mysqli_begin_transaction($koneksi);

try {
    while (($data = fgetcsv($handle)) !== false) {
        if (count(array_filter($data, function ($value) { return trim((string) $value) !== ''; })) === 0) {
            continue;
        }

        $data = array_pad($data, 6, '');
        $nama = trim($data[0]);
        $jekel = normalize_gender($data[1]);
        $kelas = trim($data[2]);
        $pangkatGol = trim($data[3]);
        $nip = trim($data[4]);
        $hp = trim($data[5]);

        if ($nama === '') {
            continue;
        }

        $idAnggota = next_member_id($nextNumber++);
        $nama = mysqli_real_escape_string($koneksi, $nama);
        $jekel = mysqli_real_escape_string($koneksi, $jekel);
        $kelas = mysqli_real_escape_string($koneksi, $kelas !== '' ? $kelas : '-');
        $pangkatGol = mysqli_real_escape_string($koneksi, $pangkatGol !== '' ? $pangkatGol : '-');
        $nip = mysqli_real_escape_string($koneksi, $nip !== '' ? $nip : '-');
        $hp = mysqli_real_escape_string($koneksi, $hp !== '' ? $hp : '-');

        $ok = mysqli_query(
            $koneksi,
            "INSERT INTO tb_anggota (id_anggota, nama, jekel, kelas, pangkat_gol, nip, no_hp)
             VALUES ('$idAnggota', '$nama', '$jekel', '$kelas', '$pangkatGol', '$nip', '$hp')"
        );

        if (!$ok) {
            throw new Exception('Gagal menyimpan data anggota.');
        }

        $inserted++;
    }

    mysqli_commit($koneksi);
    fclose($handle);
    redirect_import('success', $inserted);
} catch (Exception $e) {
    mysqli_rollback($koneksi);
    fclose($handle);
    redirect_import('error', 0, $e->getMessage());
}
