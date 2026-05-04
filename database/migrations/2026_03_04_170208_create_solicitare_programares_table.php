<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('solicitari_programari', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pacient_id')->constrained('pacienti')->onDelete('cascade');
            $table->foreignId('medic_id')->constrained('users')->onDelete('cascade');
            $table->date('data_start');
            $table->date('data_end');
            $table->text('mesaj')->nullable();
            $table->enum('status', ['trimisa','rezolvata','respinsa'])->default('trimisa');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitari_programari');
    }
};