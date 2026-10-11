<?php

return [
    'api_key' => env('GEMINI_API_KEY'),
    'model'   => env('GEMINI_MODEL'),
    // 'model'   => env('GEMINI_MODEL', 'gemini-2.5-flash'),

    // Sebaiknya pakai koneksi DB read-only (user MySQL hanya punya GRANT SELECT)
    'connection' => env('AI_QUERY_DB_CONNECTION', null),

    // WHITELIST tabel yang boleh dilihat AI. Jangan masukkan tabel sensitif.
    'allowed_tables' => [
        'karyawans',
        'yfrekappresensis',
        'jabatans',
        'departments',
        'companies',
        'placements',
    ],

    // (Opsional) Relasi manual jika penamaan kolom tidak mengikuti pola <nama>_id / id_<nama>.
    // Relasi otomatis sudah dideteksi dari nama kolom. Contoh: 'karyawans.perusahaan' => 'companies.id'
    'relations' => [
        // 'karyawans.company_id' => 'companies.id',
        // Aktifkan jika user_id (int) di absensi memang mengacu ke id_karyawan (int) di karyawans:
        // 'yfrekappresensis.user_id' => 'karyawans.id_karyawan',
    ],

    // Petunjuk tambahan untuk AI (satu kalimat per baris). Tambahkan aturan bisnis Anda di sini.
    'hints' => [
        'karyawans.id adalah UUID. yfrekappresensis.karyawan_id mengacu ke karyawans.id.',
        'Nama perusahaan ada di companies.company_name, penempatan di placements.placement_name, jabatan di jabatans.nama_jabatan, departemen di departments.nama_department.',
        'Jika diminta "data karyawan" tanpa rincian, tampilkan kolom ringkas: id_karyawan, nama, status_karyawan, tanggal_bergabung, nama perusahaan, penempatan, departemen, dan jabatan.',
    ],

    // Kolom sensitif: tidak dikirim ke AI, ditolak jika muncul di query, dan SELALU dibuang dari hasil
    // (termasuk saat AI memakai SELECT *). Tambahkan kolom lain sesuai kebutuhan, mis. 'gaji_pokok'.
    'hidden_columns' => [
        'password',
        'remember_token',
        'api_token',
        'jenis_identitas',
        'no_identitas',
        'no_npwp',
        'nomor_rekening',
        'nama_bank',
    ],

    // Kolom yang hanya disembunyikan dari TAMPILAN hasil (nama harus persis sama).
    // Tetap boleh dipakai di JOIN/WHERE/ORDER BY. 'id_karyawan' tidak ikut tersembunyi.
    'hidden_output_columns' => ['id'],

    // Tabel referensi kecil (bukan data sensitif) yang isinya dikirim ke AI sebagai contoh,
    // supaya "company das" bisa dicocokkan ke nama perusahaan yang sebenarnya.
    'lookup_tables' => ['companies', 'departments', 'jabatans', 'placements'],
    'lookup_sample_rows' => 50,

    'max_rows' => 500,
];
