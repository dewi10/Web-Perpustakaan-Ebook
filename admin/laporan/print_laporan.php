<?php
include "../../inc/koneksi.php";
include_once "../../inc/buku_helpers.php";

$sql = mysqli_query($koneksi, "SELECT tb_sirkulasi.id_buku,
    tb_buku.seri_buku,
    tb_buku.judul_buku,
    tb_anggota.id_anggota,
    tb_anggota.nama,
    tb_sirkulasi.id_sk,
    tb_sirkulasi.tgl_pinjam,
    tb_sirkulasi.tgl_kembali,
    tb_sirkulasi.tgl_dikembalikan,
    IF(DATEDIFF(NOW(), tb_sirkulasi.tgl_kembali) <= 0, 0, DATEDIFF(NOW(), tb_sirkulasi.tgl_kembali)) AS telat_pengembalian
    FROM tb_sirkulasi
    JOIN tb_anggota ON tb_anggota.id_anggota = tb_sirkulasi.id_anggota
    JOIN tb_buku ON tb_buku.id_buku = tb_sirkulasi.id_buku
    WHERE tb_sirkulasi.status='KEM'
    ORDER BY id_anggota");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Perpustakaan - Laporan Sirkulasi</title>
    <link rel="stylesheet" href="../../assets_style/assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets_style/assets/bower_components/font-awesome/css/font-awesome.min.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f3f4f7; margin: 0; color: #222; }
        .toolbar { position: sticky; top: 0; z-index: 10; background: #fff; border-bottom: 1px solid #ddd; padding: 14px 20px; display: flex; justify-content: space-between; align-items: center; }
        .toolbar .btn + .btn { margin-left: 8px; }
        .sheet { max-width: 1400px; margin: 24px auto; background: #fff; padding: 24px; box-shadow: 0 6px 24px rgba(0,0,0,0.08); }
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
        .report-title { text-align: center; margin: 0 0 6px; font-size: 28px; }
        .report-subtitle { text-align: center; margin: 0 0 24px; font-size: 22px; }
        .table > thead > tr > th { background: #b40019; color: #fff; text-align: center; vertical-align: middle; }
        @media print {
            .toolbar { display: none; }
            body { background: #fff; }
            .sheet { max-width: none; margin: 0; box-shadow: none; padding: 0; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <div><strong>Preview Cetak Laporan Sirkulasi</strong></div>
        <div>
            <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
            <button type="button" class="btn btn-default" onclick="closePreview()"><i class="fa fa-times"></i> Tutup</button>
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
        <h3 class="report-title">.:: Laporan Perpustakaan ::.</h3>
        <h4 class="report-subtitle">Laporan Sirkulasi</h4>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID SKL</th>
                    <th>Buku</th>
                    <th>Peminjam</th>
                    <th>Tgl Pinjam</th>
                    <th>Jatuh Tempo</th>
                    <th>Tgl Dikembalikan</th>
                    <th>Denda</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $totalDenda = 0;
                $tarifDenda = 1000;
                if (mysqli_num_rows($sql) > 0) {
                    while ($data = mysqli_fetch_assoc($sql)) {
                        $denda = $data['telat_pengembalian'] * $tarifDenda;
                        $totalDenda += $denda;
                        echo "<tr>";
                        echo "<td>" . $no++ . "</td>";
                        echo "<td>" . htmlspecialchars($data['id_sk']) . "</td>";
                        echo "<td>" . htmlspecialchars(format_judul_buku($data['seri_buku'], $data['judul_buku'])) . "</td>";
                        echo "<td>" . htmlspecialchars($data['nama']) . "</td>";
                        echo "<td>" . date('d/M/Y', strtotime($data['tgl_pinjam'])) . "</td>";
                        echo "<td>" . date('d/M/Y', strtotime($data['tgl_kembali'])) . "</td>";
                        echo "<td>" . date('d/M/Y', strtotime($data['tgl_dikembalikan'])) . "</td>";
                        echo "<td>Rp. " . number_format($denda, 0, ',', '.') . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' class='text-center'>Data tidak ada</td></tr>";
                }
                ?>
                <tr>
                    <th colspan="8" style="text-align:right; padding-right:20px;">
                        Total Denda Rp. <?= number_format($totalDenda, 0, ',', '.') ?>
                    </th>
                </tr>
            </tbody>
        </table>
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
        window.location.href = '../../index.php?page=laporan_sirkulasi';
    }
    </script>
</body>
</html>
