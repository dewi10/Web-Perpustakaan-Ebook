<?php

declare(strict_types=1);

require_once __DIR__ . '/../inc/koneksi.php';

$sourceFile = __DIR__ . '/../import_anggota_20260805.sql';

if (!file_exists($sourceFile)) {
    fwrite(STDERR, "File sumber tidak ditemukan: {$sourceFile}\n");
    exit(1);
}

$sqlDump = file($sourceFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

if ($sqlDump === false) {
    fwrite(STDERR, "Gagal membaca file sumber.\n");
    exit(1);
}

mysqli_set_charset($koneksi, 'utf8mb4');

$pattern = "/INSERT INTO tb_anggota \\(id_anggota, nama, jekel, kelas, no_hp\\) VALUES \\('((?:[^'\\\\]|\\\\.)*)','((?:[^'\\\\]|\\\\.)*)','((?:[^'\\\\]|\\\\.)*)','((?:[^'\\\\]|\\\\.)*)','((?:[^'\\\\]|\\\\.)*)'\\);/";

$parsedRows = [];

function guess_legacy_nip(string $rawValue): string
{
    $digits = preg_replace('/\D+/', '', $rawValue);
    if ($digits === null) {
        return '-';
    }

    // NIP PNS umumnya 18 digit dan diawali tahun lahir.
    if (preg_match('/^(19|20)\d{16}$/', $digits) === 1) {
        return $digits;
    }

    return '-';
}

foreach ($sqlDump as $line) {
    $line = trim($line);
    if (!preg_match($pattern, $line, $match)) {
        continue;
    }

    $idAnggota = stripcslashes($match[1]);
    $nama = stripcslashes($match[2]);
    $jekel = stripcslashes($match[3]);
    $kelasRaw = stripcslashes($match[4]);
    $noHp = stripcslashes($match[5]);

    $segments = array_values(array_filter(array_map('trim', explode('|', $kelasRaw)), static function ($value) {
        return $value !== '';
    }));

    if (count($segments) >= 2) {
        $pangkatGol = array_pop($segments);
        $kelas = implode(' | ', $segments);
    } else {
        $kelas = $kelasRaw;
        $pangkatGol = '-';
    }

    $nip = guess_legacy_nip($noHp);

    $parsedRows[] = [
        'id_anggota' => $idAnggota,
        'nama' => $nama,
        'jekel' => $jekel !== '' ? $jekel : '-',
        'kelas' => $kelas,
        'pangkat_gol' => $pangkatGol,
        'nip' => $nip,
        'no_hp' => $noHp,
    ];
}

if ($parsedRows === []) {
    fwrite(STDERR, "Tidak ada baris anggota yang berhasil diparsing.\n");
    exit(1);
}

mysqli_begin_transaction($koneksi);

try {
    $stmt = mysqli_prepare(
        $koneksi,
        "INSERT INTO tb_anggota (id_anggota, nama, jekel, kelas, pangkat_gol, nip, no_hp)
         VALUES (?, ?, ?, ?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE
           nama = VALUES(nama),
           jekel = VALUES(jekel),
           kelas = VALUES(kelas),
           pangkat_gol = VALUES(pangkat_gol),
           nip = VALUES(nip),
           no_hp = VALUES(no_hp)"
    );

    if (!$stmt) {
        throw new RuntimeException('Gagal menyiapkan statement impor.');
    }

    foreach ($parsedRows as $row) {
        mysqli_stmt_bind_param(
            $stmt,
            'sssssss',
            $row['id_anggota'],
            $row['nama'],
            $row['jekel'],
            $row['kelas'],
            $row['pangkat_gol'],
            $row['nip'],
            $row['no_hp']
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new RuntimeException('Gagal mengimpor anggota ID ' . $row['id_anggota'] . ': ' . mysqli_stmt_error($stmt));
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_commit($koneksi);

    echo 'Berhasil impor ulang ' . count($parsedRows) . " data anggota.\n";
    echo "Catatan: kolom nip hanya diisi jika field lama terdeteksi sebagai NIP 18 digit.\n";
    exit(0);
} catch (Throwable $e) {
    mysqli_rollback($koneksi);
    fwrite(STDERR, $e->getMessage() . "\n");
    exit(1);
}
