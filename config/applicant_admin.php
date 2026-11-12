<?php

return [
    // Disk (config/filesystems.php) tempat file portal pelamar berada. Isi sesuai bucket PORTAL,
    // yang bisa berbeda dengan disk "s3" milik aplikasi payroll.
    'applicant_disk' => env('APPLICANT_FILES_DISK', 's3'),

    // Disk bucket karyawan tujuan pemindahan file.
    'karyawan_disk'  => env('KARYAWAN_FILES_DISK', 's3_karyawan'),

    // Pemeriksaan resign/blacklist (check_resigned_blacklist) dibuat untuk data pelamar lama di database payroll.
    // Isi true HANYA setelah helper itu dipastikan memeriksa pelamar dari database applicant dengan benar.
    'screening_verified' => env('APPLICANT_SCREENING_VERIFIED', false),
];
