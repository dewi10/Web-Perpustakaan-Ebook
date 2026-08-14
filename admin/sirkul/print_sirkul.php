<?php
include "../../inc/koneksi.php";
include_once "../../inc/buku_helpers.php";

$idAnggota = isset($_GET['id_anggota']) ? mysqli_real_escape_string($koneksi, $_GET['id_anggota']) : '';
$where = "WHERE s.status='PIN'";
$titleSuffix = 'Semua Peminjaman Aktif';

if ($idAnggota !== '') {
    $where .= " AND s.id_anggota='" . $idAnggota . "'";
    $info = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id_anggota, nama FROM tb_anggota WHERE id_anggota='" . $idAnggota . "'"));
    if ($info) {
        $titleSuffix = 'Peminjam: ' . $info['id_anggota'] . ' - ' . $info['nama'];
    }
}

$query = "
    SELECT s.id_sk, s.tgl_pinjam, s.tgl_kembali, b.id_buku, b.seri_buku, b.judul_buku, a.id_anggota, a.nama
    FROM tb_sirkulasi s
    INNER JOIN tb_buku b ON b.id_buku = s.id_buku
    INNER JOIN tb_anggota a ON a.id_anggota = s.id_anggota
    $where
    ORDER BY a.nama, s.tgl_pinjam DESC
";
$sql = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Perpustakaan - Sirkulasi</title>
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
        <div><strong>Preview Cetak Sirkulasi</strong></div>
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
        <h4 class="report-subtitle"><?= htmlspecialchars($titleSuffix) ?></h4>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Sirkulasi</th>
                    <th>ID Buku</th>
                    <th>Judul Buku</th>
                    <th>Peminjam</th>
                    <th>Tgl Pinjam</th>
                    <th>Jatuh Tempo</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if (mysqli_num_rows($sql) > 0) {
                    while ($row = mysqli_fetch_assoc($sql)) {
                        echo "<tr>";
                        echo "<td>" . $no++ . "</td>";
                        echo "<td>" . htmlspecialchars($row['id_sk']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['id_buku']) . "</td>";
                        echo "<td>" . htmlspecialchars(format_judul_buku($row['seri_buku'], $row['judul_buku'])) . "</td>";
                        echo "<td>" . htmlspecialchars($row['id_anggota'] . ' - ' . $row['nama']) . "</td>";
                        echo "<td>" . date('d/M/Y', strtotime($row['tgl_pinjam'])) . "</td>";
                        echo "<td>" . date('d/M/Y', strtotime($row['tgl_kembali'])) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>Data tidak ada</td></tr>";
                }
                ?>
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
        window.location.href = '../../index.php?page=data_sirkul';
    }
    </script>
</body>
</html>
