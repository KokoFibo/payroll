<?php

/**
 * Tambahkan entri ini ke dalam array return di config/services.php:
 *
 *     'presensi' => [
 *         'url'     => env('PRESENSI_API_URL'),
 *         'token'   => env('PRESENSI_API_TOKEN'),
 *         'db_code' => env('PRESENSI_DB_CODE'),
 *         'timeout' => (int) env('PRESENSI_API_TIMEOUT', 15),
 *     ],
 *
 * Lalu isi .env (JANGAN di-commit ke Git):
 *
 *     PRESENSI_API_URL=https://presensidb.yifang.co.id
 *     PRESENSI_API_TOKEN=<token BARU dari server presensi>
 *     PRESENSI_DB_CODE=            # opsional, TANPA nilai bawaan; kosongkan jika db_code selalu dikirim lewat parameter
 *     PRESENSI_API_TIMEOUT=15
 *
 * 'token' dibaca oleh createUser() DAN createPresensiAccount(). 'url', 'db_code', dan 'timeout' hanya dipakai
 * createPresensiAccount(); createUser() tetap memakai URL dan db_code yang tertulis di fungsinya.
 *
 * Untuk uji lokal, PRESENSI_API_URL=http://127.0.0.1:8000 (menggantikan baris yang dikomentari di createUser() lama).
 * Setelah mengubah .env: php artisan config:clear
 */

return [
    'presensi' => [
        'url'     => env('PRESENSI_API_URL'),
        'token'   => env('PRESENSI_API_TOKEN'),
        'db_code' => env('PRESENSI_DB_CODE'),
        'timeout' => (int) env('PRESENSI_API_TIMEOUT', 15),
    ],
];
