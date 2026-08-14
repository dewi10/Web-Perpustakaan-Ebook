<?php
$ebookBooks = [];
$bookResult = $koneksi->query("SELECT id_buku, seri_buku, judul_buku FROM tb_buku ORDER BY seri_buku ASC, judul_buku ASC");
while ($bookResult && $book = $bookResult->fetch_assoc()) {
  $label = trim($book['seri_buku']) !== '' ? $book['seri_buku'] . ' - ' . $book['judul_buku'] : $book['judul_buku'];
  $ebookBooks[] = [
    'id_buku' => $book['id_buku'],
    'label' => $label,
  ];
}

if (!function_exists('ebook_catalog_palette')) {
  function ebook_catalog_palette($seed)
  {
    $palettes = [
      ['#163d33', '#21574a', '#f3efe1', '#dce9e1'],
      ['#123c69', '#1e5a91', '#f4efe6', '#d9e7f5'],
      ['#6f1d1b', '#a33b36', '#f7ede4', '#f0d9d3'],
      ['#5b3f8c', '#845ec2', '#f6efff', '#e7dbfb'],
      ['#9a3412', '#ea580c', '#fff3e8', '#fde4cf'],
      ['#0f766e', '#14b8a6', '#ecfdf8', '#d2f5ee'],
    ];

    $index = abs(crc32((string) $seed)) % count($palettes);
    return $palettes[$index];
  }
}
?>

