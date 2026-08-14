<?php
session_start();
include "inc/koneksi.php";

if (!function_exists('ebook_catalog_palette_public')) {
    function ebook_catalog_palette_public($seed)
    {
        $palettes = [
            ['#163d33', '#21574a'],
            ['#123c69', '#1e5a91'],
            ['#6f1d1b', '#a33b36'],
            ['#5b3f8c', '#845ec2'],
            ['#9a3412', '#ea580c'],
            ['#0f766e', '#14b8a6'],
        ];
        return $palettes[abs(crc32((string) $seed)) % count($palettes)];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Katalog E-Book</title>
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets_style/assets/bower_components/font-awesome/css/font-awesome.min.css">
  <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <style>
    body {
      margin: 0;
      font-family: "Segoe UI", Arial, sans-serif;
      background:
        radial-gradient(circle at top right, rgba(20,184,166,.10), transparent 24%),
        linear-gradient(180deg, #f5effa 0%, #f9f7ff 100%);
      color: #22343a;
    }
    .reader-site {
      max-width: 1320px;
      margin: 0 auto;
      padding: 18px;
    }
    .reader-hero {
      background: linear-gradient(135deg, #1d3a4a, #23384c);
      color: #fff;
      border-radius: 24px;
      padding: 24px;
      display: flex;
      justify-content: space-between;
      gap: 18px;
      align-items: center;
      flex-wrap: wrap;
      box-shadow: 0 18px 40px rgba(29,58,74,.18);
    }
    .reader-brand {
      display: flex;
      align-items: center;
      gap: 14px;
    }
    .reader-logo {
      width: 56px;
      height: 56px;
      border-radius: 18px;
      background: linear-gradient(135deg, #ff2f72, #ff6f3d);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      box-shadow: 0 10px 24px rgba(255,47,114,.24);
    }
    .reader-brand h1 {
      margin: 0;
      font-size: 34px;
      font-weight: 800;
      line-height: 1.06;
    }
    .reader-brand p {
      margin: 6px 0 0;
      color: rgba(255,255,255,.82);
      font-size: 14px;
    }
    .reader-search {
      position: relative;
      min-width: 360px;
      flex: 1;
      max-width: 520px;
    }
    .reader-search i {
      position: absolute;
      left: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: #7f93a2;
    }
    .reader-search input {
      width: 100%;
      border: 0;
      border-radius: 999px;
      padding: 14px 18px 14px 44px;
      font-size: 15px;
      color: #22343a;
    }
    .reader-section {
      margin-top: 18px;
      background: rgba(255,255,255,.72);
      border: 1px solid #ece4f5;
      border-radius: 22px;
      padding: 18px;
    }
    .reader-section-head {
      display: flex;
      justify-content: space-between;
      gap: 12px;
      align-items: center;
      margin-bottom: 16px;
      flex-wrap: wrap;
    }
    .reader-section-head h2 {
      margin: 0;
      font-size: 28px;
      font-weight: 800;
      color: #243648;
    }
    .reader-count {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: #fff;
      border: 1px solid #eadff5;
      border-radius: 999px;
      padding: 8px 12px;
      color: #6f5d7d;
      font-size: 12px;
      font-weight: 700;
    }
    .reader-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 18px;
    }
    .reader-card {
      background: rgba(255,255,255,.92);
      border: 1px solid #ebdff4;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 12px 26px rgba(87, 61, 116, .08);
      transition: transform .2s ease, box-shadow .2s ease;
    }
    .reader-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 18px 34px rgba(87,61,116,.12);
    }
    .reader-card-body {
      display: flex;
      gap: 16px;
      padding: 18px;
    }
    .reader-cover {
      width: 112px;
      min-width: 112px;
      height: 156px;
      border-radius: 14px;
      padding: 10px;
      color: #fff;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      box-shadow: 0 12px 22px rgba(21,39,54,.16);
    }
    .reader-cover small {
      font-size: 10px;
      text-transform: uppercase;
      letter-spacing: .12em;
      opacity: .9;
    }
    .reader-cover strong {
      font-size: 18px;
      line-height: 1.06;
      font-weight: 800;
      word-break: break-word;
    }
    .reader-cover span {
      font-size: 10px;
      opacity: .92;
      line-height: 1.4;
    }
    .reader-content {
      flex: 1;
      min-width: 0;
      display: flex;
      flex-direction: column;
    }
    .reader-title {
      margin: 0 0 8px;
      font-size: 20px;
      font-weight: 800;
      color: #ff256d;
      line-height: 1.2;
    }
    .reader-desc {
      color: #637381;
      font-size: 14px;
      line-height: 1.55;
      margin-bottom: 12px;
      min-height: 64px;
    }
    .reader-meta {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
      margin-bottom: 12px;
    }
    .reader-tag {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      border-radius: 999px;
      padding: 6px 10px;
      font-size: 11px;
      font-weight: 700;
    }
    .reader-tag.source { background: #eef7ff; color: #1f5f91; }
    .reader-tag.size { background: #fff5e9; color: #b96917; }
    .reader-tag.category { background: #eef8f2; color: #17734b; }
    .reader-actions {
      margin-top: auto;
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
    }
    .reader-btn {
      border: 0;
      border-radius: 999px;
      padding: 10px 14px;
      font-size: 13px;
      font-weight: 700;
      color: #fff !important;
      text-decoration: none !important;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }
    .reader-btn.read { background: linear-gradient(135deg, #ff256d, #ff5c3a); }
    .reader-btn.open { background: linear-gradient(135deg, #0ea5e9, #2563eb); }
    .reader-btn.download { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .reader-empty {
      background: rgba(255,255,255,.95);
      border: 1px dashed #d8c8ea;
      border-radius: 18px;
      padding: 34px 24px;
      text-align: center;
      color: #7b6f87;
      display: none;
    }
    .reader-empty i {
      font-size: 34px;
      color: #c98be3;
      margin-bottom: 10px;
    }
    .reader-modal .modal-dialog {
      width: 94%;
      max-width: 1180px;
      margin: 20px auto;
    }
    .reader-modal .modal-content {
      border-radius: 14px;
      overflow: hidden;
    }
    .reader-modal .modal-header {
      background: linear-gradient(135deg,#14532d,#0f766e);
      color: #fff;
      border-bottom: 0;
    }
    .reader-modal .modal-header .close {
      color: #fff;
      opacity: 1;
      text-shadow: none;
    }
    .reader-modal-frame {
      width: 100%;
      height: 78vh;
      border: 1px solid #d0d5dd;
      border-radius: 10px;
      background: #f8fafc;
    }
    @media (max-width: 1199px) {
      .reader-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 767px) {
      .reader-site { padding: 10px; }
      .reader-hero { padding: 16px; border-radius: 18px; }
      .reader-brand h1 { font-size: 26px; }
      .reader-search { min-width: 100%; max-width: 100%; }
      .reader-grid { grid-template-columns: 1fr; }
      .reader-card-body { flex-direction: column; }
      .reader-cover { width: 100%; min-width: 100%; height: 180px; }
      .reader-modal .modal-dialog { width: calc(100% - 16px); margin: 8px auto; }
      .reader-modal-frame { height: 70vh; }
    }
  </style>
</head>
<body>
  <div class="reader-site">
    <div class="reader-hero">
      <div class="reader-brand">
        <div class="reader-logo"><i class="fa fa-book"></i></div>
        <div>
          <h1>Katalog E-Book</h1>
          <p>Cari, baca cepat lewat popup, atau buka file PDF langsung di tab baru.</p>
        </div>
      </div>
      <div class="reader-search">
        <i class="fa fa-search"></i>
        <input type="text" id="publicEbookSearch" placeholder="Cari judul, penulis, kategori, penerbit, atau kode e-book...">
      </div>
    </div>

    <div class="reader-section">
      <div class="reader-section-head">
        <h2>Books</h2>
        <div class="reader-count"><i class="fa fa-clone"></i> <span id="publicEbookCount">0</span> item tampil</div>
      </div>

      <div class="reader-grid" id="publicEbookGrid">
        <?php
        $sql = $koneksi->query("SELECT * FROM tb_ebook WHERE status_aktif='1' ORDER BY updated_at DESC, id_ebook DESC");
        while ($sql && $row = $sql->fetch_assoc()):
            $viewerUrl = '';
            if (($row['sumber_file'] ?? '') === 'url' && trim((string) ($row['file_url'] ?? '')) !== '') {
                $viewerUrl = $row['file_url'];
            } elseif (trim((string) ($row['file_path'] ?? '')) !== '') {
                $viewerUrl = $row['file_path'];
            }
            if ($viewerUrl === '') {
                continue;
            }
            $desc = trim((string) ($row['deskripsi'] ?? ''));
            if ($desc === '') {
                $desc = 'E-book digital siap dibaca melalui popup internal atau tab baru dari sumber PDF yang tersedia.';
            }
            $descShort = function_exists('mb_strimwidth')
                ? mb_strimwidth($desc, 0, 180, '...')
                : substr($desc, 0, 177) . (strlen($desc) > 177 ? '...' : '');
            $coverTitle = function_exists('mb_strimwidth')
                ? mb_strimwidth($row['judul_ebook'], 0, 26, '...')
                : substr($row['judul_ebook'], 0, 23) . (strlen($row['judul_ebook']) > 23 ? '...' : '');
            $palette = ebook_catalog_palette_public($row['id_ebook'] . $row['judul_ebook']);
            $searchText = strtolower(trim(implode(' ', [
                $row['kode_ebook'],
                $row['judul_ebook'],
                $row['penulis'],
                $row['kategori'],
                $row['penerbit'],
                $row['tahun_terbit'],
                $row['sumber_file']
            ])));
        ?>
        <article class="reader-card" data-public-ebook data-search="<?= htmlspecialchars($searchText) ?>">
          <div class="reader-card-body">
            <div class="reader-cover" style="background:linear-gradient(160deg, <?= htmlspecialchars($palette[0]) ?>, <?= htmlspecialchars($palette[1]) ?>);">
              <small>E-Book</small>
              <strong><?= htmlspecialchars($coverTitle) ?></strong>
              <span><?= htmlspecialchars($row['penulis'] ?: 'Penulis belum diisi') ?></span>
            </div>
            <div class="reader-content">
              <h3 class="reader-title"><?= htmlspecialchars($row['judul_ebook']) ?></h3>
              <div class="reader-desc"><?= htmlspecialchars($descShort) ?></div>
              <div class="reader-meta">
                <span class="reader-tag source"><i class="fa fa-link"></i> <?= htmlspecialchars(strtoupper($row['sumber_file'])) ?></span>
                <span class="reader-tag size"><i class="fa fa-file-pdf-o"></i> <?= htmlspecialchars($row['ukuran_label'] ?: '-') ?></span>
                <span class="reader-tag category"><i class="fa fa-folder-open-o"></i> <?= htmlspecialchars($row['kategori'] ?: 'Tanpa kategori') ?></span>
              </div>
              <div class="reader-actions">
                <a href="<?= htmlspecialchars($viewerUrl) ?>" target="_blank" rel="noopener" class="reader-btn read">
                  <i class="fa fa-book"></i> Baca
                </a>
                <a href="<?= htmlspecialchars($viewerUrl) ?>" target="_blank" rel="noopener" class="reader-btn open">
                  <i class="fa fa-external-link"></i> Buka Sumber
                </a>
                <a href="<?= htmlspecialchars($viewerUrl) ?>" class="reader-btn download" download>
                  <i class="fa fa-download"></i> Download
                </a>
              </div>
            </div>
          </div>
        </article>
        <?php endwhile; ?>
      </div>

      <div class="reader-empty" id="publicEbookEmpty">
        <i class="fa fa-search"></i>
        <div><strong>E-book tidak ditemukan.</strong></div>
        <div>Coba ubah kata kunci pencarian.</div>
      </div>
    </div>
  </div>

  <script>
    function filterPublicEbooks() {
      var keyword = ($('#publicEbookSearch').val() || '').toLowerCase().trim();
      var visible = 0;

      $('[data-public-ebook]').each(function() {
        var haystack = ($(this).data('search') || '').toString().toLowerCase();
        var match = keyword === '' || haystack.indexOf(keyword) !== -1;
        $(this).toggle(match);
        if (match) visible++;
      });

      $('#publicEbookCount').text(visible);
      $('#publicEbookEmpty').toggle(visible === 0);
    }

    $('#publicEbookSearch').on('input', filterPublicEbooks);

    $(function() {
      filterPublicEbooks();
    });
  </script>
</body>
</html>
