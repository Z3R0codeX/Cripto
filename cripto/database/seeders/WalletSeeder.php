<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Criptomoneda;
use App\Models\Wallet;
use App\Models\User;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::where('email', 'admin@crypto.com')->first();
        $demoUser = User::where('email', 'demo@crypto.com')->first();
        $btc = Criptomoneda::where('SHORTNAME', 'BTC')->first();
        $eth = Criptomoneda::where('SHORTNAME', 'ETH')->first();

        Wallet::create([
            'user_id' => $adminUser->id,
            'ID_CRIPTO' => $btc->ID_CRIPTO,
            'SALDO' => 2.5
        ]);
        
        Wallet::create([
            'user_id' => $adminUser->id,
            'ID_CRIPTO' => $eth->ID_CRIPTO,
            'SALDO' => 10.0
        ]);

        // 3. Crear Wallet para el Usuario Demo
        Wallet::create([
            'user_id' => $demoUser->id,
            'ID_CRIPTO' => $btc->ID_CRIPTO,
            'SALDO' => 0.75
        ]);
    }
}
