<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\DetailLokasiController;
use App\Http\Controllers\StoController;
use App\Http\Controllers\convertExcelToCsv;


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
Route::get('/', [AuthController::class, 'showAdmin'])->name('admin.login');
Route::post('admin/login', [AuthController::class, 'login'])->name('admin.login.post');
Route::post('admin/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('admin/user', [UserController::class, 'index'])->name('users.index');
Route::get('admin/user/create', [UserController::class, 'create'])->name('users.create');
Route::post('admin/user/store', [UserController::class, 'store'])->name('users.store');

Route::get('admin/user/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('admin/user/{id}', [UserController::class, 'update'])->name('users.update');
Route::delete('admin/user/{id}', [UserController::class, 'destroy'])->name('users.destroy');

// part
Route::get('/parts', [PartController::class, 'index'])->name('parts.index');
Route::get('/parts/create', [PartController::class, 'create'])->name('parts.create');
Route::post('/parts', [PartController::class, 'store'])->name('parts.store');
Route::get('/parts/{part}/edit', [PartController::class, 'edit'])->name('parts.edit');
Route::put('/parts/{part}', [PartController::class, 'update'])->name('parts.update');
Route::delete('/parts/{part}', [PartController::class, 'destroy'])->name('parts.destroy');
Route::post('/parts/import', [PartController::class, 'import'])->name('parts.import');

Route::get('/detail-lokasi', [DetailLokasiController::class, 'index'])->name('detail-lokasi.index');
Route::get('/create', [DetailLokasiController::class, 'create'])->name('create.detail-lokasi');
Route::post('/detail-lokasi-post', [DetailLokasiController::class, 'store'])->name('detail-lokasi.store');
Route::get('/{rak}/edit', [DetailLokasiController::class, 'edit'])->name('edit.detail-lokasi');
Route::put('/{rak}', [DetailLokasiController::class, 'update'])->name('update.detail-lokasi');
Route::delete('/{rak}', [DetailLokasiController::class, 'destroy'])->name('destroy.detail-lokasi');

// sto
Route::get('/sto', [StoController::class, 'index'])->name('sto.index');
Route::get('/sto/create', [StoController::class, 'create'])->name('sto.create.get');
Route::post('/sto/store', [StoController::class, 'store'])->name('sto.store');
Route::get('/sto/edit/{id}', [StoController::class, 'edit'])->name('sto.edit');
Route::put('/sto/update/{id}', [StoController::class, 'update'])->name('sto.update');


// select part area
Route::get('/get-areas/{plantId}', [PartController::class, 'getAreas']);
Route::get('/get-raks/{areaId}', [PartController::class, 'getRaks']);

// convert excel to csv
Route::post('/convert', [convertExcelToCsv::class, 'convert'])->name('convert.excel');



// example route
Route::get('admin/dashboard', function () {
    return view('welcome');  // Ganti dengan tampilan dashboard kamu
})->name('admin.dashboard');

// Route::get('/', function () {
//     return view('welcome');
// });
