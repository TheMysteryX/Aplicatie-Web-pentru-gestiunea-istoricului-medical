<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SpecializariSeeder::class,
            AdminiSeeder::class,
            MediciSeeder::class,
            PacientiSeeder::class,
            ProgramariSeeder::class,
            DiagnosticeSeeder::class,
            ReteteSeeder::class,
            TratamenteSeeder::class,
            TrimiteriSeeder::class,
        ]);
    }
}
