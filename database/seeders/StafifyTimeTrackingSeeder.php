<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StafifyTimeTrackingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed time tracking data (sample of the most important records)
        DB::table('stafify_time_tracking')->insert([
            // April 2025 records
            ['record_id' => 1, 'shift_id' => 1, 'user_id' => 4, 'clock_in_time' => null, 'clock_out_time' => null, 'record_date' => '2025-04-23', 'total_hours' => null, 'status' => 'incomplete', 'location' => null, 'notes' => null, 'created_at' => '2025-04-22 09:19:32'],
            ['record_id' => 2, 'shift_id' => 2, 'user_id' => 4, 'clock_in_time' => null, 'clock_out_time' => null, 'record_date' => '2025-04-24', 'total_hours' => null, 'status' => 'pending', 'location' => null, 'notes' => null, 'created_at' => '2025-04-22 09:19:32'],
            ['record_id' => 3, 'shift_id' => 3, 'user_id' => 4, 'clock_in_time' => null, 'clock_out_time' => null, 'record_date' => '2025-04-25', 'total_hours' => null, 'status' => 'pending', 'location' => null, 'notes' => null, 'created_at' => '2025-04-22 09:19:32'],
            
            // Allen Lim's April records
            ['record_id' => 22, 'shift_id' => 22, 'user_id' => 2, 'clock_in_time' => null, 'clock_out_time' => null, 'record_date' => '2025-04-21', 'total_hours' => null, 'status' => 'pending', 'location' => null, 'notes' => null, 'created_at' => '2025-04-22 18:24:34'],
            ['record_id' => 23, 'shift_id' => 23, 'user_id' => 2, 'clock_in_time' => null, 'clock_out_time' => null, 'record_date' => '2025-04-22', 'total_hours' => null, 'status' => 'pending', 'location' => null, 'notes' => null, 'created_at' => '2025-04-22 18:24:34'],
            ['record_id' => 24, 'shift_id' => 24, 'user_id' => 2, 'clock_in_time' => null, 'clock_out_time' => null, 'record_date' => '2025-04-23', 'total_hours' => null, 'status' => 'pending', 'location' => null, 'notes' => null, 'created_at' => '2025-04-22 18:24:34'],
            ['record_id' => 25, 'shift_id' => 25, 'user_id' => 2, 'clock_in_time' => null, 'clock_out_time' => null, 'record_date' => '2025-04-24', 'total_hours' => null, 'status' => 'pending', 'location' => null, 'notes' => null, 'created_at' => '2025-04-22 18:24:34'],
            ['record_id' => 26, 'shift_id' => 26, 'user_id' => 2, 'clock_in_time' => null, 'clock_out_time' => null, 'record_date' => '2025-04-25', 'total_hours' => null, 'status' => 'pending', 'location' => null, 'notes' => null, 'created_at' => '2025-04-22 18:24:34'],
            ['record_id' => 27, 'shift_id' => 27, 'user_id' => 2, 'clock_in_time' => null, 'clock_out_time' => null, 'record_date' => '2025-04-26', 'total_hours' => null, 'status' => 'pending', 'location' => null, 'notes' => null, 'created_at' => '2025-04-22 18:24:34'],
            ['record_id' => 28, 'shift_id' => 28, 'user_id' => 2, 'clock_in_time' => null, 'clock_out_time' => null, 'record_date' => '2025-04-27', 'total_hours' => null, 'status' => 'pending', 'location' => null, 'notes' => null, 'created_at' => '2025-04-22 18:24:34'],
            
            // May 2025 records with actual clock in/out times
            ['record_id' => 29, 'shift_id' => 1, 'user_id' => 4, 'clock_in_time' => '2025-04-23 10:07:09', 'clock_out_time' => '2025-04-23 17:00:00', 'record_date' => '2025-04-23', 'total_hours' => 6.88, 'status' => 'incomplete', 'location' => '', 'notes' => "\nSystem auto clock-out: shift ended", 'created_at' => '2025-04-23 02:07:09'],
            
            // May 5th records
            ['record_id' => 30, 'shift_id' => 29, 'user_id' => 2, 'clock_in_time' => null, 'clock_out_time' => null, 'record_date' => '2025-05-05', 'total_hours' => null, 'status' => 'incomplete', 'location' => null, 'notes' => null, 'created_at' => '2025-05-04 09:03:08'],
            ['record_id' => 40, 'shift_id' => 39, 'user_id' => 4, 'clock_in_time' => null, 'clock_out_time' => null, 'record_date' => '2025-05-05', 'total_hours' => null, 'status' => 'completed', 'location' => null, 'notes' => null, 'created_at' => '2025-05-04 09:03:08'],
            ['record_id' => 50, 'shift_id' => 49, 'user_id' => 5, 'clock_in_time' => null, 'clock_out_time' => null, 'record_date' => '2025-05-05', 'total_hours' => null, 'status' => 'completed', 'location' => null, 'notes' => null, 'created_at' => '2025-05-04 09:03:08'],
            ['record_id' => 80, 'shift_id' => 79, 'user_id' => 3, 'clock_in_time' => null, 'clock_out_time' => null, 'record_date' => '2025-05-05', 'total_hours' => null, 'status' => 'completed', 'location' => null, 'notes' => null, 'created_at' => '2025-05-04 09:03:08'],
            
            // May 5th actual clock records
            ['record_id' => 85, 'shift_id' => 39, 'user_id' => 4, 'clock_in_time' => '2025-05-05 12:58:50', 'clock_out_time' => '2025-05-05 21:00:00', 'record_date' => '2025-05-05', 'total_hours' => 8.02, 'status' => 'completed', 'location' => '', 'notes' => "\nSystem auto clock-out: shift ended", 'created_at' => '2025-05-05 04:58:50'],
            ['record_id' => 86, 'shift_id' => 49, 'user_id' => 5, 'clock_in_time' => '2025-05-05 12:59:41', 'clock_out_time' => '2025-05-05 21:00:00', 'record_date' => '2025-05-05', 'total_hours' => 8.01, 'status' => 'completed', 'location' => '', 'notes' => "\nSystem auto clock-out: shift ended", 'created_at' => '2025-05-05 04:59:41'],
            ['record_id' => 87, 'shift_id' => 79, 'user_id' => 3, 'clock_in_time' => '2025-05-05 12:59:53', 'clock_out_time' => '2025-05-05 21:00:00', 'record_date' => '2025-05-05', 'total_hours' => 8.00, 'status' => 'completed', 'location' => '', 'notes' => "\nSystem auto clock-out: shift ended", 'created_at' => '2025-05-05 04:59:53'],
            ['record_id' => 88, 'shift_id' => 29, 'user_id' => 2, 'clock_in_time' => '2025-05-05 13:01:03', 'clock_out_time' => '2025-05-05 21:00:00', 'record_date' => '2025-05-05', 'total_hours' => 7.98, 'status' => 'incomplete', 'location' => '', 'notes' => "\nSystem auto clock-out: shift ended", 'created_at' => '2025-05-05 05:01:03'],
            
            // May 6th records
            ['record_id' => 92, 'shift_id' => 60, 'user_id' => 11, 'clock_in_time' => '2025-05-06 12:56:29', 'clock_out_time' => '2025-05-06 21:03:57', 'record_date' => '2025-05-06', 'total_hours' => 8.12, 'status' => 'completed', 'location' => '', 'notes' => '', 'created_at' => '2025-05-06 04:56:29'],
            ['record_id' => 93, 'shift_id' => 75, 'user_id' => 7, 'clock_in_time' => '2025-05-06 12:58:17', 'clock_out_time' => '2025-05-06 21:02:44', 'record_date' => '2025-05-06', 'total_hours' => 8.07, 'status' => 'completed', 'location' => '', 'notes' => '', 'created_at' => '2025-05-06 04:58:17'],
            
            // August 2025 record
            ['record_id' => 105, 'shift_id' => 84, 'user_id' => 24, 'clock_in_time' => null, 'clock_out_time' => null, 'record_date' => '2025-08-15', 'total_hours' => null, 'status' => 'pending', 'location' => null, 'notes' => null, 'created_at' => '2025-08-12 01:35:29'],
        ]);
    }
}