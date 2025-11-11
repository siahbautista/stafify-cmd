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
        // User::factory(10)->create();

        // Note: We're using custom users table structure, not Laravel's default
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Seed Stafify database tables
        $this->call([
            UsersSeeder::class,  // Must run first for foreign key references
            StafifyDatabaseSeeder::class,
            StafifyShiftsSeeder::class,
            StafifyTimeTrackingSeeder::class,
            HrToolkitSeeder::class,
            LegalToolkitSeeder::class,
            TalentToolkitSeeder::class,
            TalentAcquisitionToolkitSeeder::class,
        ]);
    }
}
