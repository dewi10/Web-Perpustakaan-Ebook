<?php
if (!function_exists('member_ebook_palette')) {
    function member_ebook_palette($seed)
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
<style>
.member-ebook-shell { background:linear-gradient(180deg,#f7f1fb 0%,#f8f6ff 100%); border:1px solid #ece5f5; border-radius:20px; padding:18px; }
.member-ebook-topbar { background:linear-gradient(135deg,#1d3a4a,#23384c); border-radius:18px; padding:16px 18px; color:#fff; display:flex; justify-content:space-between; align-items:center; gap:14px; flex-wrap:wrap; }
.member-ebook-brand { display:flex; align-items:center; gap:12px; }
.member-ebook-badge { width:44px; height:44px; border-radius:14px; background:linear-gradient(135deg,#ff2f72,#ff6f3d); display:flex; align-items:center; justify-content:center; font-size:20px; box-shadow:0 10px 20px rgba(255,47,114,.24); }
.member-ebook-title { margin:0; font-size:26px; font-weight:800; line-height:1.1; }
.member-ebook-subtitle { margin-top:4px; color:rgba(255,255,255,.82); font-size:13px; }
.member-ebook-search { position:relative; min-width:320px; }
.member-ebook-search i { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#8ca0aa; }
.member-ebook-search input { width:100%; border:0; border-radius:999px; padding:11px 14px 11px 40px; font-size:14px; color:#21343b; }
.member-ebook-meta { display:flex; justify-content:space-between; align-items:center; gap:12px; margin:16px 2px 18px; flex-wrap:wrap; }
.member-ebook-heading h3 { margin:0; color:#243648; font-size:28px; font-weight:800; }
.member-ebook-count { display:inline-flex; align-items:center; gap:8px; background:#fff; border:1px solid #eadff5; border-radius:999px; padding:8px 12px; color:#6f5d7d; font-size:12px; font-weight:700; }
.member-ebook-grid { display:grid; grid-template-columns:repeat(3, minmax(0,1fr)); gap:18px; }
.member-ebook-card { background:rgba(255,255,255,.88); border:1px solid #ebdff4; border-radius:20px; overflow:hidden; box-shadow:0 12px 26px rgba(87,61,116,.08); transition:transform .2s ease, box-shadow .2s ease; }
.member-ebook-card:hover { transform:translateY(-3px); box-shadow:0 18px 34px rgba(87,61,116,.13); }
.member-ebook-card-body { display:flex; gap:16px; padding:18px; }
.member-ebook-cover { width:106px; min-width:106px; height:152px; border-radius:14px; padding:10px; display:flex; flex-direction:column; justify-content:space-between; color:#fff; box-shadow:0 12px 22px rgba(21,39,54,.16); }
.member-ebook-cover small { font-size:10px; letter-spacing:.12em; text-transform:uppercase; opacity:.92; }
.member-ebook-cover strong { font-size:18px; font-weight:800; line-height:1.06; word-break:break-word; }
.member-ebook-cover span { font-size:10px; line-height:1.4; opacity:.92; }
.member-ebook-content { flex:1; min-width:0; display:flex; flex-direction:column; }
.member-ebook-name { margin:0 0 8px; font-size:18px; line-height:1.2; font-weight:800; color:#ff256d; }
.member-ebook-desc { color:#637381; font-size:14px; line-height:1.55; margin-bottom:12px; min-height:50px; }
.member-ebook-related { border-left:4px solid #ff256d; padding-left:10px; color:#50616e; font-size:12px; line-height:1.5; margin-bottom:12px; }
.member-ebook-tags { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:14px; }
.member-ebook-tag { display:inline-flex; align-items:center; gap:6px; border-radius:999px; padding:6px 10px; font-size:11px; font-weight:700; }
.member-ebook-tag.source { background:#eef7ff; color:#1f5f91; }
.member-ebook-tag.size { background:#fff5e9; color:#b96917; }
.member-ebook-tag.status { background:#e8f8ef; color:#17734b; }
.member-ebook-actions { display:flex; gap:8px; flex-wrap:wrap; margin-top:auto; }
.member-ebook-btn { border:0; border-radius:999px; padding:10px 14px; font-size:13px; font-weight:700; color:#fff!important; text-decoration:none!important; display:inline-flex; align-items:center; gap:8px; }
.member-ebook-btn.read { background:linear-gradient(135deg,#ff256d,#ff5c3a); }
.member-ebook-btn.download { background:linear-gradient(135deg,#f59e0b,#d97706); }
.member-ebook-empty { background:rgba(255,255,255,.95); border:1px dashed #d8c8ea; border-radius:18px; padding:34px 24px; text-align:center; color:#7b6f87; display:none; }
.member-ebook-empty i { font-size:34px; color:#c98be3; margin-bottom:10px; }
@media (max-width:1199px){ .member-ebook-grid { grid-template-columns:repeat(2, minmax(0,1fr)); } }
@media (max-width:767px){ .member-ebook-search { min-width:100%; } .member-ebook-grid { grid-template-columns:1fr; } .member-ebook-card-body { flex-direction:column; } .member-ebook-cover { width:100%; min-width:100%; height:180px; } }
</style>

<section class="content-header">
  <h1>E-Book</h1>
</section>

<section class="content">
  <div class="member-ebook-shell">
    <div class="member-ebook-topbar">
      <div class="member-ebook-brand">
        <div class="member-ebook-badge"><i class="fa fa-book"></i></div>
        <div>
          <h2 class="member-ebook-title">Katalog E-Book</h2>
          <div class="member-ebook-subtitle">Cari, baca, dan download e-book aktif yang tersedia untuk anggota.</div>
        </div>
      </div>
      <div class="member-ebook-search">
        <i class="fa fa-search"></i>
        <input type="text" id="memberEbookSearch" placeholder="Cari judul, penulis, kategori, penerbit, atau kode e-book...">
      </div>
    </div>

    <div class="member-ebook-meta">
      <div class="member-ebook-heading"><h3>Books</h3></div>
      <div class="member-ebook-count"><i class="fa fa-clone"></i> <span id="memberEbookCount">0</span> item tampil</div>
    </div>

    <div class="member-ebook-grid">
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
              $desc = 'E-book digital siap dibaca langsung di tab baru atau diunduh ke perangkat Anda.';
          }
          $descShort = function_exists('mb_strimwidth') ? mb_strimwidth($desc, 0, 180, '...') : substr($desc, 0, 177) . (strlen($desc) > 177 ? '...' : '');
          $coverTitle = function_exists('mb_strimwidth') ? mb_strimwidth($row['judul_ebook'], 0, 26, '...') : substr($row['judul_ebook'], 0, 23) . (strlen($row['judul_ebook']) > 23 ? '...' : '');
          $palette = member_ebook_palette($row['id_ebook'] . $row['judul_ebook']);
          $searchText = strtolower(trim(implode(' ', [$row['kode_ebook'], $row['judul_ebook'], $row['penulis'], $row['kategori'], $row['penerbit'], $row['tahun_terbit'], $row['sumber_file']])));
      ?>
      <article class="member-ebook-card" data-member-ebook data-search="<?= htmlspecialchars($searchText) ?>">
        <div class="member-ebook-card-body">
          <div class="member-ebook-cover" style="background:linear-gradient(160deg, <?= htmlspecialchars($palette[0]) ?>, <?= htmlspecialchars($palette[1]) ?>);">
            <small>E-Book</small>
            <strong><?= htmlspecialchars($coverTitle) ?></strong>
            <span><?= htmlspecialchars($row['penulis'] ?: 'Penulis belum diisi') ?></span>
          </div>
          <div class="member-ebook-content">
            <h3 class="member-ebook-name"><?= htmlspecialchars($row['judul_ebook']) ?></h3>
            <div class="member-ebook-desc"><?= htmlspecialchars($descShort) ?></div>
            <div class="member-ebook-related">
              Penerbit: <?= htmlspecialchars($row['penerbit'] ?: '-') ?> | Tahun: <?= htmlspecialchars($row['tahun_terbit'] ?: '-') ?>
            </div>
            <div class="member-ebook-tags">
              <span class="member-ebook-tag source"><i class="fa fa-link"></i> <?= htmlspecialchars(strtoupper($row['sumber_file'])) ?></span>
              <span class="member-ebook-tag size"><i class="fa fa-file-pdf-o"></i> <?= htmlspecialchars($row['ukuran_label'] ?: '-') ?></span>
              <span class="member-ebook-tag status"><i class="fa fa-check-circle-o"></i> Aktif</span>
            </div>
            <div class="member-ebook-actions">
              <a href="<?= htmlspecialchars($viewerUrl) ?>" target="_blank" rel="noopener" class="member-ebook-btn read"><i class="fa fa-book"></i> Baca</a>
              <a href="<?= htmlspecialchars($viewerUrl) ?>" class="member-ebook-btn download" download><i class="fa fa-download"></i> Download</a>
            </div>
          </div>
        </div>
      </article>
      <?php endwhile; ?>
    </div>

    <div class="member-ebook-empty" id="memberEbookEmpty">
      <i class="fa fa-search"></i>
      <div><strong>E-book tidak ditemukan.</strong></div>
      <div>Coba ganti kata kunci pencarian.</div>
    </div>
  </div>
</section>

<script>
(function() {
  function filterMemberEbooks() {
    var keyword = ($('#memberEbookSearch').val() || '').toLowerCase().trim();
    var visible = 0;
    $('[data-member-ebook]').each(function() {
      var haystack = ($(this).data('search') || '').toString().toLowerCase();
      var match = keyword === '' || haystack.indexOf(keyword) !== -1;
      $(this).toggle(match);
      if (match) visible++;
    });
    $('#memberEbookCount').text(visible);
    $('#memberEbookEmpty').toggle(visible === 0);
  }

  $('#memberEbookSearch').on('input', filterMemberEbooks);
  filterMemberEbooks();
})();
</script>
