<section class="content-header">
  <h1>Pengguna Sistem</h1>
  <ol class="breadcrumb">
    <li><a href="index.php"><i class="fa fa-home"></i> SI Perpustakaan</a></li>
    <li class="active">Pengguna Sistem</li>
  </ol>
</section>

<section class="content">
  <div class="box box-primary">
    <div class="box-header with-border toolbar-split">
      <button class="btn btn-primary" onclick="pggTambah()">
        <i class="fa fa-plus"></i> Tambah Data
      </button>
    </div>
    <div class="box-body">
      <div class="table-responsive">
        <table id="example1" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>No</th><th>Nama</th><th>Username</th><th>Level</th><th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            $sql = $koneksi->query("SELECT * FROM tb_pengguna");
            while ($data = $sql->fetch_assoc()):
            ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= htmlspecialchars($data['nama_pengguna']) ?></td>
              <td><?= htmlspecialchars($data['username']) ?></td>
              <td><?= htmlspecialchars($data['level']) ?></td>
              <td>
                <button class="btn btn-success btn-sm" onclick="pggEdit('<?= $data['id_pengguna'] ?>')" title="Ubah">
                  <i class="fa fa-pencil"></i>
                </button>
                <a href="?page=MyApp/del_pengguna&kode=<?= $data['id_pengguna'] ?>"
                   onclick="return confirm('Yakin hapus data ini?')" class="btn btn-danger btn-sm" title="Hapus">
                  <i class="fa fa-trash"></i>
                </a>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

<!-- Modal Pengguna -->
<div class="modal fade" id="modalPgg" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-header-red">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="modalPggTitle">Tambah Pengguna</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" id="p_action" value="tambah">
        <input type="hidden" id="p_id" value="">
        <div class="form-group">
          <label>Nama Pengguna</label>
          <input type="text" id="p_nama" class="form-control" placeholder="Nama Pengguna">
        </div>
        <div class="form-group">
          <label>Username</label>
          <input type="text" id="p_uname" class="form-control" placeholder="Username">
        </div>
        <div class="form-group">
          <label>Password <small id="p_pass_hint" style="color:#999;display:none;">(kosongkan jika tidak diubah)</small></label>
          <input type="password" id="p_pass" class="form-control" placeholder="Password">
        </div>
        <div class="form-group">
          <label>Level</label>
          <select id="p_level" class="form-control">
            <option value="">-- Pilih Level --</option>
            <option value="Administrator">Administrator</option>
            <option value="Petugas">Petugas</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
        <button class="btn btn-primary" onclick="pggSimpan()"><i class="fa fa-save"></i> Simpan</button>
      </div>
    </div>
  </div>
</div>

<script>
function pggTambah() {
  $('#modalPggTitle').text('Tambah Pengguna');
  $('#p_action').val('tambah');
  $('#p_id,#p_nama,#p_uname,#p_pass').val('');
  $('#p_level').val('');
  $('#p_pass_hint').hide();
  $('#modalPgg').modal('show');
}

function pggEdit(id) {
  $('#modalPggTitle').text('Ubah Pengguna');
  $('#p_action').val('ubah');
  $('#p_pass').val('');
  $('#p_pass_hint').show();
  $.get('admin/pengguna/handler_pengguna.php', {action:'get', id:id}, function(r){
    $('#p_id').val(r.id_pengguna);
    $('#p_nama').val(r.nama_pengguna);
    $('#p_uname').val(r.username);
    $('#p_level').val(r.level);
  }, 'json');
  $('#modalPgg').modal('show');
}

function pggSimpan() {
  $.post('admin/pengguna/handler_pengguna.php', {
    action:        $('#p_action').val(),
    id_pengguna:   $('#p_id').val(),
    nama_pengguna: $('#p_nama').val(),
    username:      $('#p_uname').val(),
    password:      $('#p_pass').val(),
    level:         $('#p_level').val()
  }, function(r) {
    if (r.ok) {
      $('#modalPgg').modal('hide');
      Swal.fire({title:'Berhasil!', icon:'success', timer:1200, showConfirmButton:false})
        .then(function(){ window.location.reload(); });
    } else {
      Swal.fire({title:'Gagal menyimpan data', icon:'error', confirmButtonColor:'#8b1a1a'});
    }
  }, 'json');
}
</script>
