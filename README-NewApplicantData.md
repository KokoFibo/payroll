# NewApplicantData: pemasangan di aplikasi lain

Halaman daftar dan detail pelamar dari **database applicant** (portal pelamar multi-company).
Semua nilai khusus aplikasi diatur lewat `.env`; tidak ada nilai bawaan yang menunjuk ke aplikasi,
perusahaan, atau bucket tertentu. Jika ada yang belum diisi, halaman memilih aman (tidak menampilkan
data / menolak aksi) dan menampilkan peringatan di atas daftar.

## Dua mode

| Mode | Fitur | Aktif lewat |
|---|---|---|
| **Kelola** (bawaan) | daftar, cari, filter, detail, pratinjau dokumen, ubah status, hapus (data + file S3) | selalu |
| **Terima karyawan** (khusus payroll) | tombol Diterima: **salin** data ke Karyawan, **salin** dokumen ke bucket karyawan, buat akun presensi. Data pelamar tetap ada. Tidak bisa dibatalkan dari halaman ini | `APPLICANT_ACCEPT_ENABLED=true` |

## File yang disalin

**Mode Kelola (cukup ini):**

- `app/Livewire/NewApplicantData.php`
- `resources/views/livewire/new-applicant-data.blade.php`
- `app/Enums/ApplicantStatus.php`
- `app/Models/Portal/{Applicant,ApplicantFile,Company}.php`
- `app/Actions/DeleteApplicant.php`
- `app/Exceptions/ApplicantConfigException.php`
- `config/applicant_admin.php`

**Tambahan hanya untuk mode Terima karyawan:** `app/Actions/AcceptApplicant.php`,
`app/Services/ApplicantFileMover.php`, `app/Exceptions/PresensiUserException.php`, serta yang sudah harus ada
di aplikasinya: model `Karyawan`, `getNextIdKaryawan()`, dan `createPresensiAccount()` (lihat `snippets/`).
Tidak ada tabel, migrasi, atau jadwal (scheduler) tambahan.

## Persyaratan

1. **Livewire 3** (membawa Alpine) dan **Tailwind** yang memindai `resources/views/livewire`. Jangan memuat Alpine kedua.
2. **Koneksi database `applicant`** di `config/database.php` (contoh di bawah). Pakai user MySQL berhak minimal:
   `SELECT, INSERT, UPDATE, DELETE` pada `applicantdatas` dan `applicantfiles`, `SELECT` pada `companies`.
3. **Disk S3** untuk bucket portal di `config/filesystems.php` (kunci IAM: `GetObject`, `DeleteObject`, `ListBucket`).
4. **Route + middleware** (lihat peringatan hak akses).
5. **Layout** Livewire (bawaan `components/layouts.app-tailwind`, ganti dengan `APPLICANT_ADMIN_LAYOUT`).
6. Helper `format_tgl()` dan `dateTimeFormat()` dipakai bila ada; jika tidak, format bawaan `d/m/Y`.

```php
// config/database.php, dalam 'connections'
'applicant' => [
    'driver' => 'mysql',
    'host' => env('APPLICANT_DB_HOST', env('DB_HOST', '127.0.0.1')),
    'port' => env('APPLICANT_DB_PORT', env('DB_PORT', '3306')),
    'database' => env('APPLICANT_DB_DATABASE'),
    'username' => env('APPLICANT_DB_USERNAME', env('DB_USERNAME')),
    'password' => env('APPLICANT_DB_PASSWORD', env('DB_PASSWORD')),
    'charset' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci', 'prefix' => '', 'strict' => true,
],
```

## Variabel `.env`

| Variabel | Wajib | Arti |
|---|---|---|
| `APPLICANT_COMPANY_CODES` | ya | kode `companies.code` yang ditampilkan, pisahkan koma. Kosong = tidak ada yang tampil. `*` = semua perusahaan. Juga menjadi `db_code` presensi untuk `createUser()`: di sana harus berisi **tepat satu** kode |
| `APPLICANT_FILES_DISK` | ya | nama disk bucket portal. Kosong = dokumen tidak tampil dan pelamar ber-dokumen **tidak bisa dihapus** |
| `APPLICANT_DB_DATABASE` (+host, user, password) | ya | koneksi `applicant` |
| `APPLICANT_ADMIN_LAYOUT` | tidak | layout Livewire |
| `APPLICANT_ETNIS_ROLES` | tidak | role (nilai kolom `role` pada user) yang melihat data etnis **di tabel daftar**. Kosong = tidak ada |
| `APPLICANT_ACCEPT_ENABLED` | tidak | `true` untuk fitur Terima karyawan (bawaan mati) |
| `KARYAWAN_FILES_DISK` | jika Terima aktif | nama disk bucket karyawan; **harus bucket berbeda** dari bucket portal |

