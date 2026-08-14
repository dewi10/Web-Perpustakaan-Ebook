<?php

    if(isset($_GET['kode'])){
    $kode = mysqli_real_escape_string($koneksi, $_GET['kode']);
    $data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id_buku, status FROM tb_sirkulasi WHERE id_sk='".$kode."'"));
    $query_ubah = false;

    if ($data && $data['status'] === 'PIN') {
        mysqli_begin_transaction($koneksi);
        $ok1 = mysqli_query($koneksi, "UPDATE tb_sirkulasi SET status='KEM' WHERE id_sk='".$kode."' AND status='PIN'");
        $ok2 = $ok1 ? mysqli_query($koneksi, "UPDATE tb_buku SET jumlah = jumlah + 1 WHERE id_buku='".$data['id_buku']."'") : false;

        if ($ok1 && $ok2) {
            mysqli_commit($koneksi);
            $query_ubah = true;
        } else {
            mysqli_rollback($koneksi);
        }
    }

    if ($query_ubah) {
        echo "<script>
        Swal.fire({title: 'Kembalikan Buku Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=data_sirkul';
            }
        })</script>";
        }else{
        echo "<script>
        Swal.fire({title: 'Kembalikan Buku Gagal',text: '',icon: 'error',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=data_sirkul';
            }
        })</script>";
    }
	}
