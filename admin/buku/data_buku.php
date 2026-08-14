<style>
.modal-header-red { background: linear-gradient(135deg,#8b1a1a,#a82020); color:#fff; }
.modal-header-red .close { color:#fff; opacity:1; text-shadow:none; font-size:22px; }
.modal-header-red .modal-title { font-weight:700; font-size:16px; }
.col-aksi {
  min-width: 92px;
  width: 92px;
  text-align: center;
}
.col-no {
  width: 40px;
  min-width: 40px;
  text-align: center;
}
.aksi-wrap {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  flex-wrap: wrap;
}
.aksi-wrap .btn {
  min-width: 32px;
  padding-left: 8px;
  padding-right: 8px;
}
.toolbar-btn {
  border: 0;
  color: #fff !important;
}
.toolbar-btn.btn-add { background: #14804a; }
.toolbar-btn.btn-import { background: #1f6fb2; }
.toolbar-btn.btn-export { background: #6f42c1; }
.toolbar-btn.btn-print { background: #d97706; }
.toolbar-btn.btn-template {
  background: #eef2f7;
  border: 1px solid #c9d2de;
  color: #344054 !important;
}
.col-kode-buku {
  min-width: 110px;
  width: 110px;
}
.col-seri-buku {
  min-width: 190px;
  width: 190px;
}
.col-judul-buku {
  min-width: 450px;
  width: 450px;
}
.col-penerbit {
  min-width: 150px;
  width: 150px;
}
.col-rak {
  min-width: 90px;
  width: 90px;
  text-align: center;
}
.col-jumlah {
  min-width: 74px;
  width: 74px;
  text-align: center;
}
.text-wrap-cell {
  display: block;
  width: 100%;
  white-space: normal;
  word-break: normal;
  overflow-wrap: break-word;
  vertical-align: middle;
}
.buku-filter-bar {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
  align-items: end;
  margin-bottom: 15px;
}
.buku-filter-item label {
  display: block;
  margin-bottom: 6px;
  font-size: 12px;
  font-weight: 600;
  color: #475467;
}
.buku-filter-item select,
.buku-filter-item input {
  min-width: 220px;
}
#example1 {
  width: 100% !important;
  min-width: 0;
  table-layout: fixed;
}
#example1 th,
#example1 td {
  white-space: normal;
  vertical-align: middle;
  word-break: break-word;
  overflow-wrap: break-word;
}
.col-judul-buku .text-wrap-cell,
.col-seri-buku .text-wrap-cell,
.col-penerbit .text-wrap-cell,
.col-rak .text-wrap-cell {
  line-height: 1.45;
}
.judul-buku-main {
  display: block;
  font-weight: 500;
}
.judul-buku-meta {
  display: block;
  margin-top: 4px;
  font-size: 12px;
  color: #667085;
}
.penerbit-main {
  display: block;
}
.penerbit-meta {
  display: block;
  margin-top: 4px;
  font-size: 12px;
  color: #667085;
}

@media (max-width: 1400px) {
  .col-kode-buku {
    min-width: 100px;
    width: 100px;
  }
  .col-rak {
    min-width: 78px;
    width: 78px;
  }
  .col-seri-buku {
    min-width: 160px;
    width: 160px;
  }
  .col-judul-buku {
    min-width: 340px;
    width: 340px;
  }
  .col-penerbit {
    min-width: 130px;
    width: 130px;
  }
}

@media (max-width: 1200px) {
  #example1 {
    table-layout: auto;
  }
  .col-no {
    width: 34px;
    min-width: 34px;
  }
  .col-kode-buku {
    min-width: 92px;
    width: 92px;
  }
  .col-rak {
    min-width: 72px;
    width: 72px;
  }
  .col-seri-buku {
    min-width: 145px;
    width: 145px;
  }
  .col-judul-buku {
    min-width: 300px;
    width: 300px;
  }
  .col-penerbit {
    min-width: 120px;
    width: 120px;
  }
  .col-jumlah {
    min-width: 64px;
    width: 64px;
  }
  .col-aksi {
    min-width: 84px;
    width: 84px;
  }
}
</style>

<?php
include_once __DIR__ . '/../../inc/buku_helpers.php';
$importStatus = $_GET['import_status'] ?? '';
$importCount = (int) ($_GET['import_count'] ?? 0);
$importMessage = $_GET['import_message'] ?? '';
$seriOptions = [];
$seriResult = $koneksi->query("SELECT DISTINCT seri_buku FROM tb_buku WHERE seri_buku <> '' ORDER BY seri_buku ASC");
while ($seriResult && $seriRow = $seriResult->fetch_assoc()) {
  $seriOptions[] = $seriRow['seri_buku'];
}
?>

<section class="content-header">
  <h1>Data Buku</h1>
  <ol class="breadcrumb">
    <li><a href="index.php"><i class="fa fa-home"></i> SI Perpustakaan</a></li>
    <li class="active">Data Buku</li>
  </ol>
</section>

<section class="content">
  <div class="box box-primary">
    <div class="box-header with-border toolbar-split">
      <button class="btn toolbar-btn btn-add" onclick="bukuTambah()">
        <i class="fa fa-plus"></i> Tambah Data
      </button>
      <button class="btn toolbar-btn btn-import" onclick="bukuImport()">
        <i class="fa fa-upload"></i> Import CSV
      </button>
      <a href="admin/buku/export_buku.php" class="btn toolbar-btn btn-export">
        <i class="fa fa-download"></i> Export CSV
      </a>
      <a href="admin/buku/print_buku.php" class="btn toolbar-btn btn-print" target="_blank">
        <i class="fa fa-print"></i> Print
      </a>
      <a href="admin/buku/template_buku.php" class="btn toolbar-btn btn-template">
        <i class="fa fa-file-text-o"></i> Download Template
      </a>
    </div>
    <div class="box-body">
      <div class="buku-filter-bar">
        <div class="buku-filter-item">
          <label for="filter_seri_buku">Filter Seri Buku</label>
          <select id="filter_seri_buku" class="form-control">
            <option value="">Semua Seri</option>
            <?php foreach ($seriOptions as $seriOption): ?>
            <option value="<?= htmlspecialchars($seriOption) ?>"><?= htmlspecialchars($seriOption) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="buku-filter-item">
          <label for="filter_judul_buku">Cari Judul Buku</label>
          <input type="text" id="filter_judul_buku" class="form-control" placeholder="Ketik judul buku...">
        </div>
      </div>
      <div class="table-responsive">
        <table id="example1" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th class="col-no">No</th><th class="col-kode-buku">Kode Buku</th><th class="col-rak">Rak</th><th class="col-seri-buku">Seri Buku</th><th class="col-judul-buku">Judul Buku</th>
              <th class="col-penerbit">Penerbit / Tahun</th><th class="col-jumlah">Jumlah</th><th class="col-aksi">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            $sql = $koneksi->query("SELECT * FROM tb_buku");
            while ($data = $sql->fetch_assoc()):
            ?>
            <tr>
              <td class="col-no"><?= $no++ ?></td>
              <td class="col-kode-buku"><span class="text-wrap-cell" title="<?= htmlspecialchars($data['kode_buku']) ?>"><?= htmlspecialchars($data['kode_buku']) ?></span></td>
              <td class="col-rak"><span class="text-wrap-cell" title="<?= htmlspecialchars($data['rak'] ?? '') ?>"><?= htmlspecialchars($data['rak'] ?? '') ?></span></td>
              <td class="col-seri-buku"><span class="text-wrap-cell" title="<?= htmlspecialchars($data['seri_buku']) ?>"><?= htmlspecialchars($data['seri_buku']) ?></span></td>
              <td class="col-judul-buku">
                <span class="text-wrap-cell">
                  <span class="judul-buku-main" title="<?= htmlspecialchars($data['judul_buku']) ?>"><?= htmlspecialchars($data['judul_buku']) ?></span>
                  <?php if (trim((string) ($data['pengarang'] ?? '')) !== ''): ?>
                  <span class="judul-buku-meta" title="<?= htmlspecialchars($data['pengarang']) ?>">Pengarang: <?= htmlspecialchars($data['pengarang']) ?></span>
                  <?php endif; ?>
                </span>
              </td>
              <td class="col-penerbit">
                <span class="text-wrap-cell">
                  <span class="penerbit-main" title="<?= htmlspecialchars($data['penerbit']) ?>"><?= htmlspecialchars($data['penerbit']) ?></span>
                  <?php if (trim((string) ($data['th_terbit'] ?? '')) !== ''): ?>
                  <span class="penerbit-meta">Tahun: <?= htmlspecialchars($data['th_terbit']) ?></span>
                  <?php endif; ?>
                </span>
              </td>
              <td class="col-jumlah"><?= htmlspecialchars($data['jumlah']) ?></td>
              <td class="col-aksi">
                <div class="aksi-wrap">
                <button class="btn btn-success btn-sm" onclick="bukuEdit('<?= $data['id_buku'] ?>')" title="Ubah">
                  <i class="fa fa-pencil"></i>
                </button>
                <a href="?page=MyApp/del_buku&kode=<?= $data['id_buku'] ?>"
                   onclick="return confirm('Yakin hapus data ini?')" class="btn btn-danger btn-sm" title="Hapus">
                  <i class="fa fa-trash"></i>
                </a>
                </div>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

<!-- Modal Buku -->
<div class="modal fade" id="modalBuku" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-header-red">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="modalBukuTitle">Tambah Buku</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" id="b_action" value="tambah">
        <input type="hidden" id="b_id">
        <div class="form-group">
          <label>Seri Buku</label>
          <select id="b_seri_select" class="form-control" onchange="handleSeriBukuChange()">
            <option value="">-- Pilih Seri Buku --</option>
            <?php foreach ($seriOptions as $seriOption): ?>
            <option value="<?= htmlspecialchars($seriOption) ?>"><?= htmlspecialchars($seriOption) ?></option>
            <?php endforeach; ?>
            <option value="__baru__">+ Buat Seri Baru</option>
          </select>
          <input type="text" id="b_seri_custom" class="form-control" placeholder="Tulis seri buku baru" style="margin-top:8px;display:none;" oninput="refreshKodeBuku()">
          <input type="hidden" id="b_seri">
        </div>
        <div class="form-group">
          <label>Kode Buku</label>
          <input type="text" id="b_kode" class="form-control" readonly>
        </div>
        <div class="form-group">
          <label>Rak</label>
          <input type="text" id="b_rak" class="form-control" placeholder="Contoh: A-01">
        </div>
        <div class="form-group">
          <label>Judul Buku</label>
          <input type="text" id="b_judul" class="form-control" placeholder="Judul Buku" oninput="refreshKodeBuku()">
        </div>
        <div class="form-group">
          <label>Pengarang</label>
          <input type="text" id="b_pengarang" class="form-control" placeholder="Nama Pengarang">
        </div>
        <div class="form-group">
          <label>Penerbit</label>
          <input type="text" id="b_penerbit" class="form-control" placeholder="Penerbit">
        </div>
        <div class="form-group">
          <label>Tahun Terbit</label>
          <input type="number" id="b_th" class="form-control" placeholder="Tahun Terbit">
        </div>
        <div class="form-group">
          <label>Jumlah</label>
          <input type="number" id="b_jumlah" class="form-control" placeholder="Jumlah Buku" min="0" value="1">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
        <button class="btn btn-primary" onclick="bukuSimpan()"><i class="fa fa-save"></i> Simpan</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalImportBuku" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-header-red">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import Buku dari CSV</h4>
      </div>
      <form action="admin/buku/import_buku.php" method="post" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="alert alert-info" style="margin-bottom:15px;">
            Gunakan file CSV sesuai template. Kolom wajib: <strong>seri_buku</strong>, <strong>judul_buku</strong>, <strong>pengarang</strong>, <strong>penerbit</strong>, <strong>rak</strong>, <strong>th_terbit</strong>, <strong>jumlah</strong>.
          </div>
          <div class="form-group">
            <label>File CSV</label>
            <input type="file" name="file_csv" class="form-control" accept=".csv,text/csv" required>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-default" data-dismiss="modal" type="button"><i class="fa fa-times"></i> Batal</button>
          <button class="btn btn-primary" type="submit"><i class="fa fa-upload"></i> Import</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function bukuImport() {
  $('#modalImportBuku').modal('show');
}

function bukuTambah() {
  $('#modalBukuTitle').text('Tambah Buku');
  $('#b_action').val('tambah');
  $('#b_id,#b_kode,#b_seri,#b_seri_custom,#b_judul,#b_pengarang,#b_penerbit,#b_rak,#b_th,#b_jumlah').val('');
  $('#b_seri_select').val('');
  toggleSeriBaruInput();
  $('#b_jumlah').val('1');
  $.get('admin/buku/handler_buku.php', {action:'next_id'}, function(r){
    $('#b_id').val(r.id);
    refreshKodeBuku();
  }, 'json');
  $('#modalBuku').modal('show');
}

function bukuEdit(id) {
  $('#modalBukuTitle').text('Ubah Buku');
  $('#b_action').val('ubah');
  $.get('admin/buku/handler_buku.php', {action:'get', id:id}, function(r){
    $('#b_id').val(r.id_buku);
    $('#b_kode').val(r.kode_buku);
    if ($('#b_seri_select option[value="' + r.seri_buku.replace(/"/g, '\\"') + '"]').length) {
      $('#b_seri_select').val(r.seri_buku);
      $('#b_seri_custom').val('');
    } else if (r.seri_buku !== '') {
      $('#b_seri_select').val('__baru__');
      $('#b_seri_custom').val(r.seri_buku);
    } else {
      $('#b_seri_select').val('');
      $('#b_seri_custom').val('');
    }
    toggleSeriBaruInput();
    syncSeriValue();
    $('#b_judul').val(r.judul_buku);
    $('#b_pengarang').val(r.pengarang);
    $('#b_penerbit').val(r.penerbit);
    $('#b_rak').val(r.rak);
    $('#b_th').val(r.th_terbit);
    $('#b_jumlah').val(r.jumlah);
  }, 'json');
  $('#modalBuku').modal('show');
}

function bukuSimpan() {
  syncSeriValue();
  $.post('admin/buku/handler_buku.php', {
    action:     $('#b_action').val(),
    id_buku:    $('#b_id').val(),
    kode_buku:  $('#b_kode').val(),
    seri_buku:  $('#b_seri').val(),
    judul_buku: $('#b_judul').val(),
    pengarang:  $('#b_pengarang').val(),
    penerbit:   $('#b_penerbit').val(),
    rak:        $('#b_rak').val(),
    th_terbit:  $('#b_th').val(),
    jumlah:     $('#b_jumlah').val()
  }, function(r) {
    if (r.ok) {
      $('#modalBuku').modal('hide');
      Swal.fire({title:'Berhasil!', icon:'success', timer:1200, showConfirmButton:false})
        .then(function(){ window.location.reload(); });
    } else {
      Swal.fire({title:'Gagal menyimpan data', icon:'error', confirmButtonColor:'#8b1a1a'});
    }
  }, 'json');
}

function getSelectedSeri() {
  if ($('#b_seri_select').val() === '__baru__') {
    return $('#b_seri_custom').val();
  }

  return $('#b_seri_select').val();
}

function syncSeriValue() {
  $('#b_seri').val(getSelectedSeri());
}

function toggleSeriBaruInput() {
  if ($('#b_seri_select').val() === '__baru__') {
    $('#b_seri_custom').show();
  } else {
    $('#b_seri_custom').hide();
  }
}

function getKodePrefix(baseText) {
  var text = (baseText || '').toString().trim().toUpperCase();
  if (!text) {
    return 'BKU';
  }

  text = text.replace(/[^A-Z0-9]+/g, ' ').trim();
  var ignoredWords = ['DAN', 'DI', 'KE', 'DARI', 'OF', 'THE', 'AND', 'UNTUK', 'PADA', 'TENTANG', 'YANG'];
  var words = text.split(/\s+/).filter(function(word) {
    return word && ignoredWords.indexOf(word) === -1;
  });

  if (words.length >= 2) {
    return words.slice(0, 4).map(function(word) {
      return word.charAt(0);
    }).join('').substring(0, 4) || 'BKU';
  }

  if (words.length === 1) {
    return (words[0] + 'XXX').substring(0, 3);
  }

  return 'BKU';
}

function handleSeriBukuChange() {
  toggleSeriBaruInput();
  syncSeriValue();
  refreshKodeBuku();
}

function refreshKodeBuku() {
  syncSeriValue();
  var seriValue = $('#b_seri').val();
  var judulValue = $('#b_judul').val();
  var baseValue = seriValue || judulValue;
  var previewCode = getKodePrefix(baseValue) + '-001';

  $('#b_kode').val(previewCode);

  if (!baseValue) {
    $('#b_kode').val('BKU-001');
    return;
  }

  $.get('admin/buku/handler_buku.php', {
    action: 'next_code',
    id_buku: $('#b_id').val(),
    kode_buku: $('#b_kode').val(),
    seri_buku: seriValue,
    judul_buku: judulValue
  }, function(r) {
    $('#b_kode').val(r.kode_buku || r.preview || previewCode);
  }, 'json');
}

window.addEventListener('load', function() {
  function initBukuPage() {
    if (!window.jQuery || !$.fn || !$.fn.DataTable) {
      window.setTimeout(initBukuPage, 100);
      return;
    }

    $(document).off('change.bukuSeri', '#b_seri_select').on('change.bukuSeri', '#b_seri_select', function() {
      handleSeriBukuChange();
    });

    $(document).off('input.bukuKode', '#b_seri_custom, #b_judul').on('input.bukuKode', '#b_seri_custom, #b_judul', function() {
      refreshKodeBuku();
    });

    if ($.fn.DataTable.isDataTable('#example1')) {
      $('#example1').DataTable().destroy();
    }

    var table = $('#example1').DataTable({
      autoWidth: false,
      columnDefs: [{
        defaultContent: '-',
        targets: '_all'
      }]
    });

    $('#example1_filter').hide();

    var seriColumnIndex = 3;
    var judulColumnIndex = 4;

    $('#filter_seri_buku').off('change.bukuFilter').on('change.bukuFilter', function() {
      var value = this.value;
      var escapedValue = $.fn.dataTable.util.escapeRegex(value);
      table
        .column(seriColumnIndex)
        .search(value ? '^' + escapedValue + '$' : '', true, false)
        .draw();
    });

    $('#filter_judul_buku').off('keyup.bukuFilter change.bukuFilter').on('keyup.bukuFilter change.bukuFilter', function() {
      table.column(judulColumnIndex).search(this.value).draw();
    });

    table.columns.adjust().draw(false);
  }

  initBukuPage();
});

<?php if ($importStatus === 'success'): ?>
Swal.fire({
  title: 'Import berhasil',
  text: '<?= $importCount ?> data buku berhasil ditambahkan.',
  icon: 'success',
  confirmButtonColor: '#8b1a1a'
});
<?php elseif ($importStatus === 'error'): ?>
Swal.fire({
  title: 'Import gagal',
  text: <?= json_encode($importMessage !== '' ? $importMessage : 'Periksa format file CSV dan template yang digunakan.') ?>,
  icon: 'error',
  confirmButtonColor: '#8b1a1a'
});
<?php endif; ?>
</script>
