<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// agregar campo MARCA

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('nombre');
            $table->integer('cantidad_unidades')->default(0);
            $table->integer('cantidad_bultos')->default(0);
            $table->integer('bultos_min_aviso')->default(0);
            $table->integer('cantidad_por_bulto')->default(0);
            $table->decimal('precio_compra_unitario', 10, 2)->default(0);
            $table->decimal('precio_compra_bulto', 10, 2)->default(0);
            $table->decimal('precio_venta_unitario', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
