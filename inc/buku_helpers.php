<?php

function format_judul_buku($seriBuku, $judulBuku) {
    $seriBuku = trim((string) $seriBuku);
    $judulBuku = trim((string) $judulBuku);

    if ($seriBuku === '') {
        return $judulBuku;
    }

    if ($judulBuku === '') {
        return $seriBuku;
    }

    return $seriBuku . ' - ' . $judulBuku;
}

function kode_buku_prefix($seriBuku, $judulBuku) {
    $baseText = trim((string) $seriBuku);
    if ($baseText === '') {
        $baseText = trim((string) $judulBuku);
    }

    $baseText = strtoupper($baseText);
    $baseText = preg_replace('/[^A-Z0-9]+/', ' ', $baseText);
    $words = preg_split('/\s+/', trim((string) $baseText));
    $words = array_values(array_filter($words, function ($word) {
        if ($word === '') {
            return false;
        }

        $ignoredWords = ['DAN', 'DI', 'KE', 'DARI', 'OF', 'THE', 'AND', 'UNTUK', 'PADA', 'TENTANG', 'YANG'];
        return !in_array($word, $ignoredWords, true);
    }));

    if (count($words) >= 2) {
        $prefix = '';
        foreach (array_slice($words, 0, 4) as $word) {
            $prefix .= substr($word, 0, 1);
        }

        return substr($prefix, 0, 4);
    }

    if (!empty($words)) {
        return substr(str_pad($words[0], 3, 'X'), 0, 3);
    }

    return 'BKU';
}

function kode_buku_info($seriBuku, $judulBuku) {
    $prefix = kode_buku_prefix($seriBuku, $judulBuku);

    return [
        'prefix' => $prefix,
        'label' => $prefix . '-001',
    ];
}

function generate_kode_buku($conn, $seriBuku, $judulBuku, $excludeIdBuku = '', $currentKodeBuku = '') {
    $prefix = kode_buku_prefix($seriBuku, $judulBuku);
    $escapedPrefix = mysqli_real_escape_string($conn, $prefix);
    $escapedExclude = mysqli_real_escape_string($conn, (string) $excludeIdBuku);
    $currentKodeBuku = trim((string) $currentKodeBuku);

    if ($currentKodeBuku !== '' && strpos($currentKodeBuku, $prefix . '-') === 0) {
        $escapedCurrent = mysqli_real_escape_string($conn, $currentKodeBuku);
        $existingCurrent = mysqli_query(
            $conn,
            "SELECT 1 FROM tb_buku WHERE id_buku = '" . $escapedExclude . "' AND kode_buku = '" . $escapedCurrent . "' LIMIT 1"
        );

        if ($existingCurrent && mysqli_num_rows($existingCurrent) > 0) {
            return $currentKodeBuku;
        }
    }

    $sql = "SELECT kode_buku FROM tb_buku WHERE kode_buku LIKE '" . $escapedPrefix . "-%'";
    if ($escapedExclude !== '') {
        $sql .= " AND id_buku <> '" . $escapedExclude . "'";
    }

    $result = mysqli_query($conn, $sql);
    $maxNumber = 0;

    while ($row = mysqli_fetch_assoc($result)) {
        if (preg_match('/-(\d+)$/', (string) $row['kode_buku'], $matches)) {
            $maxNumber = max($maxNumber, (int) $matches[1]);
        }
    }

    return $prefix . '-' . str_pad((string) ($maxNumber + 1), 3, '0', STR_PAD_LEFT);
}
