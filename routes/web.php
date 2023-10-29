<?php

use App\Livewire\Test;
use App\Livewire\Developer;
use App\Livewire\MissingId;
use App\Livewire\Prindexwr;
use App\Livewire\Karyawanwr;
use App\Http\Controllers\Testaja;
use App\Livewire\ChangeFieldData;
use App\Livewire\Changeprofilewr;
use App\Livewire\Karyawanindexwr;
use App\Livewire\Changeuserrolewr;
use App\Livewire\Deletepresensiwr;
use App\Livewire\Importkaryawanwr;
use App\Livewire\Updatekaryawanwr;
use App\Livewire\Karyawansettingwr;
use App\Livewire\Yfpresensiindexwr;
use Illuminate\Support\Facades\Route;
use App\Livewire\Yfdeletetanggalpresensiwr;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\YfpresensiController;

// Middleware
Auth::routes([
    'register' => false, // Register Routes...
  'verify' => false, // Email Verification Routes...
]);


Route::middleware(['guest'])->group(function () {});

Route::middleware(['auth'])->group(function () {

    Route::middleware(['User'])->group(function () {
        // DASHBOARD
        Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
        Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

        Route::middleware(['Admin'])->group(function () {
            //Dashboard
            Route::get('/dashboard', [DashboardController::class, 'index']);
            // KARYAWAN
            Route::get('/karyawancreate', Karyawanwr::class)->name('karyawancreate');
            Route::get('/karyawanupdate/{id}', Updatekaryawanwr::class)->name('karyawanupdate');
            // Route::get('/karyawanupdate', Updatekaryawanwr::class)->name('karyawanupdate');
            Route::get('/karyawanindex', Karyawanindexwr::class)->name('karyawanindex');
            // Route::resource('karyawan', KaryawanController::class);
            Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
            // Route::get('/karyawan/hapus/{$id}', [KaryawanController::class,'hapus']);
            Route::delete('/karyawan/{id}/destroy', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');
            Route::post('/cari', [KaryawanController::class, 'cari'])->name('karyawan.cari');
            Route::get('/resettable', [KaryawanController::class, 'resetTable'])->name('karyawan.resettable');

            // YF PRESENSI
            Route::get('/yfupload', function () {
                return view('yfpresensi.upload');
            });
            Route::get('/yfindex', [YfpresensiController::class, 'index']);
            Route::post('/yfstore', [YfpresensiController::class, 'store']);
            Route::get('/yfdeletepresensi', [YfpresensiController::class, 'deletepresensi']);
            Route::get('/yfpresensiindexwr', Yfpresensiindexwr::class);

            // PAYROLL
            Route::get('/payrollindex', Prindexwr::class);

            // USER SETTING

            Route::get('/changeprofilewr', Changeprofilewr::class)->name('changeprofile');
            Route::get('/karyawansettingwr', Karyawansettingwr::class)->name('karyawansettingwr');

            Route::middleware(['SuperAdmin'])->group(function () {
                Route::get('/yfdeletetanggalpresensiwr', Yfdeletetanggalpresensiwr::class);
                Route::get('/changeuserrolewr', Changeuserrolewr::class);

                Route::middleware(['Developer'])->group(function () {
                    // KHUSUS DEVELOPER
                    Route::post('/karyawanimport', [KaryawanController::class, 'import'])->name('karyawan.import');
                    Route::get('/karyawanviewimport', function () {
                        return view('karyawan.importview');
                    });
                    Route::get('/erasedatakarayawan', [KaryawanController::class, 'erase'])->name('karyawan.erase');
                    Route::get('/deletenoscan', [YfpresensiController::class, 'deleteNoScan']);
                    Route::get('/deletejamkerja', [YfpresensiController::class, 'deleteJamKerja']);
                    Route::get('/generateusers', [YfpresensiController::class, 'generateUsers']);
                    Route::get('/testto', [YfpresensiController::class, 'testto']);
                    Route::get('/developer', Developer::class);
                    Route::get('/ChangeFieldData', ChangeFieldData::class);
                    Route::get('/MissingId', MissingId::class);

                    // TEST
                    Route::get('/test', Test::class)->name('test');
                    Route::get('/testaja', [Testaja::class, 'index']);
                    Route::get('/testok', function () {
                        return view('test');
                    });
                });
            });
        });
    });

    // PRESENSI
    // Route::get('/presensidelete', Deletepresensiwr::class)->name('presensidelete');
    // Route::get('/presensiupload', function() {
    //     return view('content.presensi.import');
    // });
    // Route::post('/presensi-update/{user_id}', [PresensiController::class, 'update_presensi'])->name('presensi.updatedata');
    // Route::delete('/presensi-delete/{user_id}/{date}', [PresensiController::class, 'delete_presensi'])->name('presensi.deletedata');
    // Route::resource('/presensi', PresensiController::class);

    // Route::get('/presensinormalize', [PresensiController::class, 'normalize'])->name('karyawan.normalize');
});
// end of middleware auth
