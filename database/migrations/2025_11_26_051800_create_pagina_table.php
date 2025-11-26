<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagina', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('ruta', 150)->unique();
            $table->integer('visitas_totales')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagina');
    }
};
