<?php
//Mulai Sesion
session_start();
if (isset($_SESSION["ses_username"]) == "") {
	header("location: login.php");
} else {
	$data_id = $_SESSION["ses_id"];
	$data_nama = $_SESSION["ses_nama"];
	$data_user = $_SESSION["ses_username"];
	$data_level = $_SESSION["ses_level"];
}

//KONEKSI DB
include "inc/koneksi.php";

$sidebar_avatar = 'dist/img/avatar.png';
$sidebar_avatar_mode = 'image';
$sidebar_avatar_icon = 'fa-user';
$sidebar_user_initials = '';
if ($data_level == 'Anggota') {
	$anggota_id_sidebar = mysqli_real_escape_string($koneksi, (string) $data_id);
	$anggota_sidebar = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama, jekel FROM tb_anggota WHERE id_anggota='$anggota_id_sidebar' LIMIT 1"));
	$jekel_sidebar = strtolower(trim((string) ($anggota_sidebar['jekel'] ?? '')));
	$sidebar_avatar_mode = 'badge';
	$sidebar_avatar_icon = in_array($jekel_sidebar, ['perempuan', 'wanita', 'pr'], true) ? 'fa-female' : 'fa-male';
	$nama_sidebar = trim((string) ($anggota_sidebar['nama'] ?? $data_nama));
	foreach (preg_split('/\s+/', $nama_sidebar) as $bagian_nama_sidebar) {
		if ($bagian_nama_sidebar === '') {
			continue;
		}
		$sidebar_user_initials .= strtoupper(substr($bagian_nama_sidebar, 0, 1));
		if (strlen($sidebar_user_initials) >= 2) {
			break;
		}
	}
	$sidebar_user_initials = $sidebar_user_initials !== '' ? $sidebar_user_initials : 'AG';
}

// Deteksi halaman aktif untuk sidebar
$default_page = 'petugas';
if ($data_level == 'Administrator') {
	$default_page = 'admin';
} elseif ($data_level == 'Anggota') {
	$default_page = 'anggota';
}
$cur_page   = $_GET['page'] ?? $default_page;
$grp_kelola = ['MyApp/data_buku','MyApp/data_agt','MyApp/add_buku','MyApp/edit_buku','MyApp/add_agt','MyApp/edit_agt'];
$grp_log    = ['log_pinjam','log_kembali'];
$grp_laporan= ['laporan_sirkulasi','MyApp/print_laporan'];
$grp_anggota= ['anggota','anggota_profil','anggota_kartu','anggota_pinjam','anggota_kembali','anggota_ebook'];
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sistem Informasi Perpustakaan</title>
	<link rel="icon" href="dist/img/logo.png">
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.6 -->
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<!-- Font Awesome (local) -->
	<link rel="stylesheet" href="assets_style/assets/bower_components/font-awesome/css/font-awesome.min.css">
	<!-- Ionicons (local) -->
	<link rel="stylesheet" href="assets_style/assets/bower_components/Ionicons/css/ionicons.min.css">
	<!-- DataTables -->
	<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
	<!-- Select2 -->
	<link rel="stylesheet" href="plugins/select2/select2.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="dist/css/AdminLTE.min.css">
	<!-- AdminLTE Skins -->
	<link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
	<!-- Custom Library Theme -->
	<link rel="stylesheet" href="assets/css/library-theme.css">

	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
</head>

