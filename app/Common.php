<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

if (!function_exists('format_tanggal_indo')) {
    function format_tanggal_indo(?string $date, bool $longMonth = false, bool $withTime = false): string
    {
        if (empty($date)) {
            return '-';
        }

        $shortMonths = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $longMonths  = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $ts = strtotime($date);
        if ($ts === false) {
            return '-';
        }

        $monthMap  = $longMonth ? $longMonths : $shortMonths;
        $formatted = date('d', $ts) . ' ' . $monthMap[(int) date('n', $ts)] . ' ' . date('Y', $ts);

        return $withTime ? $formatted . ' ' . date('H:i', $ts) : $formatted;
    }
}
