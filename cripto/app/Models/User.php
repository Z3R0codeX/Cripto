<?php

namespace App\Models;

// 1. QUITAMOS las líneas de JWTSubject y Tymon
// use Tymon\JWTAuth\Contracts\JWTSubject; <--- ELIMINAR

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // <--- 2. AGREGAMOS SANCTUM

// 3. QUITAMOS "implements JWTSubject"
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    // 4. AGREGAMOS "HasApiTokens" AQUÍ DENTRO
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'FECHA_NACIMIENTO', // Mantengo tus campos personalizados
        'PHOTO',            // Mantengo tus campos personalizados
        'balance',          // Agregué este porque lo usamos en la BD
        'account_number',   // Agregué este porque lo usamos en la BD
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- RELACIONES (Las mantenemos igual) ---

    public function wallets()
    {
        return $this->hasMany(Wallet::class, 'user_id', 'id');
    }

    public function contactos()
    {
        return $this->hasMany(Contacto::class, 'user_id', 'id');
    }

    // 5. ELIMINAMOS los métodos getJWTIdentifier() y getJWTCustomClaims()
    // Ya no son necesarios con Sanctum.
}