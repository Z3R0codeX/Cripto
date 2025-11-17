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
      Schema::create('crypto_criptomonedas', function (Blueprint $table) {
        $table->increments('ID_CRIPTO');
        $table->string('NAME', 50)->unique();
        $table->string('SHORTNAME', 10)->unique();
        $table->decimal('DECIMALES', 10, 0);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crypto_criptomonedas');
    }
};
