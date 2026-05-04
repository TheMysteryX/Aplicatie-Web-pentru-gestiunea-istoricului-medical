<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Specializare;

class SpecializariSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specializari = [
            'Cardiologie','Dermatologie','Pediatrie','Neurologie',
            'Psihiatrie','Ortopedie','Oncologie','Ginecologie',
            'Urologie','Oftalmologie'
        ];

        foreach ($specializari as $spec) {
            Specializare::create(['nume' => $spec]);
        }
    }
}
