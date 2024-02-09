<?php

use App\Http\Controllers\CarrierController;
use App\Http\Controllers\ServiceQualificationController;
use App\Http\Controllers\SimCardController;
use App\Http\Middleware\CentralTenantAccessMiddleware;
use App\Models\ServiceQualification;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use App\Http\Controllers\Api\Auth\QntrlController;
use App\Http\Controllers\RadiusController;
use App\Http\Controllers\ResellerController;

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

Route::resource('resellers', ResellerController::class);

Route::get('/', function () {
    return \Redirect::to('http://localhost:8000/login');
});
Route::get('/login', function () {
    return \Redirect::to('http://localhost:8000/login');
});


// Route::group(['middleware' => 'tenancy.enforce'], function () {
//     Auth::routes();
// });

// Route::middleware([
//     'auth:sanctum', 
//     'verified', 
//     'universal',
//     InitializeTenancyByDomain::class,
//     CentralTenantAccessMiddleware::class
// ])->group( function() {

//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');

//     Route::get('/superloop/orders/create', function () {
//         return view('superloop.orders.create');
//     })->name('nbn.order.create');
//     Route::get('/superloop/location/search', function () {
//         return view('superloop.location.search');
//     })->name('sq.search');
//     Route::get('/superloop/location/qualification', function () {
//         return view('superloop.location.qualification');
//     })->name('sq.qualify');

//     Route::resource('sq', ServiceQualificationController::class);
//     Route::resource('carriers', CarrierController::class);
//     Route::resource('simcards', SimCardController::class);
    
//     Route::get('/teams', function () {
//         return view('teams.list');
//     })->name('teams.index');

//     Route::get('/users', function () {
//         return view('users');
//     })->name('users');

//     Route::prefix('qntrl')->as('qntrl.')->controller(QntrlController::class)->group(function() {
//         Route::get('index', 'index')->name('index');
//         Route::get('create', 'create')->name('create');
//         Route::post('store', 'store')->name('store');
//         Route::get('show/{id}', 'show')->name('show');
//         Route::get('{id}/edit', 'edit')->name('edit');
//         Route::post('update', 'update')->name('update');
//         Route::delete('delete/{id}', 'delete')->name('delete');
//     });

//     Route::get('/radius/index', [RadiusController::class, 'index']);

// });
