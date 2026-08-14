<?php

declare(strict_types=1);

require_once __DIR__ . '/../inc/koneksi.php';

mysqli_set_charset($koneksi, 'utf8mb4');

function guess_legacy_nip(string $rawValue): string
{
    $digits = preg_replace('/\D+/', '', $rawValue);
    if ($digits === null) {
        return '-';
    }

    if (preg_match('/^(19|20)\d{16}$/', $digits) === 1) {
        return $digits;
    }

    return '-';
}

$sql = mysqli_query($koneksi, "SELECT id_anggota, nip, no_hp FROM tb_anggota ORDER BY id_anggota ASC");

if (!$sql) {
    fwrite(STDERR, "Gagal membaca data anggota.\n");
    exit(1);
}

mysqli_begin_transaction($koneksi);

try {
    $updated = 0;
    while ($row = mysqli_fetch_assoc($sql)) {
        $currentNip = trim((string) ($row['nip'] ?? ''));
        $suggestedNip = guess_legacy_nip((string) ($row['no_hp'] ?? ''));

        if ($currentNip === (string) $row['id_anggota'] || $currentNip === '' || $currentNip === '-') {
            $newNip = $suggestedNip;
            $idAnggota = mysqli_real_escape_string($koneksi, (string) $row['id_anggota']);
            $newNipEscaped = mysqli_real_escape_string($koneksi, $newNip);

            if (!mysqli_query($koneksi, "UPDATE tb_anggota SET nip='$newNipEscaped' WHERE id_anggota='$idAnggota'")) {
                throw new RuntimeException('Gagal update anggota ' . $row['id_anggota']);
            }

            $updated++;
        }
    }

    mysqli_commit($koneksi);
    echo "Berhasil membetulkan kolom NIP untuk {$updated} data anggota.\n";
    echo "NIP yang tidak terbaca valid dibiarkan '-'.\n";
    exit(0);
} catch (Throwable $e) {
    mysqli_rollback($koneksi);
    fwrite(STDERR, $e->getMessage() . "\n");
    exit(1);
}
