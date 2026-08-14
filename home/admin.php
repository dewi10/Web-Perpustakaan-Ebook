<?php
include_once __DIR__ . '/../inc/buku_helpers.php';
/* ---- Statistik utama ---- */
$r = $koneksi->query("SELECT count(id_buku) as t FROM tb_buku");
$buku = $r->fetch_assoc()['t'];

$r = $koneksi->query("SELECT count(id_anggota) as t FROM tb_anggota");
$agt = $r->fetch_assoc()['t'];

$r = $koneksi->query("SELECT count(id_sk) as t FROM tb_sirkulasi WHERE status='PIN'");
$pin = $r->fetch_assoc()['t'];

$r = $koneksi->query("SELECT count(id_sk) as t FROM tb_sirkulasi WHERE status='KEM'");
$kem = $r->fetch_assoc()['t'];

/* ---- Data grafik: peminjaman 6 bulan terakhir (dari log_pinjam) ---- */
$chart_labels = [];
$chart_pinjam = [];
for ($i = 5; $i >= 0; $i--) {
    $bln_str = date('Y-m', strtotime("-$i months"));
    $bln_label = date('M Y', strtotime("-$i months"));
    $chart_labels[] = $bln_label;
    $q = $koneksi->query("SELECT count(*) as t FROM log_pinjam WHERE DATE_FORMAT(tgl_pinjam,'%Y-%m')='$bln_str'");
    $chart_pinjam[] = (int)$q->fetch_assoc()['t'];
}

/* ---- Tabel sirkulasi terbaru ---- */
$sql_recent = $koneksi->query(
    "SELECT sk.id_sk, sk.tgl_pinjam, sk.tgl_kembali, sk.status,
            b.seri_buku, b.judul_buku, a.nama
     FROM tb_sirkulasi sk
     JOIN tb_buku     b ON sk.id_buku    = b.id_buku
     JOIN tb_anggota  a ON sk.id_anggota = a.id_anggota
     ORDER BY sk.tgl_pinjam DESC
     LIMIT 6"
);

