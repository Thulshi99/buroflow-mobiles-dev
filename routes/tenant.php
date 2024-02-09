<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarrierController;
use App\Http\Controllers\SimCardController;
use App\Http\Controllers\LoginController;
use App\Http\Middleware\TeamSubdomainMiddleware;
use App\Http\Controllers\ServiceQualificationController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use App\Http\Controllers\Api\Auth\QntrlController;
use App\Http\Controllers\Api\Auth\SimcardQntrlController;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Stancl\Tenancy\Features\UserImpersonation;
use App\Http\Controllers\DataPoolController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ResellerController;
use App\Http\Controllers\MobileplanController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ReportViewController;
use App\Http\Controllers\ServicesController;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/
// Route::group(['middleware' => 'tenancy.enforce'], function () {
//     Auth::routes();
// });

Route::post('/user-login', [LoginController::class, 'loginUser']);

Route::middleware([
    // 'auth',
    'auth:sanctum',
    'verified',
    'universal',
    // 'auth:tenant',
    'web',
    InitializeTenancyByDomain::class,
    // TeamSubdomainMiddleware::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/superloop/orders/create', function () {
        return view('superloop.orders.create');
    })->name('nbn.order.create');

    Route::get('/superloop/location/search', function () {
        return view('superloop.location.search');
    })->name('sq.search');

    Route::get('/superloop/location/qualification', function () {
        return view('superloop.location.qualification');
    })->name('sq.qualify');

    Route::resource('sq', ServiceQualificationController::class);
    Route::resource('carriers', CarrierController::class);
    Route::resource('simcards', SimCardController::class);

    Route::get('/teams', function () {
        return view('teams.list');
    })->name('teams.index');

    Route::get('/users', function () {
        return view('users');
    })->name('users');

    Route::get('/users/create', function () {
        return view('users.create');
    })->name('users.create');


    Route::get('email/emailPdf', [EmailController::class, 'emailPdf'])->name("email.emailPdf");
    Route::resource('email', EmailController::class);

    // Route::get('/send-mail',[EmailController::class,'sendMailWithPdf']);
    Route::get('reporttests/download', [ReportController::class, 'generatePDF'])->name("reporttests.download");
    Route::resource('reporttests', ReportController::class);

    Route::get('reports/reportcustomisation', function () {
        return view('reports.reportcustomization');
    })->name('reportcustomization');


    Route::get('reports/reportgeneration', function () {
        return view('reports.reportgeneration');
    })->name('reportgeneration');

    Route::prefix('qntrl')->as('qntrl.')->controller(QntrlController::class)->group(function() {
        Route::get('index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('show/{id}', 'show')->name('show');
        Route::get('{id}/edit', 'edit')->name('edit');
        Route::post('{update', 'update')->name('update');
        Route::delete('delete/{id}', 'delete')->name('delete');
    });

    Route::prefix('simcard-qntrl')->as('simcard-qntrl.')->controller(SimcardQntrlController::class)->group(function() {
        Route::get('index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('show/{id}', 'show')->name('show');
        Route::get('{id}/edit', 'edit')->name('edit');
        Route::post('{update', 'update')->name('update');
        Route::delete('delete/{id}', 'delete')->name('delete');
    });


    Route::get('datapools/manage/{id}', [DataPoolController::class, 'manage'])->name('datapools.manage');
    Route::get('datapools/{id}/add-bolton', [DataPoolController::class, 'addBolton'])->name('datapools.add-bolton');
    //Route::get('datapools/{id}/add_data_topup', [DataPoolController::class, 'addDataTopUp'])->name('datapools.addDataTopup');
    Route::get('datapools/manage/{id}/add_services', [DataPoolController::class, 'addService'])->name('datapools.manage.add');
    Route::get('datapools/manage/{id}/add_services_bulk', [DataPoolController::class, 'addServicesBulk'])->name('datapools.manage.add.fromFile');
    //Route::get('datapools/{id}/data_limit_config', [DataPoolController::class, 'dataLimitConfig'])->name('datapools.dataLimitConfig');
    Route::get('datapools/{id}/data_consumption', [DataPoolController::class, 'dataConsumption'])->name('datapools.dataConsumption');
    Route::resource('datapools', DataPoolController::class);

    Route::resource('customers', CustomerController::class);
    Route::get('mobileplans/assign', [MobileplanController::class, 'assignMobilePlan'])->name("mobileplans.assign");
    Route::resource('mobileplans', MobileplanController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('services', ServicesController::class);
    Route::resource('supporttickets', SupportTicketController::class);



});
