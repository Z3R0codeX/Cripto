<?php

namespace App\Http\Controllers;

use App\Models\Contacto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;


class ContactoController extends Controller
{
    public function __construct()
    {
        // Require Sanctum token authentication for mutating actions
        // Ensure index also requires auth so each user sees only their contacts
        $this->middleware('auth:sanctum')->only(['index','store', 'update', 'destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthenticated'], 401);
        }

        $data  = Contacto::with(['owner', 'contactUser'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json([
            'status'=>'ok',
            'data'=>$data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userId = Auth::id();

        Log::info('ContactoController@store called', ['user_id' => $userId, 'input' => $request->all()]);

        $validated = $request->validate([
            'contacto_user_id' => [
                'required','integer','exists:users,id',
                // Evita duplicados por usuario dueño (columna específica)
                Rule::unique('crypto_contactos', 'contacto_user_id')->where(function ($query) use ($userId) {
                    return $query->where('user_id', $userId);
                })
            ],
            'NAME' => 'required|string|max:255',
        ]);

        // Evitar que un usuario se agregue a sí mismo
        if ((int)$validated['contacto_user_id'] === (int)$userId) {
            return response()->json([
                'status' => 'error',
                'message' => 'No puedes agregarte a ti mismo como contacto.'
            ], 422);
        }
        $validated['user_id'] = $userId;

        try {
            $contacto = Contacto::create($validated);
            Log::info('Contacto creado', ['contacto_id' => $contacto->{$contacto->getKeyName()} ?? null]);
            return response()->json([
                'status' => 'contacto creado',
                'data' => $contacto
            ], 201);
        } catch (QueryException $e) {
            // Manejar errores de base de datos (p.ej. constraint violations)
            Log::error('Error al crear contacto', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Error al crear contacto',
                'detail' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $contacto = Contacto::with(['owner', 'contactUser'])->findOrFail($id);

        return response()->json([
            'status' => 'ok',
            'message' => 'Contacto encontrado',
            'data' => $contacto
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contacto $contacto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {

        $validated = $request->validate([
            'contacto_user_id' => 'sometimes|required|integer|exists:users,id',
            'NAME' => 'sometimes|required|string|max:255',
        ]);

        $data = Contacto::findOrFail($id);
        if($data){
            $data->update($validated);
            return response()->json([
                'status' => 'ok',
                'message' => 'Contacto actualizado',
                'data' => $data
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Contacto no encontrado'  
        ],400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $data = Contacto::findOrFail($id);
        if($data){
            $data->delete();
        }
            return response()->json([
                'status'=>'ok',
                'message'=>'Contacto eliminado'
            ]);
        
        
    }
}
