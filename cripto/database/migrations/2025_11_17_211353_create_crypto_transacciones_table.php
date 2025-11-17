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
      Schema::create('crypto_transacciones', function (Blueprint $table) {
        $table->increments('ID_TRANSACCION');
        $table->unsignedInteger('ID_WALLET'); // Clave foránea para 'wallets'
        $table->string('TIPO', 25);
        $table->decimal('MONTO', 36, 18);
        $table->string('DESCRIPCION', 255)->nullable();
        $table->timestamps();

        $table->foreign('ID_WALLET')->references('ID_WALLET')->on('crypto_wallets')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crypto_transacciones');
    }
};
