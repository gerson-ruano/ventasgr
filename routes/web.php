<?php

use App\Livewire\Asignar;
use App\Livewire\Pos;
use Illuminate\Support\Facades\Route;
use App\Livewire\Categories;
use App\Livewire\Products;
use App\Livewire\Coins;
use App\Livewire\Users;
use App\Livewire\Roles;
use App\Livewire\Permisos;
use App\Livewire\Reports;
use App\Livewire\Cashout;
use App\Http\Controllers\ExportController;


Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

Route::middleware(['auth','checkStatus'])->group(function () {

    Route::view('profile', 'profile')
        ->middleware(['auth'])
        ->name('profile');

//Route::prefix('admin')->middleware(['permission'])->group(function () {
    Route::middleware(['role:Admin'])->group(function () {

        Route::get('users', Users::class)->name('users');
        Route::get('roles', Roles::class)->name('roles');
        Route::get('permisos', Permisos::class)->name('permisos');
        Route::get('asignar', Asignar::class)->name('asignar');


    });
    Route::middleware(['role:Admin|Employee'])->group(function () {

        Route::get('categories', Categories::class)->name('categories');
        Route::get('products', Products::class)->name('products');
        Route::get('coins', Coins::class)->name('coins');
        Route::get('pos', Pos::class)->name('pos');
        Route::get('reports', Reports::class)->name('reports');
        Route::get('cashout', Cashout::class)->name('cashout');

        //REPORTES GENERALES PDF
        Route::get('report/pdf/{user}/{type}/{f1}/{f2}/{selectTipoEstado}', [ExportController::class, 'reportPDF']);
        //REPORTE VENTA PDF
        Route::get('report/details/{seller}/{nextSaleNumber}', [ExportController::class, 'reportDetails'])->name('report.details');
        //REPORTES CIERRE DE CAJA PDF
        Route::get('report/box/{seller}/{nextSaleNumber}', [ExportController::class, 'reportBox'])->name('report.box');
        //IMPRESION VENTA PDF
        Route::get('report/venta/{change}/{efectivo}/{seller}/{nextSaleNumber}', [ExportController::class, 'reportVenta'])->name('report.venta');

        //REPORTES EXCEL
        Route::get('report-excel/{user}/{type}/{f1}/{f2}/{selectTipoEstado}', [ExportController::class, 'reportExcel']);
        //Route::get('report-excel', [ExportController::class,'reportExcel']);
    });

    Route::middleware(['role:Admin|Employee|Seller'])->group(function () {

    });
});


//Route::view('categories', Categories::class);
require __DIR__ . '/auth.php';

