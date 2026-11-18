# Folder snippets: BUKAN untuk disalin utuh

Isi ketiga file ini ditempel ke file yang **sudah ada** di aplikasi payroll. Jangan menyalin filenya ke `app/` atau `config/`.

| File di sini | Apa | Tempel / ganti di |
|---|---|---|
| `config_services_presensi.php` | entri `'presensi' => [...]` | `config/services.php`, di dalam array `return [ ... ]` |
| `helpers_createUser.php` | fungsi `createUser()` dengan token dari `.env` | **GANTI** `createUser()` lama di file helper Anda |
| `helpers_createPresensiAccount.php` | fungsi **baru** `createPresensiAccount()` | **TAMBAH** di file helper yang sama (di bawah `createUser()`) |

## Menemukan file helper

```bash
grep -rn "function createUser\|function getNextIdKaryawan" app
```

Kedua fungsi itu biasanya ada di file yang sama (mis. `app/Helpers/helpers.php`).

## Langkah

1. **`config/services.php`**: tambahkan di dalam array return:

   ```php
   'presensi' => [
       'url'     => env('PRESENSI_API_URL'),
       'token'   => env('PRESENSI_API_TOKEN'),
       'timeout' => (int) env('PRESENSI_API_TIMEOUT', 15),
   ],
   ```

2. **`.env`** (token BARU dari server presensi, jangan di-commit):

   ```
   PRESENSI_API_URL=https://presensidb.yifang.co.id
   PRESENSI_API_TOKEN=<token baru>
   ```

3. **File helper**:
   - `db_code` tidak diatur di sini: `createUser()` mengambilnya dari `APPLICANT_COMPANY_CODES` di `.env`
     (harus berisi **tepat satu** kode, mis. `payroll`; kosong, `*`, atau lebih dari satu akan ditolak).
   - Hapus `createUser()` lama, tempel `createUser()` dari `helpers_createUser.php`.
     Hanya fungsinya (mulai dari `function createUser(`), tanpa baris `<?php`. Tidak boleh ada dua `createUser()`.
   - Tempel isi `helpers_createPresensiAccount.php` (mulai dari `if (! function_exists(...`) di bawahnya, tanpa baris `<?php`.
   - `createUser()` memakai `Karyawan`, `Carbon`, dan `Http` dari `use` di atas file helper (sudah ada karena fungsi lama
     juga memakainya). `createPresensiAccount()` tidak butuh `use` apa pun.

4. Terapkan dan periksa:

   ```bash
   php artisan config:clear
   php artisan applicant:check --ping
   ```

Mengedit file helper yang sudah terdaftar di `composer.json` tidak perlu `composer dump-autoload`.
