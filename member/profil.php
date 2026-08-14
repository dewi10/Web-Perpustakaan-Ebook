<?php
include_once __DIR__ . '/../inc/anggota_helpers.php';
$anggotaId = mysqli_real_escape_string($koneksi, $data_id);
$anggota = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_anggota WHERE id_anggota='$anggotaId' LIMIT 1"));

$jenisKelamin = trim((string) ($anggota['jekel'] ?? '-'));
$isPerempuan = in_array(strtolower($jenisKelamin), ['perempuan', 'wanita', 'pr'], true);
$avatarPath = $isPerempuan
    ? 'assets_style/assets/bower_components/Ionicons/png/512/woman.png'
    : 'assets_style/assets/bower_components/Ionicons/png/512/man.png';

$namaAnggota = trim((string) ($anggota['nama'] ?? $data_nama ?? 'Anggota Perpustakaan'));
$nipAnggota = trim((string) ($anggota['nip'] ?? '-'));
$idAnggotaLabel = trim((string) ($anggota['id_anggota'] ?? '-'));
$jabatanAnggota = format_jabatan_unit($anggota['kelas'] ?? '-', $anggota['pangkat_gol'] ?? '');
$pangkatAnggota = trim((string) ($anggota['pangkat_gol'] ?? '-'));
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
$inisial = $inisial !== '' ? $inisial : 'AP';
?>
<style>
.profile-member-shell {
  background: linear-gradient(180deg, #f8fbff 0%, #f6f8fc 100%);
  border: 1px solid #e1e9f2;
  border-radius: 28px;
  box-shadow: 0 18px 40px rgba(28, 53, 74, 0.08);
  overflow: hidden;
}
.profile-member-hero {
  position: relative;
  background:
    radial-gradient(circle at top right, rgba(255,255,255,0.12), transparent 24%),
    linear-gradient(135deg, #103b35 0%, #17604d 58%, #1f7a63 100%);
  padding: 28px 28px 30px;
  color: #fff;
}
.profile-member-hero:after {
  content: '';
  position: absolute;
  right: -32px;
  top: -24px;
  width: 180px;
  height: 180px;
  border-radius: 50%;
  background: rgba(255,255,255,0.08);
}
.profile-member-top {
  position: relative;
  z-index: 1;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
  flex-wrap: wrap;
}
.profile-member-user {
  display: flex;
  align-items: center;
  gap: 18px;
  min-width: 0;
}
.profile-member-avatar {
  position: relative;
  width: 92px;
  height: 92px;
  min-width: 92px;
  border-radius: 28px;
  background: rgba(255,255,255,0.16);
  border: 1px solid rgba(255,255,255,0.18);
  box-shadow: 0 18px 30px rgba(8, 32, 28, 0.25);
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
}
.profile-member-avatar img {
  width: 72px;
  height: 72px;
  object-fit: contain;
  filter: drop-shadow(0 8px 12px rgba(0, 0, 0, 0.12));
}
.profile-member-initial {
  position: absolute;
  right: 8px;
  bottom: 8px;
  width: 28px;
  height: 28px;
  border-radius: 50%;
  background: #fff;
  color: #17604d;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 11px;
  font-weight: 800;
  box-shadow: 0 10px 18px rgba(17, 56, 46, 0.24);
}
.profile-member-copy {
  min-width: 0;
}
.profile-member-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  border-radius: 999px;
  padding: 7px 12px;
  background: rgba(255,255,255,0.12);
  color: rgba(255,255,255,0.92);
  font-size: 11px;
  font-weight: 700;
  letter-spacing: .08em;
  text-transform: uppercase;
  margin-bottom: 12px;
}
.profile-member-copy h2 {
  margin: 0;
  font-size: 34px;
  line-height: 1.08;
  font-weight: 800;
  letter-spacing: -0.03em;
}
.profile-member-copy p {
  margin: 10px 0 0;
  max-width: 620px;
  color: rgba(255,255,255,0.85);
  font-size: 15px;
  line-height: 1.7;
}
.profile-member-status {
  position: relative;
  z-index: 1;
  min-width: 220px;
  background: rgba(255,255,255,0.12);
  border: 1px solid rgba(255,255,255,0.16);
  border-radius: 20px;
  padding: 18px 20px;
  box-shadow: inset 0 1px 0 rgba(255,255,255,0.06);
}
.profile-member-status small {
  display: block;
  color: rgba(255,255,255,0.74);
  text-transform: uppercase;
  letter-spacing: .12em;
  font-size: 10px;
  margin-bottom: 8px;
}
.profile-member-status strong {
  display: block;
  font-size: 22px;
  line-height: 1.2;
  font-weight: 800;
}
.profile-member-status span {
  display: block;
  margin-top: 6px;
  color: rgba(255,255,255,0.82);
  font-size: 13px;
}
.profile-member-body {
  padding: 24px;
}
.profile-member-summary {
  display: grid;
  grid-template-columns: 1.2fr .8fr;
  gap: 18px;
  margin-bottom: 20px;
}
.profile-member-panel {
  background: #fff;
  border: 1px solid #e6edf5;
  border-radius: 22px;
  padding: 22px;
  box-shadow: 0 10px 26px rgba(29, 49, 68, 0.04);
}
.profile-member-panel h3 {
  margin: 0 0 8px;
  font-size: 22px;
  font-weight: 800;
  color: #1f3342;
}
.profile-member-panel p {
  margin: 0;
  color: #667688;
  line-height: 1.7;
  font-size: 14px;
}
.profile-member-identity {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 14px;
}
.profile-member-item {
  background: linear-gradient(180deg, #fcfdff 0%, #f5f9fc 100%);
  border: 1px solid #dde8ef;
  border-radius: 18px;
  padding: 18px 16px;
}
.profile-member-item-label {
  color: #7f8f9d;
  text-transform: uppercase;
  letter-spacing: .12em;
  font-size: 10px;
  font-weight: 700;
  margin-bottom: 10px;
}
.profile-member-item-value {
  color: #203745;
  font-size: 18px;
  line-height: 1.5;
  font-weight: 700;
  word-break: break-word;
}
.profile-member-item-value.muted {
  color: #5c7282;
  font-weight: 600;
}
.profile-member-accent {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  margin-top: 14px;
  border-radius: 999px;
  padding: 8px 12px;
  background: #edf7f2;
  color: #186247;
  font-size: 12px;
  font-weight: 700;
}
.profile-member-actions {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  margin-top: 16px;
}
.profile-member-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  border-radius: 999px;
  padding: 10px 14px;
  font-size: 13px;
  font-weight: 700;
  text-decoration: none !important;
}
.profile-member-btn.primary {
  background: linear-gradient(135deg, #165f4c, #1f7a63);
  color: #fff !important;
}
.profile-member-btn.light {
  background: #fff;
  color: #245443 !important;
  border: 1px solid #dbe6df;
}
.profile-member-card-preview {
  background:
    radial-gradient(circle at top right, rgba(255,255,255,0.18), transparent 28%),
    linear-gradient(135deg, #103b35 0%, #17604d 55%, #1e7a62 100%);
  color: #fff;
  border-radius: 24px;
  padding: 22px;
  min-height: 220px;
  box-shadow: 0 18px 36px rgba(17, 57, 45, 0.18);
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}
.profile-member-card-top {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 14px;
}
.profile-member-card-brand {
  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: 800;
  letter-spacing: -0.02em;
}
.profile-member-card-brand img {
  width: 34px;
  height: 34px;
  object-fit: contain;
  border-radius: 50%;
  background: rgba(255,255,255,0.12);
  padding: 3px;
}
.profile-member-card-chip {
  width: 52px;
  height: 38px;
  border-radius: 12px;
  background: linear-gradient(135deg, #f0d390, #c7a95b);
  box-shadow: inset 0 1px 0 rgba(255,255,255,0.45);
}
.profile-member-card-name {
  margin: 18px 0 8px;
  font-size: 24px;
  line-height: 1.15;
  font-weight: 800;
}
.profile-member-card-meta {
  display: grid;
  grid-template-columns: repeat(2, minmax(0,1fr));
  gap: 12px;
}
.profile-member-card-meta small {
  display: block;
  color: rgba(255,255,255,0.72);
  text-transform: uppercase;
  letter-spacing: .12em;
  font-size: 10px;
  margin-bottom: 5px;
}
.profile-member-card-meta strong {
  display: block;
  font-size: 14px;
  line-height: 1.5;
  font-weight: 700;
}
@media (max-width: 991px) {
  .profile-member-summary {
    grid-template-columns: 1fr;
  }
}
@media (max-width: 767px) {
  .profile-member-hero {
    padding: 22px 18px 24px;
  }
  .profile-member-body {
    padding: 18px;
  }
  .profile-member-copy h2 {
    font-size: 27px;
  }
  .profile-member-user {
    align-items: flex-start;
  }
  .profile-member-avatar {
    width: 78px;
    height: 78px;
    min-width: 78px;
    border-radius: 22px;
  }
  .profile-member-avatar img {
    width: 62px;
    height: 62px;
  }
  .profile-member-identity {
    grid-template-columns: 1fr;
  }
  .profile-member-status {
    width: 100%;
  }
  .profile-member-card-meta {
    grid-template-columns: 1fr;
  }
}
</style>

<section class="content-header">
  <h1>Profil Saya</h1>
</section>

<section class="content">
  <div class="profile-member-shell">
    <div class="profile-member-hero">
      <div class="profile-member-top">
        <div class="profile-member-user">
          <div class="profile-member-avatar">
            <img src="<?= htmlspecialchars($avatarPath) ?>" alt="Avatar anggota">
            <div class="profile-member-initial"><?= htmlspecialchars($inisial) ?></div>
          </div>
          <div class="profile-member-copy">
            <div class="profile-member-badge">
              <i class="fa fa-id-badge"></i> Profil Anggota
            </div>
            <h2><?= htmlspecialchars($namaAnggota) ?></h2>
            <p>Informasi akun anggota perpustakaan ditampilkan secara ringkas agar mudah dicek saat menggunakan layanan sirkulasi dan akses e-book.</p>
          </div>
        </div>
        <div class="profile-member-status">
          <small>Status Akun</small>
          <strong>Anggota Aktif</strong>
          <span>Login menggunakan NIP yang terdaftar pada sistem.</span>
        </div>
      </div>
    </div>

    <div class="profile-member-body">
      <div class="profile-member-summary">
        <div class="profile-member-panel">
          <h3>Ringkasan Profesional</h3>
          <p>Profil ini memuat identitas pokok anggota, unit kerja, pangkat atau golongan, dan data kontak yang tersimpan di aplikasi perpustakaan.</p>
          <div class="profile-member-accent">
            <i class="fa fa-shield"></i> Data digunakan untuk layanan anggota dan login
          </div>
          <div class="profile-member-actions">
            <a href="?page=anggota_kartu" class="profile-member-btn primary"><i class="fa fa-id-card-o"></i> Lihat Kartu Anggota</a>
            <a href="?page=anggota_kartu&print=1" target="_blank" class="profile-member-btn light"><i class="fa fa-print"></i> Print Kartu</a>
          </div>
        </div>
        <div class="profile-member-panel">
          <div class="profile-member-card-preview">
            <div class="profile-member-card-top">
              <div class="profile-member-card-brand">
                <img src="dist/img/logokemhan.png" alt="Logo">
                <span>Kartu Anggota</span>
              </div>
              <div class="profile-member-card-chip"></div>
            </div>
            <div>
              <div class="profile-member-card-name"><?= htmlspecialchars($namaAnggota) ?></div>
              <div style="color:rgba(255,255,255,0.78);font-size:13px;line-height:1.6;">Sistem Informasi Perpustakaan Puskod Baloghan Kemhan</div>
            </div>
            <div class="profile-member-card-meta">
              <div>
                <small>ID Anggota</small>
                <strong><?= htmlspecialchars($idAnggotaLabel) ?></strong>
              </div>
              <div>
                <small>NIP</small>
                <strong><?= htmlspecialchars($nipAnggota) ?></strong>
              </div>
              <div>
                <small>Pangkat / Gol</small>
                <strong><?= htmlspecialchars($pangkatAnggota) ?></strong>
              </div>
              <div>
                <small>Jabatan / Unit</small>
                <strong><?= htmlspecialchars($jabatanAnggota) ?></strong>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="profile-member-identity">
        <div class="profile-member-item">
          <div class="profile-member-item-label">ID Anggota</div>
          <div class="profile-member-item-value"><?= htmlspecialchars($anggota['id_anggota'] ?? '-') ?></div>
        </div>
        <div class="profile-member-item">
          <div class="profile-member-item-label">NIP</div>
          <div class="profile-member-item-value"><?= htmlspecialchars($anggota['nip'] ?? '-') ?></div>
        </div>
        <div class="profile-member-item">
          <div class="profile-member-item-label">Jenis Kelamin</div>
          <div class="profile-member-item-value"><?= htmlspecialchars($jenisKelamin !== '' ? $jenisKelamin : '-') ?></div>
        </div>
        <div class="profile-member-item">
          <div class="profile-member-item-label">No HP</div>
          <div class="profile-member-item-value muted"><?= htmlspecialchars($anggota['no_hp'] ?? '-') ?></div>
        </div>
        <div class="profile-member-item">
          <div class="profile-member-item-label">Jabatan / Unit</div>
          <div class="profile-member-item-value"><?= htmlspecialchars($anggota['kelas'] ?? '-') ?></div>
        </div>
        <div class="profile-member-item">
          <div class="profile-member-item-label">Pangkat / Gol</div>
          <div class="profile-member-item-value"><?= htmlspecialchars($anggota['pangkat_gol'] ?? '-') ?></div>
        </div>
      </div>
    </div>
  </div>
</section>
