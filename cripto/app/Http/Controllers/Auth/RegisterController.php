<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Criptomoneda;
use App\Models\Transaccion;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'balance' => 100,
        ]);

        // Create a default wallet for the user and an initial transaction so history shows up
        try {
            // Find a default crypto (first one) or fallback to ID 1
            $crypto = Criptomoneda::first();
            $cryptoId = $crypto ? $crypto->ID_CRIPTO : 1;

            $wallet = Wallet::create([
                'user_id' => $user->id,
                'ID_CRIPTO' => $cryptoId,
                'SALDO' => 100,
            ]);

            // Create an initial transaction record
            Transaccion::create([
                'ID_WALLET' => $wallet->ID_WALLET,
                'TIPO' => 'inicial',
                'MONTO' => 100,
                'DESCRIPCION' => 'Saldo inicial'
            ]);
        } catch (\Exception $e) {
            // No bloquear el registro del usuario por errores en wallets/transacciones,
            // pero logueamos el error para que lo revises.
            logger()->error('Error creando wallet/tx inicial: ' . $e->getMessage());
        }

        // Create token via Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 201);
    }
}
