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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();

            // Agregar columnas para el cliente
            $table->string('nombre');  // Nombre del cliente
            $table->string('apellido_paterno');  // Apellido paterno
            $table->string('apellido_materno');  // Apellido materno
            $table->string('telefono');  // Teléfono del cliente
            $table->string('direccion');  // Dirección del cliente
            $table->string('dni')->unique(); // DNI del cliente (único)

            $table->timestamps();  // Fechas de creación y actualización
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
