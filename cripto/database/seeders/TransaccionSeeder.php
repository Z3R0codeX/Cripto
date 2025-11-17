<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Wallet;
use App\Models\Transaccion;
use App\Models\User;
use App\Models\Criptomoneda;

class TransaccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::where('email', 'admin@crypto.com')->first();
        $btc = Criptomoneda::where('SHORTNAME', 'BTC')->first();
        
        $walletAdminBtc = Wallet::where('user_id', $adminUser->id)
                                  ->where('ID_CRIPTO', $btc->ID_CRIPTO)
                                  ->first();

       
        Transaccion::create([
            'ID_WALLET' => $walletAdminBtc->ID_WALLET,
            'TIPO' => 'compra',
            'MONTO' => 1.5,
            'DESCRIPCION' => 'Compra inicial en exchange',
        ]);
        
        Transaccion::create([
            'ID_WALLET' => $walletAdminBtc->ID_WALLET,
            'TIPO' => 'deposito',
            'MONTO' => 1.0,
            'DESCRIPCION' => 'Deposito de minado',
        ]);
    }
}
