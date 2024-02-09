<?php

// use App\Http\Controllers\Api\Auth\QntrlController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\SimcardQntrlController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group( function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/users', function () {
        return response()->json(User::all());
    });
    Route::post('/users/{id}', function ($id) {
        return response()->json(User::findOrFail($id));
    });
    
});

Route::get('/activation', [SimcardQntrlController::class, 'activation']);
Route::get('/otpverify', [SimcardQntrlController::class, 'otpverify']);

// Route::prefix('qntrl')->as('qntrl.')->controller(QntrlController::class)->group(function() {
//     Route::get('index', 'index')->name('index');
//     Route::get('create', 'create')->name('create');
//     Route::post('store', 'store')->name('store');
//     Route::get('show/{id}', 'show')->name('show');
//     Route::get('{id}/edit', 'edit')->name('edit');
//     Route::post('{update', 'update')->name('update');
//     Route::delete('delete/{id}', 'delete')->name('delete');
// });
