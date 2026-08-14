<?php
    include_once __DIR__ . '/../../inc/buku_helpers.php';

    if(isset($_GET['kode'])){
        $sql_cek = "SELECT * FROM tb_buku WHERE id_buku='".$_GET['kode']."'";
        $query_cek = mysqli_query($koneksi, $sql_cek);
        $data_cek = mysqli_fetch_array($query_cek,MYSQLI_BOTH);
    }
?>

<section class="content-header">
	<h1>
		Master Data
		<small>Data Buku</small>
	</h1>
	<ol class="breadcrumb">
		<li>
			<a href="index.php">
				<i class="fa fa-home"></i>
				<b>Si Perpustakaan</b>
			</a>
		</li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<!-- general form elements -->
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title">Ubah buku</h3>
				</div>
				<!-- /.box-header -->
				<!-- form start -->
				<form action="" method="post" enctype="multipart/form-data">
					<div class="box-body">

						<div class="form-group">
							<label>Id Buku</label>
							<input type='text' class="form-control" name="id_buku" value="<?php echo $data_cek['id_buku']; ?>"
							 readonly/>
						</div>

						<div class="form-group">
							<label>Kode Buku</label>
							<input type='text' class="form-control" name="kode_buku_preview" value="<?php echo $data_cek['kode_buku']; ?>"
							 readonly/>
						</div>

						<div class="form-group">
							<label>Seri Buku</label>
							<input type='text' class="form-control" name="seri_buku" value="<?php echo $data_cek['seri_buku']; ?>"
							/>
						</div>

						<div class="form-group">
							<label>Judul Buku</label>
							<input type='text' class="form-control" name="judul_buku" value="<?php echo $data_cek['judul_buku']; ?>"
							/>
						</div>

						<div class="form-group">
							<label>Pengarang</label>
							<input type='text' class="form-control" name="pengarang" value="<?php echo $data_cek['pengarang']; ?>"
							/>
						</div>

						<div class="form-group">
							<label>Penerbit</label>
							<input class="form-control" name="penerbit" value="<?php echo $data_cek['penerbit']; ?>"
							/>
						</div>

						<div class="form-group">
							<label>Rak</label>
							<input class="form-control" name="rak" value="<?php echo $data_cek['rak'] ?? ''; ?>"
							/>
						</div>

						<div class="form-group">
							<label>Th Terbit</label>
							<input class="form-control" name="th_terbit" value="<?php echo $data_cek['th_terbit']; ?>">
						</div>

						<div class="form-group">
							<label>Jumlah</label>
							<input class="form-control" type="number" min="0" name="jumlah" value="<?php echo $data_cek['jumlah']; ?>">
						</div>

					</div>
					<!-- /.box-body -->

					<div class="box-footer">
						<input type="submit" name="Ubah" value="Ubah" class="btn btn-success">
						<a href="?page=MyApp/data_buku" class="btn btn-warning">Batal</a>
					</div>
				</form>
			</div>
			<!-- /.box -->
</section>

<?php

if (isset ($_POST['Ubah'])){
    //mulai proses ubah
    $tahun = trim($_POST['th_terbit']) === '' ? "NULL" : "'".$_POST['th_terbit']."'";
    $jumlah = trim($_POST['jumlah']) === '' ? 1 : (is_numeric($_POST['jumlah']) ? max(0, (int) $_POST['jumlah']) : 0);
    $rak = mysqli_real_escape_string($koneksi, trim($_POST['rak'] ?? ''));
    $currentData = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT kode_buku FROM tb_buku WHERE id_buku='".$_POST['id_buku']."'"));
    $kode_buku = generate_kode_buku($koneksi, $_POST['seri_buku'] ?? '', $_POST['judul_buku'] ?? '', $_POST['id_buku'], $currentData['kode_buku'] ?? '');
    $sql_ubah = "UPDATE tb_buku SET
        kode_buku='".$kode_buku."',
        seri_buku='".$_POST['seri_buku']."',
        judul_buku='".$_POST['judul_buku']."',
        pengarang='".$_POST['pengarang']."',
        penerbit='".$_POST['penerbit']."',
        rak='".$rak."',
        th_terbit=".$tahun.",
        jumlah=".$jumlah."
        WHERE id_buku='".$_POST['id_buku']."'";
    $query_ubah = mysqli_query($koneksi, $sql_ubah);

    if ($query_ubah) {
        echo "<script>
        Swal.fire({title: 'Ubah Data Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=MyApp/data_buku';
            }
        })</script>";
        }else{
        echo "<script>
        Swal.fire({title: 'Ubah Data Gagal',text: '',icon: 'error',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=MyApp/data_buku';
            }
        })</script>";
    }
}
