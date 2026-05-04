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
        Schema::create('programari', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pacient')->constrained('pacienti')->onDelete('cascade');
            $table->foreignId('id_medic')->constrained('users')->onDelete('cascade');
            $table->datetime('data');
            $table->enum('status', ['viitoare', 'finalizata', 'amanata'])->default('viitoare');
            $table->text('detalii')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programari');
    }
};
