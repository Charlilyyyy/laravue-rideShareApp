<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\loginController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\TripController;

Route::post('/login',[loginController::class, 'submit']);
Route::post('/login/verify',[loginController::class, 'verify']);

Route::group(['midlleware' => 'auth:sanctum'] , function () {
    Route::prefix('driver')->group(function () {
        Route::get('/', [DriverController::class, 'show']);
        Route::post('/', [DriverController::class, 'update']);
    });    

    Route::prefix('trips')->group(function () {
        Route::post('/', [TripController::class, 'store']);
        Route::get('/{trip}', [TripController::class, 'show']);
        Route::post('/{trip}/accept', [TripController::class, 'accept']);
        Route::post('/{trip}/start', [TripController::class, 'start']);
        Route::post('/{trip}/end', [TripController::class, 'end']);
        Route::post('/{trip}/location', [TripController::class, 'location']);
    });
    

    // Route::post('/trip', [TripController::class, 'store']);
    // Route::get('/trip/{trip}', [TripController::class, 'show']);
    // Route::post('/trip/{trip}/accept', [TripController::class, 'accept']);
    // Route::post('/trip/{trip}/start', [TripController::class, 'start']);
    // Route::post('/trip/{trip}/end', [TripController::class, 'end']);
    // Route::post('/trip/{trip}/location', [TripController::class, 'location']);

    Route::get('/user', function(Request $request){
        return $request->user();
    });
});