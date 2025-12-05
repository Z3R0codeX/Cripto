<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contacto;
use App\Models\User;

class ContactoSeeder extends Seeder
{
    public function run()
    {
        // 1. RECUPERAR AL ADMIN (Esto es lo que te faltaba)
        // Asegúrate de que este email sea el mismo que creaste en UserSeeder
        $adminUser = User::where('email', 'admin@banco.com')->first(); 

        // 2. RECUPERAR AL USUARIO "DEMO" (El que será el contacto)
        // OJO: Asegúrate de que UserSeeder haya creado este email también
        $demoUser = User::where('email', 'demo@crypto.com')->first();

        // VALIDACIÓN DE SEGURIDAD
        // Si alguno de los dos no existe, detenemos el script para que no explote
        if (!$adminUser || !$demoUser) {
            $this->command->warn('⚠️ No se encontraron los usuarios (admin o demo) para crear contactos.');
            return;
        }

        // 3. CREAR EL CONTACTO
        Contacto::create([
            'user_id' => $adminUser->id,          // El dueño de la agenda
            'contacto_user_id' => $demoUser->id,  // El amigo agregado
            'NAME' => 'Demo (Vecino)',            // Alias
            // Asegúrate si tu tabla pide 'account_number' o no. 
            // Si usas mi migración anterior, quizás necesites 'account_number' en lugar de 'contacto_user_id'
            // Revisa tu migración create_contacts_table.
        ]);
    }
}