<?php

namespace App\Http\Controllers;

use App\Models\Contacto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class ContactoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data  = Contacto::whith('user')->get();
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
        $validated = $request->validate([
            'contacto_user_id' => 'required|integer|exists:users,id',
            'NAME' => 'required|string|max:255',
        ]);
        $validated['user_id'] = Auth()::id();
        $contacto = Contacto::create($validated);
        return response()->json([
            'status' => 'contacto creado',
            'data' => $contacto
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $contacto = Contacto::whith('user_id','contacto_user_id')->findOrFail($id);
    {
        return response()->json([
            'status' => 'ok',
            'message' => 'Contacto encontrado',
            'data' => $contacto
        ]);
    }
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
