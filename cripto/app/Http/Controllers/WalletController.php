<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Criptomoneda;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Wallet::whith('user')->get();
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
            'ID_CRIPTO' => 'required|integer|exists:crypto_criptomonedas,ID_CRIPTO',
            'SALDO' => 'required|numeric|min:0',
        ]);
        $validated['user_id'] = Auth()::id(); 
        $wallet = Wallet::create($validated);
        return response()->json([
            'status' => 'wallet creada',
            'data' => $wallet
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $data = Wallet::whith('user','cripto_id')->findOrFail($id);
        {
            return response()->json([
            'status' => 'ok',
            'data' => $data
            ]); 
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wallet $wallet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        $validated = $request->validate([
            'ID_CRIPTO' => 'sometimes|required|integer|exists:crypto_criptomonedas,ID_CRIPTO',
            'SALDO' => 'sometimes|required|numeric|min:0',
        ]);
        $wallet = Wallet::findOrFail($id);
        $wallet->update($validated);
        return response()->json([
            'status' => 'wallet actualizada',
            'data' => $wallet
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $data = Wallet::findOrFail($id);
        if($data){
            $data->delete();   
        }
        return 
        response()->json([
        'status'=>'ok',
        'message'=>'Wallet eliminada'
        ]); 
    }
}
