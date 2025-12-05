<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaccion; // O el modelo que uses
use App\Models\User;
use App\Models\Criptomoneda;
use App\Models\Wallet;

class TransaccionSeeder extends Seeder
{
    public function run()
    {
        // 1. CORREGIR EL EMAIL (Debe coincidir con UserSeeder)
        // Antes tenías 'admin@crypto.com', cámbialo al que usaste al principio
        $adminUser = User::where('email', 'admin@banco.com')->first(); 
        
        $btc = Criptomoneda::where('SHORTNAME', 'BTC')->first();

        // 2. VALIDACIÓN DE SEGURIDAD (Para que no explote)
        if (!$adminUser || !$btc) {
            $this->command->warn('⚠️ TransaccionSeeder: No se encontró el usuario admin o la moneda BTC.');
            return;
        }

        // 3. BUSCAR LA WALLET
        // Ahora $adminUser->id ya existe seguro
        $walletAdminBtc = Wallet::where('user_id', $adminUser->id)
                                ->where('ID_CRIPTO', $btc->ID_CRIPTO)
                                ->first();

        if (!$walletAdminBtc) {
            $this->command->warn('⚠️ TransaccionSeeder: El usuario no tiene Wallet de BTC creada.');
            return;
        }

        // 4. CREAR TRANSACCIÓN (Ejemplo)
        Transaccion::create([
            'ID_WALLET' => $walletAdminBtc->ID_WALLET,
            'TIPO' => 'deposito',
            'MONTO' => 1.0,
            'DESCRIPCION' => 'Deposito de minado',
        ]);
    }
}