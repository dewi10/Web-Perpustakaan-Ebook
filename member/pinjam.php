<?php
include_once __DIR__ . '/../inc/buku_helpers.php';

$anggotaId = mysqli_real_escape_string($koneksi, $data_id);
$flash = null;

if (!function_exists('loan_extension_count')) {
    function loan_extension_count($tglPinjam, $tglKembali)
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

if (isset($_GET['aksi'], $_GET['kode']) && $_GET['aksi'] === 'perpanjang') {
    $idSk = mysqli_real_escape_string($koneksi, (string) $_GET['kode']);
    $qPinjam = mysqli_query(
        $koneksi,
        "SELECT id_sk, tgl_pinjam, tgl_kembali
         FROM tb_sirkulasi
         WHERE id_sk='$idSk' AND id_anggota='$anggotaId' AND status='PIN'
         LIMIT 1"
    );
    $dataPinjam = $qPinjam ? mysqli_fetch_assoc($qPinjam) : null;

    if ($dataPinjam) {
        $tanggalAcuan = trim((string) ($dataPinjam['tgl_kembali'] ?? ''));
        $jumlahPerpanjang = loan_extension_count($dataPinjam['tgl_pinjam'] ?? '', $tanggalAcuan);
        $hariIni = date('Y-m-d');

        if ($tanggalAcuan !== '' && $tanggalAcuan < $hariIni) {
            $flash = [
                'status' => 'error',
                'title' => 'Perpanjangan ditolak',
                'text' => 'Pinjaman ini sudah melewati batas pengembalian, jadi tidak bisa diperpanjang.',
            ];
        } elseif ($jumlahPerpanjang >= 2) {
            $flash = [
                'status' => 'error',
                'title' => 'Batas perpanjangan tercapai',
                'text' => 'Setiap transaksi pinjam hanya bisa diperpanjang maksimal 2 kali.',
            ];
        } else {
        if ($tanggalAcuan === '' || $tanggalAcuan === '0000-00-00') {
            $tanggalAcuan = $hariIni;
        }
        $tanggalBaru = date('Y-m-d', strtotime($tanggalAcuan . ' +7 days'));
        $ok = mysqli_query($koneksi, "UPDATE tb_sirkulasi SET tgl_kembali='$tanggalBaru' WHERE id_sk='$idSk' AND id_anggota='$anggotaId' AND status='PIN'");

        $flash = [
            'status' => $ok ? 'success' : 'error',
            'title' => $ok ? 'Perpanjangan berhasil' : 'Perpanjangan gagal',
            'text' => $ok ? 'Batas pengembalian ditambah 7 hari dari tanggal sebelumnya.' : 'Data pinjaman tidak berhasil diperbarui.',
        ];
        }
    } else {
        $flash = [
            'status' => 'error',
            'title' => 'Perpanjangan gagal',
            'text' => 'Pinjaman aktif tidak ditemukan atau bukan milik akun ini.',
        ];
    }
}

$sqlAktif = mysqli_query(
    $koneksi,
    "SELECT s.id_sk, s.tgl_pinjam, s.tgl_kembali, s.status, b.seri_buku, b.judul_buku
     FROM tb_sirkulasi s
     JOIN tb_buku b ON s.id_buku=b.id_buku
     WHERE s.id_anggota='$anggotaId' AND s.status='PIN'
     ORDER BY s.tgl_kembali ASC, s.tgl_pinjam DESC"
);

$sqlRiwayat = mysqli_query(
    $koneksi,
    "SELECT s.id_sk, s.tgl_pinjam, s.tgl_kembali, s.tgl_dikembalikan, s.status, b.seri_buku, b.judul_buku
     FROM tb_sirkulasi s
     JOIN tb_buku b ON s.id_buku=b.id_buku
     WHERE s.id_anggota='$anggotaId'
     ORDER BY COALESCE(s.tgl_dikembalikan, s.tgl_kembali) DESC, s.tgl_pinjam DESC"
);
?>
<style>
.member-loan-shell { display:flex; flex-direction:column; gap:20px; }
.member-loan-card { background:#fff; border:1px solid #e6edf5; border-radius:22px; box-shadow:0 12px 28px rgba(23,53,45,.06); overflow:hidden; }
.member-loan-head { padding:18px 20px; border-bottom:1px solid #edf2f7; display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; }
.member-loan-head h3 { margin:0!important; font-size:20px!important; font-weight:800!important; color:#1d3342!important; }
.member-loan-head p { margin:6px 0 0; color:#6d7f8d; font-size:13px; }
.member-loan-badge { display:inline-flex; align-items:center; gap:8px; border-radius:999px; padding:8px 12px; background:#f3f8ff; color:#23527c; font-size:12px; font-weight:700; }
.member-loan-body { padding:18px 20px 20px; }
.member-loan-empty { border:1px dashed #d9e3ec; background:#f9fbfd; border-radius:18px; padding:28px 20px; text-align:center; color:#7a8d9c; }
.member-loan-grid { display:grid; grid-template-columns:repeat(2, minmax(0,1fr)); gap:16px; }
.member-loan-item { border:1px solid #e6edf5; border-radius:18px; padding:18px; background:linear-gradient(180deg,#ffffff 0%,#f7fbff 100%); }
.member-loan-title { margin:0 0 10px; font-size:18px; font-weight:800; line-height:1.35; color:#203745; }
.member-loan-meta { display:grid; grid-template-columns:repeat(2, minmax(0,1fr)); gap:12px; margin-bottom:14px; }
.member-loan-meta-box { background:#fff; border:1px solid #e8eef5; border-radius:14px; padding:12px; }
.member-loan-meta-label { font-size:10px; text-transform:uppercase; letter-spacing:.12em; color:#7a8b98; margin-bottom:6px; font-weight:700; }
.member-loan-meta-value { font-size:15px; color:#1f3745; font-weight:700; }
.member-loan-alert { display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; border-radius:14px; background:#eef8f3; color:#176241; padding:12px 14px; margin-bottom:14px; }
.member-loan-status { display:inline-flex; align-items:center; gap:6px; border-radius:999px; padding:6px 10px; font-size:11px; font-weight:700; }
.member-loan-status.pin { background:#fff4d8; color:#b97709; }
.member-loan-status.kem { background:#e7f7ec; color:#156b42; }
.member-loan-btn { display:inline-flex; align-items:center; gap:8px; border:0; border-radius:999px; padding:10px 14px; background:linear-gradient(135deg,#0f766e,#0f9b8e); color:#fff!important; text-decoration:none!important; font-size:13px; font-weight:700; box-shadow:0 10px 18px rgba(15,118,110,.18); }
.member-loan-btn:hover { opacity:.95; }
.member-loan-table { width:100%; border-collapse:collapse; }
.member-loan-table th { background:linear-gradient(135deg,#173b35,#1f5e4e)!important; color:#fff!important; font-size:12px!important; padding:11px 12px!important; border:none!important; }
.member-loan-table td { padding:11px 12px!important; border-bottom:1px solid #edf2f7!important; font-size:13px!important; color:#324754; vertical-align:top; }
.member-loan-table tr:last-child td { border-bottom:none!important; }
@media (max-width: 991px) { .member-loan-grid { grid-template-columns:1fr; } }
@media (max-width: 767px) { .member-loan-meta { grid-template-columns:1fr; } }
</style>

<section class="content-header">
  <h1>Pinjaman Saya</h1>
</section>

<section class="content">
  <div class="member-loan-shell">
    <div class="member-loan-card">
      <div class="member-loan-head">
        <div>
          <h3>Pinjaman Aktif</h3>
          <p>Buku yang masih Anda pinjam saat ini. Setiap klik perpanjang akan menambah 7 hari pada batas pengembalian.</p>
        </div>
        <div class="member-loan-badge"><i class="fa fa-refresh"></i> Bisa perpanjang 7 hari</div>
      </div>
      <div class="member-loan-body">
        <?php if ($sqlAktif && mysqli_num_rows($sqlAktif) > 0): ?>
          <div class="member-loan-grid">
            <?php while ($row = mysqli_fetch_assoc($sqlAktif)): ?>
              <?php
              $jumlahPerpanjang = loan_extension_count($row['tgl_pinjam'] ?? '', $row['tgl_kembali'] ?? '');
              $sisaPerpanjang = max(0, 2 - $jumlahPerpanjang);
              $sudahJatuhTempo = !empty($row['tgl_kembali']) && $row['tgl_kembali'] < date('Y-m-d');
              ?>
              <div class="member-loan-item">
                <h4 class="member-loan-title"><?= htmlspecialchars(format_judul_buku($row['seri_buku'], $row['judul_buku'])) ?></h4>
                <div class="member-loan-meta">
                  <div class="member-loan-meta-box">
                    <div class="member-loan-meta-label">Tanggal Pinjam</div>
                    <div class="member-loan-meta-value"><?= htmlspecialchars(date('d/m/Y', strtotime($row['tgl_pinjam']))) ?></div>
                  </div>
                  <div class="member-loan-meta-box">
                    <div class="member-loan-meta-label">Batas Kembali</div>
                    <div class="member-loan-meta-value"><?= htmlspecialchars(date('d/m/Y', strtotime($row['tgl_kembali']))) ?></div>
                  </div>
                </div>
                <div class="member-loan-alert">
                  <span class="member-loan-status pin"><i class="fa fa-clock-o"></i> Sedang dipinjam</span>
                  <?php if ($sudahJatuhTempo): ?>
                    <span style="font-size:12px;font-weight:700;color:#b45309;">Melewati jatuh tempo, tidak bisa diperpanjang.</span>
                  <?php elseif ($sisaPerpanjang <= 0): ?>
                    <span style="font-size:12px;font-weight:700;color:#475569;">Batas perpanjangan 2x sudah habis.</span>
                  <?php else: ?>
                    <a class="member-loan-btn" href="?page=anggota_pinjam&aksi=perpanjang&kode=<?= urlencode($row['id_sk']) ?>" onclick="return confirm('Perpanjang pinjaman ini 7 hari?');">
                      <i class="fa fa-calendar-plus-o"></i> Perpanjang 7 Hari
                    </a>
                  <?php endif; ?>
                </div>
                <div style="font-size:12px;color:#64748b;">Sisa kesempatan perpanjang: <strong><?= $sisaPerpanjang ?></strong> kali</div>
              </div>
            <?php endwhile; ?>
          </div>
        <?php else: ?>
          <div class="member-loan-empty">
            <i class="fa fa-check-circle" style="font-size:28px;margin-bottom:10px;color:#1f7a63;"></i>
            <div><strong>Tidak ada pinjaman aktif.</strong></div>
            <div>Semua buku yang pernah dipinjam akan tetap muncul di riwayat di bawah.</div>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="member-loan-card">
      <div class="member-loan-head">
        <div>
          <h3>Riwayat Peminjaman</h3>
          <p>Riwayat lengkap pinjaman aktif maupun yang sudah dikembalikan.</p>
        </div>
      </div>
      <div class="member-loan-body">
        <div class="table-responsive">
          <table id="example1" class="table table-bordered table-striped member-loan-table">
            <thead>
              <tr>
                <th>No</th>
                <th>Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Batas Kembali</th>
                <th>Tanggal Dikembalikan</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; if ($sqlRiwayat && mysqli_num_rows($sqlRiwayat) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($sqlRiwayat)): ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars(format_judul_buku($row['seri_buku'], $row['judul_buku'])) ?></td>
                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($row['tgl_pinjam']))) ?></td>
                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($row['tgl_kembali']))) ?></td>
                    <td><?= htmlspecialchars($row['tgl_dikembalikan'] ? date('d/m/Y', strtotime($row['tgl_dikembalikan'])) : '-') ?></td>
                    <td>
                      <span class="member-loan-status <?= $row['status'] === 'PIN' ? 'pin' : 'kem' ?>">
                        <?= $row['status'] === 'PIN' ? 'Dipinjam' : 'Kembali' ?>
                      </span>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td colspan="6" style="text-align:center;">Belum ada riwayat peminjaman.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<?php if ($flash): ?>
<script>
Swal.fire({
  title: <?= json_encode($flash['title']) ?>,
  text: <?= json_encode($flash['text']) ?>,
  icon: <?= json_encode($flash['status']) ?>,
  confirmButtonColor: '#0f766e'
}).then(function () {
  window.location = 'index.php?page=anggota_pinjam';
});
</script>
<?php endif; ?>
