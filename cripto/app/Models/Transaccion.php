<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaccion extends Model
{
    use HasFactory;

    protected $table = 'crypto_transacciones';
    protected $primaryKey = 'ID_TRANSACCION';

    protected $fillable = ['ID_WALLET', 'TIPO', 'MONTO', 'DESCRIPCION'];

    public function wallet()
    {
        // Clave foránea local, Clave primaria en 'crypto_wallets'
        return $this->belongsTo(Wallet::class, 'ID_WALLET', 'ID_WALLET');
    }
}