<style>
  .modal-header-red {
    background: linear-gradient(135deg, #8b1a1a, #a82020);
    color: #fff;
  }

  .modal-header-red .close {
    color: #fff;
    opacity: 1;
    text-shadow: none;
    font-size: 22px;
  }

  .modal-header-red .modal-title {
    font-weight: 700;
    font-size: 16px;
  }

  .ebook-toolbar .btn {
    border: 0;
    color: #fff !important;
    margin-right: 8px;
  }

  .ebook-toolbar .btn-add {
    background: #14804a;
  }

  .ebook-toolbar .btn-refresh {
    background: #1f6fb2;
  }

  .ebook-catalog-shell {
    background:
      radial-gradient(circle at top right, rgba(15, 118, 110, .08), transparent 28%),
      linear-gradient(180deg, #f7f1fb 0%, #f8f6ff 100%);
    border: 1px solid #ece5f5;
    border-radius: 20px;
    padding: 18px;
  }

  .ebook-catalog-topbar {
    background: linear-gradient(135deg, #1d3a4a, #23384c);
    border-radius: 18px;
    padding: 16px 18px;
    color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
  }

  .ebook-catalog-brand {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .ebook-catalog-badge {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    background: linear-gradient(135deg, #ff2f72, #ff6f3d);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    box-shadow: 0 10px 20px rgba(255, 47, 114, .24);
  }

  .ebook-catalog-title {
    margin: 0;
    font-size: 26px;
    font-weight: 800;
    line-height: 1.1;
  }

  .ebook-catalog-subtitle {
    margin-top: 4px;
    color: rgba(255, 255, 255, .82);
    font-size: 13px;
  }

  .ebook-catalog-tools {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
  }

  .ebook-search-wrap {
    position: relative;
    min-width: 320px;
  }

  .ebook-search-wrap i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #8ca0aa;
  }

  .ebook-search-input {
    width: 100%;
    border: 0;
    border-radius: 999px;
    padding: 11px 14px 11px 40px;
    font-size: 14px;
    color: #21343b;
  }

  .ebook-catalog-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin: 16px 2px 18px;
    flex-wrap: wrap;
  }

  .ebook-catalog-heading {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .ebook-catalog-heading h3 {
    margin: 0;
    color: #243648;
    font-size: 28px;
    font-weight: 800;
  }

  .ebook-catalog-count {
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

  .ebook-catalog-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
  }

  .ebook-card {
    background: rgba(255, 255, 255, .88);
    border: 1px solid #ebdff4;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 12px 26px rgba(87, 61, 116, .08);
    transition: transform .2s ease, box-shadow .2s ease;
  }

  .ebook-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 18px 34px rgba(87, 61, 116, .13);
  }

  .ebook-card-body {
    display: flex;
    gap: 16px;
    padding: 18px;
  }

  .ebook-cover {
    width: 106px;
    min-width: 106px;
    height: 152px;
    border-radius: 14px;
    padding: 10px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    color: #fff;
    box-shadow: 0 12px 22px rgba(21, 39, 54, .16);
  }

  .ebook-cover-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    font-size: 10px;
    letter-spacing: .12em;
    text-transform: uppercase;
    opacity: .92;
  }

  .ebook-cover-code {
    background: rgba(255, 255, 255, .16);
    border-radius: 999px;
    font-size: 10px;
    letter-spacing: .08em;
  }

  .ebook-cover-title {
    font-size: 18px;
    font-weight: 800;
    line-height: 1.06;
    word-break: break-word;
  }

  .ebook-cover-author {
    font-size: 10px;
    line-height: 1.4;
    opacity: .92;
  }

  .ebook-card-content {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
  }

  .ebook-card-title {
    font-size: 18px;
    line-height: 1.2;
    font-weight: 800;
    color: #ff256d;
    margin: 0 0 8px;
  }

  .ebook-card-desc {
    color: #637381;
    font-size: 14px;
    line-height: 1.55;
    margin-bottom: 12px;
    min-height: 50px;
  }

  .ebook-card-related {
    border-left: 4px solid #ff256d;
    padding-left: 10px;
    color: #50616e;
    font-size: 12px;
    line-height: 1.5;
    margin-bottom: 12px;
  }

  .ebook-card-tags {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 14px;
  }

  .ebook-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border-radius: 999px;
    padding: 6px 10px;
    font-size: 11px;
    font-weight: 700;
  }

  .ebook-tag.source {
    background: #eef7ff;
    color: #1f5f91;
  }

  .ebook-tag.size {
    background: #fff5e9;
    color: #b96917;
  }

  .ebook-tag.status.active {
    background: #e8f8ef;
    color: #17734b;
  }

  .ebook-tag.status.inactive {
    background: #feebeb;
    color: #b42318;
  }

  .ebook-card-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: auto;
    align-items: center;
  }

  .ebook-action-btn {
    border: 0;
    background: transparent !important;
    min-width: 28px;
    height: 28px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none !important;
    color: #475467 !important;
    font-size: 16px;
    box-shadow: none !important;
    padding: 0;
    border-radius: 0;
  }

  .ebook-action-btn.read {
    color: #e11d48 !important;
  }

  .ebook-action-btn.open {
    color: #2563eb !important;
  }

  .ebook-action-btn.download {
    color: #d97706 !important;
  }

  .ebook-action-btn.edit {
    color: #15803d !important;
  }

  .ebook-action-btn.delete {
    color: #dc2626 !important;
  }

  .ebook-action-btn:hover,
  .ebook-action-btn:focus {
    transform: translateY(-1px);
    color: #101828 !important;
  }

  .ebook-empty-state {
    background: rgba(255, 255, 255, .95);
    border: 1px dashed #d8c8ea;
    border-radius: 18px;
    padding: 34px 24px;
    text-align: center;
    color: #7b6f87;
  }

  .ebook-empty-state i {
    font-size: 34px;
    color: #c98be3;
    margin-bottom: 10px;
  }

  .ebook-viewer-modal .modal-dialog {
    width: 94%;
    max-width: 1180px;
    margin: 20px auto;
  }

  .ebook-viewer-modal .modal-content {
    border-radius: 14px;
    overflow: hidden;
  }

  .ebook-viewer-modal .modal-header {
    background: linear-gradient(135deg, #14532d, #0f766e);
    color: #fff;
    border-bottom: 0;
  }

  .ebook-viewer-modal .modal-header .close {
    color: #fff;
    opacity: 1;
    text-shadow: none;
  }

  .ebook-viewer-toolbar {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    align-items: center;
    margin-bottom: 12px;
    flex-wrap: wrap;
  }

  .ebook-viewer-frame {
    width: 100%;
    height: 78vh;
    border: 1px solid #d0d5dd;
    border-radius: 10px;
    background: #f8fafc;
  }

  .ebook-help {
    background: #f8fafc;
    border: 1px solid #dbe4ee;
    border-radius: 10px;
    padding: 12px 14px;
    margin-bottom: 15px;
    color: #475467;
  }

  .ebook-help strong {
    color: #101828;
  }

  @media (max-width: 1499px) {
    .ebook-catalog-grid {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }
  }

  @media (max-width: 1199px) {
    .ebook-catalog-grid {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }
  }

  @media (max-width: 767px) {
    .ebook-viewer-modal .modal-dialog {
      width: calc(100% - 16px);
      margin: 8px auto;
    }

    .ebook-viewer-frame {
      height: 70vh;
    }

    .ebook-catalog-shell {
      padding: 12px;
      border-radius: 16px;
    }

    .ebook-catalog-topbar {
      padding: 14px;
      border-radius: 14px;
    }

    .ebook-catalog-title {
      font-size: 22px;
    }

    .ebook-catalog-heading h3 {
      font-size: 22px;
    }

    .ebook-search-wrap {
      min-width: 100%;
    }

    .ebook-catalog-grid {
      grid-template-columns: 1fr;
    }

    .ebook-card-body {
      flex-direction: column;
    }

    .ebook-cover {
      width: 100%;
      min-width: 100%;
      height: 180px;
    }
  }
</style>

<section class="content-header">
  <h1>Data E-Book</h1>
  <ol class="breadcrumb">
    <li><a href="index.php"><i class="fa fa-home"></i> SI Perpustakaan</a></li>
    <li class="active">Data E-Book</li>
  </ol>
</section>

<section class="content">
  <div class="box box-primary">
    <div class="box-header with-border ebook-toolbar toolbar-split">
      <button class="btn btn-add" onclick="ebookTambah()"><i class="fa fa-plus"></i> Tambah E-Book</button>
      <button class="btn btn-refresh" onclick="window.location.reload()"><i class="fa fa-refresh"></i> Refresh</button>
    </div>
    <div class="box-body">
      <div class="ebook-help">
        <strong>Fitur baca digital:</strong> unggah file PDF atau isi URL PDF. Data e-book dipisah dari sirkulasi buku fisik, tetapi bisa dikaitkan ke data buku yang sudah ada.
      </div>

      <div class="ebook-catalog-shell">
        <div class="ebook-catalog-topbar">
          <div class="ebook-catalog-brand">
            <div class="ebook-catalog-badge"><i class="fa fa-book"></i></div>

            <div class="ebook-catalog-meta">
              <div class="ebook-catalog-heading">
                <h3 style="color:#fff;">Books</h3>
                <div class="ebook-catalog-count"><i class="fa fa-clone"></i> <span id="ebookVisibleCount">0</span> item tampil</div>
              </div>
              <!-- <div style="color:#7a6c88;font-size:13px;">Gunakan pencarian untuk memfilter katalog tanpa reload halaman.</div>-->
            </div>
            <!-- <div>
              <h2 class="ebook-catalog-title">FREE E-BOOK Shelf</h2>
              <div class="ebook-catalog-subtitle">Rak digital dengan pencarian cepat, popup reader, dan link langsung ke sumber asli.</div>
            </div> -->
          </div>
          <div class="ebook-catalog-tools">
            <div class="ebook-search-wrap">
              <i class="fa fa-search"></i>
              <input type="text" id="ebookSearchInput" class="ebook-search-input" placeholder="Cari judul, penulis, kategori, penerbit, atau kode e-book...">
            </div>
          </div>
        </div>



        <div style="margin-top:30px" id="ebookCatalogGrid" class="ebook-catalog-grid">
          <?php
          $sql = $koneksi->query("SELECT e.*, b.seri_buku, b.judul_buku
                                  FROM tb_ebook e
                                  LEFT JOIN tb_buku b ON e.id_buku = b.id_buku
                                  ORDER BY e.updated_at DESC, e.id_ebook DESC");
          while ($sql && $data = $sql->fetch_assoc()):
            $bukuTerkait = trim((string) $data['judul_buku']) !== ''
              ? (trim((string) $data['seri_buku']) !== '' ? $data['seri_buku'] . ' - ' . $data['judul_buku'] : $data['judul_buku'])
              : '-';
            $directViewerUrl = '';
            if (($data['sumber_file'] ?? '') === 'url' && trim((string) ($data['file_url'] ?? '')) !== '') {
              $directViewerUrl = $data['file_url'];
            } elseif (trim((string) ($data['file_path'] ?? '')) !== '') {
              $directViewerUrl = $data['file_path'];
            }
            $description = trim((string) ($data['deskripsi'] ?? ''));
            if ($description === '') {
              $description = 'E-book digital siap dibaca melalui popup internal atau tab baru dari sumber PDF yang tersedia.';
            }
            $descriptionShort = function_exists('mb_strimwidth')
              ? mb_strimwidth($description, 0, 180, '...')
              : substr($description, 0, 177) . (strlen($description) > 177 ? '...' : '');
            $coverTitle = trim((string) $data['judul_ebook']);
            $coverShort = function_exists('mb_strimwidth')
              ? mb_strimwidth($coverTitle, 0, 26, '...')
              : substr($coverTitle, 0, 23) . (strlen($coverTitle) > 23 ? '...' : '');
            $coverAuthor = trim((string) ($data['penulis'] ?: 'Penulis belum diisi'));
            $palette = ebook_catalog_palette($data['id_ebook'] . $coverTitle);
            $searchText = strtolower(trim(implode(' ', [
              $data['kode_ebook'],
              $data['judul_ebook'],
              $data['penulis'],
              $data['kategori'],
              $data['penerbit'],
              $data['tahun_terbit'],
              $bukuTerkait,
              $data['sumber_file']
            ])));
          ?>
            <article class="ebook-card" data-ebook-card data-search="<?= htmlspecialchars($searchText) ?>">
              <div class="ebook-card-body">
                <div class="ebook-cover" style="background:linear-gradient(160deg, <?= htmlspecialchars($palette[0]) ?>, <?= htmlspecialchars($palette[1]) ?>); border:1px solid <?= htmlspecialchars($palette[3]) ?>;">
                  <div class="ebook-cover-top">
                    <!-- <span>E-Book</span> -->

                  </div>
                  <div class="ebook-cover-title"><?= htmlspecialchars($coverShort) ?></div>
                  <div class="ebook-cover-author"><?= htmlspecialchars($coverAuthor) ?></div>
                </div>

                <div class="ebook-card-content">
                  <h4 class="ebook-card-title"><?= htmlspecialchars($data['judul_ebook']) ?></h4>
                  <div style="font-size:12px" class="ebook-card-desc"><?= htmlspecialchars($descriptionShort) ?></div>
                  <div class="ebook-card-related">
                    <span class="ebook-cover-code"><?= htmlspecialchars($data['kode_ebook']) ?></span><br>
                    Buku terkait: <strong><?= htmlspecialchars($bukuTerkait) ?></strong><br>
                    Penerbit: <?= htmlspecialchars($data['penerbit'] ?: '-') ?> | Tahun: <?= htmlspecialchars($data['tahun_terbit'] ?: '-') ?>
                  </div>
                  <div class="ebook-card-tags">
                    <span class="ebook-tag source"><i class="fa fa-link"></i> <?= htmlspecialchars(strtoupper($data['sumber_file'])) ?></span>
                    <span class="ebook-tag size"><i class="fa fa-file-pdf-o"></i> <?= htmlspecialchars($data['ukuran_label'] ?: '-') ?></span>
                    <span class="ebook-tag status <?= $data['status_aktif'] === '1' ? 'active' : 'inactive' ?>"><i class="fa fa-check-circle-o"></i> <?= $data['status_aktif'] === '1' ? 'Aktif' : 'Nonaktif' ?></span>
                  </div>
                <div class="ebook-card-actions">
                  <a href="<?= htmlspecialchars($directViewerUrl !== '' ? $directViewerUrl : ('?page=MyApp/baca_ebook&kode=' . urlencode($data['id_ebook']))) ?>" class="ebook-action-btn read" title="Baca E-Book" target="_blank" rel="noopener">
                    <i class="fa fa-book"></i>
                  </a>
                  <a href="<?= htmlspecialchars($directViewerUrl !== '' ? $directViewerUrl : ('?page=MyApp/baca_ebook&kode=' . urlencode($data['id_ebook']))) ?>" class="ebook-action-btn open" title="Buka Sumber" target="_blank" rel="noopener">
                    <i class="fa fa-external-link"></i>
                  </a>
                  <?php if ($directViewerUrl !== ''): ?>
                  <a href="<?= htmlspecialchars($directViewerUrl) ?>" class="ebook-action-btn download" title="Download PDF" download>
                    <i class="fa fa-download"></i>
                  </a>
                  <?php endif; ?>
                  <button class="ebook-action-btn edit" onclick="ebookEdit('<?= htmlspecialchars($data['id_ebook'], ENT_QUOTES) ?>')" title="Ubah">
                    <i class="fa fa-pencil"></i>
                  </button>
                    <a href="?page=MyApp/del_ebook&kode=<?= urlencode($data['id_ebook']) ?>" onclick="return confirm('Yakin hapus data e-book ini?')" class="ebook-action-btn delete" title="Hapus">
                      <i class="fa fa-trash"></i>
                    </a>
                  </div>
                </div>
              </div>
            </article>
          <?php endwhile; ?>
        </div>

        <div id="ebookEmptyState" class="ebook-empty-state" style="display:none;">
          <i class="fa fa-search"></i>
          <div><strong>E-book tidak ditemukan.</strong></div>
          <div>Coba ganti kata kunci pencarian judul, penulis, kategori, penerbit, atau kode e-book.</div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="modalEbook" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header modal-header-red">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="modalEbookTitle">Tambah E-Book</h4>
      </div>
      <div class="modal-body">
        <form id="ebookForm" enctype="multipart/form-data">
          <input type="hidden" name="action" id="e_action" value="tambah">
          <input type="hidden" name="id_ebook" id="e_id">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>ID E-Book</label>
                <input type="text" id="e_id_display" class="form-control" readonly>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Status</label>
                <select name="status_aktif" id="e_status" class="form-control">
                  <option value="1">Aktif</option>
                  <option value="0">Nonaktif</option>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Kaitkan ke Buku Fisik (opsional)</label>
            <select name="id_buku" id="e_id_buku" class="form-control">
              <option value="">-- Tidak dikaitkan --</option>
              <?php foreach ($ebookBooks as $book): ?>
                <option value="<?= htmlspecialchars($book['id_buku']) ?>"><?= htmlspecialchars($book['label']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label>Judul E-Book</label>
                <input type="text" name="judul_ebook" id="e_judul" class="form-control" placeholder="Judul e-book" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Kategori</label>
                <input type="text" name="kategori" id="e_kategori" class="form-control" placeholder="Kategori">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Penulis</label>
                <input type="text" name="penulis" id="e_penulis" class="form-control" placeholder="Penulis">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Penerbit</label>
                <input type="text" name="penerbit" id="e_penerbit" class="form-control" placeholder="Penerbit">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Tahun Terbit</label>
                <input type="number" name="tahun_terbit" id="e_tahun" class="form-control" placeholder="Tahun terbit">
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Deskripsi Singkat</label>
            <textarea name="deskripsi" id="e_deskripsi" class="form-control" rows="3" placeholder="Ringkasan e-book"></textarea>
          </div>
          <div class="form-group">
            <label>URL PDF (opsional)</label>
            <input type="url" name="file_url" id="e_file_url" class="form-control" placeholder="https://contoh.com/ebook.pdf">
          </div>
          <div class="form-group">
            <label>Upload File PDF</label>
            <input type="file" name="file_ebook" id="e_file" class="form-control" accept="application/pdf,.pdf">
            <p class="help-block" id="e_file_help">Unggah file PDF jika ingin disimpan di server aplikasi.</p>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
        <button class="btn btn-primary" onclick="ebookSimpan()"><i class="fa fa-save"></i> Simpan</button>
      </div>
    </div>
  </div>
</div>

<script>
  function ebookResetForm() {
    $('#ebookForm')[0].reset();
    $('#e_id').val('');
    $('#e_id_display').val('');
    $('#e_status').val('1');
    $('#e_file_help').text('Unggah file PDF jika ingin disimpan di server aplikasi.');
  }

  function ebookTambah() {
    ebookResetForm();
    $('#modalEbookTitle').text('Tambah E-Book');
    $('#e_action').val('tambah');
    $.get('admin/ebook/handler_ebook.php', {
      action: 'next_id'
    }, function(r) {
      $('#e_id').val(r.id);
      $('#e_id_display').val(r.id);
      $('#modalEbook').modal('show');
    }, 'json');
  }

  function ebookEdit(id) {
    ebookResetForm();
    $('#modalEbookTitle').text('Ubah E-Book');
    $('#e_action').val('ubah');
    $.get('admin/ebook/handler_ebook.php', {
      action: 'get',
      id: id
    }, function(r) {
      $('#e_id').val(r.id_ebook);
      $('#e_id_display').val(r.id_ebook);
      $('#e_id_buku').val(r.id_buku || '');
      $('#e_judul').val(r.judul_ebook || '');
      $('#e_penulis').val(r.penulis || '');
      $('#e_penerbit').val(r.penerbit || '');
      $('#e_tahun').val(r.tahun_terbit || '');
      $('#e_kategori').val(r.kategori || '');
      $('#e_deskripsi').val(r.deskripsi || '');
      $('#e_file_url').val(r.file_url || '');
      $('#e_status').val(r.status_aktif || '1');
      if (r.nama_file_asli) {
        $('#e_file_help').text('File saat ini: ' + r.nama_file_asli + '. Upload file baru jika ingin mengganti.');
      }
      $('#modalEbook').modal('show');
    }, 'json');
  }

  function ebookSimpan() {
    var form = document.getElementById('ebookForm');
    var formData = new FormData(form);
    formData.set('id_ebook', $('#e_id').val());

    $.ajax({
      url: 'admin/ebook/handler_ebook.php',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function(r) {
        if (r.ok) {
          $('#modalEbook').modal('hide');
          Swal.fire({
              title: 'Berhasil!',
              text: r.message || 'Data e-book tersimpan.',
              icon: 'success',
              timer: 1400,
              showConfirmButton: false
            })
            .then(function() {
              window.location.reload();
            });
        } else {
          Swal.fire({
            title: 'Gagal',
            text: r.message || 'Data e-book gagal disimpan.',
            icon: 'error',
            confirmButtonColor: '#8b1a1a'
          });
        }
      },
      error: function() {
        Swal.fire({
          title: 'Gagal',
          text: 'Terjadi kesalahan saat menghubungi server.',
          icon: 'error',
          confirmButtonColor: '#8b1a1a'
        });
      }
    });
  }

function filterEbookCatalog() {
    var keyword = ($('#ebookSearchInput').val() || '').toLowerCase().trim();
    var visible = 0;

    $('[data-ebook-card]').each(function() {
      var haystack = ($(this).data('search') || '').toString().toLowerCase();
      var match = keyword === '' || haystack.indexOf(keyword) !== -1;
      $(this).toggle(match);
      if (match) visible++;
    });

    $('#ebookVisibleCount').text(visible);
    $('#ebookEmptyState').toggle(visible === 0);
  }

  $('#ebookSearchInput').on('input', filterEbookCatalog);

$(function() {
  filterEbookCatalog();
});
</script>
