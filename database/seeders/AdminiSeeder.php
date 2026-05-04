<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nume'=>'Radu',
            'prenume'=>'Maria',
            'email'=>'mariaalexandraradu@yahoo.com',
            'password'=>Hash::make('mariaradu'),
            'rol'=>'admin',
            'cnp'=>'6098765432',
            'data_nasterii'=>'2000-01-01',
            'adresa' => 'Str. Lalelelor, Nr.69',
            'telefon'=>'0786868686',            
        ]);
    }
}
