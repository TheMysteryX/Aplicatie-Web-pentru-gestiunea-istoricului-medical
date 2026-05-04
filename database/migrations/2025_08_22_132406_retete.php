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
        Schema::create('retete', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pacient')->constrained('pacienti')->onDelete('cascade');
            $table->foreignId('id_medic')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_diagnostic')->constrained('diagnostice')->onDelete('cascade');
            $table->date('data_emitere');
            $table->date('data_expirare');
            $table->text('medicamente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retete');
    }
};
