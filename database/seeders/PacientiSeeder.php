<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pacient;
use App\Models\User;
use Faker\Factory as Faker;

class PacientiSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ro_RO');

        for ($i=0; $i<10; $i++) {
            Pacient::create([
                'nume' => $faker->lastName,
                'prenume' => $faker->firstName,
                'data_nasterii' => $faker->date(),
                'cnp' => $faker->numerify('###########'),
                'data_nasterii' => $faker->date(),
                'telefon' => $faker->numerify('07########'),
                'adresa' => $faker->address,
                'asigurat/a' => $faker->boolean
            ]);
        }
    }
}
