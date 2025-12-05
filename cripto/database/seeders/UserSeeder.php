<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      
        // 1. El Admin
    User::create([
        'name' => 'Admin',
        'email' => 'admin@banco.com', // <--- Este lo busca WalletSeeder y ContactoSeeder
        'password' => bcrypt('1234'),
        // ... otros datos
    ]);

    // 2. El Usuario Demo (Para poder agregarlo como contacto)
    User::create([
        'name' => 'Usuario Demo',
        'email' => 'demo@crypto.com', // <--- Este lo busca ContactoSeeder
        'password' => bcrypt('1234'),
        // ... otros datos
    ]);
    }
}
