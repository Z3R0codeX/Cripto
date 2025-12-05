<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Wallet;
use App\Models\User;
use App\Models\Criptomoneda; // Asegúrate de tener este modelo
use Illuminate\Support\Facades\Hash;

class WalletSeeder extends Seeder
{
    public function run()
    {
        // 1. Buscar el usuario Admin (el que creamos antes)
        $adminUser = User::where('email', 'admin@banco.com')->first();

        // SEGURIDAD: Si no existe, lo creamos para evitar el error "null"
        if (!$adminUser) {
            $adminUser = User::create([
                'name' => 'Admin Banco',
                'email' => 'admin@banco.com',
                'password' => Hash::make('1234'),
                'balance' => 50000,
                'account_number' => '1234567890'
            ]);
        }

        // 2. Buscar las monedas (BTC y ETH)
        // NOTA: Asegúrate de que tu CryptoSeeder haya corrido antes que este
        $btc = Criptomoneda::where('SHORTNAME', 'BTC')->first();
        $eth = Criptomoneda::where('SHORTNAME', 'ETH')->first();

        // Si no hay monedas, no podemos crear wallets. Salimos.
        if (!$btc || !$eth) {
            $this->command->info('⚠️ No se encontraron criptomonedas (BTC/ETH). Ejecuta primero CriptoSeeder.');
            return;
        }

        // 3. Crear las Wallets
        // Wallet de BTC
        Wallet::create([
            'user_id' => $adminUser->id, // Ahora seguro que no es null
            'ID_CRIPTO' => $btc->ID_CRIPTO, // Asegúrate que tu modelo use este nombre de columna
            'SALDO' => 2.5
        ]);

        // Wallet de ETH
        Wallet::create([
            'user_id' => $adminUser->id,
            'ID_CRIPTO' => $eth->ID_CRIPTO,
            'SALDO' => 10.0
        ]);
    }
}