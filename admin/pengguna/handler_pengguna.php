<?php
session_start();
include '../../inc/koneksi.php';
header('Content-Type: application/json');

$action = $_REQUEST['action'] ?? '';

if ($action === 'get') {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $row = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id_pengguna,nama_pengguna,username,level FROM tb_pengguna WHERE id_pengguna='$id'"));
    echo json_encode($row);

} elseif ($action === 'tambah') {
    $nama  = mysqli_real_escape_string($koneksi, $_POST['nama_pengguna']);
    $uname = mysqli_real_escape_string($koneksi, $_POST['username']);
    $pass  = md5($_POST['password']);
    $level = mysqli_real_escape_string($koneksi, $_POST['level']);
    $ok = mysqli_query($koneksi, "INSERT INTO tb_pengguna (nama_pengguna,username,password,level) VALUES ('$nama','$uname','$pass','$level')");
    echo json_encode(['ok' => (bool)$ok]);

} elseif ($action === 'ubah') {
    $id    = mysqli_real_escape_string($koneksi, $_POST['id_pengguna']);
    $nama  = mysqli_real_escape_string($koneksi, $_POST['nama_pengguna']);
    $uname = mysqli_real_escape_string($koneksi, $_POST['username']);
    $level = mysqli_real_escape_string($koneksi, $_POST['level']);
    $sql = "UPDATE tb_pengguna SET nama_pengguna='$nama',username='$uname',level='$level'";
    if (!empty($_POST['password'])) {
        $pass = md5($_POST['password']);
        $sql .= ",password='$pass'";
    }
    $sql .= " WHERE id_pengguna='$id'";
    $ok = mysqli_query($koneksi, $sql);
    echo json_encode(['ok' => (bool)$ok]);
}
exit;
