<?php

declare(strict_types=1);

require_once __DIR__ . '/../inc/koneksi.php';

$csvFile = __DIR__ . '/../Daftar Nominatif Juli 2026 (1).csv';

if (!file_exists($csvFile)) {
    fwrite(STDERR, "File CSV nominatif tidak ditemukan.\n");
    exit(1);
}

function normalize_name(string $value): string
{
    $value = strtolower(trim($value));
    $value = str_replace(
        ["\t", "\r", "\n", '"', "'", '`'],
        ' ',
        $value
    );
    $value = preg_replace('/[^a-z0-9]+/i', ' ', $value);
    $value = preg_replace('/\s+/', ' ', (string) $value);
    return trim((string) $value);
}

function read_nominatif_records(string $csvFile): array
{
    $handle = fopen($csvFile, 'r');
    if (!$handle) {
        throw new RuntimeException('CSV nominatif tidak bisa dibuka.');
    }

    $records = [];
    $current = null;

    while (($row = fgetcsv($handle, 0, ',')) !== false) {
        $row = array_map(static function ($value) {
            return trim((string) iconv('CP1252', 'UTF-8//IGNORE', (string) $value));
        }, $row);

        $row = array_pad($row, 12, '');
        $col0 = $row[0];

        if (ctype_digit($col0)) {
            if ($current && $current['name'] !== '' && $current['name'] !== '2') {
                $records[] = $current;
            }

            $current = [
                'no' => $col0,
                'name' => $row[1],
                'pangkat_gol' => $row[2],
                'nip' => '',
            ];
            continue;
        }

        if ($current === null) {
            continue;
        }

        if ($row[1] !== '' && $current['nip'] === '') {
            $current['nip'] = $row[2];
        }
    }

    fclose($handle);

    if ($current && $current['name'] !== '' && $current['name'] !== '2') {
        $records[] = $current;
    }

    $mapped = [];
    foreach ($records as $record) {
        $key = normalize_name($record['name']);
        if ($key === '') {
            continue;
        }

        $mapped[$key] = [
            'name' => $record['name'],
            'pangkat_gol' => trim($record['pangkat_gol']) !== '' ? trim($record['pangkat_gol']) : '-',
            'nip' => trim($record['nip']) !== '' ? trim($record['nip']) : '-',
        ];
    }

    return $mapped;
}

try {
    mysqli_set_charset($koneksi, 'utf8mb4');
    $nominatif = read_nominatif_records($csvFile);
} catch (Throwable $e) {
    fwrite(STDERR, $e->getMessage() . "\n");
    exit(1);
}

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
        if ($key === '' || !isset($nominatif[$key])) {
            continue;
        }

        $item = $nominatif[$key];
        $id = mysqli_real_escape_string($koneksi, (string) $row['id_anggota']);
        $pangkat = mysqli_real_escape_string($koneksi, $item['pangkat_gol']);
        $nip = mysqli_real_escape_string($koneksi, $item['nip']);

        $ok = mysqli_query(
            $koneksi,
            "UPDATE tb_anggota
             SET pangkat_gol='$pangkat', nip='$nip'
             WHERE id_anggota='$id'"
        );

        if (!$ok) {
            throw new RuntimeException('Gagal update anggota ' . $row['id_anggota']);
        }

        $updated++;
    }

    mysqli_commit($koneksi);
    echo "Berhasil update {$updated} data anggota dari file nominatif.\n";
    exit(0);
} catch (Throwable $e) {
    mysqli_rollback($koneksi);
    fwrite(STDERR, $e->getMessage() . "\n");
    exit(1);
}
