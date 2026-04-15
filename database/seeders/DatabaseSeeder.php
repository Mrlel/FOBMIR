<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            PaysSeeder::class,
            DistrictSeeder::class,
            RegionSeeder::class,
            DepartementSeeder::class,
            sous_prefectureSeeder::class,
            CommuneSeeder::class,
            VillageSeeder::class,
            Type_quartierSeeder::class,

            AdminSeeder::class,
            Type_quartierSeeder::class,
            Type_sous_quartSeeder::class,
            Origine_menageSeeder::class,
            Type_documentSeeder::class,
            ClasseurSeeder::class,
            
        ]);
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
