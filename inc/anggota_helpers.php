<?php

function format_jabatan_unit($jabatanUnit, $pangkatGol = '') {
    $jabatanUnit = trim((string) $jabatanUnit);
    $pangkatGol = trim((string) $pangkatGol);

    if ($jabatanUnit === '') {
        return '-';
    }

    $parts = array_values(array_filter(array_map('trim', explode('|', $jabatanUnit)), function ($value) {
        return $value !== '';
    }));

    $parts = array_values(array_filter($parts, function ($value) {
        return !preg_match('/^\d{2}-\d{2}-\d{4}$/', $value);
    }));

    if ($pangkatGol !== '' && $pangkatGol !== '-' && count($parts) > 1) {
        $lastPart = $parts[count($parts) - 1];
        if (strcasecmp($lastPart, $pangkatGol) === 0) {
            array_pop($parts);
        }
    }

    return !empty($parts) ? implode(' | ', $parts) : $jabatanUnit;
}
