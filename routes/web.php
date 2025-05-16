<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\DetailLokasiController;
use App\Http\Controllers\StoController;
use App\Http\Controllers\convertExcelToCsv;
use App\Http\Controllers\DailyStockLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForecastController;
use App\Http\Controllers\DailyReportController;

/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [AuthController::class, 'showAdmin'])->name('admin.login');
Route::get('user/login', [AuthController::class, 'showUser'])->name('user.login');
Route::post('admin/login', [AuthController::class, 'login'])->name('admin.login.post');
Route::post('user/login', [AuthController::class, 'userLogin'])->name('user.login.post');
Route::post('admin/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'admin.only'])->group(function () {
    // dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/fetch-daily-stock', [DashboardController::class, 'dailyStockSummary']);
    Route::get('/dashboard/daily-chart-data', [DashboardController::class, 'getDailyChartData'])->name('dashboard.dailyChartData');
    Route::get('/dashboard/sto-chart-data', [DashboardController::class, 'getStoChartData']);
    // forecast
    Route::get('/forecast', [ForecastController::class, 'index'])->name('forecast.index');
    Route::post('/forecast/generate', [ForecastController::class, 'generate'])->name('forecast.generate');
    Route::get('/forecast/create', [ForecastController::class, 'create'])->name('forecast.create');
    Route::post('/forecast/store', [ForecastController::class, 'store'])->name('forecast.store');
    Route::get('/forecast/{id}/edit', [ForecastController::class, 'edit'])->name('forecast.edit');
    Route::put('/forecast/{id}', [ForecastController::class, 'update'])->name('forecast.update');
    Route::delete('/forecast/{id}', [ForecastController::class, 'destroy'])->name('forecast.destroy');
    Route::post('/forecast/import', [ForecastController::class, 'import'])->name('forecast.import');
    // user management
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
    // detail lokasi
    Route::get('/detail-lokasi', [DetailLokasiController::class, 'index'])->name('detail-lokasi.index');
    Route::get('/create', [DetailLokasiController::class, 'create'])->name('create.detail-lokasi');
    Route::post('/detail-lokasi-post', [DetailLokasiController::class, 'store'])->name('detail-lokasi.store');
    Route::get('/{rak}/edit', [DetailLokasiController::class, 'edit'])->name('edit.detail-lokasi');
    Route::put('/{rak}', [DetailLokasiController::class, 'update'])->name('update.detail-lokasi');
    Route::delete('/{rak}', [DetailLokasiController::class, 'destroy'])->name('destroy.detail-lokasi');
    Route::post('/detail-lokasi/import', [DetailLokasiController::class, 'import'])->name('detail-lokasi.import');
    Route::get('/get-area-by-plan/{id}', [DetailLokasiController::class, 'getByPlan']);
    // sto
    Route::get('/sto', [StoController::class, 'index'])->name('sto.index');
    Route::get('/sto/create', [StoController::class, 'create'])->name('sto.create.get');
    Route::post('/sto/store', [StoController::class, 'store'])->name('sto.store');
    Route::get('/sto/edit/{id}', [StoController::class, 'edit'])->name('sto.edit');
    Route::put('/sto/update/{id}', [StoController::class, 'update'])->name('sto.update');
    Route::delete('/sto/destroy/{id}', [StoController::class, 'destroy'])->name('sto.destroy');
    Route::post('/sto/import', [StoController::class, 'import'])->name('sto.import');
    Route::get('/sto/export', [StoController::class, 'export'])->name('sto.export');
    // daily stock log
    Route::get('/daily-stock', [DailyStockLogController::class, 'index'])->name('daily-stock.index');
    Route::post('/daily-stock/import', [DailyStockLogController::class, 'import'])->name('daily-stock.import.process');
    Route::get('daily-stock/{id}/edit', [DailyStockLogController::class, 'edit'])->name('reports.edit');
    Route::put('daily-stock/{id}', [DailyStockLogController::class, 'update'])->name('reports.update');
    Route::delete('daily-stock/{id}', [DailyStockLogController::class, 'destroy'])->name('reports.destroy');
    Route::get('/daily-stock/export', [DailyStockLogController::class, 'export'])->name('daily-stock.export');
    // dynamic select
    Route::get('/get-areas/{plantId}', [PartController::class, 'getAreas']);
    Route::get('/get-raks/{areaId}', [PartController::class, 'getRaks']);
    // excel convert
    Route::post('/convert', [convertExcelToCsv::class, 'convert'])->name('convert.excel');

});

Route::middleware(['auth', 'user.only'])->group(function () {
    Route::get('/daily-report', [DailyReportController::class, 'index'])->name('dailyreport.index');
    Route::post('/daily-report/scan', [DailyReportController::class, 'scan'])->name('sto.scan');
    Route::get('/daily-report/search', [DailyReportController::class, 'search'])->name('sto.search');
    Route::get('/daily-report/create', [DailyReportController::class, 'create'])->name('sto.create.report');
    Route::get('/report/print/{id}', [DailyReportController::class, 'printReport'])->name('reports.print');
    Route::post('/daily-report/newdaily', [DailyReportController::class, 'storeNew'])->name('sto.storeNew');
    Route::post('/daily-report/{inventory_id}', [DailyReportController::class, 'storecreate'])->name('sto.store');
    Route::get('/sto/form/{inventory_id}', [DailyReportController::class, 'form'])->name('sto.edit.report');
    Route::get('/sto/edit-log/{id}', [DailyReportController::class, 'editLog'])->name('sto.edit.log');
    Route::post('/sto/update-log/{id}', [DailyReportController::class, 'updateLog'])->name('sto.updateLog');
});



