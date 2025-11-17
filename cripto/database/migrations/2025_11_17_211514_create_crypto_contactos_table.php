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
Schema::create('crypto_contactos', function (Blueprint $table) {
        $table->increments('ID_CONTACTO');
        $table->unsignedBigInteger('user_id'); // El usuario dueño
        $table->unsignedBigInteger('contacto_user_id'); // El usuario que es el contacto
        $table->string('NAME', 100)->nullable(); // Alias para el contacto
        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('contacto_user_id')->references('id')->on('users')->onDelete('cascade');
        
        $table->unique(['user_id', 'contacto_user_id']); // Evita duplicados
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crypto_contactos');
    }
};
