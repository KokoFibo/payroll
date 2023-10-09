<?php

use App\Livewire\Test;
use App\Livewire\Karyawanwr;
use App\Http\Controllers\Testaja;
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
Route::get('/karyawan', [KaryawanController::class,'index'])->name('karyawan');
Route::get('/testaja', [Testaja::class,'index']);
Route::middleware(['guest'])->group(function() {

});

Route::get('/presensiupload', function() {
    return view('content.presensi.import');
});

Route::get('/dashboard', [DashboardController::class,'index']);
Route::post('/presensi-update/{user_id}', [PresensiController::class, 'update_presensi'])->name('presensi.updatedata');
Route::delete('/presensi-delete/{user_id}/{date}', [PresensiController::class, 'delete_presensi'])->name('presensi.deletedata');
Route::resource('/presensi', PresensiController::class);
