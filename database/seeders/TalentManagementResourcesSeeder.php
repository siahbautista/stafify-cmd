<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TalentManagementResourcesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $resources = [
            [
                'resource_key' => 'interview-invitation',
                'title' => 'E-Interview Invitation Form and Responses',
                'type' => 'spreadsheet',
                'url' => 'https://docs.google.com/spreadsheets/d/1db1WRYV7Cpd4qQm1w0GStzttaC4UlC7JAi1r6h4NaW0/edit?usp=sharing',
                'form_url' => 'https://docs.google.com/forms/d/e/1FAIpQLScrznr5xjPJOyjzb7wQBaMESwPHeaUgjJbsGukDvZsiNsUjeQ/viewform?usp=dialog',
                'icon_path' => null,
                'icon_lordicon' => 'rhvddzym',
                'display_order' => 1,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'resource_key' => 'reviewer',
                'title' => 'Reviewer for Internal Team',
                'type' => 'document',
                'url' => 'https://docs.google.com/document/d/1Uvh3sIRRGozBvYKeq9WSvPUkUwHNWtj_BJOPOqolZBk/edit?usp=sharing',
                'form_url' => null,
                'icon_path' => null,
                'icon_lordicon' => 'zpxybbhl',
                'display_order' => 2,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'resource_key' => 'final-exam',
                'title' => 'Final Exam for Internal Team',
                'type' => 'form',
                'url' => 'https://docs.google.com/forms/d/e/1FAIpQLSdAmRFVJ0Z-XeI0sI4Xy1zoyTsiqPruXAOc9Ir_SIrJoXy0qg/viewform?usp=dialog',
                'form_url' => 'https://docs.google.com/forms/d/e/1FAIpQLSdAmRFVJ0Z-XeI0sI4Xy1zoyTsiqPruXAOc9Ir_SIrJoXy0qg/viewform?usp=dialog',
                'icon_path' => null,
                'icon_lordicon' => 'jvucoldz',
                'display_order' => 3,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'resource_key' => 'training-guide',
                'title' => 'Training Guide for Internal Team',
                'type' => 'document',
                'url' => 'https://docs.google.com/document/d/1QPKCyfv6zkmGQn2a5exhWSOo3P68xDKiKHaMetQGX5Q/edit?usp=sharing',
                'form_url' => null,
                'icon_path' => null,
                'icon_lordicon' => 'dxjqoygy',
                'display_order' => 4,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('talent_management_resources')->insert($resources);
    }
}