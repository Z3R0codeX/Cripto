<?php

namespace App\Http\Controllers;

use App\Models\Transaccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Criptomoneda;
use App\Models\Contacto;

class TransaccionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Transaccion::whith('user')->get();
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cripto_id' => 'required|integer|exists:crypto_criptomonedas,ID_CRIPTO',
            'cantidad' => 'required|numeric|min:0',
            'tipo' => 'required|string|in:compra,venta',
        ]);
        $validated['user_id'] = Auth()::id(); 
        $transaccion = Transaccion::create($validated);
        return response()->json([
            'status' => 'transaccion creada',
            'data' => $transaccion
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $transaccion = Transaccion::whith('user','ID_WALLET')->findOrFail($id);
    {
        return response()->json([
            'status' => 'ok',
            'data' => $transaccion
        ]);
    }
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaccion $transaccion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        
        $validated = $request->validate([
            'cripto_id' => 'sometimes|required|integer|exists:crypto_criptomonedas,ID_CRIPTO,'.$transaccion->ID_TRANSACCION.',ID_TRANSACCION',
            'cantidad' => 'sometimes|required|numeric|min:0',
            'tipo' => 'sometimes|required|string|in:compra,venta',
        ]);
        $transaccion = Transaccion::findOrFail($id);
        $transaccion->update($validated);
        return response()->json([
            'status'=>'ok',
            'data'=>$transaccion
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $data = Transaccion::findOrFail($id);
        if($data){
            $data->delete();    
        }
            return response()->json([
                'status'=>'ok',
                'message'=>'Transaccion eliminada'
            ]); 
    }
}
