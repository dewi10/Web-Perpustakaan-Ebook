<?php
$anggotaId = mysqli_real_escape_string($koneksi, $data_id);
$anggota = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_anggota WHERE id_anggota='$anggotaId' LIMIT 1"));

$totalPinjam = 0;
$r = mysqli_query($koneksi, "SELECT COUNT(*) AS t FROM log_pinjam WHERE id_anggota='$anggotaId'");
if ($r) { $totalPinjam = (int) mysqli_fetch_assoc($r)['t']; }

$sedangDipinjam = 0;
$r = mysqli_query($koneksi, "SELECT COUNT(*) AS t FROM tb_sirkulasi WHERE id_anggota='$anggotaId' AND status='PIN'");
if ($r) { $sedangDipinjam = (int) mysqli_fetch_assoc($r)['t']; }

$sudahKembali = 0;
$r = mysqli_query($koneksi, "SELECT COUNT(*) AS t FROM tb_sirkulasi WHERE id_anggota='$anggotaId' AND status='KEM'");
if ($r) { $sudahKembali = (int) mysqli_fetch_assoc($r)['t']; }

$ebookAktif = 0;
$r = mysqli_query($koneksi, "SELECT COUNT(*) AS t FROM tb_ebook WHERE status_aktif='1'");
if ($r) { $ebookAktif = (int) mysqli_fetch_assoc($r)['t']; }

$sqlAktif = mysqli_query(
    $koneksi,
    "SELECT sk.id_sk, sk.tgl_pinjam, sk.tgl_kembali, b.seri_buku, b.judul_buku
     FROM tb_sirkulasi sk
     JOIN tb_buku b ON sk.id_buku=b.id_buku
     WHERE sk.id_anggota='$anggotaId' AND sk.status='PIN'
     ORDER BY sk.tgl_kembali ASC, sk.tgl_pinjam DESC
     LIMIT 3"
);

$sqlRecent = mysqli_query(
    $koneksi,
    "SELECT sk.tgl_pinjam, sk.tgl_kembali, sk.tgl_dikembalikan, sk.status, b.seri_buku, b.judul_buku
     FROM tb_sirkulasi sk
     JOIN tb_buku b ON sk.id_buku=b.id_buku
     WHERE sk.id_anggota='$anggotaId'
     ORDER BY COALESCE(sk.tgl_dikembalikan, sk.tgl_kembali) DESC, sk.tgl_pinjam DESC
     LIMIT 6"
);

