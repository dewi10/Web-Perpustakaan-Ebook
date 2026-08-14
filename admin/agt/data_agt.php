<style>
.col-id-anggota {
  display: none;
}
.col-aksi {
  min-width: 150px;
  width: 150px;
  text-align: center;
}
.col-pangkat {
  min-width: 160px;
}
.col-nip {
  min-width: 170px;
}
.aksi-wrap {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  flex-wrap: wrap;
}
.aksi-wrap .btn {
  min-width: 36px;
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
</style>

<?php
include_once __DIR__ . '/../../inc/anggota_helpers.php';
$importStatus = $_GET['import_status'] ?? '';
$importCount = (int) ($_GET['import_count'] ?? 0);
$importMessage = $_GET['import_message'] ?? '';
?>

<section class="content-header">
  <h1>Data Anggota</h1>
  <ol class="breadcrumb">
    <li><a href="index.php"><i class="fa fa-home"></i> SI Perpustakaan</a></li>
    <li class="active">Data Anggota</li>
  </ol>
</section>

<section class="content">
  <div class="box box-primary">
    <div class="box-header with-border toolbar-split">
      <button class="btn toolbar-btn btn-add" onclick="agtTambah()">
        <i class="fa fa-plus"></i> Tambah Data
      </button>
      <button class="btn toolbar-btn btn-import" onclick="agtImport()">
        <i class="fa fa-upload"></i> Import CSV
      </button>
      <a href="admin/agt/export_agt.php" class="btn toolbar-btn btn-export">
        <i class="fa fa-download"></i> Export CSV
      </a>
      <a href="admin/agt/template_agt.php" class="btn toolbar-btn btn-template">
        <i class="fa fa-file-text-o"></i> Download Template
      </a>
      <a href="admin/agt/print_allagt.php" class="btn toolbar-btn btn-print" target="_blank">
        <i class="fa fa-print"></i> Print
      </a>
    </div>
    <div class="box-body">
      <div class="table-responsive">
        <table id="exampleAgt" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>No</th>
              <!-- <th class="col-id-anggota">ID Anggota</th> -->
              <th>Nama</th>
              <th>JK</th><th>Jabatan/Unit</th><th class="col-pangkat">Pangkat/Gol</th><th class="col-nip">NIP</th><th>No HP</th><th class="col-aksi">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            $sql = $koneksi->query("SELECT * FROM tb_anggota");
            while ($data = $sql->fetch_assoc()):
            ?>
            <tr>
              <td><?= $no++ ?></td>
              <!-- <td class="col-id-anggota"><?= htmlspecialchars($data['id_anggota']) ?></td> -->
              <td><?= htmlspecialchars($data['nama']) ?></td>
              <td><?= htmlspecialchars($data['jekel']) ?></td>
              <td><?= htmlspecialchars(format_jabatan_unit($data['kelas'] ?? '-', $data['pangkat_gol'] ?? '')) ?></td>
              <td><?= htmlspecialchars($data['pangkat_gol'] ?? '-') ?></td>
              <td><?= htmlspecialchars($data['nip'] ?? '-') ?></td>
              <td><?= htmlspecialchars($data['no_hp']) ?></td>
              <td class="col-aksi">
                <div class="aksi-wrap">
                <button class="btn btn-success btn-sm" onclick="agtEdit('<?= $data['id_anggota'] ?>')" title="Ubah">
                  <i class="fa fa-pencil"></i>
                </button>
                <a href="?page=MyApp/del_agt&kode=<?= $data['id_anggota'] ?>"
                   onclick="return confirm('Yakin hapus data ini?')" class="btn btn-danger btn-sm" title="Hapus">
                  <i class="fa fa-trash"></i>
                </a>
                <a href="?page=MyApp/print_agt&kode=<?= $data['id_anggota'] ?>" target="_blank"
                   class="btn btn-warning btn-sm" title="Print">
                  <i class="fa fa-print"></i>
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

<!-- Modal Anggota -->
<div class="modal fade" id="modalAgt" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-header-red">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="modalAgtTitle">Tambah Anggota</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" id="a_action" value="tambah">
        <div class="form-group">
          <label>ID Anggota</label>
          <input type="text" id="a_id" class="form-control" readonly>
        </div>
        <div class="form-group">
          <label>Nama Anggota</label>
          <input type="text" id="a_nama" class="form-control" placeholder="Nama Anggota">
        </div>
        <div class="form-group">
          <label>Jenis Kelamin</label>
          <select id="a_jekel" class="form-control">
            <option value="">-- Pilih --</option>
            <option value="Laki-laki">Laki-laki</option>
            <option value="Perempuan">Perempuan</option>
            <option value="-">Tidak diketahui</option>
          </select>
        </div>
        <div class="form-group">
          <label>Jabatan/Unit</label>
          <input type="text" id="a_kelas" class="form-control" placeholder="Jabatan atau Unit">
        </div>
        <div class="form-group">
          <label>Pangkat/Gol</label>
          <input type="text" id="a_pangkat" class="form-control" placeholder="Pangkat atau Golongan">
        </div>
        <div class="form-group">
          <label>NIP</label>
          <input type="text" id="a_nip" class="form-control" placeholder="NIP">
        </div>
        <div class="form-group">
          <label>No HP</label>
          <input type="text" id="a_hp" class="form-control" placeholder="No HP">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
        <button class="btn btn-primary" onclick="agtSimpan()"><i class="fa fa-save"></i> Simpan</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalImportAgt" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-header-red">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import Anggota dari CSV</h4>
      </div>
      <form action="admin/agt/import_agt.php" method="post" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="alert alert-info" style="margin-bottom:15px;">
            Gunakan file CSV sesuai template. Kolom wajib: <strong>nama</strong>, <strong>jekel</strong>, <strong>kelas</strong>, <strong>pangkat_gol</strong>, <strong>nip</strong>, <strong>no_hp</strong>.
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
function agtImport() {
  $('#modalImportAgt').modal('show');
}

function agtTambah() {
  $('#modalAgtTitle').text('Tambah Anggota');
  $('#a_action').val('tambah');
  $('#a_id,#a_nama,#a_kelas,#a_pangkat,#a_nip,#a_hp').val('');
  $('#a_jekel').val('');
  $.get('admin/agt/handler_agt.php', {action:'next_id'}, function(r){ $('#a_id').val(r.id); }, 'json');
  $('#modalAgt').modal('show');
}

function agtEdit(id) {
  $('#modalAgtTitle').text('Ubah Anggota');
  $('#a_action').val('ubah');
  $.get('admin/agt/handler_agt.php', {action:'get', id:id}, function(r){
    $('#a_id').val(r.id_anggota);
    $('#a_nama').val(r.nama);
    $('#a_jekel').val(r.jekel);
    $('#a_kelas').val(r.kelas);
    $('#a_pangkat').val(r.pangkat_gol || '');
    $('#a_nip').val(r.nip || '');
    $('#a_hp').val(r.no_hp);
  }, 'json');
  $('#modalAgt').modal('show');
}

function agtSimpan() {
  $.post('admin/agt/handler_agt.php', {
    action:      $('#a_action').val(),
    id_anggota:  $('#a_id').val(),
    nama:        $('#a_nama').val(),
    jekel:       $('#a_jekel').val(),
    kelas:       $('#a_kelas').val(),
    pangkat_gol: $('#a_pangkat').val(),
    nip:         $('#a_nip').val(),
    no_hp:       $('#a_hp').val()
  }, function(r) {
    if (r.ok) {
      $('#modalAgt').modal('hide');
      Swal.fire({title:'Berhasil!', icon:'success', timer:1200, showConfirmButton:false})
        .then(function(){ window.location.reload(); });
    } else {
      Swal.fire({title:'Gagal menyimpan data', icon:'error', confirmButtonColor:'#8b1a1a'});
    }
  }, 'json');
}

<?php if ($importStatus === 'success'): ?>
Swal.fire({
  title: 'Import berhasil',
  text: '<?= $importCount ?> data anggota berhasil ditambahkan.',
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

window.addEventListener('load', function() {
  function initAgtTable() {
    if (!window.jQuery || !$.fn || !$.fn.DataTable) {
      window.setTimeout(initAgtTable, 100);
      return;
    }

    if ($.fn.DataTable.isDataTable('#exampleAgt')) {
      $('#exampleAgt').DataTable().destroy();
    }

    $('#exampleAgt').DataTable({
      autoWidth: false,
      columnDefs: [
        {
          defaultContent: '-',
          targets: '_all'
        }
      ]
    });
  }

  initAgtTable();
});
</script>
