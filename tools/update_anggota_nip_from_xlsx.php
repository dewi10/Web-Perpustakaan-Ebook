<?php

declare(strict_types=1);

require_once __DIR__ . '/../inc/koneksi.php';

$xlsxFile = __DIR__ . '/../Daftar Nominatif Juli 2026 (1).xlsx';

if (!file_exists($xlsxFile)) {
    fwrite(STDERR, "File XLSX nominatif tidak ditemukan.\n");
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

function column_letters(string $cellRef): string
{
    if (preg_match('/^[A-Z]+/', $cellRef, $matches) !== 1) {
        return '';
    }

    return $matches[0];
}

function read_shared_strings(string $xlsxFile): array
{
    $command = 'unzip -p ' . escapeshellarg($xlsxFile) . ' xl/sharedStrings.xml';
    $xmlContent = shell_exec($command);

    if (!is_string($xmlContent) || trim($xmlContent) === '') {
        throw new RuntimeException('Gagal membaca shared strings dari file XLSX.');
    }

    $xml = simplexml_load_string($xmlContent);
    if ($xml === false) {
        throw new RuntimeException('Shared strings XLSX tidak valid.');
    }

    $xml->registerXPathNamespace('a', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');

    $sharedStrings = [];
    foreach ($xml->xpath('//a:si') as $item) {
        $texts = $item->xpath('.//a:t');
        $value = '';
        foreach ($texts as $text) {
            $value .= (string) $text;
        }
        $sharedStrings[] = trim($value);
    }

    return $sharedStrings;
}

function read_sheet_rows(string $xlsxFile, string $sheetPath, array $sharedStrings): array
{
    $command = 'unzip -p ' . escapeshellarg($xlsxFile) . ' ' . escapeshellarg($sheetPath);
    $xmlContent = shell_exec($command);

    if (!is_string($xmlContent) || trim($xmlContent) === '') {
        throw new RuntimeException("Gagal membaca {$sheetPath} dari file XLSX.");
    }

    $xml = simplexml_load_string($xmlContent);
    if ($xml === false) {
        throw new RuntimeException("XML {$sheetPath} tidak valid.");
    }

    $xml->registerXPathNamespace('a', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');

    $rows = [];
    foreach ($xml->xpath('//a:sheetData/a:row') as $row) {
        $cells = [];
        foreach ($row->xpath('./a:c') as $cell) {
            $ref = (string) $cell['r'];
            $col = column_letters($ref);
            $type = (string) $cell['t'];
            $value = '';

            if (isset($cell->v)) {
                $raw = (string) $cell->v;
                if ($type === 's' && isset($sharedStrings[(int) $raw])) {
                    $value = $sharedStrings[(int) $raw];
                } else {
                    $value = $raw;
                }
            } elseif (isset($cell->is->t)) {
                $value = (string) $cell->is->t;
            }

            $cells[$col] = trim($value);
        }

        $rows[] = $cells;
    }

    return $rows;
}

function extract_name_nip_map(array $rows): array
{
    $map = [];
    $currentName = '';
    $currentNo = '';

    foreach ($rows as $cells) {
        $colA = trim((string) ($cells['A'] ?? ''));
        $colB = trim((string) ($cells['B'] ?? ''));
        $colC = trim((string) ($cells['C'] ?? ''));

        if ($colA !== '' && ctype_digit($colA) && $colB !== '' && $colB !== '2') {
            $currentNo = $colA;
            $currentName = $colB;
            continue;
        }

        if ($currentName === '') {
            continue;
        }

        if ($colA === '' && $colC !== '' && !in_array($colC, ['3', 'PKT/NRP', 'NRP/NIP', 'TMT'], true)) {
            $map[normalize_name($currentName)] = [
                'source_no' => $currentNo,
                'name' => $currentName,
                'nip' => $colC,
            ];
            $currentName = '';
            $currentNo = '';
        }
    }

    return $map;
}

try {
    mysqli_set_charset($koneksi, 'utf8mb4');
    $sharedStrings = read_shared_strings($xlsxFile);
    $sheet1Rows = read_sheet_rows($xlsxFile, 'xl/worksheets/sheet1.xml', $sharedStrings);
    $sheet2Rows = read_sheet_rows($xlsxFile, 'xl/worksheets/sheet2.xml', $sharedStrings);
    $nipMap = array_merge(
        extract_name_nip_map($sheet1Rows),
        extract_name_nip_map($sheet2Rows)
    );
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
        if ($key === '' || !isset($nipMap[$key])) {
            continue;
        }

        $nip = mysqli_real_escape_string($koneksi, $nipMap[$key]['nip']);
        $id = mysqli_real_escape_string($koneksi, (string) $row['id_anggota']);

        $ok = mysqli_query($koneksi, "UPDATE tb_anggota SET nip='$nip' WHERE id_anggota='$id'");
        if (!$ok) {
            throw new RuntimeException('Gagal update NIP anggota ' . $row['id_anggota']);
        }

        $updated++;
    }

    mysqli_commit($koneksi);
    echo "Berhasil update {$updated} NIP anggota dari Sheet1 dan Sheet2.\n";
    exit(0);
} catch (Throwable $e) {
    mysqli_rollback($koneksi);
    fwrite(STDERR, $e->getMessage() . "\n");
    exit(1);
}
