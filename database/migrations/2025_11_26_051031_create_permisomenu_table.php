<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permisomenu', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_usuario', 50);
            $table->unsignedBigInteger('id_menu');
            $table->timestamps();

            $table->foreign('id_menu')->references('id')->on('menu')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permisomenu');
    }
};
