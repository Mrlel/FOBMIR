<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nom' => 'Lela',
            'prenom' => 'dominick',
            'telephone' => '0720796688',
            'role' => 'superadmin',
            'password' => Hash::make('12345678'),
        ]);
    }
}
