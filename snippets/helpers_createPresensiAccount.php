<?php

/**
 * HELPER BARU: createPresensiAccount(). TEMPEL di file helper Anda, DI SAMPING createUser() yang lama.
 * createUser() yang lama tidak diubah dan tetap dipakai pemanggil lain. Fungsi ini dipakai oleh penerimaan
 * pelamar (App\Actions\AcceptApplicant). Nama lengkap kelas (\App\..., \Carbon\...) dipakai supaya tidak
 * bergantung pada `use` di file helper.
 *
 * Perbedaan dari createUser() lama:
 *  - URL, token, dan batas waktu dari config('services.presensi') (isi lewat .env), tidak tertulis di kode.
 *  - db_code: parameter ke-2 (kode perusahaan pelamar); jika tidak diberikan, diambil dari APPLICANT_COMPANY_CODES
 *    (.env) dan hanya bila berisi TEPAT SATU kode. Kosong, '*', atau lebih dari satu = ambigu, jadi ditolak
 *    (status false) supaya akun tidak terbuat di database presensi yang salah. Tidak ada nilai bawaan.
 *  - Payload ke API presensi sama dengan createUser() lama (db_code kini bisa diganti lewat parameter).
 *  - Selalu mengembalikan array (status true/false); gangguan jaringan tidak melempar exception.
 *  - Tanggal lahir kosong ditolak (di versi lama Carbon::parse(null) diam-diam memakai tanggal hari ini).
 */
if (! function_exists('createPresensiAccount')) {
    function createPresensiAccount($id_karyawan, $dbCode = null)
    {
        $data = \App\Models\Karyawan::where('id_karyawan', $id_karyawan)->first();

        if (! $data) {
            return [
                'status'  => false,
                'message' => 'Data karyawan tidak ditemukan',
            ];
        }

        $cfg = config('services.presensi');

        if (blank($cfg['url'] ?? null) || blank($cfg['token'] ?? null)) {
            return [
                'status'  => false,
                'message' => 'Konfigurasi API presensi belum diisi (PRESENSI_API_URL dan PRESENSI_API_TOKEN di .env)',
            ];
        }

        if (blank($data->tanggal_lahir)) {
            return [
                'status'  => false,
                'message' => 'Tanggal lahir karyawan kosong (password akun presensi dibuat dari tanggal lahir)',
            ];
        }

        // db_code = kode database/tenant tujuan di sistem presensi. Tanpa nilai bawaan.
        if (blank($dbCode)) {
            $codes  = array_values((array) config('applicant_admin.company_codes', []));
            $dbCode = (count($codes) === 1 && $codes[0] !== '*') ? $codes[0] : null;
        }

        if (blank($dbCode)) {
            return [
                'status'  => false,
                'message' => 'db_code tidak dapat ditentukan (kirim sebagai parameter ke-2, atau isi APPLICANT_COMPANY_CODES di .env dengan tepat satu kode perusahaan)',
            ];
        }

        // password awal dari tanggal lahir (ddmmyy)
        $password = \Carbon\Carbon::parse($data->tanggal_lahir)->format('dmy');

        try {
            $response = \Illuminate\Support\Facades\Http::withToken($cfg['token'])
                ->timeout((int) ($cfg['timeout'] ?? 15))
                ->acceptJson()
                ->post(rtrim($cfg['url'], '/') . '/api/create-user', [
                    'name'             => $data->nama,
                    'email'            => $data->email,
                    'password'         => $password,
                    'db_code'          => $dbCode,
                    'id_karyawan'      => $data->id_karyawan,
                    'id_unik_karyawan' => $data->id,
                    'role'             => 1,
                    'language'         => 'Id',
                    'outsource'        => $data->outsource,
                    'company_name'     => nama_company($data->company_id) ?? null,
                ]);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return [
                'status'  => false,
                'message' => 'Server presensi tidak dapat dihubungi',
                'error'   => $e->getMessage(),
            ];
        }

        if ($response->successful()) {
            return [
                'status'  => true,
                'message' => 'User berhasil dibuat',
                'data'    => $response->json(),
            ];
        }

        return [
            'status'  => false,
            'message' => 'Gagal create user',
            'error'   => $response->json(),
        ];
    }
}