$namaAnggota = trim((string) ($anggota['nama'] ?? $data_nama ?? 'Anggota'));
$jenisKelamin = trim((string) ($anggota['jekel'] ?? '-'));
$isPerempuan = in_array(strtolower($jenisKelamin), ['perempuan', 'wanita', 'pr'], true);
$avatarIcon = $isPerempuan ? 'fa-female' : 'fa-male';
$inisial = '';
foreach (preg_split('/\s+/', $namaAnggota) as $bagianNama) {
    if ($bagianNama === '') {
        continue;
    }
    $inisial .= strtoupper(substr($bagianNama, 0, 1));
    if (strlen($inisial) >= 2) {
        break;
    }
}
$inisial = $inisial !== '' ? $inisial : 'AG';
?>
<style>
.member-dash-shell { display:flex; flex-direction:column; gap:20px; }
.member-dash-hero {
  position: relative;
  overflow: hidden;
  border-radius: 28px;
  padding: 26px;
  color: #fff;
  background:
    radial-gradient(circle at top right, rgba(255,255,255,0.14), transparent 22%),
    radial-gradient(circle at bottom left, rgba(199,169,91,0.18), transparent 20%),
    linear-gradient(135deg, #103b35 0%, #17604d 56%, #1e7a62 100%);
  box-shadow: 0 22px 44px rgba(17, 57, 45, 0.18);
}
.member-dash-hero:after {
  content:'';
  position:absolute;
  top:-36px;
  right:-36px;
  width:200px;
  height:200px;
  border-radius:50%;
  background:rgba(255,255,255,0.08);
}
.member-dash-top {
  position:relative;
  z-index:1;
  display:flex;
  align-items:flex-start;
  justify-content:space-between;
  gap:18px;
  flex-wrap:wrap;
}
.member-dash-user { display:flex; align-items:center; gap:18px; min-width:0; }
.member-dash-avatar {
  position:relative;
  width:88px;
  height:88px;
  min-width:88px;
  border-radius:26px;
  background:rgba(255,255,255,0.12);
  border:1px solid rgba(255,255,255,0.16);
  display:flex;
  align-items:center;
  justify-content:center;
  box-shadow:0 16px 28px rgba(10, 37, 30, 0.2);
}
.member-dash-avatar .fa { font-size:40px; color:#fff; }
.member-dash-avatar span {
  position:absolute;
  right:7px;
  bottom:7px;
  width:26px;
  height:26px;
  border-radius:50%;
  background:#fff;
  color:#17604d;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:10px;
  font-weight:800;
}
.member-dash-copy { min-width:0; }
.member-dash-badge {
  display:inline-flex;
  align-items:center;
  gap:8px;
  padding:7px 12px;
  border-radius:999px;
  background:rgba(255,255,255,0.12);
  color:rgba(255,255,255,0.92);
  font-size:11px;
  font-weight:700;
  letter-spacing:.08em;
  text-transform:uppercase;
  margin-bottom:12px;
}
.member-dash-copy h1 {
  margin:0 !important;
  font-size:34px !important;
  line-height:1.08 !important;
  font-weight:800 !important;
  letter-spacing:-0.03em;
  color:#fff !important;
}
.member-dash-copy p {
  margin:10px 0 0;
  color:rgba(255,255,255,0.84);
  font-size:14px;
  line-height:1.75;
  max-width:650px;
}
.member-dash-chip {
  position:relative;
  z-index:1;
  min-width:220px;
  background:rgba(255,255,255,0.12);
  border:1px solid rgba(255,255,255,0.16);
  border-radius:20px;
  padding:18px 20px;
}
.member-dash-chip small {
  display:block;
  color:rgba(255,255,255,0.72);
  text-transform:uppercase;
  letter-spacing:.12em;
  font-size:10px;
  margin-bottom:7px;
}
.member-dash-chip strong {
  display:block;
  font-size:21px;
  line-height:1.3;
  font-weight:800;
}
.member-dash-chip span {
  display:block;
  margin-top:6px;
  color:rgba(255,255,255,0.82);
  font-size:13px;
}
.member-dash-stats { display:grid; grid-template-columns:repeat(4, minmax(0,1fr)); gap:16px; }
.member-dash-stat {
  background:#fff;
  border:1px solid #e4edf3;
  border-radius:22px;
  padding:18px;
  box-shadow:0 12px 28px rgba(23,53,45,.05);
}
.member-dash-stat-top { display:flex; justify-content:space-between; align-items:flex-start; gap:12px; margin-bottom:16px; }
.member-dash-stat-icon {
  width:52px;
  height:52px;
  border-radius:16px;
  display:flex;
  align-items:center;
  justify-content:center;
  color:#fff;
  font-size:22px;
}
.mdi-blue { background:linear-gradient(135deg,#2563eb,#1d4ed8); }
.mdi-orange { background:linear-gradient(135deg,#f59e0b,#d97706); }
.mdi-green { background:linear-gradient(135deg,#10b981,#059669); }
.mdi-red { background:linear-gradient(135deg,#ef4444,#dc2626); }
.member-dash-stat-label {
  color:#7d8e9b;
  text-transform:uppercase;
  letter-spacing:.1em;
  font-size:10px;
  font-weight:700;
}
.member-dash-stat-value {
  color:#203745;
  font-size:32px;
  line-height:1;
  font-weight:800;
}
.member-dash-layout { display:grid; grid-template-columns:1.05fr 1.4fr; gap:18px; }
.member-dash-card {
  background:#fff;
  border:1px solid #e6edf5;
  border-radius:24px;
  overflow:hidden;
  box-shadow:0 12px 28px rgba(23,53,45,.05);
}
.member-dash-card-head {
  padding:18px 20px;
  border-bottom:1px solid #edf2f7;
  display:flex;
  justify-content:space-between;
  align-items:center;
  gap:12px;
  flex-wrap:wrap;
}
.member-dash-card-head h3 {
  margin:0 !important;
  font-size:20px !important;
  color:#203745 !important;
  font-weight:800 !important;
}
.member-dash-card-head p {
  margin:5px 0 0;
  color:#728493;
  font-size:13px;
}
.member-dash-link {
  display:inline-flex;
  align-items:center;
  gap:8px;
  text-decoration:none !important;
  color:#16604d !important;
  font-size:13px;
  font-weight:700;
}
.member-dash-card-body { padding:20px; }
.member-dash-profile-grid { display:grid; grid-template-columns:repeat(2, minmax(0,1fr)); gap:14px; }
.member-dash-field {
  background:linear-gradient(180deg,#fcfdff 0%,#f6fafc 100%);
  border:1px solid #dfe8ef;
  border-radius:18px;
  padding:14px 15px;
}
.member-dash-field small {
  display:block;
  color:#7a8b98;
  text-transform:uppercase;
  letter-spacing:.12em;
  font-size:10px;
  margin-bottom:7px;
  font-weight:700;
}
.member-dash-field strong {
  display:block;
  color:#203745;
  font-size:15px;
  line-height:1.55;
  font-weight:700;
  word-break:break-word;
}
.member-dash-note {
  margin-top:14px;
  border-radius:16px;
  background:#eef7f2;
  color:#1a6248;
  padding:13px 14px;
  font-size:13px;
  line-height:1.7;
}
.member-dash-active-list { display:flex; flex-direction:column; gap:14px; }
.member-dash-active-item {
  border:1px solid #e4ebf3;
  border-radius:18px;
  background:linear-gradient(180deg,#ffffff 0%,#f8fbff 100%);
  padding:16px;
}
.member-dash-active-title {
  margin:0 0 10px;
  color:#203745;
  font-size:17px;
  line-height:1.35;
  font-weight:800;
}
.member-dash-active-meta {
  display:grid;
  grid-template-columns:repeat(2, minmax(0,1fr));
  gap:12px;
  margin-bottom:12px;
}
.member-dash-active-box {
  background:#fff;
  border:1px solid #e8eef5;
  border-radius:14px;
  padding:11px 12px;
}
.member-dash-active-box small {
  display:block;
  color:#7a8b98;
  text-transform:uppercase;
  letter-spacing:.12em;
  font-size:10px;
  margin-bottom:6px;
  font-weight:700;
}
.member-dash-active-box strong {
  display:block;
  color:#213847;
  font-size:14px;
  font-weight:700;
}
.member-dash-active-footer {
  display:flex;
  justify-content:space-between;
  align-items:center;
  gap:12px;
  flex-wrap:wrap;
}
.member-dash-pill {
  display:inline-flex;
  align-items:center;
  gap:6px;
  padding:6px 10px;
  border-radius:999px;
  background:#fff4d8;
  color:#b97709;
  font-size:11px;
  font-weight:700;
}
.member-dash-btn {
  display:inline-flex;
  align-items:center;
  gap:8px;
  border-radius:999px;
  padding:10px 14px;
  background:linear-gradient(135deg,#0f766e,#0f9b8e);
  color:#fff !important;
  text-decoration:none !important;
  font-size:13px;
  font-weight:700;
  box-shadow:0 10px 18px rgba(15,118,110,.16);
}
.member-dash-table { width:100%; border-collapse:collapse; }
.member-dash-table th {
  background:linear-gradient(135deg,#173b35,#1f5e4e) !important;
  color:#fff !important;
  font-size:12px !important;
  padding:11px 12px !important;
  border:none !important;
}
.member-dash-table td {
  padding:11px 12px !important;
  border-bottom:1px solid #edf2f7 !important;
  font-size:13px !important;
  color:#324754;
  vertical-align:top;
}
.member-dash-table tr:last-child td { border-bottom:none !important; }
.member-dash-status {
  display:inline-flex;
  align-items:center;
  gap:6px;
  padding:6px 10px;
  border-radius:999px;
  font-size:11px;
  font-weight:700;
}
.member-dash-status.pin { background:#fff4d8; color:#b97709; }
.member-dash-status.kem { background:#e7f7ec; color:#156b42; }
.member-dash-empty {
  border:1px dashed #d9e3ec;
  background:#f9fbfd;
  border-radius:18px;
  padding:28px 20px;
  text-align:center;
  color:#7a8d9c;
}
@media (max-width: 1199px) {
  .member-dash-stats { grid-template-columns:repeat(2, minmax(0,1fr)); }
  .member-dash-layout { grid-template-columns:1fr; }
}
@media (max-width: 767px) {
  .member-dash-hero { padding:22px 18px; }
  .member-dash-copy h1 { font-size:28px !important; }
  .member-dash-avatar { width:78px; height:78px; min-width:78px; border-radius:22px; }
  .member-dash-avatar .fa { font-size:34px; }
  .member-dash-chip { width:100%; }
  .member-dash-stats { grid-template-columns:1fr; }
  .member-dash-profile-grid,
  .member-dash-active-meta { grid-template-columns:1fr; }
}
</style>

<section class="content-header">
  <h1>Dashboard Anggota</h1>
</section>

<section class="content">
  <div class="member-dash-shell">
    <div class="member-dash-hero">
      <div class="member-dash-top">
        <div class="member-dash-user">
          <div class="member-dash-avatar">
            <i class="fa <?= htmlspecialchars($avatarIcon) ?>"></i>
            <span><?= htmlspecialchars($inisial) ?></span>
          </div>
          <div class="member-dash-copy">
            <div class="member-dash-badge"><i class="fa fa-home"></i> Dashboard Anggota</div>
            <h1><?= htmlspecialchars($namaAnggota) ?></h1>
            <p>Ringkasan layanan anggota untuk memantau pinjaman aktif, status pengembalian, dan akses cepat ke katalog e-book perpustakaan.</p>
          </div>
        </div>
        <div class="member-dash-chip">
          <small>NIP Anggota</small>
          <strong><?= htmlspecialchars($anggota['nip'] ?? '-') ?></strong>
          <span><?= htmlspecialchars($anggota['pangkat_gol'] ?? '-') ?></span>
        </div>
      </div>
    </div>

    <div class="member-dash-stats">
      <div class="member-dash-stat">
        <div class="member-dash-stat-top">
          <div>
            <div class="member-dash-stat-label">Total Peminjaman</div>
            <div class="member-dash-stat-value"><?= $totalPinjam ?></div>
          </div>
          <div class="member-dash-stat-icon mdi-blue"><i class="fa fa-book"></i></div>
        </div>
      </div>
      <div class="member-dash-stat">
        <div class="member-dash-stat-top">
          <div>
            <div class="member-dash-stat-label">Sedang Dipinjam</div>
            <div class="member-dash-stat-value"><?= $sedangDipinjam ?></div>
          </div>
          <div class="member-dash-stat-icon mdi-orange"><i class="fa fa-refresh"></i></div>
        </div>
      </div>
      <div class="member-dash-stat">
        <div class="member-dash-stat-top">
          <div>
            <div class="member-dash-stat-label">Sudah Kembali</div>
            <div class="member-dash-stat-value"><?= $sudahKembali ?></div>
          </div>
          <div class="member-dash-stat-icon mdi-green"><i class="fa fa-check-circle"></i></div>
        </div>
      </div>
      <div class="member-dash-stat">
        <div class="member-dash-stat-top">
          <div>
            <div class="member-dash-stat-label">E-Book Aktif</div>
            <div class="member-dash-stat-value"><?= $ebookAktif ?></div>
          </div>
          <div class="member-dash-stat-icon mdi-red"><i class="fa fa-tablet"></i></div>
        </div>
      </div>
    </div>

    <div class="member-dash-layout">
      <div class="member-dash-card">
        <div class="member-dash-card-head">
          <div>
            <h3>Ringkasan Profil</h3>
            <p>Identitas pokok anggota yang dipakai untuk layanan perpustakaan.</p>
          </div>
          <a href="?page=anggota_profil" class="member-dash-link"><i class="fa fa-user-circle-o"></i> Buka Profil</a>
        </div>
        <div class="member-dash-card-body">
          <div class="member-dash-profile-grid">
            <div class="member-dash-field"><small>ID Anggota</small><strong><?= htmlspecialchars($anggota['id_anggota'] ?? '-') ?></strong></div>
            <div class="member-dash-field"><small>NIP</small><strong><?= htmlspecialchars($anggota['nip'] ?? '-') ?></strong></div>
            <div class="member-dash-field"><small>Jenis Kelamin</small><strong><?= htmlspecialchars($anggota['jekel'] ?? '-') ?></strong></div>
            <div class="member-dash-field"><small>No HP</small><strong><?= htmlspecialchars($anggota['no_hp'] ?? '-') ?></strong></div>
            <div class="member-dash-field"><small>Jabatan / Unit</small><strong><?= htmlspecialchars($anggota['kelas'] ?? '-') ?></strong></div>
            <div class="member-dash-field"><small>Pangkat / Gol</small><strong><?= htmlspecialchars($anggota['pangkat_gol'] ?? '-') ?></strong></div>
          </div>
          <div class="member-dash-note">
            Username dan password awal anggota mengikuti <strong>NIP</strong> yang terdaftar pada sistem.
          </div>
        </div>
      </div>

      <div class="member-dash-card">
        <div class="member-dash-card-head">
          <div>
            <h3>Pinjaman Aktif</h3>
            <p>Daftar buku yang masih sedang dipinjam dan bisa dilanjutkan dari halaman pinjaman.</p>
          </div>
          <a href="?page=anggota_pinjam" class="member-dash-link"><i class="fa fa-book"></i> Kelola Pinjaman</a>
        </div>
        <div class="member-dash-card-body">
          <?php if ($sqlAktif && mysqli_num_rows($sqlAktif) > 0): ?>
            <div class="member-dash-active-list">
              <?php while ($row = mysqli_fetch_assoc($sqlAktif)): ?>
                <div class="member-dash-active-item">
                  <h4 class="member-dash-active-title"><?= htmlspecialchars(format_judul_buku($row['seri_buku'], $row['judul_buku'])) ?></h4>
                  <div class="member-dash-active-meta">
                    <div class="member-dash-active-box">
                      <small>Tanggal Pinjam</small>
                      <strong><?= htmlspecialchars(date('d/m/Y', strtotime($row['tgl_pinjam']))) ?></strong>
                    </div>
                    <div class="member-dash-active-box">
                      <small>Batas Kembali</small>
                      <strong><?= htmlspecialchars(date('d/m/Y', strtotime($row['tgl_kembali']))) ?></strong>
                    </div>
                  </div>
                  <div class="member-dash-active-footer">
                    <span class="member-dash-pill"><i class="fa fa-clock-o"></i> Sedang dipinjam</span>
                    <a href="?page=anggota_pinjam" class="member-dash-btn"><i class="fa fa-arrow-right"></i> Buka Detail</a>
                  </div>
                </div>
              <?php endwhile; ?>
            </div>
          <?php else: ?>
            <div class="member-dash-empty">
              <i class="fa fa-check-circle" style="font-size:28px;margin-bottom:10px;color:#1f7a63;"></i>
              <div><strong>Tidak ada pinjaman aktif.</strong></div>
              <div>Kalau nanti ada buku yang dipinjam, ringkasannya akan muncul di sini.</div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="member-dash-card">
      <div class="member-dash-card-head">
        <div>
          <h3>Riwayat Terbaru</h3>
          <p>Aktivitas pinjaman terbaru, termasuk buku yang sudah kembali.</p>
        </div>
        <a href="?page=anggota_ebook" class="member-dash-link"><i class="fa fa-tablet"></i> Buka E-Book</a>
      </div>
      <div class="member-dash-card-body">
        <div class="table-responsive">
          <table class="member-dash-table">
            <thead>
              <tr>
                <th>Buku</th>
                <th>Tgl Pinjam</th>
                <th>Batas Kembali</th>
                <th>Tgl Dikembalikan</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($sqlRecent && mysqli_num_rows($sqlRecent) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($sqlRecent)): ?>
                  <tr>
                    <td><?= htmlspecialchars(format_judul_buku($row['seri_buku'], $row['judul_buku'])) ?></td>
                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($row['tgl_pinjam']))) ?></td>
                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($row['tgl_kembali']))) ?></td>
                    <td><?= htmlspecialchars($row['tgl_dikembalikan'] ? date('d/m/Y', strtotime($row['tgl_dikembalikan'])) : '-') ?></td>
                    <td>
                      <span class="member-dash-status <?= $row['status'] === 'PIN' ? 'pin' : 'kem' ?>">
                        <?= $row['status'] === 'PIN' ? 'Dipinjam' : 'Kembali' ?>
                      </span>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td colspan="5" style="text-align:center;color:#92a19a;">Belum ada riwayat pinjam.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
