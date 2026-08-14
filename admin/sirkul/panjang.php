<?php
    if (!function_exists('admin_extension_count')) {
        function admin_extension_count($tglPinjam, $tglKembali)
        {
            $pinjam = strtotime((string) $tglPinjam);
            $kembali = strtotime((string) $tglKembali);
            if (!$pinjam || !$kembali) {
                return 0;
            }

            $selisihHari = (int) floor(($kembali - $pinjam) / 86400);
            $kelebihan = max(0, $selisihHari - 14);
            return (int) floor($kelebihan / 7);
        }
    }

    if(isset($_GET['kode'])){
        $sql_cek = "SELECT * FROM tb_sirkulasi WHERE id_sk='".$_GET['kode']."'";
        $query_cek = mysqli_query($koneksi, $sql_cek);
        $data_cek = mysqli_fetch_array($query_cek,MYSQLI_BOTH);
    }

    $query_ubah = false;
    $messageTitle = 'Perpanjang Gagal';
    $messageIcon = 'error';

    if ($data_cek && $data_cek['status'] === 'PIN') {
        $jumlahPerpanjang = admin_extension_count($data_cek['tgl_pinjam'] ?? '', $data_cek['tgl_kembali'] ?? '');
        $hariIni = date('Y-m-d');

        if (!empty($data_cek['tgl_kembali']) && $data_cek['tgl_kembali'] < $hariIni) {
            $messageTitle = 'Perpanjang Ditolak';
        } elseif ($jumlahPerpanjang >= 2) {
            $messageTitle = 'Batas Perpanjangan Habis';
        } else {
            $tgl_kk = date('Y-m-d', strtotime($data_cek['tgl_kembali'].' +7 days'));
            $sql_ubah = "UPDATE tb_sirkulasi SET tgl_kembali='$tgl_kk' WHERE id_sk='".$_GET['kode']."' AND status='PIN'";
            $query_ubah = mysqli_query($koneksi, $sql_ubah);
            if ($query_ubah) {
                $messageTitle = 'Perpanjang Berhasil';
                $messageIcon = 'success';
            }
        }
    }

    if ($query_ubah) {
        echo "<script>
        Swal.fire({title: 'Perpanjang Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=data_sirkul';
            }
        })</script>";
        }else{
        echo "<script>
        Swal.fire({title: '".$messageTitle."',text: '',icon: '".$messageIcon."',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=data_sirkul';
            }
        })</script>";
    }
