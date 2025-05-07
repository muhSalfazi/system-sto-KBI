<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PartController;


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




// example route
Route::get('admin/dashboard', function () {
    return view('welcome');  // Ganti dengan tampilan dashboard kamu
})->name('admin.dashboard');

// Route::get('/', function () {
//     return view('welcome');
// });
