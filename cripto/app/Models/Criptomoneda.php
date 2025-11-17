<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criptomoneda extends Model
{
    use HasFactory;

    protected $table = 'crypto_criptomonedas';
    protected $primaryKey = 'ID_CRIPTO';
    
    // Campos que permitimos llenar masivamente
    protected $fillable = ['NAME', 'SHORTNAME', 'DECIMALES'];

    public function wallets()
    {
        // Clave foránea en 'crypto_wallets', Clave local en 'crypto_criptomonedas'
        return $this->hasMany(Wallet::class, 'ID_CRIPTO', 'ID_CRIPTO');
    }
}
