<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Diagnostic;
use App\Models\Programare;
use Faker\Factory as Faker;

class DiagnosticeSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ro_RO');
        $programari = Programare::all();

        foreach ($programari->take(10) as $prog) {
            Diagnostic::create([
                'id_pacient' => $prog->id_pacient,
                'id_medic' => $prog->id_medic,
                'id_programare' => $prog->id,
                'nume' => $faker->word,
                'descriere' => $faker->sentence,
                'data' => $faker->date()
            ]);
        }
    }
}

