<?php

declare(strict_types=1);

require_once __DIR__ . '/../inc/koneksi.php';

mysqli_set_charset($koneksi, 'utf8mb4');

$members = [
    [
        'nama' => 'Wagiman',
        'jekel' => 'Laki-laki',
        'kelas' => 'Penyusun Bahan Ketatausahaan Subbag TU Puskod Baloghan Kemhan',
        'pangkat_gol' => 'Penata III/c',
        'nip' => '196002081982021001',
        'no_hp' => '-',
    ],
    [
        'nama' => 'Sutono',
        'jekel' => 'Laki-laki',
        'kelas' => 'Pengolah Bahan Evlap Subbid Tala Bidrenminkod Puskod Baloghan Kemhan',
        'pangkat_gol' => 'Penda Tk.I III/b',
        'nip' => '197004161994031001',
        'no_hp' => '-',
    ],
];

function next_member_id(mysqli $conn): string
{
    $result = mysqli_query($conn, "SELECT MAX(CAST(SUBSTRING(id_anggota,2) AS UNSIGNED)) AS last_id FROM tb_anggota");
    $row = $result ? mysqli_fetch_assoc($result) : null;
    $next = ((int) ($row['last_id'] ?? 0)) + 1;
    return 'A' . str_pad((string) $next, 3, '0', STR_PAD_LEFT);
}

mysqli_begin_transaction($koneksi);

try {
    $updated = 0;
    $inserted = 0;

    foreach ($members as $member) {
        $namaEscaped = mysqli_real_escape_string($koneksi, $member['nama']);
        $check = mysqli_query($koneksi, "SELECT id_anggota FROM tb_anggota WHERE nama='$namaEscaped' LIMIT 1");
        $existing = $check ? mysqli_fetch_assoc($check) : null;

        $jekel = mysqli_real_escape_string($koneksi, $member['jekel']);
        $kelas = mysqli_real_escape_string($koneksi, $member['kelas']);
        $pangkat = mysqli_real_escape_string($koneksi, $member['pangkat_gol']);
        $nip = mysqli_real_escape_string($koneksi, $member['nip']);
        $hp = mysqli_real_escape_string($koneksi, $member['no_hp']);

        if ($existing) {
            $id = mysqli_real_escape_string($koneksi, $existing['id_anggota']);
            $ok = mysqli_query(
                $koneksi,
                "UPDATE tb_anggota
                 SET jekel='$jekel', kelas='$kelas', pangkat_gol='$pangkat', nip='$nip', no_hp='$hp'
                 WHERE id_anggota='$id'"
            );
            if (!$ok) {
                throw new RuntimeException('Gagal update anggota ' . $member['nama']);
            }
            $updated++;
            continue;
        }

        $idBaru = mysqli_real_escape_string($koneksi, next_member_id($koneksi));
        $ok = mysqli_query(
            $koneksi,
            "INSERT INTO tb_anggota (id_anggota, nama, jekel, kelas, pangkat_gol, nip, no_hp)
             VALUES ('$idBaru', '$namaEscaped', '$jekel', '$kelas', '$pangkat', '$nip', '$hp')"
        );
        if (!$ok) {
            throw new RuntimeException('Gagal insert anggota ' . $member['nama']);
        }
        $inserted++;
    }

    mysqli_commit($koneksi);
    echo "Berhasil insert {$inserted} dan update {$updated} anggota.\n";
    exit(0);
} catch (Throwable $e) {
    mysqli_rollback($koneksi);
    fwrite(STDERR, $e->getMessage() . "\n");
    exit(1);
}
