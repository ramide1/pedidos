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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('nombre')->nullable();
            $table->string('telefono')->nullable();
            $table->string('direccion');
            $table->text('notas')->nullable();
            $table->dateTime('entrega')->nullable();
            $table->enum('estado', ['pendiente', 'confirmado', 'en_preparacion', 'en_camino', 'entregado', 'cancelado'])->default('pendiente');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('costo_envio', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('metodo_pago')->nullable();
            $table->boolean('pago_confirmado')->default(false);
            $table->foreignId('restaurante_id')->constrained()->onDelete('cascade');
            $table->string('codigo')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
