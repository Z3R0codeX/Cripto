<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacto extends Model
{
    use HasFactory;
  
    protected $table = 'crypto_contactos';
    protected $primaryKey = 'ID_CONTACTO';

    protected $fillable = ['user_id', 'contacto_user_id', 'NAME'];

    public function owner()
    {
        // Clave foránea local, Clave primaria en 'users'
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function contactUser()
    {
        // Clave foránea local, Clave primaria en 'users'
        return $this->belongsTo(User::class, 'contacto_user_id', 'id');
    }
}
