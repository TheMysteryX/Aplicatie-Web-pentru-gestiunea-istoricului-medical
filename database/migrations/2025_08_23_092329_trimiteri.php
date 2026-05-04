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
        Schema::create('trimiteri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pacient')->constrained('pacienti')->onDelete('cascade');
            $table->foreignId('id_medic')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_programare')->constrained('programari')->onDelete('cascade');
            $table->string('titlu');
            $table->text('detalii')->nullable();
            $table->text('locatie');
            $table->date('data_emitere');
            $table->date('data_expirare');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trimiteri');
    }
};
