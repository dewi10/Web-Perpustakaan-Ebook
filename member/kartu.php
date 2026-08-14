<?php
include_once __DIR__ . '/../inc/anggota_helpers.php';
$anggotaId = mysqli_real_escape_string($koneksi, $data_id);
$anggota = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_anggota WHERE id_anggota='$anggotaId' LIMIT 1"));

$namaAnggota = trim((string) ($anggota['nama'] ?? $data_nama ?? 'Anggota Perpustakaan'));
$nipAnggota = trim((string) ($anggota['nip'] ?? '-'));
$idAnggotaLabel = trim((string) ($anggota['id_anggota'] ?? '-'));
$jabatanAnggota = format_jabatan_unit($anggota['kelas'] ?? '-', $anggota['pangkat_gol'] ?? '');
$pangkatAnggota = trim((string) ($anggota['pangkat_gol'] ?? '-'));
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
.member-card-page { max-width: 920px; margin: 0 auto; }
.member-card-toolbar { display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:18px; flex-wrap:wrap; }
.member-card-toolbar h2 { margin:0; font-size:24px; font-weight:800; color:#203745; }
.member-card-toolbar p { margin:6px 0 0; color:#6d7f8d; }
.member-card-actions { display:flex; gap:10px; flex-wrap:wrap; }
.member-card-btn { display:inline-flex; align-items:center; gap:8px; border-radius:999px; padding:10px 14px; text-decoration:none !important; font-size:13px; font-weight:700; }
.member-card-btn.primary { background:linear-gradient(135deg,#165f4c,#1f7a63); color:#fff !important; }
.member-card-btn.light { background:#fff; border:1px solid #dbe6df; color:#245443 !important; }
.member-card-shell {
  background:
    radial-gradient(circle at top right, rgba(255,255,255,0.15), transparent 24%),
    radial-gradient(circle at bottom left, rgba(199,169,91,0.22), transparent 18%),
    linear-gradient(135deg, #103b35 0%, #17604d 52%, #1f7a63 100%);
  border-radius: 28px;
  color: #fff;
  padding: 28px;
  box-shadow: 0 24px 46px rgba(17, 57, 45, 0.18);
  overflow: hidden;
  position: relative;
}
.member-card-shell:after {
  content:'';
  position:absolute;
  right:-40px;
  top:-40px;
  width:220px;
  height:220px;
  border-radius:50%;
  background:rgba(255,255,255,0.08);
}
.member-card-top { position:relative; z-index:1; display:flex; justify-content:space-between; gap:18px; align-items:flex-start; flex-wrap:wrap; }
.member-card-brand { display:flex; align-items:center; gap:12px; }
.member-card-brand img { width:42px; height:42px; object-fit:contain; border-radius:50%; background:rgba(255,255,255,0.12); padding:4px; }
.member-card-brand strong { display:block; font-size:20px; line-height:1.1; }
.member-card-brand span { display:block; color:rgba(255,255,255,0.75); font-size:12px; margin-top:4px; }
.member-card-chip { width:62px; height:46px; border-radius:14px; background:linear-gradient(135deg,#f0d390,#c7a95b); box-shadow:inset 0 1px 0 rgba(255,255,255,0.5); }
.member-card-body { position:relative; z-index:1; display:grid; grid-template-columns: 120px 1fr; gap:20px; align-items:center; margin:24px 0 26px; }
.member-card-avatar {
  width:120px; height:120px; border-radius:28px; background:rgba(255,255,255,0.12);
  border:1px solid rgba(255,255,255,0.16); display:flex; align-items:center; justify-content:center;
  position:relative; box-shadow:0 18px 30px rgba(11,42,34,0.24);
}
.member-card-avatar .fa { font-size:54px; color:#fff; }
.member-card-avatar span {
  position:absolute; right:8px; bottom:8px; width:28px; height:28px; border-radius:50%;
  background:#fff; color:#17604d; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:800;
}
.member-card-copy h3 { margin:0; font-size:34px; line-height:1.08; font-weight:800; }
.member-card-copy p { margin:10px 0 0; color:rgba(255,255,255,0.82); font-size:14px; line-height:1.7; }
.member-card-grid { position:relative; z-index:1; display:grid; grid-template-columns:repeat(2, minmax(0,1fr)); gap:14px; }
.member-card-field { background:rgba(255,255,255,0.10); border:1px solid rgba(255,255,255,0.14); border-radius:18px; padding:14px 16px; }
.member-card-field small { display:block; color:rgba(255,255,255,0.7); text-transform:uppercase; letter-spacing:.12em; font-size:10px; margin-bottom:6px; }
.member-card-field strong { display:block; font-size:15px; line-height:1.5; font-weight:700; word-break:break-word; }
@media print {
  .content-header, .member-card-toolbar, .main-header, .main-sidebar, .main-footer { display:none !important; }
  .content-wrapper, .right-side, .main-footer { margin-left:0 !important; }
  .content { padding:0 !important; }
  .member-card-page { max-width:none; margin:0; }
  .member-card-shell { box-shadow:none; }
}
@media (max-width: 767px) {
  .member-card-body, .member-card-grid { grid-template-columns:1fr; }
  .member-card-copy h3 { font-size:28px; }
}
</style>

<section class="content-header">
  <h1>Kartu Anggota</h1>
</section>

<section class="content">
  <div class="member-card-page">
    <div class="member-card-toolbar">
      <div>
        <h2>Kartu Anggota Perpustakaan</h2>
        <p>Tampilan ini dapat dicek langsung atau dicetak sebagai identitas anggota.</p>
      </div>
      <div class="member-card-actions">
        <a href="?page=anggota_profil" class="member-card-btn light"><i class="fa fa-arrow-left"></i> Kembali ke Profil</a>
        <a href="?page=anggota_kartu&print=1" class="member-card-btn primary"><i class="fa fa-print"></i> Print</a>
      </div>
    </div>

    <div class="member-card-shell">
      <div class="member-card-top">
        <div class="member-card-brand">
          <img src="dist/img/logokemhan.png" alt="Logo">
          <div>
            <strong>SI Perpustakaan</strong>
            <span>Puskod Baloghan Kemhan</span>
          </div>
        </div>
        <div class="member-card-chip"></div>
      </div>

      <div class="member-card-body">
        <div class="member-card-avatar">
          <i class="fa <?= htmlspecialchars($avatarIcon) ?>"></i>
          <span><?= htmlspecialchars($inisial) ?></span>
        </div>
        <div class="member-card-copy">
          <h3><?= htmlspecialchars($namaAnggota) ?></h3>
          <p>Kartu ini terhubung dengan akun anggota perpustakaan dan digunakan untuk identifikasi layanan sirkulasi serta akses login anggota.</p>
        </div>
      </div>

      <div class="member-card-grid">
        <div class="member-card-field">
          <small>ID Anggota</small>
          <strong><?= htmlspecialchars($idAnggotaLabel) ?></strong>
        </div>
        <div class="member-card-field">
          <small>NIP</small>
          <strong><?= htmlspecialchars($nipAnggota) ?></strong>
        </div>
        <div class="member-card-field">
          <small>Jenis Kelamin</small>
          <strong><?= htmlspecialchars($jenisKelamin !== '' ? $jenisKelamin : '-') ?></strong>
        </div>
        <div class="member-card-field">
          <small>Pangkat / Gol</small>
          <strong><?= htmlspecialchars($pangkatAnggota) ?></strong>
        </div>
        <div class="member-card-field" style="grid-column:1 / -1;">
          <small>Jabatan / Unit</small>
          <strong><?= htmlspecialchars($jabatanAnggota) ?></strong>
        </div>
      </div>
    </div>
  </div>
</section>

<?php if (isset($_GET['print']) && $_GET['print'] === '1'): ?>
<script>
window.onload = function () {
  window.print();
};
</script>
<?php endif; ?>
