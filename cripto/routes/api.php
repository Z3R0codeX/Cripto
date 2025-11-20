<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CriptomonedasController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});

Route::resource('criptomonedas', CriptomonedasController::class);
Route::resource('contacto', ContactoController::class);
Route::resource('transactions', TransactionController::class);
Route::resource('wallet', WalletController::class);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
