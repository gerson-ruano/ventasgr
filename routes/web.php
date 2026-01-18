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
use App\Http\Controllers\GraphicsController;
use App\Livewire\ApiIntegration;
use App\Http\Controllers\HomeController;
use App\Livewire\Settings\Components;
use App\Livewire\Companies;
use App\Livewire\Admodules;


Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

Route::redirect('/', '/login');

// Actualizar Tema de Usuario
Route::middleware('auth')->post('/user/update-theme', [Users::class, 'updateTheme'])->name('user.update-theme');

Route::middleware(['auth','checkStatus'])->group(function () {
    Route::view('profile', 'profile')->name('profile');
    Route::get('/home', [HomeController::class, 'index'])->middleware(['auth'])->name('home');
    Route::get('home/{module}', [HomeController::class, 'show'])->name('modules.show');

//Route::prefix('admin')->middleware(['permission'])->group(function () {
    Route::middleware(['role:Admin'])->group(function () {
        Route::get('config', Components::class)->name('config');
        Route::get('companies', Companies::class)->name('companies');
        Route::get('users', Users::class)->name('users');
        Route::get('roles', Roles::class)->name('roles');
        Route::get('permisos', Permisos::class)->name('permisos');
        Route::get('asignar', Asignar::class)->name('asignar');
        Route::get('modules', Admodules::class)->name('modules');
    });
    Route::middleware(['role:Admin|Employee'])->group(function () {
        Route::get('categories', Categories::class)->name('categories');
        Route::get('products', Products::class)->name('products');
        Route::get('coins', Coins::class)->name('coins');
        Route::get('reports', Reports::class)->name('reports');
        Route::get('cashout', Cashout::class)->name('cashout');
        Route::get('graphics', [GraphicsController::class, 'index'])->name('graphics');
        Route::get('api', ApiIntegration::class)->name('api');

        //REPORTE GENERAL DE VENTAS PDF
        Route::get('report/pdf/{user}/{type}/{f1}/{f2}/{selectTipoEstado}', [ExportController::class, 'reportPDF']);
        //REPORTE DE X VENTA PDF
        Route::get('report/details/{seller}/{nextSaleNumber}', [ExportController::class, 'reportDetails'])->name('report.details');
        //REPORTE CIERRE DE CAJA X VENTA PDF
        Route::get('report/box/{seller}/{nextSaleNumber}', [ExportController::class, 'reportBox'])->name('report.box');
        //REPORTE GENERAL DE CIERRE DE CAJA PDF
        Route::get('report/box/{userid}/{f1}/{f2}', [ExportController::class, 'reportBoxGeneral'])->name('report.boxgeneral');

        //REPORTES EXCEL
        //Reporte general de ventas
        Route::get('report-excel/{user}/{type}/{f1}/{f2}/{selectTipoEstado}', [ExportController::class, 'reportExcel']);
        //Reporte general de cierre de caja
        Route::get('report-excel/{userid}/{f1}/{f2}', [ExportController::class, 'reportBoxExcel']);
        //Route::get('report-excel', [ExportController::class,'reportExcel']);
    });

    //IMPRESION VENTA PDF
    Route::get('report/venta/{change}/{efectivo}/{seller}/{nextSaleNumber}/{totalTaxes}/{discount}/{customer_data}', [ExportController::class, 'reportVenta'])->name('report.venta');

    Route::middleware(['role:Admin|Employee|Seller'])->group(function () {
        Route::get('pos', Pos::class)->name('pos');
    });

    //RegisterModuleRoutes(config('modules'));
});


//Route::view('categories', Categories::class);
require __DIR__ . '/auth.php';