/* ---- Buku terpopuler (paling sering dipinjam) ---- */
$sql_pop = $koneksi->query(
    "SELECT b.seri_buku, b.judul_buku, b.pengarang, count(lp.id_log) as jml
     FROM tb_buku b
     LEFT JOIN log_pinjam lp ON b.id_buku = lp.id_buku
     GROUP BY b.id_buku
     ORDER BY jml DESC
     LIMIT 5"
);
?>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<style>
  /* ---- Page header ---- */
  .dash-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 22px;
  }
  .dash-header h1 {
    font-size: 22px !important;
    font-weight: 700 !important;
    color: #1a1a2e !important;
    margin: 0 !important;
  }
  .dash-header .date-badge {
    background: #fff;
    border: 1px solid #eaecef;
    border-radius: 8px;
    padding: 6px 14px;
    font-size: 12.5px;
    color: #777;
    font-weight: 500;
  }
  .dash-header .date-badge i { color: #8b1a1a; margin-right: 5px; }

  /* ---- Stat cards ---- */
  .stat-row { margin-bottom: 20px; }
  .stat-card {
    background: #fff;
    border-radius: 14px;
    padding: 20px 22px;
    box-shadow: 0 3px 14px rgba(0,0,0,0.08);
    display: flex;
    align-items: center;
    gap: 16px;
    transition: transform 0.25s, box-shadow 0.25s;
    text-decoration: none !important;
    color: inherit !important;
    border-left: 4px solid transparent;
    height: 100%;
  }
  .stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 26px rgba(0,0,0,0.12);
    text-decoration: none !important;
  }
  .stat-icon {
    width: 58px; height: 58px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 26px;
    color: #fff;
    flex-shrink: 0;
  }
  .stat-info { flex: 1; min-width: 0; }
  .stat-number {
    font-size: 32px;
    font-weight: 800;
    color: #1a1a2e;
    line-height: 1;
    margin-bottom: 4px;
  }
  .stat-label {
    font-size: 12.5px;
    color: #8a8a9a;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .stat-card.blue  { border-left-color: #3b82f6; }
  .stat-card.orange{ border-left-color: #f59e0b; }
  .stat-card.green { border-left-color: #10b981; }
  .stat-card.red   { border-left-color: #22c55e; }
  .si-blue   { background: linear-gradient(135deg,#3b82f6,#2563eb); }
  .si-orange { background: linear-gradient(135deg,#f59e0b,#d97706); }
  .si-green  { background: linear-gradient(135deg,#10b981,#059669); }
  .si-red    { background: linear-gradient(135deg,#22c55e,#15803d); }

  /* ---- Chart/Table card ---- */
  .dash-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 3px 14px rgba(0,0,0,0.07);
    overflow: hidden;
    margin-bottom: 22px;
    height: 100%;
  }
  .dash-card-header {
    padding: 14px 18px;
    border-bottom: 1px solid #f0f2f5;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .dash-card-header h4 {
    margin: 0 !important;
    font-size: 14px !important;
    font-weight: 700 !important;
    color: #1a1a2e !important;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .dash-card-header h4 i { color: #1f7a4d; }
  .dash-card-header .view-all {
    font-size: 12px;
    color: #1f7a4d;
    font-weight: 600;
    text-decoration: none;
  }
  .dash-card-header .view-all:hover { text-decoration: underline; }
  .dash-card-body { padding: 18px; }

  /* ---- Status badge ---- */
  .badge-pin {
    background: #fef3c7;
    color: #d97706;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
  }
  .badge-kem {
    background: #d1fae5;
    color: #059669;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
  }

  /* ---- Recent table ---- */
  .recent-table { width: 100%; border-collapse: collapse; }
  .recent-table th {
    background: linear-gradient(135deg, #1f7a4d, #14532d) !important;
    color: #fff !important;
    font-size: 11.5px !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    padding: 10px 14px !important;
    border: none !important;
  }
  .recent-table td {
    padding: 10px 14px !important;
    border-bottom: 1px solid #f4f4f8 !important;
    font-size: 13px !important;
    vertical-align: middle !important;
    color: #333;
  }
  .recent-table tr:last-child td { border-bottom: none !important; }
  .recent-table tr:hover td { background: #f3fbf6; }

  /* ---- Popular book rank ---- */
  .pop-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid #f4f4f8;
  }
  .pop-item:last-child { border-bottom: none; }
  .pop-rank {
    width: 28px; height: 28px;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 700; color: #fff;
    flex-shrink: 0;
  }
  .rank-1 { background: linear-gradient(135deg,#f59e0b,#d97706); }
  .rank-2 { background: linear-gradient(135deg,#94a3b8,#64748b); }
  .rank-3 { background: linear-gradient(135deg,#cd7c3b,#b45309); }
  .rank-n { background: #e5e7eb; color: #6b7280; }
  .pop-info { flex: 1; min-width: 0; }
  .pop-title {
    font-size: 13px;
    font-weight: 600;
    color: #1a1a2e;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .pop-author {
    font-size: 11.5px;
    color: #888;
    margin-top: 1px;
  }
  .pop-count {
    font-size: 12px;
    font-weight: 700;
    color: #1f7a4d;
    background: #edf9f0;
    padding: 2px 9px;
    border-radius: 20px;
    flex-shrink: 0;
  }
</style>

<!-- ========== CONTENT HEADER ========== -->
<section class="content-header">
  <div class="dash-header">
    <h1>Dashboard <small>Administrator</small></h1>
    <div class="date-badge">
      <i class="fa fa-calendar"></i>
      <?php echo date('d F Y'); ?>
    </div>
  </div>
</section>

<!-- ========== MAIN CONTENT ========== -->
<section class="content">

  <!-- ---- Stat Cards ---- -->
  <div class="row stat-row">
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12" style="margin-bottom:16px;">
      <a href="?page=MyApp/data_buku" class="stat-card blue">
        <div class="stat-icon si-blue"><i class="fa fa-book"></i></div>
        <div class="stat-info">
          <div class="stat-number"><?= $buku ?></div>
          <div class="stat-label">Total Buku</div>
        </div>
      </a>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12" style="margin-bottom:16px;">
      <a href="?page=MyApp/data_agt" class="stat-card orange">
        <div class="stat-icon si-orange"><i class="fa fa-users"></i></div>
        <div class="stat-info">
          <div class="stat-number"><?= $agt ?></div>
          <div class="stat-label">Total Anggota</div>
        </div>
      </a>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12" style="margin-bottom:16px;">
      <a href="?page=data_sirkul" class="stat-card green">
        <div class="stat-icon si-green"><i class="fa fa-refresh"></i></div>
        <div class="stat-info">
          <div class="stat-number"><?= $pin ?></div>
          <div class="stat-label">Sedang Dipinjam</div>
        </div>
      </a>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12" style="margin-bottom:16px;">
      <a href="?page=log_kembali" class="stat-card red">
        <div class="stat-icon si-red"><i class="fa fa-check-circle"></i></div>
        <div class="stat-info">
          <div class="stat-number"><?= $kem ?></div>
          <div class="stat-label">Telah Dikembalikan</div>
        </div>
      </a>
    </div>
  </div>

  <!-- ---- Grafik + Donut (flex row agar sama tinggi) ---- -->
  <div style="display:flex; gap:20px; margin-bottom:22px; align-items:stretch;">

    <!-- Bar Chart: Tren Peminjaman -->
    <div style="flex:2; min-width:0;">
      <div class="dash-card" style="height:100%; display:flex; flex-direction:column;">
        <div class="dash-card-header">
          <h4><i class="fa fa-bar-chart"></i> Tren Peminjaman 6 Bulan Terakhir</h4>
        </div>
        <div class="dash-card-body" style="flex:1; display:flex; align-items:center;">
          <div style="position:relative; width:100%; height:240px;">
            <canvas id="chartPinjam"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Donut: Status Sirkulasi -->
    <div style="flex:1; min-width:0;">
      <div class="dash-card" style="height:100%; display:flex; flex-direction:column;">
        <div class="dash-card-header">
          <h4><i class="fa fa-pie-chart"></i> Status Sirkulasi</h4>
        </div>
        <div class="dash-card-body" style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center;">
          <div style="position:relative; width:200px; height:200px;">
            <canvas id="chartStatus"></canvas>
          </div>
          <div style="margin-top:16px; display:flex; justify-content:center; gap:16px; font-size:12.5px; font-weight:600; flex-wrap:wrap;">
            <span><span style="display:inline-block;width:12px;height:12px;border-radius:50%;background:#f59e0b;margin-right:5px;vertical-align:middle;"></span>Dipinjam (<?= $pin ?>)</span>
            <span><span style="display:inline-block;width:12px;height:12px;border-radius:50%;background:#10b981;margin-right:5px;vertical-align:middle;"></span>Dikembalikan (<?= $kem ?>)</span>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- ---- Tabel Terbaru + Buku Populer ---- -->
  <div class="row">

    <!-- Sirkulasi Terbaru -->
    <div class="col-md-7 col-sm-12" style="margin-bottom:20px;">
      <div class="dash-card">
        <div class="dash-card-header">
          <h4><i class="fa fa-clock-o"></i> Sirkulasi Terbaru</h4>
          <a href="?page=data_sirkul" class="view-all">Lihat Semua &rarr;</a>
        </div>
        <div style="padding:0;">
          <table class="recent-table">
            <thead>
              <tr>
                <th>Buku</th>
                <th>Anggota</th>
                <th>Tgl Pinjam</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($sql_recent && $sql_recent->num_rows > 0): ?>
                <?php while ($row = $sql_recent->fetch_assoc()): ?>
                <tr>
                  <td style="font-weight:600;"><?= htmlspecialchars(format_judul_buku($row['seri_buku'], $row['judul_buku'])) ?></td>
                  <td><?= htmlspecialchars($row['nama']) ?></td>
                  <td><?= date('d/m/Y', strtotime($row['tgl_pinjam'])) ?></td>
                  <td>
                    <?php if ($row['status'] == 'PIN'): ?>
                      <span class="badge-pin"><i class="fa fa-arrow-circle-down"></i> Pinjam</span>
                    <?php else: ?>
                      <span class="badge-kem"><i class="fa fa-arrow-circle-up"></i> Kembali</span>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td colspan="4" style="text-align:center;color:#aaa;padding:24px;">Belum ada data sirkulasi</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Buku Paling Sering Dipinjam -->
    <div class="col-md-5 col-sm-12" style="margin-bottom:20px;">
      <div class="dash-card">
        <div class="dash-card-header">
          <h4><i class="fa fa-trophy"></i> Buku Terpopuler</h4>
          <a href="?page=MyApp/data_buku" class="view-all">Lihat Semua &rarr;</a>
        </div>
        <div class="dash-card-body" style="padding:14px 18px;">
          <?php
          $rank = 1;
          if ($sql_pop && $sql_pop->num_rows > 0):
            while ($row = $sql_pop->fetch_assoc()):
              $rankClass = $rank <= 3 ? "rank-$rank" : "rank-n";
          ?>
          <div class="pop-item">
            <div class="pop-rank <?= $rankClass ?>"><?= $rank ?></div>
            <div class="pop-info">
              <div class="pop-title"><?= htmlspecialchars(format_judul_buku($row['seri_buku'], $row['judul_buku'])) ?></div>
              <div class="pop-author"><?= htmlspecialchars($row['pengarang']) ?></div>
            </div>
            <div class="pop-count"><?= $row['jml'] ?>x</div>
          </div>
          <?php $rank++; endwhile; else: ?>
          <div style="text-align:center;color:#aaa;padding:20px;">Belum ada data</div>
          <?php endif; ?>
        </div>
      </div>
    </div>

  </div>

</section>

<!-- ========== Chart.js Scripts ========== -->
<script>
(function(){
  // Bar Chart - Tren Peminjaman
  var ctxBar = document.getElementById('chartPinjam').getContext('2d');
  new Chart(ctxBar, {
    type: 'bar',
    data: {
      labels: <?= json_encode($chart_labels) ?>,
      datasets: [{
        label: 'Jumlah Peminjaman',
        data: <?= json_encode($chart_pinjam) ?>,
        backgroundColor: 'rgba(139,26,26,0.15)',
        borderColor: '#8b1a1a',
        borderWidth: 2,
        borderRadius: 8,
        borderSkipped: false,
        hoverBackgroundColor: 'rgba(139,26,26,0.3)',
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: function(ctx){ return ' ' + ctx.parsed.y + ' peminjaman'; }
          }
        }
      },
      scales: {
        x: {
          grid: { display: false },
          ticks: { font: { size: 12, family: 'Inter, Segoe UI, sans-serif' }, color: '#888' }
        },
        y: {
          beginAtZero: true,
          grid: { color: '#f0f2f5', lineWidth: 1 },
          ticks: {
            stepSize: 1,
            font: { size: 12, family: 'Inter, Segoe UI, sans-serif' },
            color: '#888'
          }
        }
      }
    }
  });

  // Donut Chart - Status Sirkulasi
  var ctxDon = document.getElementById('chartStatus').getContext('2d');
  new Chart(ctxDon, {
    type: 'doughnut',
    data: {
      labels: ['Dipinjam', 'Dikembalikan'],
      datasets: [{
        data: [<?= $pin ?>, <?= $kem ?>],
        backgroundColor: ['#f59e0b','#10b981'],
        hoverBackgroundColor: ['#d97706','#059669'],
        borderWidth: 0,
        hoverOffset: 6
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      cutout: '70%',
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: function(ctx){
              return ' ' + ctx.label + ': ' + ctx.parsed + ' buku';
            }
          }
        }
      }
    }
  });
})();
</script>
