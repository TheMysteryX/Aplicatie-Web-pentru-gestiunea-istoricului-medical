<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medic_pacient', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_medic')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_pacient')->constrained('pacienti')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medic_pacient');
    }
};


