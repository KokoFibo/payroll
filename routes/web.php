<?php

use App\Livewire\Test;
use App\Livewire\Profile;
use App\Livewire\UserLog;
use App\Livewire\Developer;
use App\Livewire\MissingId;
use App\Livewire\Payrollwr;
use App\Livewire\Prindexwr;
use App\Livewire\Rubahidwr;
use App\Livewire\Karyawanwr;
use App\Livewire\Tambahanwr;
use App\Livewire\UserMobile;
use App\Livewire\Informasiwr;
use App\Livewire\DataResigned;
use App\Livewire\AbsensiKosong;
use App\Livewire\Informationwr;
use App\Livewire\Editpresensiwr;
use App\Livewire\UserRegulation;
use App\Http\Controllers\Testaja;
use App\Livewire\ChangeFieldData;
use App\Livewire\Changeprofilewr;
use App\Livewire\Karyawanindexwr;
use App\Livewire\Liburnasionalwr;
use App\Livewire\UpdatedPresensi;
use App\Livewire\UserInformation;
use App\Livewire\Changeuserrolewr;
use App\Livewire\Deletepresensiwr;
use App\Livewire\Importkaryawanwr;
use App\Livewire\Presensidetailwr;
use App\Livewire\Removepresensiwr;
use App\Livewire\SalaryAdjustment;
use App\Livewire\Updatekaryawanwr;
use App\Livewire\Karyawansettingwr;
use App\Livewire\Yfpresensiindexwr;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ReportController;
use App\Livewire\Removepresensiduplikatwr;
use App\Livewire\Yfdeletetanggalpresensiwr;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\YfpresensiController;
use App\Http\Controllers\ExcelUploaderController;
use App\Http\Controllers\KaryawanExcelController;
use App\Livewire\AddPresensi;
use App\Livewire\UserNotFound;

// Middleware
Auth::routes([
    'register' => false, // Register Routes...
    'verify' => false, // Email Verification Routes...
]);

Route::middleware(['guest'])->group(function () {
});

Route::middleware(['auth'])->group(function () {
    // Route::post('logout', LogoutController::class)->name('logout1');
    Route::middleware(['User'])->group(function () {
        Route::get('locale/{locale}', function ($locale) {
            Session::put('locale', $locale);
            return redirect()->back();
        });
        // DASHBOARD
        Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
        Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);
        Route::get('/userinfo', function () {
            return view('user_information');
        });
        Route::get('/userslipgaji', function () {
            return view('user_slipgaji');
        });

        Route::get('/usermobile', UserMobile::class);
        Route::get('/profile', Profile::class);
        Route::get('/userinformation', UserInformation::class);
        Route::get('/userregulation', UserRegulation::class);

        Route::middleware(['Admin'])->group(function () {
            //Dashboard
            Route::get('/dashboard', [DashboardController::class, 'index']);
            Route::get('/mobile', [DashboardController::class, 'mobile']);
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
            Route::get('/informasi', Informasiwr::class);
            Route::get('/informationwr', Informationwr::class);
            Route::get('/tambahan', Tambahanwr::class);

            // YF PRESENSI
            Route::get('/yfupload', function () {
                return view('yfpresensi.upload');
            });
            Route::get('/yfindex', [YfpresensiController::class, 'index']);
            Route::post('/yfstore', [YfpresensiController::class, 'store']);
            Route::get('/yfdeletepresensi', [YfpresensiController::class, 'deletepresensi']);
            Route::get('/yfpresensiindexwr', Yfpresensiindexwr::class);
            Route::get('/presensidetailwr', Presensidetailwr::class);

            // Presensi Summary Excel
            Route::get('/presensisummaryindex', [ReportController::class, 'presensi_summary_index']);
            Route::post('/createexcelpresensisummary', [ReportController::class, 'createExcelPresensiSummary']);


            // USER SETTING

            Route::get('/changeprofilewr', Changeprofilewr::class)->name('changeprofile');
            Route::get('/karyawansettingwr', Karyawansettingwr::class)->name('karyawansettingwr');
            Route::get('/payrollindex', Prindexwr::class);
            Route::get('/salaryadjustment', SalaryAdjustment::class);



            // KHUSUS Super Admin
            Route::middleware(['SuperAdmin'])->group(function () {
                Route::get('/yfdeletetanggalpresensiwr', Yfdeletetanggalpresensiwr::class);
                Route::get('/changeuserrolewr', Changeuserrolewr::class);
                // PAYROLL
                Route::get('/payroll', Payrollwr::class);
                Route::get('/reportindex', [ReportController::class, 'index']);
                Route::post('/createexcel', [ReportController::class, 'createExcel']);

                // Route::get('/karyawan/excel', [KaryawanExcelController::class, 'index']);
                // Route::post('/karyawan/createexcel', [KaryawanExcelController::class, 'createExcel']);


                // KHUSUS DEVELOPER
                Route::middleware(['Developer'])->group(function () {
                    Route::post('/karyawanimport', [KaryawanController::class, 'import'])->name('karyawan.import');
                    Route::get('/importKaryawanExcel', [KaryawanController::class, 'importKaryawanExcel']);
                    Route::get('/karyawanviewimport', function () {
                        return view('karyawan.importview');
                    });
                    Route::get('/erasedatakarayawan', [KaryawanController::class, 'erase'])->name('karyawan.erase');
                    Route::get('/deletenoscan', [YfpresensiController::class, 'deleteNoScan']);
                    Route::get('/deletejamkerja', [YfpresensiController::class, 'deleteJamKerja']);
                    Route::get('/generateusers', [YfpresensiController::class, 'generateUsers']);
                    Route::get('/testto', [YfpresensiController::class, 'testto']);
                    Route::get('/rubahid', Rubahidwr::class);
                    Route::get('/editpresensi', Editpresensiwr::class);
                    Route::get('/removepresensi', Removepresensiwr::class);
                    Route::get('/removepresensiduplikat', Removepresensiduplikatwr::class);
                    Route::get('/exceluploader', [ExcelUploaderController::class, 'index']);
                    Route::post('/xlstore', [ExcelUploaderController::class, 'store']);
                    Route::get('/UserLog', UserLog::class);

                    Route::get('/MissingId', MissingId::class);
                    Route::get('/UpdatedPresensi', UpdatedPresensi::class);
                    Route::get('/absensikosong', AbsensiKosong::class);
                    Route::get('/liburnasional', Liburnasionalwr::class);
                    Route::get('/dataresigned', DataResigned::class);
                    Route::get('/addpresensi', AddPresensi::class);
                    Route::get('/usernotfound', UserNotFound::class);


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
