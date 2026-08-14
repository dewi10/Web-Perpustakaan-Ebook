<?php
session_start();
include '../../inc/koneksi.php';
header('Content-Type: application/json');

$action = $_REQUEST['action'] ?? '';
$uploadDir = dirname(__DIR__, 2) . '/uploads/ebooks';
$uploadDirRelative = 'uploads/ebooks';

if (!is_dir($uploadDir)) {
    @mkdir($uploadDir, 0777, true);
}
@chmod(dirname($uploadDir), 0777);
@chmod($uploadDir, 0777);

function ebook_response($data)
{
    echo json_encode($data);
    exit;
}

function ebook_escape($value)
{
    return mysqli_real_escape_string($GLOBALS['koneksi'], trim((string) $value));
}

function ebook_year_value($value)
{
    $value = trim((string) $value);
    if ($value === '') {
        return 'NULL';
    }

    return "'" . ebook_escape($value) . "'";
}

function ebook_file_size_label($bytes)
{
    $bytes = (int) $bytes;
    if ($bytes <= 0) {
        return '-';
    }

    $units = ['B', 'KB', 'MB', 'GB'];
    $index = 0;
    $size = (float) $bytes;
    while ($size >= 1024 && $index < count($units) - 1) {
        $size /= 1024;
        $index++;
    }

    return number_format($size, $index === 0 ? 0 : 2, ',', '.') . ' ' . $units[$index];
}

function ebook_next_id()
{
    $result = mysqli_query($GLOBALS['koneksi'], "SELECT id_ebook FROM tb_ebook ORDER BY id_ebook DESC LIMIT 1");
    $row = $result ? mysqli_fetch_assoc($result) : null;
    $lastId = $row['id_ebook'] ?? 'E000';
    $number = (int) substr($lastId, 1) + 1;

    return 'E' . str_pad((string) $number, 3, '0', STR_PAD_LEFT);
}

function ebook_safe_file_path($storedPath)
{
    $storedPath = trim((string) $storedPath);
    if ($storedPath === '') {
        return '';
    }

    $normalized = str_replace('\\', '/', $storedPath);
    if (strpos($normalized, 'uploads/ebooks/') !== 0) {
        return '';
    }

    return dirname(__DIR__, 2) . '/' . $normalized;
}

function ebook_delete_file($storedPath)
{
    $path = ebook_safe_file_path($storedPath);
    if ($path !== '' && is_file($path)) {
        @unlink($path);
    }
}

function ebook_upload_file($fieldName)
{
    if (empty($_FILES[$fieldName]) || !is_array($_FILES[$fieldName])) {
        return ['ok' => true, 'uploaded' => false];
    }

    $file = $_FILES[$fieldName];
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return ['ok' => true, 'uploaded' => false];
    }

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        return ['ok' => false, 'message' => 'Upload file e-book gagal diproses.'];
    }

    $originalName = $file['name'] ?? '';
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    if ($extension !== 'pdf') {
        return ['ok' => false, 'message' => 'File e-book harus berformat PDF.'];
    }

    $size = (int) ($file['size'] ?? 0);
    if ($size <= 0) {
        return ['ok' => false, 'message' => 'Ukuran file e-book tidak valid.'];
    }

    if (!is_dir($GLOBALS['uploadDir'])) {
        @mkdir($GLOBALS['uploadDir'], 0777, true);
    }
    @chmod(dirname($GLOBALS['uploadDir']), 0777);
    @chmod($GLOBALS['uploadDir'], 0777);

    if (!is_writable($GLOBALS['uploadDir'])) {
        return ['ok' => false, 'message' => 'Folder upload e-book belum writable oleh server.'];
    }

    $newName = 'ebook_' . date('Ymd_His') . '_' . substr(md5(uniqid((string) mt_rand(), true)), 0, 10) . '.pdf';
    $target = $GLOBALS['uploadDir'] . '/' . $newName;

    $saved = move_uploaded_file($file['tmp_name'], $target);
    if (!$saved && is_uploaded_file($file['tmp_name'])) {
        $saved = @copy($file['tmp_name'], $target);
        if ($saved) {
            @unlink($file['tmp_name']);
        }
    }

    if (!$saved) {
        return ['ok' => false, 'message' => 'File e-book gagal disimpan ke server. Pastikan folder uploads/ebooks bisa ditulis server.'];
    }

    @chmod($target, 0666);

    return [
        'ok' => true,
        'uploaded' => true,
        'original_name' => $originalName,
        'stored_name' => $newName,
        'path' => $GLOBALS['uploadDirRelative'] . '/' . $newName,
        'size' => $size,
        'extension' => $extension,
    ];
}

if ($action === 'next_id') {
    ebook_response(['id' => ebook_next_id()]);
}

if ($action === 'get') {
    $id = ebook_escape($_GET['id'] ?? '');
    $sql = mysqli_query($koneksi, "SELECT * FROM tb_ebook WHERE id_ebook='$id'");
    $row = $sql ? mysqli_fetch_assoc($sql) : null;
    ebook_response($row ?: []);
}

