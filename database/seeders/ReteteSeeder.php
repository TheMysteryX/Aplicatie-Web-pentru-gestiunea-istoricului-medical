<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reteta;
use App\Models\Diagnostic;
use Faker\Factory as Faker;

class ReteteSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ro_RO');
        $diagnostice = Diagnostic::take(10)->get();

        foreach ($diagnostice as $diag) {
            Reteta::create([
                'id_pacient' => $diag->id_pacient,
                'id_medic' => $diag->id_medic,
                'id_diagnostic' => $diag->id,
                'data_emitere' => now(),
                'data_expirare' => now()->addDays(30),
                'medicamente' => implode(', ', $faker->words(3))
            ]);
        }
    }
}
