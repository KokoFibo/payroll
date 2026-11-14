<?php

/**
 * Konfigurasi halaman pelamar (NewApplicantData). SEMUA nilai khusus aplikasi diatur lewat .env,
 * tidak ada nilai bawaan yang menunjuk ke aplikasi/perusahaan/bucket tertentu. Jika belum diisi,
 * halaman memilih AMAN (tidak menampilkan data / menolak aksi) dan menampilkan peringatan di layar.
 */

// "a, b ,c" -> ['a','b','c'] (nilai kosong dibuang)
$list = fn ($v) => array_values(array_filter(array_map('trim', explode(',', (string) $v)), 'strlen'));

return [
    // Layout Livewire untuk halaman ini.
    'layout' => env('APPLICANT_ADMIN_LAYOUT', 'components/layouts.app-tailwind'),

    // Perusahaan yang pelamarnya ditampilkan: kode di companies.code (database applicant), pisahkan koma.
    // WAJIB diisi. Kosong = TIDAK ADA yang ditampilkan. '*' = semua perusahaan (sengaja, bukan bawaan).
    'company_codes' => $list(env('APPLICANT_COMPANY_CODES')),

    // Disk (config/filesystems.php) tempat file portal pelamar berada. WAJIB diisi: nama disk di aplikasi INI
    // bisa menunjuk bucket lain, dan menghapus di bucket yang salah akan terlihat "berhasil" padahal file asli tertinggal.
    'applicant_disk' => env('APPLICANT_FILES_DISK'),

    // Fitur "Terima jadi karyawan" (tidak bisa dibatalkan). Khusus aplikasi payroll: butuh model Karyawan,
    // getNextIdKaryawan(), createPresensiAccount(), dan disk karyawan di bawah.
    // Bawaan MATI agar aplikasi lain tidak ikut menjalankannya.
    'accept_enabled' => (bool) env('APPLICANT_ACCEPT_ENABLED', false),

    // Disk bucket karyawan (hanya dipakai jika accept_enabled). Tanpa nilai bawaan.
    'karyawan_disk' => env('KARYAWAN_FILES_DISK'),

    // Role yang boleh melihat data etnis di tabel daftar (hanya menyembunyikan tampilan, BUKAN pengaman server).
    // Kosong = tidak ada yang melihat etnis di tabel.
    'etnis_roles' => $list(env('APPLICANT_ETNIS_ROLES')),
];
