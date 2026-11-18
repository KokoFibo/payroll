<?php

/**
 * Tambahkan entri ini ke dalam array return di config/services.php:
 *
 *     'presensi' => [
 *         'url'     => env('PRESENSI_API_URL'),
 *         'token'   => env('PRESENSI_API_TOKEN'),
 *         'timeout' => (int) env('PRESENSI_API_TIMEOUT', 15),
 *     ],
 *
 * Lalu isi .env (JANGAN di-commit ke Git):
 *
 *     PRESENSI_API_URL=https://presensidb.yifang.co.id
 *     PRESENSI_API_TOKEN=<token BARU dari server presensi>
 *     PRESENSI_API_TIMEOUT=15
 *
 * 'token' dibaca oleh createUser() DAN createPresensiAccount(). 'url' dan 'timeout' hanya dipakai
 * createPresensiAccount(); createUser() tetap memakai URL yang tertulis di fungsinya.
 * db_code TIDAK diatur di sini: berasal dari APPLICANT_COMPANY_CODES (config/applicant_admin.php).
 *
 * Untuk uji lokal, PRESENSI_API_URL=http://127.0.0.1:8000 (menggantikan baris yang dikomentari di createUser() lama).
 * Setelah mengubah .env: php artisan config:clear
 */

return [
    'presensi' => [
        'url'     => env('PRESENSI_API_URL'),
        'token'   => env('PRESENSI_API_TOKEN'),
        'timeout' => (int) env('PRESENSI_API_TIMEOUT', 15),
    ],
];
