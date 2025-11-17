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
      
        User::create([
            'name' => 'Admin Usuario',
            'email' => 'admin@crypto.com',
            'password' => Hash::make('password123'), // Contraseña encriptada
            'FECHA_NACIMIENTO' => '1990-01-15',
            'PHOTO' => 'https://example.com/photos/admin.jpg',
        ]);

        User::create([
            'name' => 'Demo Usuario',
            'email' => 'demo@crypto.com',
            'password' => Hash::make('password123'),
            'FECHA_NACIMIENTO' => '1995-05-20',
            'PHOTO' => 'https://example.com/photos/demo.jpg',
        ]);
    }
}
