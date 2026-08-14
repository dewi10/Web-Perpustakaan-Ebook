<?php
session_start();
include '../../inc/koneksi.php';
header('Content-Type: application/json');

$action = $_REQUEST['action'] ?? '';

if ($action === 'next_id') {
    $r = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id_sk FROM tb_sirkulasi ORDER BY id_sk DESC LIMIT 1"));
    $kode = $r['id_sk'] ?? 'S000';
    $urut = (int)substr($kode, 1) + 1;
    echo json_encode(['id' => 'S' . str_pad($urut, 3, '0', STR_PAD_LEFT)]);

} elseif ($action === 'tambah') {
    $id_sk   = mysqli_real_escape_string($koneksi, $_POST['id_sk']);
    $id_buku = mysqli_real_escape_string($koneksi, $_POST['id_buku']);
    $id_agt  = mysqli_real_escape_string($koneksi, $_POST['id_anggota']);
    $tgl_p   = mysqli_real_escape_string($koneksi, $_POST['tgl_pinjam']);
    $tgl_k   = date('Y-m-d', strtotime('+14 days', strtotime($tgl_p)));
    $stokRow = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT jumlah FROM tb_buku WHERE id_buku='$id_buku'"));
    $stok = isset($stokRow['jumlah']) ? (int)$stokRow['jumlah'] : 0;

    if ($stok < 1) {
        echo json_encode(['ok' => false, 'message' => 'Stok buku habis']);
        exit;
    }

    mysqli_begin_transaction($koneksi);
    $ok1 = mysqli_query($koneksi, "INSERT INTO tb_sirkulasi (id_sk,id_buku,id_anggota,tgl_pinjam,status,tgl_kembali) VALUES ('$id_sk','$id_buku','$id_agt','$tgl_p','PIN','$tgl_k')");
    $ok2 = $ok1 ? mysqli_query($koneksi, "INSERT INTO log_pinjam (id_buku,id_anggota,tgl_pinjam) VALUES ('$id_buku','$id_agt','$tgl_p')") : false;
    $ok3 = $ok2 ? mysqli_query($koneksi, "UPDATE tb_buku SET jumlah = jumlah - 1 WHERE id_buku='$id_buku' AND jumlah > 0") : false;

    if ($ok1 && $ok2 && $ok3 && mysqli_affected_rows($koneksi) > 0) {
        mysqli_commit($koneksi);
        echo json_encode(['ok' => true]);
    } else {
        mysqli_rollback($koneksi);
        echo json_encode(['ok' => false, 'message' => 'Gagal menyimpan peminjaman']);
    }
}
exit;
