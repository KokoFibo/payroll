<?php

namespace App\Http\Controllers;

use ZipArchive;
use Illuminate\Http\Request;
use App\Exports\KaryawanExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MultipleKaryawanForm;
use Illuminate\Support\Facades\Storage;
use App\Models\Karyawan;


class ExcelController extends Controller
{


    public function testExport()
    {
        $testZipPath = storage_path('app/exports/test_manual.zip');
        $zipTest = new ZipArchive;
        if ($zipTest->open($testZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            // Buat file txt sementara
            $testFile = storage_path('app/exports/testfile.txt');
            file_put_contents($testFile, "Ini file test buat ZIP");

            $zipTest->addFile($testFile, 'testfile.txt');
            $zipTest->close();

            return response()->download($testZipPath)->deleteFileAfterSend(true);
        } else {
            return response()->json(['error' => 'Gagal buat ZIP manual'], 500);
        }
    }





    public function downloadKaryawanZip()
    {
        Storage::disk('local')->makeDirectory('exports');

        $karyawans = \App\Models\Karyawan::all();

        // $missingPlacement = $karyawans->filter(fn($k) => empty($k->placement_id));
        // $missingCompany = $karyawans->filter(fn($k) => empty($k->company_id));
        // $missingDept = $karyawans->filter(fn($k) => empty($k->departemen_id));




        // $grouped = $karyawans->groupBy('placement_id')
        //     ->map(fn($group) => $group->groupBy('company_id')
        //         ->map(fn($subGroup) => $subGroup->groupBy('departemen_id')));

        // $grouped = $karyawans->groupBy('placement_id')
        //     ->map(fn($group) => $group->groupBy('company_id'));

        $filtered = $karyawans->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan']);

        $grouped = $filtered->groupBy(function ($item) {
            return $item->placement_id . '-' . $item->company_id;
        });
        // $grouped = $karyawans->groupBy(function ($item) {
        //     return $item->placement_id . '-' . $item->company_id;
        // });


        $zipFilename = 'exports/karyawan_form.zip';
        $zipPath = storage_path("app/{$zipFilename}");

        $zip = new ZipArchive;

        $openResult = $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        if ($openResult !== true) {
            \Log::error('ZipArchive gagal membuka file zip. Error code: ' . $openResult);
            return response()->json(['error' => 'Gagal buat ZIP, kode error: ' . $openResult], 500);
        }

        $storedFiles = [];

        foreach ($grouped as $key => $karyawanGroup) {
            [$placementId, $companyId] = explode('-', $key);
            $nama_placement = nama_placement($placementId);
            $nama_company = nama_company($companyId);

            \Log::info("📦 Proses: placement_id = $placementId, company_id = $companyId, count = " . $karyawanGroup->count());

            $relativePath = "exports/placement_{$nama_placement}_company_{$nama_company}.xlsx";
            $header_text = "Data Karyawan OS Placement {$nama_placement} - Company {$nama_company} - " . now()->format('d-m-Y H:i:s');
            $stored = Excel::store(new MultipleKaryawanForm($karyawanGroup, $header_text), $relativePath, 'local');
            $fullPath = storage_path("app/{$relativePath}");

            if ($stored && file_exists($fullPath)) {
                $zip->addFile($fullPath, "placement_{$nama_placement}/placement_{$nama_placement}_company_{$nama_company}.xlsx");
                $storedFiles[] = $relativePath;
                \Log::info("✅ Berhasil simpan Excel di: {$fullPath}");
            } else {
                \Log::error("❌ Gagal simpan Excel: {$fullPath}");
            }
        }




        if (empty($storedFiles)) {
            \Log::error('Tidak ada file Excel yang berhasil dibuat untuk ZIP.');
            $zip->close();
            return response()->json(['error' => 'Tidak ada file Excel untuk dimasukkan ke ZIP'], 400);
        }

        $zip->close();

        if (!file_exists($zipPath)) {
            \Log::error("File ZIP tidak ditemukan setelah close: {$zipPath}");
            return response()->json(['error' => 'File ZIP tidak ditemukan setelah dibuat'], 500);
        }

        Storage::disk('local')->delete($storedFiles);

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
