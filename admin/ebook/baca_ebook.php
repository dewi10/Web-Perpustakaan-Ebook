<?php
if (!isset($koneksi) || !($koneksi instanceof mysqli)) {
    include_once __DIR__ . '/../../inc/koneksi.php';
}

$kode = mysqli_real_escape_string($koneksi, $_GET['kode'] ?? '');
$mode = $_GET['mode'] ?? '';
$direct = $_GET['direct'] ?? '';

$sql = mysqli_query($koneksi, "SELECT e.*, b.seri_buku, b.judul_buku
                               FROM tb_ebook e
                               LEFT JOIN tb_buku b ON e.id_buku = b.id_buku
                               WHERE e.id_ebook='$kode' LIMIT 1");
$ebook = $sql ? mysqli_fetch_assoc($sql) : null;

$viewerUrl = '';
if ($ebook) {
    if (($ebook['sumber_file'] ?? '') === 'url' && trim((string) ($ebook['file_url'] ?? '')) !== '') {
        $viewerUrl = $ebook['file_url'];
    } elseif (trim((string) ($ebook['file_path'] ?? '')) !== '') {
        $viewerUrl = $ebook['file_path'];
    }
}

if ($viewerUrl !== '' && $mode !== 'popup') {
    header('Location: ' . $viewerUrl);
    exit;
}

$pageTitle = $ebook['judul_ebook'] ?? 'Baca E-Book';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../assets_style/assets/bower_components/font-awesome/css/font-awesome.min.css">
  <style>
    body {
      margin: 0;
      background: linear-gradient(180deg, #edf6f1 0%, #f7faf8 100%);
      font-family: "Segoe UI", Arial, sans-serif;
      color: #163d33;
    }
    .viewer-shell {
      min-height: 100vh;
      padding: 12px;
    }
    .viewer-card {
      background: rgba(255,255,255,.96);
      border: 1px solid #dbe7df;
      border-radius: 18px;
      box-shadow: 0 18px 40px rgba(22, 61, 51, .10);
      overflow: hidden;
    }
    .viewer-topbar {
      background: linear-gradient(135deg, #14532d, #0f766e);
      color: #fff;
      padding: 14px 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      flex-wrap: wrap;
    }
    .viewer-title {
      margin: 0;
      font-size: 22px;
      font-weight: 700;
      line-height: 1.2;
    }
    .viewer-sub {
      margin-top: 4px;
      font-size: 12px;
      opacity: .85;
      text-transform: uppercase;
      letter-spacing: .14em;
    }
    .viewer-actions {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
    }
    .viewer-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      border-radius: 999px;
      padding: 10px 14px;
      font-size: 13px;
      font-weight: 700;
      text-decoration: none !important;
      border: 0;
    }
    .viewer-btn-white {
      background: #fff;
      color: #14532d !important;
    }
    .viewer-btn-glass {
      background: rgba(255,255,255,.16);
      color: #fff !important;
      border: 1px solid rgba(255,255,255,.14);
    }
    .viewer-body {
      padding: 12px;
    }
    .viewer-frame-wrap {
      border-radius: 14px;
      overflow: hidden;
      border: 1px solid #dbe7df;
      background: #eef4f0;
      min-height: calc(100vh - 116px);
    }
    .viewer-frame {
      width: 100%;
      height: calc(100vh - 116px);
      border: 0;
      background: #fff;
    }
    .viewer-empty {
      margin: 12px;
      padding: 18px;
      border-radius: 14px;
      background: #fff7e4;
      border: 1px solid #efd18b;
      color: #775a1e;
      line-height: 1.6;
      font-size: 14px;
    }
    @media (max-width: 767px) {
      .viewer-shell {
        padding: 8px;
      }
      .viewer-topbar {
        padding: 12px;
      }
      .viewer-title {
        font-size: 18px;
      }
      .viewer-frame-wrap,
      .viewer-frame {
        min-height: calc(100vh - 128px);
        height: calc(100vh - 128px);
      }
    }
  </style>
</head>
<body>
  <div class="viewer-shell">
    <div class="viewer-card">
      <div class="viewer-topbar">
        <div>
          <div class="viewer-sub">Digital Reader</div>
          <h1 class="viewer-title"><?= htmlspecialchars($pageTitle) ?></h1>
        </div>
        <div class="viewer-actions">
          <?php if ($viewerUrl !== ''): ?>
          <a href="<?= htmlspecialchars($viewerUrl) ?>" target="_blank" class="viewer-btn viewer-btn-white">
            <i class="fa fa-external-link"></i> Buka Tab Baru
          </a>
          <a href="<?= htmlspecialchars($viewerUrl) ?>" class="viewer-btn viewer-btn-white" download>
            <i class="fa fa-download"></i> Download
          </a>
          <?php endif; ?>
          <?php if ($mode === 'popup'): ?>
          <button type="button" class="viewer-btn viewer-btn-glass" onclick="if(window.parent && window.parent !== window && window.parent.jQuery){ window.parent.jQuery('#modalEbookViewer').modal('hide'); }">
            <i class="fa fa-times"></i> Tutup
          </button>
          <?php endif; ?>
        </div>
      </div>

      <?php if (!$ebook): ?>
      <div class="viewer-empty">Data e-book tidak ditemukan.</div>
      <?php elseif ($viewerUrl === ''): ?>
      <div class="viewer-empty">File PDF atau URL baca belum tersedia untuk e-book ini.</div>
      <?php else: ?>
      <div class="viewer-body">
        <div class="viewer-frame-wrap">
          <iframe src="<?= htmlspecialchars($viewerUrl) ?>" class="viewer-frame" title="Pembaca E-Book"></iframe>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
