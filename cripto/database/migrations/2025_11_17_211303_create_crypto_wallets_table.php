<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('crypto_wallets', function (Blueprint $table) {
        $table->increments('ID_WALLET');
        $table->unsignedBigInteger('user_id'); // Clave foránea para 'users'
        $table->unsignedInteger('ID_CRIPTO'); // Clave foránea para 'criptomonedas'
        $table->decimal('SALDO', 36, 18)->default(0);
        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('ID_CRIPTO')->references('ID_CRIPTO')->on('crypto_criptomonedas')->onDelete('cascade');
        
        $table->unique(['user_id', 'ID_CRIPTO']); // Evita duplicados
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crypto_wallets');
    }
};
