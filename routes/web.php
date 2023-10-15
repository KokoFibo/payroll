<?php

use App\Livewire\Test;
use App\Livewire\Karyawanwr;
use App\Http\Controllers\Testaja;
use App\Livewire\Karyawanindexwr;
use App\Livewire\Deletepresensiwr;
use App\Livewire\Importkaryawanwr;
use App\Livewire\Updatekaryawanwr;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/test', Test::class)->name('test');
Route::get('/karyawancreate', Karyawanwr::class)->name('karyawancreate');
Route::get('/karyawanupdate/{id}', Updatekaryawanwr::class)->name('karyawanupdate');
// Route::get('/karyawanupdate', Updatekaryawanwr::class)->name('karyawanupdate');

Route::get('/karyawanindex', Karyawanindexwr::class)->name('karyawanindex');

// Route::resource('karyawan', KaryawanController::class);
Route::get('/karyawan', [KaryawanController::class,'index'])->name('karyawan.index');

// Route::get('/karyawan/hapus/{$id}', [KaryawanController::class,'hapus']);
Route::delete('/karyawan/{id}/destroy', [KaryawanController::class,'destroy'])->name('karyawan.destroy');

Route::delete('/karyawan/{id}/destroy', [KaryawanController::class,'destroy'])->name('karyawan.destroy');
Route::get('/presensidelete', Deletepresensiwr::class)->name('presensidelete');




Route::get('/testaja', [Testaja::class,'index']);
Route::middleware(['guest'])->group(function() {

});

Route::get('/testok', function() {
    return view('test');
});



Route::get('/presensiupload', function() {
    return view('content.presensi.import');
});

Route::get('/dashboard', [DashboardController::class,'index']);
Route::post('/presensi-update/{user_id}', [PresensiController::class, 'update_presensi'])->name('presensi.updatedata');
Route::delete('/presensi-delete/{user_id}/{date}', [PresensiController::class, 'delete_presensi'])->name('presensi.deletedata');
Route::resource('/presensi', PresensiController::class);

Route::get('/presensinormalize', [PresensiController::class, 'normalize'])->name('karyawan.normalize');
Route::post('/cari', [KaryawanController::class, 'cari'])->name('karyawan.cari');
Route::get('/resettable', [KaryawanController::class, 'resetTable'])->name('karyawan.resettable');

// menu khusus developer


Route::post('/karyawanimport', [KaryawanController::class,'import'])->name('karyawan.import');
Route::get('/karyawanviewimport', function() {
    return view('karyawan.importview');
});
Route::get('/erasedatakarayawan', [KaryawanController::class,'erase'])->name('karyawan.erase');








