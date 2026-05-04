<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Programare;
use App\Models\Pacient;
use App\Models\User;
use Faker\Factory as Faker;

class ProgramariSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ro_RO');
        $pacienti = Pacient::pluck('id')->toArray();
        $medici = User::where('rol','medic')->pluck('id')->toArray();

        for ($i=0; $i<10; $i++) {
            Programare::create([
                'id_pacient' => $faker->randomElement($pacienti),
                'id_medic' => $faker->randomElement($medici),
                'data' => $faker->dateTimeBetween('-1 month','+1 month'),
                'status' => $faker->randomElement(['viitoare','finalizata','amanata']),
                'detalii' => $faker->sentence()
            ]);
        }
    }
}
