<?php
session_start();
include '../../inc/koneksi.php';
include_once '../../inc/buku_helpers.php';
header('Content-Type: application/json');

$action = $_REQUEST['action'] ?? '';

function parseYearValue($value) {
    $value = trim((string)$value);
    if ($value === '') {
        return 'NULL';
    }

    return "'" . mysqli_real_escape_string($GLOBALS['koneksi'], $value) . "'";
}

function parseJumlahValue($value) {
    $value = trim((string)$value);
    if ($value === '') {
        return 1;
    }

    if (!is_numeric($value)) {
        return 0;
    }

    return max(0, (int)$value);
}

function parseRakValue($value) {
    return mysqli_real_escape_string($GLOBALS['koneksi'], trim((string) $value));
}

if ($action === 'next_id') {
    $r = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id_buku FROM tb_buku ORDER BY id_buku DESC LIMIT 1"));
    $kode = $r['id_buku'] ?? 'B000';
    $urut = (int)substr($kode, 1) + 1;
    echo json_encode(['id' => 'B' . str_pad($urut, 3, '0', STR_PAD_LEFT)]);

} elseif ($action === 'next_code') {
    $seri = $_GET['seri_buku'] ?? '';
    $judul = $_GET['judul_buku'] ?? '';
    $id = mysqli_real_escape_string($koneksi, $_GET['id_buku'] ?? '');
    $current = mysqli_real_escape_string($koneksi, $_GET['kode_buku'] ?? '');
    $kodeBuku = generate_kode_buku($koneksi, $seri, $judul, $id, $current);
    $kodeInfo = kode_buku_info($seri, $judul);
    echo json_encode([
        'kode_buku' => $kodeBuku,
        'prefix' => $kodeInfo['prefix'],
        'preview' => $kodeInfo['label'],
    ]);

} elseif ($action === 'get') {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $row = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_buku WHERE id_buku='$id'"));
    echo json_encode($row);

} elseif ($action === 'tambah') {
    $id       = mysqli_real_escape_string($koneksi, $_POST['id_buku']);
    $seri     = mysqli_real_escape_string($koneksi, $_POST['seri_buku'] ?? '');
    $judul    = mysqli_real_escape_string($koneksi, $_POST['judul_buku']);
    $pengarang= mysqli_real_escape_string($koneksi, $_POST['pengarang']);
    $penerbit = mysqli_real_escape_string($koneksi, $_POST['penerbit']);
    $rak      = parseRakValue($_POST['rak'] ?? '');
    $th       = parseYearValue($_POST['th_terbit'] ?? '');
    $jumlah   = parseJumlahValue($_POST['jumlah'] ?? 0);
    $kodeBuku = mysqli_real_escape_string($koneksi, generate_kode_buku($koneksi, $_POST['seri_buku'] ?? '', $_POST['judul_buku'] ?? ''));
    $ok = mysqli_query($koneksi, "INSERT INTO tb_buku (id_buku,kode_buku,seri_buku,judul_buku,pengarang,penerbit,rak,th_terbit,jumlah) VALUES ('$id','$kodeBuku','$seri','$judul','$pengarang','$penerbit','$rak',$th,$jumlah)");
    echo json_encode(['ok' => (bool)$ok]);

} elseif ($action === 'ubah') {
    $id       = mysqli_real_escape_string($koneksi, $_POST['id_buku']);
    $seri     = mysqli_real_escape_string($koneksi, $_POST['seri_buku'] ?? '');
    $judul    = mysqli_real_escape_string($koneksi, $_POST['judul_buku']);
    $pengarang= mysqli_real_escape_string($koneksi, $_POST['pengarang']);
    $penerbit = mysqli_real_escape_string($koneksi, $_POST['penerbit']);
    $rak      = parseRakValue($_POST['rak'] ?? '');
    $th       = parseYearValue($_POST['th_terbit'] ?? '');
    $jumlah   = parseJumlahValue($_POST['jumlah'] ?? 0);
    $currentRow = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT kode_buku FROM tb_buku WHERE id_buku='$id'"));
    $kodeBuku = mysqli_real_escape_string($koneksi, generate_kode_buku($koneksi, $_POST['seri_buku'] ?? '', $_POST['judul_buku'] ?? '', $id, $currentRow['kode_buku'] ?? ''));
    $ok = mysqli_query($koneksi, "UPDATE tb_buku SET kode_buku='$kodeBuku',seri_buku='$seri',judul_buku='$judul',pengarang='$pengarang',penerbit='$penerbit',rak='$rak',th_terbit=$th,jumlah=$jumlah WHERE id_buku='$id'");
    echo json_encode(['ok' => (bool)$ok]);
}
exit;
