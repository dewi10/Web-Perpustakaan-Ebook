<?php
include_once __DIR__ . '/../../inc/buku_helpers.php';
if (!function_exists('sirkul_extension_count')) {
    function sirkul_extension_count($tglPinjam, $tglKembali)
    {
        $pinjam = strtotime((string) $tglPinjam);
        $kembali = strtotime((string) $tglKembali);
        if (!$pinjam || !$kembali) {
            return 0;
        }

        $selisihHari = (int) floor(($kembali - $pinjam) / 86400);
        $kelebihan = max(0, $selisihHari - 14);
        return (int) floor($kelebihan / 7);
    }
}
// Ambil data untuk dropdown di modal
$opt_anggota = '';
$r = $koneksi->query("SELECT id_anggota, nama FROM tb_anggota ORDER BY nama");
while ($row = $r->fetch_assoc()) {
    $opt_anggota .= "<option value='{$row['id_anggota']}'>{$row['id_anggota']} - {$row['nama']}</option>";
}

$opt_buku = '';
$r = $koneksi->query("SELECT id_buku, seri_buku, judul_buku, jumlah FROM tb_buku WHERE jumlah > 0 ORDER BY seri_buku, judul_buku");
while ($row = $r->fetch_assoc()) {
    $judulBuku = htmlspecialchars(format_judul_buku($row['seri_buku'], $row['judul_buku']), ENT_QUOTES, 'UTF-8');
    $opt_buku .= "<option value='{$row['id_buku']}'>{$row['id_buku']} - {$judulBuku} (Stok: {$row['jumlah']})</option>";
}
?>

