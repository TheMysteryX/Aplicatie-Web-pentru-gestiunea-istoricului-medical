<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trimitere;
use App\Models\Programare;
use Faker\Factory as Faker;

class TrimiteriSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ro_RO');
        $programari = Programare::all();

        foreach ($programari->take(10) as $prog) {
            Trimitere::create([
                'id_pacient' => $prog->id_pacient,
                'id_medic' => $prog->id_medic,
                'id_programare' => $prog->id,
                'titlu' => $faker->word,
                'detalii' => $faker->sentence(6),
                'locatie' => $faker->city,
                'data_emitere' => now(),
                'data_expirare' => now()->addDays(30)
            ]);
        }
    }
}
