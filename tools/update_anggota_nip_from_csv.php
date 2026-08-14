<?php

declare(strict_types=1);

require_once __DIR__ . '/../inc/koneksi.php';

$csvFile = __DIR__ . '/../tmp_nip_mapping.csv';

if (!file_exists($csvFile)) {
    fwrite(STDERR, "File mapping CSV tidak ditemukan.\n");
    exit(1);
}

function normalize_name(string $value): string
{
    $value = strtolower(trim($value));
    $value = str_replace(["\t", "\r", "\n", '"', "'", '`'], ' ', $value);
    $value = preg_replace('/[^a-z0-9]+/i', ' ', $value);
    $value = preg_replace('/\s+/', ' ', (string) $value);
    return trim((string) $value);
}

$handle = fopen($csvFile, 'r');
if (!$handle) {
    fwrite(STDERR, "CSV mapping tidak bisa dibuka.\n");
    exit(1);
}

$header = fgetcsv($handle);
if ($header === false) {
    fclose($handle);
    fwrite(STDERR, "CSV mapping kosong.\n");
    exit(1);
}

$mapping = [];
while (($row = fgetcsv($handle)) !== false) {
    $row = array_pad($row, 2, '');
    $name = trim((string) $row[0]);
    $nip = trim((string) $row[1]);

    if ($name === '' || $nip === '') {
        continue;
    }

    $mapping[normalize_name($name)] = $nip;
}
fclose($handle);

mysqli_set_charset($koneksi, 'utf8mb4');

$sql = mysqli_query($koneksi, "SELECT id_anggota, nama FROM tb_anggota ORDER BY id_anggota ASC");
if (!$sql) {
    fwrite(STDERR, "Gagal membaca data anggota.\n");
    exit(1);
}

mysqli_begin_transaction($koneksi);

try {
    $updated = 0;

    while ($row = mysqli_fetch_assoc($sql)) {
        $key = normalize_name((string) $row['nama']);
        if ($key === '' || !isset($mapping[$key])) {
            continue;
        }

        $nip = mysqli_real_escape_string($koneksi, $mapping[$key]);
        $id = mysqli_real_escape_string($koneksi, (string) $row['id_anggota']);

        if (!mysqli_query($koneksi, "UPDATE tb_anggota SET nip='$nip' WHERE id_anggota='$id'")) {
            throw new RuntimeException('Gagal update NIP anggota ' . $row['id_anggota']);
        }

        $updated++;
    }

    mysqli_commit($koneksi);
    echo "Berhasil update {$updated} data NIP anggota dari CSV mapping.\n";
    exit(0);
} catch (Throwable $e) {
    mysqli_rollback($koneksi);
    fwrite(STDERR, $e->getMessage() . "\n");
    exit(1);
}