<style>
.modal-header-red { background: linear-gradient(135deg,#8b1a1a,#a82020); color:#fff; }
.modal-header-red .close { color:#fff; opacity:1; text-shadow:none; font-size:22px; }
.modal-header-red .modal-title { font-weight:700; font-size:16px; }
.col-aksi {
  min-width: 130px;
  width: 130px;
  text-align: center;
}
.aksi-wrap {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  flex-wrap: wrap;
}
.toolbar-btn {
  border: 0;
  color: #fff !important;
}
.toolbar-btn.btn-add { background: #14804a; }
.toolbar-btn.btn-print-all { background: #d97706; }
.toolbar-btn.btn-print-user { background: #0f766e; }
</style>

<section class="content-header">
  <h1>Sirkulasi <small>Buku</small></h1>
  <ol class="breadcrumb">
    <li><a href="index.php"><i class="fa fa-home"></i> SI Perpustakaan</a></li>
    <li class="active">Sirkulasi</li>
  </ol>
</section>

<section class="content">
  <div class="box box-primary">
    <div class="box-header with-border toolbar-split">
      <button class="btn toolbar-btn btn-add" onclick="sirkulTambah()">
        <i class="fa fa-plus"></i> Tambah Peminjaman
      </button>
      <a href="admin/sirkul/print_sirkul.php" class="btn toolbar-btn btn-print-all" target="_blank">
        <i class="fa fa-print"></i> Print Semua
      </a>
      <button class="btn toolbar-btn btn-print-user" onclick="sirkulPrintUser()">
        <i class="fa fa-user"></i> Print per Peminjam
      </button>
    </div>
    <div class="box-body">
      <div class="table-responsive">
        <table id="example1" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>No</th><th>ID SKL</th><th>Buku</th><th>Peminjam</th>
              <th>Tgl Pinjam</th><th>Jatuh Tempo</th><th>Denda</th><th class="col-aksi">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            $sql = $koneksi->query("SELECT s.id_sk, b.seri_buku, b.judul_buku, b.jumlah, a.id_anggota, a.nama, s.tgl_pinjam, s.tgl_kembali
              FROM tb_sirkulasi s
              INNER JOIN tb_buku b ON s.id_buku = b.id_buku
              INNER JOIN tb_anggota a ON s.id_anggota = a.id_anggota
              WHERE s.status='PIN' ORDER BY s.tgl_pinjam DESC");
            while ($data = $sql->fetch_assoc()):
              $jd1 = GregorianToJD(date('m'), date('d'), date('Y'));
              $jd2 = GregorianToJD(
                (int)substr($data['tgl_kembali'],5,2),
                (int)substr($data['tgl_kembali'],8,2),
                (int)substr($data['tgl_kembali'],0,4)
              );
              $selisih = $jd1 - $jd2;
              $denda   = $selisih * 1000;
              $jumlahPerpanjang = sirkul_extension_count($data['tgl_pinjam'] ?? '', $data['tgl_kembali'] ?? '');
              $bisaPerpanjang = $selisih <= 0 && $jumlahPerpanjang < 2;
            ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= $data['id_sk'] ?></td>
              <td><?= htmlspecialchars(format_judul_buku($data['seri_buku'], $data['judul_buku'])) ?><br><small>Sisa stok: <?= (int) $data['jumlah'] ?></small></td>
              <td><?= $data['id_anggota'] ?> - <?= htmlspecialchars($data['nama']) ?></td>
              <td><?= date('d/M/Y', strtotime($data['tgl_pinjam'])) ?></td>
              <td><?= date('d/M/Y', strtotime($data['tgl_kembali'])) ?></td>
              <td>
                <?php if ($selisih <= 0): ?>
                  <span class="label label-primary">Masa Peminjaman</span>
                <?php else: ?>
                  <span class="label label-danger">Rp. <?= number_format($denda,0,',','.') ?></span>
                  <br><small>Terlambat <?= $selisih ?> hari</small>
                <?php endif; ?>
              </td>
              <td class="col-aksi">
                <div class="aksi-wrap">
                <a href="?page=panjang&kode=<?= $data['id_sk'] ?>"
                   onclick="<?= $bisaPerpanjang ? "return confirm('Perpanjang peminjaman ini 7 hari?')" : "alert('Perpanjangan hanya bisa sebelum jatuh tempo dan maksimal 2 kali.'); return false;" ?>" class="btn btn-success btn-sm" title="Perpanjang">
                  <i class="fa fa-upload"></i>
                </a>
                <a href="?page=kembali&kode=<?= $data['id_sk'] ?>"
                   onclick="return confirm('Kembalikan buku ini?')" class="btn btn-danger btn-sm" title="Kembalikan">
                  <i class="fa fa-download"></i>
                </a>
                </div>
                <small style="display:block;margin-top:6px;color:#64748b;">Perpanjang: <?= $jumlahPerpanjang ?>/2</small>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <h4>*Note<br>
    Masa peminjaman <strong style="color:red;">14 hari</strong> dari tanggal pinjam.
    Perpanjangan hanya bisa <strong style="color:red;">sebelum jatuh tempo</strong>, durasi <strong style="color:red;">7 hari</strong> per klik, maksimal <strong style="color:red;">2 kali</strong> per transaksi.
    Keterlambatan dikenakan denda <strong style="color:red;">Rp 1.000/hari</strong>.
  </h4>
</section>

<!-- Modal Sirkulasi -->
<div class="modal fade" id="modalSirkul" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-header-red">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tambah Peminjaman</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>ID Sirkulasi</label>
          <input type="text" id="sk_id" class="form-control" readonly>
        </div>
        <div class="form-group">
          <label>Nama Peminjam</label>
          <select id="sk_agt" class="form-control select2" style="width:100%;">
            <option value="">-- Pilih Anggota --</option>
            <?= $opt_anggota ?>
          </select>
        </div>
        <div class="form-group">
          <label>Buku</label>
          <select id="sk_buku" class="form-control select2" style="width:100%;">
            <option value="">-- Pilih Buku --</option>
            <?= $opt_buku ?>
          </select>
        </div>
        <div class="form-group">
          <label>Tanggal Pinjam</label>
          <input type="date" id="sk_tgl" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
        <button class="btn btn-primary" onclick="sirkulSimpan()"><i class="fa fa-save"></i> Simpan</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalPrintUser" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-header-red">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Print Sirkulasi per Peminjam</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Pilih Peminjam</label>
          <select id="print_agt" class="form-control select2" style="width:100%;">
            <option value="">-- Pilih Anggota --</option>
            <?= $opt_anggota ?>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-default" data-dismiss="modal" type="button"><i class="fa fa-times"></i> Batal</button>
        <button class="btn btn-primary" type="button" onclick="goPrintUser()"><i class="fa fa-print"></i> Print</button>
      </div>
    </div>
  </div>
</div>

<script>
function sirkulPrintUser() {
  $('#print_agt').val('').trigger('change');
  $('#modalPrintUser').modal('show');
}

function goPrintUser() {
  var anggota = $('#print_agt').val();
  if (!anggota) {
    Swal.fire({title:'Pilih peminjam dulu', icon:'warning', confirmButtonColor:'#8b1a1a'});
    return;
  }
  window.open('admin/sirkul/print_sirkul.php?id_anggota=' + encodeURIComponent(anggota), '_blank');
  $('#modalPrintUser').modal('hide');
}

function sirkulTambah() {
  $('#sk_agt,#sk_buku').val('').trigger('change');
  $('#sk_tgl').val('');
  $.get('admin/sirkul/handler_sirkul.php', {action:'next_id'}, function(r){ $('#sk_id').val(r.id); }, 'json');
  $('#modalSirkul').modal('show');
}

function sirkulSimpan() {
  $.post('admin/sirkul/handler_sirkul.php', {
    action:     'tambah',
    id_sk:      $('#sk_id').val(),
    id_anggota: $('#sk_agt').val(),
    id_buku:    $('#sk_buku').val(),
    tgl_pinjam: $('#sk_tgl').val()
  }, function(r) {
    if (r.ok) {
      $('#modalSirkul').modal('hide');
      Swal.fire({title:'Berhasil!', icon:'success', timer:1200, showConfirmButton:false})
        .then(function(){ window.location.reload(); });
    } else {
      Swal.fire({title: r.message || 'Gagal menyimpan data', icon:'error', confirmButtonColor:'#8b1a1a'});
    }
  }, 'json');
}

// Init Select2 saat modal terbuka
$('#modalSirkul').on('shown.bs.modal', function() {
  $(this).find('.select2').select2({ dropdownParent: $('#modalSirkul') });
});

$('#modalPrintUser').on('shown.bs.modal', function() {
  $(this).find('.select2').select2({ dropdownParent: $('#modalPrintUser') });
});
</script>
