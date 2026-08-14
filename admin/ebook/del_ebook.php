<?php
$kode = mysqli_real_escape_string($koneksi, $_GET['kode'] ?? '');
$query = mysqli_query($koneksi, "SELECT file_path FROM tb_ebook WHERE id_ebook='$kode' LIMIT 1");
$row = $query ? mysqli_fetch_assoc($query) : null;
$ok = mysqli_query($koneksi, "DELETE FROM tb_ebook WHERE id_ebook='$kode'");

if ($ok && !empty($row['file_path'])) {
    $normalized = str_replace('\\', '/', $row['file_path']);
    if (strpos($normalized, 'uploads/ebooks/') === 0) {
        $fullPath = __DIR__ . '/../../' . $normalized;
        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }
}
?>
<script>
<?php if ($ok): ?>
Swal.fire({title:'Berhasil!', text:'Data e-book berhasil dihapus.', icon:'success', confirmButtonColor:'#8b1a1a'})
  .then(function(){ window.location='index.php?page=MyApp/data_ebook'; });
<?php else: ?>
Swal.fire({title:'Gagal!', text:'Data e-book gagal dihapus.', icon:'error', confirmButtonColor:'#8b1a1a'})
  .then(function(){ window.location='index.php?page=MyApp/data_ebook'; });
<?php endif; ?>
</script>
