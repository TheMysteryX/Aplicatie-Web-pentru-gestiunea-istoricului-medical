<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tratament;
use App\Models\Pacient;
use App\Models\User;
use App\Models\Diagnostic;
use Faker\Factory as Faker;

class TratamenteSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ro_RO');
        $diagnostice = Diagnostic::take(10)->get();

        foreach ($diagnostice as $diag) {
            Tratament::create([
                'id_pacient'    => $diag->id_pacient, 
                'id_medic'      => $diag->id_medic,
                'id_diagnostic' => $diag->id,   
                'nume'          => $faker->word,
                'instructiuni'  => $faker->sentence(8),
                'data_inceput'  => now(),
                'data_sfarsit'  => now()->addDays(30)
            ]);
        }
    }
}
