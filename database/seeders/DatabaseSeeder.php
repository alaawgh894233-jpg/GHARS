<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            AdminSeeder::class,
            RegionSeeder::class,
            SpecializationSeeder::class,
            ProductionTypeSeeder::class,
            LabSampleTypeSeeder::class,
            LabTestSeeder::class,
            LaboratorySeeder::class
        ]);
//        // User::factory(10)->create();
//
//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);
    }
}