Setelah mengubah `.env`: `php artisan config:clear`.

## PERINGATAN: hak akses hanya di tampilan

Kolom etnis di tabel disembunyikan di Blade (`APPLICANT_ETNIS_ROLES`), dan detail pelamar menampilkan etnis ke semua
pengguna. Lebih penting lagi, **method Livewire (`save`, `confirmDelete`, `delete`, `diterima`, ...) tidak memeriksa
peran**. Siapa pun yang bisa membuka halaman bisa memanggilnya langsung dari browser. Karena itu:

- Pasang route di grup dengan middleware autentikasi **dan** peran yang tepat di aplikasi baru:

```php
Route::get('/data-applicant', \App\Livewire\NewApplicantData::class)
    ->middleware(['auth', 'can:manage-applicants']);   // sesuaikan dengan sistem peran aplikasi tersebut
```

- Perlakukan siapa pun yang lolos middleware sebagai berhak melihat, mengubah, menghapus, dan (jika aktif) menerima
  pelamar, termasuk data pribadinya (KTP, alamat, dst.).
- Jika butuh pemisahan per peran di dalam halaman, pemeriksaan harus ditambahkan di setiap method aksi.

## Hapus dan terima: dua langkah

Tombol Hapus dan Simpan-dengan-status-Diterima membuka **modal konfirmasi yang dirender server** (state `$confirm`).
Aksinya (`delete()` / `diterima()`) hanya berjalan bila konfirmasi dengan jenis yang sesuai sedang menunggu, sehingga tidak
bisa dipanggil langsung dari browser tanpa melewati konfirmasi.

## Terima karyawan: cara kerja (salin, bukan pindah)

Penerimaan **menyalin**. Data pelamar di database applicant dan file di bucket portal **tidak dihapus**; keduanya tetap
ada sampai Anda menghapusnya sendiri lewat tombol Hapus.

1. Cek awal: belum pernah diterima, email belum ada di `users`, email dan tanggal lahir terisi.
2. `db_code` akun presensi = kolom `code` perusahaan pelamar (database applicant, mis. `payroll`); ditolak jika kosong.
3. File disalin ke bucket karyawan (`yifang-payroll`) dan diverifikasi (ukuran sama).
4. Transaksi payroll: buat `Karyawan` dari data pelamar, salin baris dokumen ke `applicantfiles` payroll, **buat akun
   presensi** (`createPresensiAccount`), commit.
5. Setelah commit, status pelamar diubah menjadi **Diterima** (8) di database applicant.

- Gagal di langkah 3-4: semuanya dibatalkan bersih (salinan file dibuang, Karyawan tidak jadi); data dan status pelamar
  tidak berubah.
- Gagal hanya di langkah 5: penerimaan tetap sah, muncul peringatan, dan status diubah manual. Penerimaan ganda tetap
  dicegah oleh pengecekan `Karyawan.id_file_karyawan`.
- Tidak ada tombol batal. Karyawan yang terlanjur dibuat dan akun presensinya dihapus manual (akun presensi tidak bisa
  ditarik otomatis). Siapa yang menerima dicatat di log (`by_user_id`), bukan di database.
- Menghapus pelamar yang sudah diterima (tombol Hapus) hanya menghapus data dan file di sisi applicant. Data Karyawan
  dan salinan dokumen di bucket karyawan tidak terpengaruh karena bucketnya berbeda.

## Migrasi dari versi yang masih punya fitur undo

Hanya untuk database payroll yang pernah menjalankan migrasi `applicant_acceptances`:

1. **Sebelum** mengganti kode: jika tabel itu berisi baris (penerimaan dari versi lama yang belum selesai dibersihkan),
   jalankan versi LAMA `php artisan applicants:finalize-acceptances` (setelah masa 60 menit lewat) sampai tabel kosong.
   Perintah itu menghapus file asli pelamar tersebut dari bucket portal, sesuai perilaku versi lama. Jika Anda lebih suka
   **mempertahankan** file itu, kosongkan tabelnya secara manual; file di bucket tidak ikut terhapus.
2. Ganti kode dengan versi ini, **hapus** baris `Schedule::command('applicants:finalize-acceptances')...` dari
   `routes/console.php`.
