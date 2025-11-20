<?php

namespace App\Http\Controllers;

use App\Models\Criptomoneda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CriptomonedaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data  = Criptomoneda::whith('user')->get();
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
            'NAME' => 'required|string|max:50|unique:crypto_criptomonedas,NAME',
            'SHORTNAME' => 'required|string|max:10|unique:crypto_criptomonedas,SHORTNAME',
            'DECIMALES' => 'required|numeric|min:0',
        ]);
        $validated['user_id'] = Auth()::id();
        $criptomoneda = Criptomoneda::create($validated);
        return response()->json([
            'status' => 'criptomoneda creada',
            'data' => $criptomoneda
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $criptomoneda = Criptomoneda::whith('owner')->findOrFail($id);
    {
        return response()->json([
            'status' => 'ok',
            'data' => $criptomoneda
        ]);
    }
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Criptomoneda $criptomoneda)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        
        $validated = $request->validate([
            'NAME' => 'sometimes|required|string|max:50|unique:crypto_criptomonedas,NAME,'.$criptomoneda->ID_CRIPTO.',ID_CRIPTO',
            'SHORTNAME' => 'sometimes|required|string|max:10|unique:crypto_criptomonedas,SHORTNAME,'.$criptomoneda->ID_CRIPTO.',ID_CRIPTO',
            'DECIMALES' => 'sometimes|required|numeric|min:0',
        ]);
        $data = Criptomoneda::findOrFail($id);
         if($data){
            $data->update($validated);
            return response()->json([
                'status'=>'ok',
                'message'=>'Criptomoneda actualizada',
                'data'=>$data
            ]);
         }
}
            

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $data = Criptomoneda::findOrFail($criptomoneda->ID_CRIPTO);
        if($data){
            $data->delete();    
        }
            return response()->json([
                'status'=>'ok',
                'message'=>'Criptomoneda eliminada'
            ]);
    }
}
