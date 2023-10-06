<?php

use App\Livewire\Test;
use App\Livewire\Karyawan;
use App\Http\Controllers\Testaja;
use Illuminate\Support\Facades\Route;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

Route::get('/test', Test::class)->name('test');
// Route::get('/karyawan', Karyawan::class)->name('karyawan');  data lama
Route::get('/testaja', [Testaja::class,'index']);
Route::middleware(['guest'])->group(function() {

});

Route::get('/presensiupload', function() {
    return view('content.presensi.import');
});

Route::get('/karyawan', Karyawan::class);
Route::get('/dashboard', [DashboardController::class,'index']);
Route::post('/presensi-update/{user_id}', [PresensiController::class, 'update_presensi'])->name('presensi.updatedata');
Route::delete('/presensi-delete/{user_id}/{date}', [PresensiController::class, 'delete_presensi'])->name('presensi.deletedata');
Route::resource('/presensi', PresensiController::class);
