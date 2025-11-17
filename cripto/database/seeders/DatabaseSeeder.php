<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            // 1. Los que no dependen de nada
            UserSeeder::class,
            CriptomonedaSeeder::class,
            
            // 2. Los que dependen de los primeros
            WalletSeeder::class,
            ContactoSeeder::class,
            
            // 3. El que depende de 'Wallet'
            TransaccionSeeder::class,
        ]);
    }
}
