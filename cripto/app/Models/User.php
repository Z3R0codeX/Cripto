<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'FECHA_NACIMIENTO',
        'PHOTO',
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
    public function wallets()
    {
        // La clave foránea en 'crypto_wallets' es 'user_id'
        // La clave local en 'users' es 'id'
        return $this->hasMany(Wallet::class, 'user_id', 'id');
    }

    public function contactos()
    {
        // La clave foránea en 'crypto_contactos' es 'user_id'
        return $this->hasMany(Contacto::class, 'user_id', 'id');
    }
}
