<?php
session_start();
include '../../inc/koneksi.php';
include_once '../../inc/buku_helpers.php';

function redirect_import($status, $count = 0, $message = '') {
    $params = [
        'page' => 'MyApp/data_buku',
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

function next_book_id($conn, $nextNumber) {
    return 'B' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
}

function split_series_title($seri, $judul) {
    $seri = trim((string) $seri);
    $judul = trim((string) $judul);

    if ($seri === '' && strpos($judul, ' - ') !== false) {
        $parts = explode(' - ', $judul, 2);
        if (count($parts) === 2) {
            $seri = trim($parts[0]);
            $judul = trim($parts[1]);
        }
    }

    return [$seri, $judul];
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

$requiredNew = ['seri_buku', 'judul_buku', 'pengarang', 'penerbit', 'rak', 'th_terbit', 'jumlah'];
$requiredOld = ['judul_buku', 'pengarang', 'penerbit', 'th_terbit', 'jumlah'];
if ($header !== $requiredNew && $header !== $requiredOld) {
    fclose($handle);
    redirect_import('error', 0, 'Header CSV buku harus: seri_buku,judul_buku,pengarang,penerbit,rak,th_terbit,jumlah');
}

$result = mysqli_query($koneksi, "SELECT MAX(CAST(SUBSTRING(id_buku,2) AS UNSIGNED)) AS last_id FROM tb_buku");
$row = mysqli_fetch_assoc($result);
$nextNumber = ((int) ($row['last_id'] ?? 0)) + 1;
$inserted = 0;

mysqli_begin_transaction($koneksi);

try {
    while (($data = fgetcsv($handle)) !== false) {
        if (count(array_filter($data, function ($value) { return trim((string) $value) !== ''; })) === 0) {
            continue;
        }

        if ($header === $requiredNew) {
            $data = array_pad($data, 7, '');
            $seri = trim($data[0]);
            $judul = trim($data[1]);
            $pengarang = trim($data[2]);
            $penerbit = trim($data[3]);
            $rak = trim($data[4]);
            $tahunRaw = trim($data[5]);
            $jumlahRaw = trim($data[6]);
        } else {
            $data = array_pad($data, 5, '');
            $seri = '';
            $judul = trim($data[0]);
            $pengarang = trim($data[1]);
            $penerbit = trim($data[2]);
            $rak = '';
            $tahunRaw = trim($data[3]);
            $jumlahRaw = trim($data[4]);
        }

        list($seri, $judul) = split_series_title($seri, $judul);

        if ($judul === '') {
            continue;
        }

        $tahunSql = 'NULL';
        if ($tahunRaw !== '' && preg_match('/^(19|20)\d{2}$/', $tahunRaw)) {
            $tahunSql = "'" . mysqli_real_escape_string($koneksi, $tahunRaw) . "'";
        }

        $jumlah = 1;
        if ($jumlahRaw !== '' && is_numeric($jumlahRaw)) {
            $jumlah = max(0, (int) $jumlahRaw);
        }

        $idBuku = next_book_id($koneksi, $nextNumber++);
        $seri = mysqli_real_escape_string($koneksi, $seri);
        $judul = mysqli_real_escape_string($koneksi, $judul);
        $pengarang = mysqli_real_escape_string($koneksi, $pengarang);
        $penerbit = mysqli_real_escape_string($koneksi, $penerbit);
        $rak = mysqli_real_escape_string($koneksi, $rak);

        $kodeBuku = mysqli_real_escape_string($koneksi, generate_kode_buku($koneksi, $seri, $judul));

        $ok = mysqli_query(
            $koneksi,
            "INSERT INTO tb_buku (id_buku, kode_buku, seri_buku, judul_buku, pengarang, penerbit, rak, th_terbit, jumlah)
             VALUES ('$idBuku', '$kodeBuku', '$seri', '$judul', '$pengarang', '$penerbit', '$rak', $tahunSql, $jumlah)"
        );

        if (!$ok) {
            throw new Exception('Gagal menyimpan data buku.');
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
