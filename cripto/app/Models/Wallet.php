<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $table = 'crypto_wallets';
    protected $primaryKey = 'ID_WALLET';
    
    protected $fillable = ['user_id', 'ID_CRIPTO', 'SALDO'];

    public function user()
    {
        // Clave foránea local, Clave primaria en 'users'
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    public function criptomoneda()
    {
        // Clave foránea local, Clave primaria en 'crypto_criptomonedas'
        return $this->belongsTo(Criptomoneda::class, 'ID_CRIPTO', 'ID_CRIPTO');
    }

    public function transacciones()
    {
        // Clave foránea en 'crypto_transacciones', Clave local
        return $this->hasMany(Transaccion::class, 'ID_WALLET', 'ID_WALLET');
    }   
}
