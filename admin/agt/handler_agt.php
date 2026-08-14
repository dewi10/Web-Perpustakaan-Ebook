<?php
session_start();
include '../../inc/koneksi.php';
header('Content-Type: application/json');

$action = $_REQUEST['action'] ?? '';

if ($action === 'next_id') {
    $r = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id_anggota FROM tb_anggota ORDER BY id_anggota DESC LIMIT 1"));
    $kode = $r['id_anggota'] ?? 'A000';
    $urut = (int)substr($kode, 1) + 1;
    echo json_encode(['id' => 'A' . str_pad($urut, 3, '0', STR_PAD_LEFT)]);

} elseif ($action === 'get') {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $row = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_anggota WHERE id_anggota='$id'"));
    echo json_encode($row);

} elseif ($action === 'tambah') {
    $id      = mysqli_real_escape_string($koneksi, $_POST['id_anggota']);
    $nama    = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $jekel   = mysqli_real_escape_string($koneksi, $_POST['jekel']);
    $kelas   = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $pangkat = mysqli_real_escape_string($koneksi, $_POST['pangkat_gol'] ?? '');
    $nip     = mysqli_real_escape_string($koneksi, $_POST['nip'] ?? '');
    $hp      = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $ok = mysqli_query($koneksi, "INSERT INTO tb_anggota (id_anggota,nama,jekel,kelas,pangkat_gol,nip,no_hp) VALUES ('$id','$nama','$jekel','$kelas','$pangkat','$nip','$hp')");
    echo json_encode(['ok' => (bool)$ok]);

} elseif ($action === 'ubah') {
    $id      = mysqli_real_escape_string($koneksi, $_POST['id_anggota']);
    $nama    = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $jekel   = mysqli_real_escape_string($koneksi, $_POST['jekel']);
    $kelas   = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $pangkat = mysqli_real_escape_string($koneksi, $_POST['pangkat_gol'] ?? '');
    $nip     = mysqli_real_escape_string($koneksi, $_POST['nip'] ?? '');
    $hp      = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $ok = mysqli_query($koneksi, "UPDATE tb_anggota SET nama='$nama',jekel='$jekel',kelas='$kelas',pangkat_gol='$pangkat',nip='$nip',no_hp='$hp' WHERE id_anggota='$id'");
    echo json_encode(['ok' => (bool)$ok]);
}
exit;
