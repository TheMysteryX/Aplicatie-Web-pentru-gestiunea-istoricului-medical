<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use App\Models\User;


class MediciSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nume'=>'Popescu',
            'prenume'=>'Ion',
            'email'=>'pop@yahoo.com',
            'password'=>'popescu',
            'rol'=>'medic',
            'spec_id'=>'2',
            'cnp'=>'6048594812',
            'data_nasterii'=>'1980-05-15',
            'adresa' => 'Str. Crizantemelor, Nr.42',
            'telefon'=>'0786247491', 
        ]);

        $faker = Faker::create('ro_RO');
        for ($i=0; $i<8; $i++) {
            User::create([
                'nume' => $faker->lastName,
                'prenume' => $faker->firstName,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'rol' => 'medic',
                'spec_id' => rand(1,10),
                'cnp' => $faker->numerify('###########'),
                'data_nasterii' => $faker->date(),
                'adresa' => $faker->address,
                'telefon' => $faker->numerify('07########')
            ]);
        }
    }
}
