<?php

use App\Livewire\Test;
use App\Livewire\Karyawanwr;
use App\Http\Controllers\Testaja;
use App\Livewire\Karyawanindexwr;
use App\Livewire\Deletepresensiwr;
use App\Livewire\Importkaryawanwr;
use App\Livewire\Updatekaryawanwr;
use App\Livewire\Yfpresensiindexwr;
use Illuminate\Support\Facades\Route;
use App\Livewire\Yfdeletetanggalpresensiwr;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\YfpresensiController;



// Middleware
Auth::routes();
Route::middleware(['guest'])->group(function() {

});

// DASHBOARD
Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);
Route::get('/dashboard', [DashboardController::class,'index']);

// KARYAWAN
Route::get('/karyawancreate', Karyawanwr::class)->name('karyawancreate');
Route::get('/karyawanupdate/{id}', Updatekaryawanwr::class)->name('karyawanupdate');
// Route::get('/karyawanupdate', Updatekaryawanwr::class)->name('karyawanupdate');
Route::get('/karyawanindex', Karyawanindexwr::class)->name('karyawanindex');
// Route::resource('karyawan', KaryawanController::class);
Route::get('/karyawan', [KaryawanController::class,'index'])->name('karyawan.index');
// Route::get('/karyawan/hapus/{$id}', [KaryawanController::class,'hapus']);
Route::delete('/karyawan/{id}/destroy', [KaryawanController::class,'destroy'])->name('karyawan.destroy');
Route::post('/cari', [KaryawanController::class, 'cari'])->name('karyawan.cari');
Route::get('/resettable', [KaryawanController::class, 'resetTable'])->name('karyawan.resettable');

// PRESENSI
// Route::get('/presensidelete', Deletepresensiwr::class)->name('presensidelete');
// Route::get('/presensiupload', function() {
//     return view('content.presensi.import');
// });
// Route::post('/presensi-update/{user_id}', [PresensiController::class, 'update_presensi'])->name('presensi.updatedata');
// Route::delete('/presensi-delete/{user_id}/{date}', [PresensiController::class, 'delete_presensi'])->name('presensi.deletedata');
// Route::resource('/presensi', PresensiController::class);

// Route::get('/presensinormalize', [PresensiController::class, 'normalize'])->name('karyawan.normalize');


// KHUSUS DEVELOPER
Route::post('/karyawanimport', [KaryawanController::class,'import'])->name('karyawan.import');
Route::get('/karyawanviewimport', function() {
    return view('karyawan.importview');
});
Route::get('/erasedatakarayawan', [KaryawanController::class,'erase'])->name('karyawan.erase');

// TEST
Route::get('/test', Test::class)->name('test');
Route::get('/testaja', [Testaja::class,'index']);
Route::get('/testok', function() {
    return view('test');
});

// YF PRESENSI
Route::get('/yfupload', function(){
    return view ('yfpresensi.upload');
});
Route::get('/yfindex', [YfpresensiController::class, 'index']);
Route::post('/yfstore', [YfpresensiController::class, 'store']);
Route::get('/yfdeletepresensi', [YfpresensiController::class, 'deletepresensi']);
Route::get('/yfpresensiindexwr', Yfpresensiindexwr::class);
Route::get('/yfdeletetanggalpresensiwr', Yfdeletetanggalpresensiwr::class);