3. Jalankan `php artisan migrate`: migrasi `2026_11_14_000001_drop_applicant_acceptances_table` menghapus tabel
   (dan menolak berjalan jika tabel masih berisi).

## Aplikasi payroll

```
APPLICANT_COMPANY_CODES=payroll
APPLICANT_FILES_DISK=<disk bucket portal, mis. s3_applicant>
APPLICANT_ACCEPT_ENABLED=true
KARYAWAN_FILES_DISK=<disk bucket karyawan, mis. s3>
APPLICANT_ETNIS_ROLES=8
PRESENSI_API_URL=...
PRESENSI_API_TOKEN=...
```

Tanpa baris-baris itu halaman payroll akan kosong dengan peringatan konfigurasi (aman, bukan rusak).

## Pemecahan masalah: "klik tombol tidak terjadi apa-apa"

Aksi di halaman ini sengaja **berhenti dan membatalkan semuanya** bila ada prasyarat yang kurang (berbeda dengan
aplikasi lama yang diam-diam melanjutkan). Penyebabnya selalu salah satu prasyarat di atas. Cara menemukannya:

1. **Jalankan pemeriksaan otomatis** (tidak mengubah data):

   ```bash
   php artisan applicant:check          # tambah --ping untuk menguji server presensi
   ```

   Ia memeriksa koneksi dan hak akses database applicant, izin bucket (baca/hapus/tulis), fungsi helper payroll,
   `PRESENSI_API_*`, `db_code` yang akan dipakai, dan cache lock. Baris `[GAGAL]` menunjukkan apa yang harus diperbaiki.
2. **Lihat halamannya:** kotak kuning "Konfigurasi belum lengkap" di atas daftar memuat prasyarat yang kurang. Setiap aksi
   (simpan, hapus, terima) menampilkan hasilnya di banner yang melayang di bagian atas layar (merah = gagal, kuning =
   peringatan, hijau = sukses). Banner dan modal konfirmasi dirender **server**, jadi tampil walau JavaScript bermasalah.
   Selama permintaan berjalan ada garis biru di tepi atas layar dan tombol berubah menjadi "Menyimpan..."/"Memproses...".
3. **Lihat log:** `tail -n 80 storage/logs/laravel.log` tepat setelah mengklik. Error yang tidak tertangkap dicatat di sini.
4. **Lihat console browser dan tab Network** (F12). Setelah mengklik, harus ada permintaan `livewire/update`. Jika tidak
   ada, Livewire sendiri tidak aktif di halaman (periksa `@livewireScripts` di layout). Konfirmasi hapus/terima TIDAK
   membutuhkan Alpine; Alpine hanya dipakai untuk pratinjau dokumen dan menutup otomatis banner sukses.

Penyebab yang paling sering:

| Gejala | Penyebab |
|---|---|
| Hapus: tidak ada yang terhapus | `APPLICANT_FILES_DISK` kosong/salah (pelamar ber-dokumen ditolak), atau user MySQL tanpa hak `DELETE` |
| Ubah status gagal | user MySQL tanpa hak `UPDATE` |
| Terima: tidak ada karyawan baru | `KARYAWAN_FILES_DISK` kosong, `PRESENSI_API_URL`/`TOKEN` kosong, fungsi `createPresensiAccount()` / `getNextIdKaryawan()` belum ditambahkan, atau API presensi menolak (email sudah terdaftar, `db_code` tidak dikenal) |
| Terima: "bucket sama" | disk portal dan disk karyawan menunjuk bucket yang sama |

## Uji setelah dipasang

1. Buka halaman: tidak ada kotak "Konfigurasi belum lengkap".
2. Daftar hanya berisi pelamar perusahaan yang diizinkan (cek dengan akun uji).
3. Buka detail, klik dokumen portrait, zoom dan geser.
4. Hapus satu pelamar uji ber-dokumen; pastikan `Storage::disk('<APPLICANT_FILES_DISK>')->exists($key)` menjadi `false`.
5. (Payroll) Terima satu pelamar uji dengan email uji. Pastikan: karyawan baru ada, dokumennya ada di bucket karyawan,
   pelamar **tetap ada** di daftar dengan status Diterima, dan file di bucket portal **masih ada**. Lalu hapus
   karyawan dan akun presensi ujinya secara manual.
6. Coba akses halaman dengan akun yang **tidak** berhak: harus ditolak oleh middleware.
