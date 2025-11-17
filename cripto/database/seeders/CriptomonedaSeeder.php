<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Criptomoneda;

class CriptomonedaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Criptomoneda::create([
            'NAME' => 'Bitcoin',
            'SHORTNAME' => 'BTC',
            'DECIMALES' => 8,
        ]);

        Criptomoneda::create([
            'NAME' => 'Ethereum',
            'SHORTNAME' => 'ETH',
            'DECIMALES' => 18,
        ]);
    }
}
