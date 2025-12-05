<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController; // <--- Usamos el que corregimos antes
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CriptomonedasController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS (No requieren token)
|--------------------------------------------------------------------------
*/

// Login directo (sin prefijo 'auth') para coincidir con React
// Sintaxis moderna: [Clase::class, 'metodo']
Route::post('login', [LoginController::class, 'login']); 
// Registro público
Route::post('register', [RegisterController::class, 'register']);

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

    // Buscar usuarios por query (email o name) para seleccionar contacto en frontend
    Route::get('users/search', function (Request $request) {
        $q = $request->query('query', '');
        $users = User::where('email', 'like', "%{$q}%")
            ->orWhere('name', 'like', "%{$q}%")
            ->limit(10)
            ->get(['id','name','email']);
        return response()->json(['status' => 'ok', 'data' => $users]);
    });

    // Transfer endpoint (transfer money to a contact)
    Route::post('transfer', [\App\Http\Controllers\TransferController::class, 'store']);
    // Resto de recursos
    Route::resource('criptomonedas', CriptomonedasController::class);
    // Endpoint para listar transacciones del usuario autenticado
    Route::get('transactions', [\App\Http\Controllers\TransaccionController::class, 'index']);
    Route::resource('wallet', WalletController::class); // Quizás necesites cambiar esto a 'wallets' si React lo pide así
});