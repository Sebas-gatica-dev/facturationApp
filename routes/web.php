<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ExcelToTxtController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {




    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/clientes-view', function () {
        return view('clientes');
    })->name('clientes-view');
  
    
    Route::get('/facturaciones', function () {
        return view('facturaciones');
    })->name('facturaciones');

    Route::get('/alta-facturaciones', function () {
        return view('altafacturaciones');
    })->name('alta-facturaciones');


    



    Route::get('/servicios', function () {
        return view('servicios');
    })->name('servicios');

    Route::get('/exports', function () {
        return view('exports');
    })->name('exports');

    // routes/web.php


    Route::post('/excel-to-txt', [ExcelToTxtController::class, 'convert'])->name('excel.to.txt');

// Route::get('/facturaciones-table', FacturationTable::class)->name('facturaciones.table');


//ruta de exportacion de datos

// routes/web.php

    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
   // routes/web.php


// Route::get('/facturation-table', FacturationTable::class)->name('facturacion.table');

    
Route::controller(ClienteController::class)->prefix('clientes')->group(function () {
 
    Route::post('store', 'store')->name('clientes.store');
  
});

});