if ($action === 'tambah' || $action === 'ubah') {
    $id = ebook_escape($_POST['id_ebook'] ?? '');
    $idBuku = ebook_escape($_POST['id_buku'] ?? '');
    $judul = ebook_escape($_POST['judul_ebook'] ?? '');
    $penulis = ebook_escape($_POST['penulis'] ?? '');
    $penerbit = ebook_escape($_POST['penerbit'] ?? '');
    $tahun = ebook_year_value($_POST['tahun_terbit'] ?? '');
    $kategori = ebook_escape($_POST['kategori'] ?? '');
    $deskripsi = ebook_escape($_POST['deskripsi'] ?? '');
    $url = trim((string) ($_POST['file_url'] ?? ''));
    $urlEscaped = ebook_escape($url);
    $statusAktif = ($_POST['status_aktif'] ?? '1') === '0' ? '0' : '1';

    if ($id === '' || $judul === '') {
        ebook_response(['ok' => false, 'message' => 'ID dan judul e-book wajib diisi.']);
    }

    $existing = null;
    if ($action === 'ubah') {
        $existingResult = mysqli_query($koneksi, "SELECT * FROM tb_ebook WHERE id_ebook='$id'");
        $existing = $existingResult ? mysqli_fetch_assoc($existingResult) : null;
        if (!$existing) {
            ebook_response(['ok' => false, 'message' => 'Data e-book tidak ditemukan.']);
        }
    }

    $upload = ebook_upload_file('file_ebook');
    if (!$upload['ok']) {
        ebook_response($upload);
    }

    $relatedBookValue = $idBuku === '' ? 'NULL' : "'" . $idBuku . "'";
    $sourceType = $upload['uploaded'] ? 'upload' : (($url !== '') ? 'url' : ($existing['sumber_file'] ?? 'upload'));
    $originalName = $upload['uploaded'] ? ebook_escape($upload['original_name']) : ebook_escape($existing['nama_file_asli'] ?? '');
    $storedName = $upload['uploaded'] ? ebook_escape($upload['stored_name']) : ebook_escape($existing['nama_file_simpan'] ?? '');
    $storedPath = $upload['uploaded'] ? ebook_escape($upload['path']) : ebook_escape($existing['file_path'] ?? '');
    $fileSize = $upload['uploaded'] ? (int) $upload['size'] : (int) ($existing['ukuran_file'] ?? 0);
    $extension = $upload['uploaded'] ? ebook_escape($upload['extension']) : ebook_escape($existing['ekstensi_file'] ?? 'pdf');
    $fileLabel = ebook_escape(ebook_file_size_label($fileSize));

    if ($sourceType === 'url' && $url === '') {
        ebook_response(['ok' => false, 'message' => 'Masukkan URL PDF atau upload file PDF.']);
    }

    if ($sourceType === 'upload' && $storedPath === '') {
        ebook_response(['ok' => false, 'message' => 'Upload file PDF untuk e-book ini.']);
    }

    if ($action === 'tambah') {
        $kodeEbook = 'EBK-' . date('Ymd') . '-' . substr($id, 1);
        $kodeEbook = ebook_escape($kodeEbook);
        $sql = "INSERT INTO tb_ebook (
                    id_ebook,id_buku,kode_ebook,judul_ebook,penulis,penerbit,tahun_terbit,kategori,deskripsi,
                    sumber_file,nama_file_asli,nama_file_simpan,file_path,file_url,ukuran_file,ukuran_label,ekstensi_file,status_aktif,created_at,updated_at
                ) VALUES (
                    '$id',$relatedBookValue,'$kodeEbook','$judul','$penulis','$penerbit',$tahun,'$kategori','$deskripsi',
                    '$sourceType','$originalName','$storedName','$storedPath','$urlEscaped',$fileSize,'$fileLabel','$extension','$statusAktif',NOW(),NOW()
                )";
        $ok = mysqli_query($koneksi, $sql);
        if (!$ok && $upload['uploaded']) {
            ebook_delete_file($upload['path']);
        }

        ebook_response([
            'ok' => (bool) $ok,
            'message' => $ok ? 'E-book berhasil ditambahkan.' : 'Gagal menambahkan data e-book.',
        ]);
    }

    if ($upload['uploaded'] && !empty($existing['file_path'])) {
        ebook_delete_file($existing['file_path']);
    }

    $sql = "UPDATE tb_ebook SET
                id_buku=$relatedBookValue,
                judul_ebook='$judul',
                penulis='$penulis',
                penerbit='$penerbit',
                tahun_terbit=$tahun,
                kategori='$kategori',
                deskripsi='$deskripsi',
                sumber_file='$sourceType',
                nama_file_asli='$originalName',
                nama_file_simpan='$storedName',
                file_path='$storedPath',
                file_url='$urlEscaped',
                ukuran_file=$fileSize,
                ukuran_label='$fileLabel',
                ekstensi_file='$extension',
                status_aktif='$statusAktif',
                updated_at=NOW()
            WHERE id_ebook='$id'";
    $ok = mysqli_query($koneksi, $sql);

    ebook_response([
        'ok' => (bool) $ok,
        'message' => $ok ? 'E-book berhasil diperbarui.' : 'Gagal memperbarui data e-book.',
    ]);
}

ebook_response(['ok' => false, 'message' => 'Aksi tidak dikenali.']);
