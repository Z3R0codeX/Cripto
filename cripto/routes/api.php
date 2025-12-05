<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController; // <--- Usamos el que corregimos antes
use App\Http\Controllers\CriptomonedasController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS (No requieren token)
|--------------------------------------------------------------------------
*/

// Login directo (sin prefijo 'auth') para coincidir con React
// Sintaxis moderna: [Clase::class, 'metodo']
Route::post('login', [LoginController::class, 'login']); 

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS (Requieren Login - Token Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Logout
    Route::post('logout', [LoginController::class, 'logout']);

    // Obtener usuario actual
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // --- RECURSOS DEL BANCO ---
    
    // Importante: Usamos 'contacts' en plural para coincidir con React
    Route::resource('contacts', ContactoController::class); 
    
    // Resto de recursos
    Route::resource('criptomonedas', CriptomonedasController::class);
    Route::resource('transactions', TransactionController::class);
    Route::resource('wallet', WalletController::class); // Quizás necesites cambiar esto a 'wallets' si React lo pide así
});