<?php
include "../../inc/koneksi.php";
include_once "../../inc/anggota_helpers.php";
$kode = isset($_GET['kode']) ? mysqli_real_escape_string($koneksi, $_GET['kode']) : '';
$sql = mysqli_query($koneksi, "SELECT * FROM tb_anggota WHERE id_anggota='$kode'");
$data = mysqli_fetch_assoc($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kartu Anggota Perpustakaan</title>
    <link rel="stylesheet" href="../../assets_style/assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets_style/assets/bower_components/font-awesome/css/font-awesome.min.css">
    <style>
        body {
            margin: 0;
            background: #eef1f5;
            font-family: Arial, sans-serif;
            color: #222;
        }
        .toolbar {
            position: sticky;
            top: 0;
            z-index: 10;
            background: #fff;
            border-bottom: 1px solid #ddd;
            padding: 14px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .toolbar .btn + .btn {
            margin-left: 8px;
        }
        .sheet {
            max-width: 900px;
            margin: 24px auto;
            background: #fff;
            padding: 28px;
            box-shadow: 0 6px 24px rgba(0,0,0,0.08);
        }
        .header {
            display: flex;
            align-items: center;
            gap: 18px;
            padding-bottom: 16px;
            margin-bottom: 8px;
        }
        .header img {
            width: 60px;
            height: 60px;
            object-fit: contain;
            flex-shrink: 0;
        }
        .header-text {
            flex: 1;
            text-align: center;
            margin-right: 78px;
        }
        .header-text h2,
        .header-text p {
            margin: 0;
        }
        .header-text h2 {
            font-size: 14pt;
            color: #14286e;
            font-weight: 800;
        }
        .header-text p {
            font-size: 9pt;
            color: #555;
            margin-top: 4px;
        }
        .sub-line {
            border-bottom: 3px solid #14286e;
            margin-bottom: 22px;
        }
        .card-wrap {
            background: #3d76b8;
            color: #fff;
            padding: 28px 30px;
        }
        .card-title {
            text-align: center;
            font-size: 22px;
            margin: 0 0 28px;
        }
        .info-table td {
            border-top: 1px solid rgba(255,255,255,0.8) !important;
            color: #fff !important;
            vertical-align: middle !important;
            padding: 12px 14px !important;
            font-size: 16px;
        }
        .photo-box {
            border: 1px solid #666;
            height: 150px;
            width: 110px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            font-size: 18px;
            color: #fff;
        }
        @media print {
            .toolbar {
                display: none;
            }
            body {
                background: #fff;
            }
            .sheet {
                max-width: none;
                margin: 0;
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <div><strong>Preview Kartu Anggota</strong></div>
        <div>
            <button type="button" class="btn btn-primary" onclick="window.print()">
                <i class="fa fa-print"></i> Print
            </button>
            <button type="button" class="btn btn-default" onclick="closePreview()">
                <i class="fa fa-times"></i> Tutup
            </button>
        </div>
    </div>

    <div class="sheet">
        <div class="header">
            <img src="../../dist/img/logokemhan.png" alt="Logo Kemhan">
            <div class="header-text">
                <h2>PUSAT KODIFIKASI<br>BADAN LOGISTIK PERTAHANAN KEMHAN</h2>
                <p>Jl. Jati No. 1 Pondok Labu, Jakarta Selatan 12450 | Telp 021-7668062-3</p>
            </div>
        </div>
        <div class="sub-line"></div>
        <div class="card-wrap">
            <h3 class="card-title">KARTU ANGGOTA PERPUSTAKAAN</h3>
            <div class="row">
                <div class="col-sm-8">
                    <table class="table info-table">
                        <tr>
                            <td width="34%">ID Anggota</td>
                            <td width="4%">:</td>
                            <td><?= htmlspecialchars($data['id_anggota'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td><?= htmlspecialchars($data['nama'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td>:</td>
                            <td><?= htmlspecialchars($data['jekel'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td>Jabatan/Unit</td>
                            <td>:</td>
                            <td><?= htmlspecialchars(format_jabatan_unit($data['kelas'] ?? '-', $data['pangkat_gol'] ?? '')) ?></td>
                        </tr>
                        <tr>
                            <td>Pangkat/Gol</td>
                            <td>:</td>
                            <td><?= htmlspecialchars($data['pangkat_gol'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td>NIP</td>
                            <td>:</td>
                            <td><?= htmlspecialchars($data['nip'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td>No HP</td>
                            <td>:</td>
                            <td><?= htmlspecialchars($data['no_hp'] ?? '-') ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-sm-4 text-center">
                    <div class="photo-box">3x4</div>
                </div>
            </div>
        </div>
    </div>

    <script>
    window.addEventListener('load', function() {
        setTimeout(function() {
            window.print();
        }, 300);
    });

    function closePreview() {
        if (window.opener) {
            window.close();
            return;
        }
        window.location.href = '../../index.php?page=MyApp/data_agt';
    }
    </script>
</body>
</html>
