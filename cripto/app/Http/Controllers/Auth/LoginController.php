<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Asegúrate de importar el modelo User

class LoginController extends Controller
{
    // ELIMINAMOS showLoginForm() PORQUE REACT YA TIENE SU PROPIO FORMULARIO

    public function login(Request $request)
    {
        // 1. Validar los datos que vienen de React
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Intentar loguear
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // 3. ¡IMPORTANTE! Generar el Token (Laravel Sanctum)
            // Esto es la llave que React guardará
            $token = $user->createToken('auth_token')->plainTextToken;

            // 4. Devolver JSON en lugar de redirect
            return response()->json([
                'message' => 'Login exitoso',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ], 200);
        }

        // 5. Si falla, devolver error JSON
        return response()->json([
            'message' => 'Las credenciales no coinciden con nuestros registros.'
        ], 401); // 401 significa No Autorizado
    }

    public function logout(Request $request)
    {
        // Borrar el token actual (Cerrar sesión en API)
        // Usamos el helper de Sanctum
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json([
            'message' => 'Sesión cerrada correctamente'
        ]);
    }
}