<body class="hold-transition skin-green sidebar-mini">
	<!-- Site wrapper -->
	<div class="wrapper">

		<header class="main-header">
			<!-- Logo -->
			<a href="index.php" class="logo">
				<span class="logo-lg">
					<img src="dist/img/logokemhan.png" width="34px" style="border-radius:50%;object-fit:cover;">
					<b>SI Perpustakaan</b>
				</span>
			</a>
			<!-- Header Navbar: style can be found in header.less -->
			<nav class="navbar navbar-static-top">
				<!-- Sidebar toggle button-->
				<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>

				<div class="navbar-custom-menu">
					<ul class="nav navbar-nav">
						<!-- Messages: style can be found in dropdown.less-->
						<li class="dropdown messages-menu">
							<a class="dropdown-toggle">
								<span>
									<b>
										Sistem Informasi Perpustakaan
									</b>
								</span>
							</a>
						</li>
					</ul>
				</div>
			</nav>
		</header>

		<!-- =============================================== -->

		<!-- Left side column. contains the sidebar -->
		<aside class="main-sidebar">
			<!-- sidebar: style can be found in sidebar.less -->
			<section class="sidebar">
				<!-- Sidebar user panel -->
				<div class="user-panel <?= $data_level == 'Anggota' ? 'member-user-panel' : '' ?>">
					<div class="pull-left image">
						<?php if ($sidebar_avatar_mode === 'badge'): ?>
							<div class="member-sidebar-avatar" aria-label="Avatar anggota">
								<i class="fa <?= htmlspecialchars($sidebar_avatar_icon) ?>"></i>
								<span><?= htmlspecialchars($sidebar_user_initials) ?></span>
							</div>
						<?php else: ?>
							<img src="<?= htmlspecialchars($sidebar_avatar) ?>" class="img-circle" alt="User Image">
						<?php endif; ?>
					</div>
					<div class="pull-left info">
						<p>
							<?php echo $data_nama; ?>
						</p>
						<span class="label label-warning">
							<?php echo $data_level; ?>
						</span>
					</div>
				</div>
				<!-- /.search form -->
				<!-- sidebar menu: : style can be found in sidebar.less -->
				<ul class="sidebar-menu">
					<li class="header">MAIN NAVIGATION</li>

					<!-- Level  -->
					<?php
					if ($data_level == "Administrator") {
					?>

						<li class="treeview <?= $cur_page=='admin' ? 'active' : '' ?>">
							<a href="?page=admin">
								<i class="fa fa-home"></i>
								<span>Dashboard</span>
							</a>
						</li>

						<li class="treeview <?= in_array($cur_page,$grp_kelola) ? 'active menu-open' : '' ?>">
							<a href="#">
								<i class="fa fa-database"></i>
								<span>Kelola Data</span>
								<span class="pull-right-container">
									<i class="fa fa-angle-left pull-right"></i>
								</span>
							</a>
							<ul class="treeview-menu">
								<li class="<?= $cur_page=='MyApp/data_buku' ? 'active' : '' ?>">
									<a href="?page=MyApp/data_buku"><i class="fa fa-book"></i>Data Buku</a>
								</li>
								<li class="<?= $cur_page=='MyApp/data_agt' ? 'active' : '' ?>">
									<a href="?page=MyApp/data_agt"><i class="fa fa-id-card-o"></i>Data Anggota</a>
								</li>
							</ul>
						</li>

						<li class="treeview <?= in_array($cur_page,['MyApp/data_ebook','MyApp/baca_ebook']) ? 'active' : '' ?>">
							<a href="?page=MyApp/data_ebook">
								<i class="fa fa-tablet"></i>
								<span>E-Book</span>
							</a>
						</li>

						<li class="treeview <?= $cur_page=='data_sirkul' ? 'active' : '' ?>">
							<a href="?page=data_sirkul">
								<i class="fa fa-exchange"></i>
								<span>Sirkulasi</span>
							</a>
						</li>

						<li class="treeview <?= in_array($cur_page,$grp_log) ? 'active menu-open' : '' ?>">
							<a href="#">
								<i class="fa fa-history"></i>
								<span>Log Data</span>
								<span class="pull-right-container">
									<i class="fa fa-angle-left pull-right"></i>
								</span>
							</a>
							<ul class="treeview-menu">
								<li class="<?= $cur_page=='log_pinjam' ? 'active' : '' ?>">
									<a href="?page=log_pinjam"><i class="fa fa-sign-out"></i>Peminjaman</a>
								</li>
								<li class="<?= $cur_page=='log_kembali' ? 'active' : '' ?>">
									<a href="?page=log_kembali"><i class="fa fa-sign-in"></i>Pengembalian</a>
								</li>
							</ul>
						</li>

						<li class="treeview <?= in_array($cur_page,$grp_laporan) ? 'active menu-open' : '' ?>">
							<a href="?page=laporan_sirkulasi">
								<i class="fa fa-bar-chart"></i>
								<span>Laporan</span>
								<span class="pull-right-container">
									<i class="fa fa-angle-left pull-right"></i>
								</span>
							</a>
							<ul class="treeview-menu">
								<li class="<?= $cur_page=='laporan_sirkulasi' ? 'active' : '' ?>">
									<a href="?page=laporan_sirkulasi"><i class="fa fa-file-text-o"></i>Laporan Sirkulasi</a>
								</li>
							</ul>
						</li>

						<li class="header">SETTING</li>

						<li class="treeview <?= $cur_page=='MyApp/data_pengguna' ? 'active' : '' ?>">
							<a href="?page=MyApp/data_pengguna">
								<i class="fa fa-user-circle-o"></i>
								<span>Pengguna Sistem</span>
							</a>
						</li>

					<?php
					} elseif ($data_level == "Petugas") {
					?>

						<li class="treeview <?= $cur_page=='petugas' ? 'active' : '' ?>">
							<a href="?page=petugas">
								<i class="fa fa-home"></i>
								<span>Dashboard</span>
							</a>
						</li>

						<li class="treeview <?= in_array($cur_page,$grp_kelola) ? 'active menu-open' : '' ?>">
							<a href="#">
								<i class="fa fa-database"></i>
								<span>Kelola Data</span>
								<span class="pull-right-container">
									<i class="fa fa-angle-left pull-right"></i>
								</span>
							</a>
							<ul class="treeview-menu">
								<li class="<?= $cur_page=='MyApp/data_buku' ? 'active' : '' ?>">
									<a href="?page=MyApp/data_buku"><i class="fa fa-book"></i>Data Buku</a>
								</li>
								<li class="<?= $cur_page=='MyApp/data_agt' ? 'active' : '' ?>">
									<a href="?page=MyApp/data_agt"><i class="fa fa-id-card-o"></i>Data Anggota</a>
								</li>
							</ul>
						</li>

						<li class="treeview <?= in_array($cur_page,['MyApp/data_ebook','MyApp/baca_ebook']) ? 'active' : '' ?>">
							<a href="?page=MyApp/data_ebook">
								<i class="fa fa-tablet"></i>
								<span>E-Book</span>
							</a>
						</li>

						<li class="treeview <?= $cur_page=='data_sirkul' ? 'active' : '' ?>">
							<a href="?page=data_sirkul">
								<i class="fa fa-exchange"></i>
								<span>Sirkulasi</span>
							</a>
						</li>

						<li class="treeview <?= in_array($cur_page,$grp_log) ? 'active menu-open' : '' ?>">
							<a href="#">
								<i class="fa fa-history"></i>
								<span>Log Data</span>
								<span class="pull-right-container">
									<i class="fa fa-angle-left pull-right"></i>
								</span>
							</a>
							<ul class="treeview-menu">
								<li class="<?= $cur_page=='log_pinjam' ? 'active' : '' ?>">
									<a href="?page=log_pinjam"><i class="fa fa-sign-out"></i>Peminjaman</a>
								</li>
								<li class="<?= $cur_page=='log_kembali' ? 'active' : '' ?>">
									<a href="?page=log_kembali"><i class="fa fa-sign-in"></i>Pengembalian</a>
								</li>
							</ul>
						</li>

						<li class="treeview <?= in_array($cur_page,$grp_laporan) ? 'active menu-open' : '' ?>">
							<a href="?page=laporan_sirkulasi">
								<i class="fa fa-bar-chart"></i>
								<span>Laporan</span>
								<span class="pull-right-container">
									<i class="fa fa-angle-left pull-right"></i>
								</span>
							</a>
							<ul class="treeview-menu">
								<li class="<?= $cur_page=='laporan_sirkulasi' ? 'active' : '' ?>">
									<a href="?page=laporan_sirkulasi"><i class="fa fa-file-text-o"></i>Laporan Sirkulasi</a>
								</li>
							</ul>
						</li>

						<li class="header">SETTING</li>

					<?php
					} elseif ($data_level == "Anggota") {
					?>

						<li class="treeview <?= $cur_page=='anggota' ? 'active' : '' ?>">
							<a href="?page=anggota">
								<i class="fa fa-home"></i>
								<span>Dashboard</span>
							</a>
						</li>

						<li class="treeview <?= $cur_page=='anggota_profil' ? 'active' : '' ?>">
							<a href="?page=anggota_profil">
								<i class="fa fa-user-circle-o"></i>
								<span>Profil Saya</span>
							</a>
						</li>

						<li class="treeview <?= $cur_page=='anggota_pinjam' ? 'active' : '' ?>">
							<a href="?page=anggota_pinjam">
								<i class="fa fa-book"></i>
								<span>Pinjaman Saya</span>
							</a>
						</li>

						<li class="treeview <?= $cur_page=='anggota_ebook' ? 'active' : '' ?>">
							<a href="?page=anggota_ebook">
								<i class="fa fa-tablet"></i>
								<span>E-Book</span>
							</a>
						</li>

					<?php
					}
					?>

					<li>
						<a href="logout.php" onclick="return confirm('Anda yakin keluar dari aplikasi ?')">
							<i class="fa fa-sign-out"></i>
							<span>Logout</span>
							<span class="pull-right-container"></span>
						</a>
					</li>


			</section>
			<!-- /.sidebar -->
		</aside>

		<!-- =============================================== -->

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<!-- Main content -->
			<section class="content">
				<?php
				if (isset($_GET['page'])) {
					$hal = $_GET['page'];

						switch ($hal) {
							//Klik Halaman Home Pengguna
						case 'admin':
							include "home/admin.php";
							break;
						case 'petugas':
							include "home/petugas.php";
							break;
						case 'anggota':
							include "member/dashboard.php";
							break;
						case 'anggota_profil':
							include "member/profil.php";
							break;
						case 'anggota_kartu':
							include "member/kartu.php";
							break;
						case 'anggota_pinjam':
							include "member/pinjam.php";
							break;
						case 'anggota_kembali':
							include "member/pinjam.php";
							break;
						case 'anggota_ebook':
							include "member/ebook.php";
							break;

							//Pengguna
						case 'MyApp/data_pengguna':
							include "admin/pengguna/data_pengguna.php";
							break;
						case 'MyApp/add_pengguna':
							include "admin/pengguna/add_pengguna.php";
							break;
						case 'MyApp/edit_pengguna':
							include "admin/pengguna/edit_pengguna.php";
							break;
						case 'MyApp/del_pengguna':
							include "admin/pengguna/del_pengguna.php";
							break;


							//agt
						case 'MyApp/data_agt':
							include "admin/agt/data_agt.php";
							break;
						case 'MyApp/add_agt':
							include "admin/agt/add_agt.php";
							break;
						case 'MyApp/edit_agt':
							include "admin/agt/edit_agt.php";
							break;
						case 'MyApp/del_agt':
							include "admin/agt/del_agt.php";
							break;
						case 'MyApp/print_agt':
							include "admin/agt/print_agt.php";
							break;
						case 'MyApp/print_allagt':
							include "admin/agt/print_allagt.php";
							break;


							//buku
						case 'MyApp/data_buku':
							include "admin/buku/data_buku.php";
							break;
						case 'MyApp/add_buku':
							include "admin/buku/add_buku.php";
							break;
						case 'MyApp/edit_buku':
							include "admin/buku/edit_buku.php";
							break;
						case 'MyApp/del_buku':
							include "admin/buku/del_buku.php";
							break;

							//ebook
						case 'MyApp/data_ebook':
							include "admin/ebook/data_ebook.php";
							break;
						case 'MyApp/baca_ebook':
							include "admin/ebook/baca_ebook.php";
							break;
						case 'MyApp/del_ebook':
							include "admin/ebook/del_ebook.php";
							break;

							//sirkul
						case 'data_sirkul':
							include "admin/sirkul/data_sirkul.php";
							break;
						case 'add_sirkul':
							include "admin/sirkul/add_sirkul.php";
							break;
						case 'panjang':
							include "admin/sirkul/panjang.php";
							break;
						case 'kembali':
							include "admin/sirkul/kembali.php";
							break;

							//log
						case 'log_pinjam':
							include "admin/log/log_pinjam.php";
							break;
						case 'log_kembali':
							include "admin/log/log_kembali.php";
							break;

							//laporan
						case 'laporan_sirkulasi':
							include "admin/laporan/laporan_sirkulasi.php";
							break;
						case 'MyApp/print_laporan':
							include "admin/laporan/print_laporan.php";
							break;



							//default
						default:
							echo "<center><br><br><br><br><br><br><br><br><br>
				  <h1> Halaman tidak ditemukan !</h1></center>";
							break;
					}
				} else {
					// Auto Halaman Home Pengguna
					if ($data_level == "Administrator") {
						include "home/admin.php";
					} elseif ($data_level == "Petugas") {
						include "home/petugas.php";
					} elseif ($data_level == "Anggota") {
						include "member/dashboard.php";
					}
				}
				?>



			</section>
			<!-- /.content -->
		</div>

		<!-- /.content-wrapper 

		<footer class="main-footer">
			<div class="pull-right hidden-xs">
			</div>
			<strong>Copyright &copy;
				<a href="https://www.facebook.com/">Muhammad Ivan Setiawan</a>.</strong> All rights reserved.
		</footer>
		<div class="control-sidebar-bg"></div>
		-->

		<!-- ./wrapper -->

		<!-- jQuery 2.2.3 -->
		<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
			 
		<!--Bootstrap 3.3.6 -->
			
		<script src = "bootstrap/js/bootstrap.min.js"></script>
		

		<script src="plugins/select2/select2.full.min.js"></script>
		<!-- DataTables -->
		<script src="plugins/datatables/jquery.dataTables.min.js"></script>
		<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>

		<!-- AdminLTE App -->
		<script src="dist/js/app.min.js"></script>
		<!-- AdminLTE for demo purposes -->
		<script src="dist/js/demo.js"></script>
		<!-- page script -->


		<script>
			$(function() {
				if ($.fn.dataTable) {
					$.extend(true, $.fn.dataTable.defaults, {
						dom:
							"<'row'<'col-sm-12'f>>" +
							"<'row'<'col-sm-12'tr>>" +
							"<'row dataTables-bottom-row'<'col-sm-12 dataTables-bottom-right'<'dataTables-length-wrap'l><'dataTables-info-wrap'i><'dataTables-pagination-wrap'p>>>",
						language: {
							search: "Cari:",
							lengthMenu: "<span class=\"dt-length-label\">Baris</span> _MENU_",
							info: "_START_–_END_ dari _TOTAL_",
							paginate: {
								previous: "‹",
								next: "›"
							}
						}
					});
				}

				$("#example1").DataTable({
					columnDefs: [{
						"defaultContent": "-",
						"targets": "_all"
					}]
				});
				$('#example2').DataTable({
					"paging": true,
					"lengthChange": false,
					"searching": false,
					"ordering": true,
					"info": true,
					"autoWidth": false
				});
			});
		</script>

		<script>
			$(function() {
				//Initialize Select2 Elements
				$(".select2").select2();
			});
		</script>
</body>

</html>
