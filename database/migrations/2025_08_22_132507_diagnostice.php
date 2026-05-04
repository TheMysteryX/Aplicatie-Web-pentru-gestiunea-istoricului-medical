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
        Schema::create('diagnostice', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pacient')->constrained('pacienti')->onDelete('cascade');
            $table->foreignId('id_medic')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_programare')->constrained('programari')->onDelete('cascade');
            $table->string('nume');
            $table->text('descriere')->nullable();
            $table->date('data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnostice');
    }
};
