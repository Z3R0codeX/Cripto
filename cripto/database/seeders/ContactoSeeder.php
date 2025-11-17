<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Contacto;

class ContactoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $adminUser = User::where('email', 'admin@crypto.com')->first();
        $demoUser = User::where('email', 'demo@crypto.com')->first();

        
        Contacto::create([
            'user_id' => $adminUser->id, // El dueño de la lista
            'contacto_user_id' => $demoUser->id, // El usuario añadido
            'NAME' => 'Demo (Vecino)', // Alias para el contacto
        ]);
    }
}
