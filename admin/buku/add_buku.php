<?php
include_once __DIR__ . '/../../inc/buku_helpers.php';
//kode 9 digit
  
$carikode = mysqli_query($koneksi,"SELECT id_buku FROM tb_buku order by id_buku desc");
$datakode = mysqli_fetch_array($carikode);
$kode = $datakode['id_buku'];
$urut = substr($kode, 1, 3);
$tambah = (int) $urut + 1;

if (strlen($tambah) == 1){
$format = "B"."00".$tambah;
 	}else if (strlen($tambah) == 2){
 	$format = "B"."0".$tambah;
			}else (strlen($tambah) == 3){
			$format = "B".$tambah
				}
?>

<section class="content-header">
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
			<div class="box box-info">
				<div class="box-header with-border">
					<h3 class="box-title">Tambah Buku</h3>
				</div>
				<!-- /.box-header -->
				<!-- form start -->
				<form action="" method="post" enctype="multipart/form-data">
					<div class="box-body">
						<div class="form-group">
							<label>ID Buku</label>
							<input type="text" name="id_buku" id="id_buku" class="form-control" value="<?php echo $format; ?>"
							 readonly/>
						</div>

						<div class="form-group">
							<label>Kode Buku</label>
							<input type="text" name="kode_buku_preview" class="form-control" value="<?php echo kode_buku_prefix('', ''); ?>-001" readonly>
						</div>

						<div class="form-group">
							<label>Seri Buku</label>
							<input type="text" name="seri_buku" id="seri_buku" class="form-control" placeholder="Seri Buku">
						</div>

						<div class="form-group">
							<label>Judul Buku</label>
							<input type="text" name="judul_buku" id="judul_buku" class="form-control" placeholder="Judul Buku">
						</div>

						<div class="form-group">
							<label>Pengarang</label>
							<input type="text" name="pengarang" id="pengarang" class="form-control" placeholder="Nama Pengarang">
						</div>

						<div class="form-group">
							<label>Penerbit</label>
							<input type="text" name="penerbit" id="penerbiit" class="form-control" placeholder="Penerbit">
						</div>

						<div class="form-group">
							<label>Rak</label>
							<input type="text" name="rak" id="rak" class="form-control" placeholder="Contoh: A-01">
						</div>

						<div class="form-group">
							<label>Tahun Terbit</label>
							<input type="number" name="th_terbit" id="th_terbit" class="form-control" placeholder="Tahun Terbit">
						</div>

						<div class="form-group">
							<label>Jumlah</label>
							<input type="number" name="jumlah" id="jumlah" class="form-control" placeholder="Jumlah Buku" min="0" value="1">
						</div>

					</div>
					<!-- /.box-body -->

					<div class="box-footer">
						<input type="submit" name="Simpan" value="Simpan" class="btn btn-info">
						<a href="?page=MyApp/data_buku" class="btn btn-warning">Batal</a>
					</div>
				</form>
			</div>
			<!-- /.box -->
</section>

<?php

    if (isset ($_POST['Simpan'])){
    
        $tahun = trim($_POST['th_terbit']) === '' ? "NULL" : "'".$_POST['th_terbit']."'";
        $jumlah = trim($_POST['jumlah']) === '' ? 1 : (is_numeric($_POST['jumlah']) ? max(0, (int) $_POST['jumlah']) : 0);
        $kode_buku = generate_kode_buku($koneksi, $_POST['seri_buku'] ?? '', $_POST['judul_buku'] ?? '');
        $rak = mysqli_real_escape_string($koneksi, trim($_POST['rak'] ?? ''));
        $sql_simpan = "INSERT INTO tb_buku (id_buku,kode_buku,seri_buku,judul_buku,pengarang,penerbit,rak,th_terbit,jumlah) VALUES (
           '".$_POST['id_buku']."',
          '".$kode_buku."',
          '".$_POST['seri_buku']."',
          '".$_POST['judul_buku']."',
          '".$_POST['pengarang']."',
          '".$_POST['penerbit']."',
          '".$rak."',
          ".$tahun.",
          ".$jumlah.")";
        $query_simpan = mysqli_query($koneksi, $sql_simpan);
        mysqli_close($koneksi);

    if ($query_simpan){

      echo "<script>
      Swal.fire({title: 'Tambah Data Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
      }).then((result) => {
          if (result.value) {
              window.location = 'index.php?page=MyApp/data_buku';
          }
      })</script>";
      }else{
      echo "<script>
      Swal.fire({title: 'Tambah Data Gagal',text: '',icon: 'error',confirmButtonText: 'OK'
      }).then((result) => {
          if (result.value) {
              window.location = 'index.php?page=MyApp/add_buku';
          }
      })</script>";
    }
  }
    
