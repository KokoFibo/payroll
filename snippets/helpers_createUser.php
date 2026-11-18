<?php

/**
 * createUser() DENGAN TOKEN DARI .env. Ganti function createUser() lama di file helper Anda dengan ini.
 *
 * Perubahan dari versi lama:
 *   1. Token tidak lagi tertulis di kode: dibaca dari config('services.presensi.token') <- PRESENSI_API_TOKEN di .env.
 *   2. Jika token belum diisi, mengembalikan ['status' => false, ...] (bentuk hasil yang sama seperti kegagalan lain),
 *      bukan mengirim permintaan tanpa token.
 *   3. db_code tidak lagi tertulis 'payroll': diambil dari APPLICANT_COMPANY_CODES di .env (config applicant_admin.company_codes).
 *      Harus berisi TEPAT SATU kode perusahaan. Kosong, '*', atau lebih dari satu = ambigu, jadi ditolak (status false).
 * URL, payload, dan perilaku lainnya SAMA PERSIS dengan versi lama.
 *
 * Prasyarat: entri 'presensi' di config/services.php (lihat config_services_presensi.php), config/applicant_admin.php, dan .env:
 *     PRESENSI_API_TOKEN=<token baru dari server presensi>
 *     APPLICANT_COMPANY_CODES=payroll
 * Gunakan config(), bukan env(), di sini: env() mengembalikan null di luar file config saat `php artisan config:cache`.
 *
 * Fungsi ini memakai `Karyawan`, `Carbon`, dan `Http` dari `use` di file helper Anda (seperti versi lama):
 *     use App\Models\Karyawan;
 *     use Carbon\Carbon;
 *     use Illuminate\Support\Facades\Http;
 */
function createUser($id_karyawan)
{
    $data = Karyawan::where('id_karyawan', $id_karyawan)->first();

    if (!$data) {
        return [
            'status' => false,
            'message' => 'Data karyawan tidak ditemukan'
        ];
    }

    // 🔑 token API presensi dari .env (PRESENSI_API_TOKEN)
    $token = config('services.presensi.token');

    if (blank($token)) {
        return [
            'status' => false,
            'message' => 'Token API presensi belum diisi (PRESENSI_API_TOKEN di .env)'
        ];
    }

    // 🏢 db_code dari APPLICANT_COMPANY_CODES (.env). Harus tepat satu kode; '*' atau lebih dari satu = ambigu.
    $codes = array_values((array) config('applicant_admin.company_codes', []));

    if (count($codes) !== 1 || $codes[0] === '*') {
        return [
            'status' => false,
            'message' => 'db_code tidak dapat ditentukan: APPLICANT_COMPANY_CODES di .env harus berisi tepat satu kode perusahaan (mis. payroll)'
        ];
    }

    $dbCode = $codes[0];

    // 🔐 password dari tanggal lahir
    $password = Carbon::parse($data->tanggal_lahir)->format('dmy');

    $response = Http::withToken($token)
        ->post('https://presensidb.yifang.co.id/api/create-user', [
            // ->post('http://127.0.0.1:8000/api/create-user', [
            'name' => $data->nama,                 // sesuaikan kolom
            'email' => $data->email,
            'password' => $password,
            'db_code' => $dbCode,                  // ✅ dari APPLICANT_COMPANY_CODES (.env)
            'id_karyawan' => $data->id_karyawan,
            'id_unik_karyawan' => $data->id,
            'role' => 1,                           // default (ubah kalau perlu)
            'language' => 'Id',
            'outsource' => $data->outsource,       // ✅ ambil dari data
            'company_name' => nama_company($data->company_id) ?? null,
        ]);

    if ($response->successful()) {
        return [
            'status' => true,
            'message' => 'User berhasil dibuat',
            'data' => $response->json()
        ];
    }

    return [
        'status' => false,
        'message' => 'Gagal create user',
        'error' => $response->json()
    ];
}
