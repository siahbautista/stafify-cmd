<?php

namespace Database\Seeders;

use App\Models\TalentToolkit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TalentToolkitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $talentToolkits = [
            [
                'user_id' => 1,
                'sales_title' => 'E-Interview',
                'form_url' => 'https://docs.google.com/forms/d/example1/viewform',
                'response_url' => 'https://docs.google.com/spreadsheets/d/example1/edit',
                'icon' => 'interview.gif',
                'type' => 'Form+Sheet',
                'is_approved' => true,
            ],
            [
                'user_id' => 1,
                'sales_title' => 'Quicklinks of Reviewer',
                'form_url' => 'https://drive.google.com/folders/example2',
                'response_url' => null,
                'icon' => 'reviewer.gif',
                'type' => 'Folder',
                'is_approved' => true,
            ],
            [
                'user_id' => 1,
                'sales_title' => 'Final Exam',
                'form_url' => 'https://docs.google.com/forms/d/example3/viewform',
                'response_url' => null,
                'icon' => 'exam.gif',
                'type' => 'Form',
                'is_approved' => true,
            ],
            [
                'user_id' => 1,
                'sales_title' => 'Training Guide',
                'form_url' => null,
                'response_url' => 'https://docs.google.com/spreadsheets/d/example4/edit',
                'icon' => 'guide.gif',
                'type' => 'Sheet',
                'is_approved' => true,
            ],
            [
                'user_id' => 1,
                'sales_title' => 'Media Assets',
                'form_url' => 'https://drive.google.com/folders/example5',
                'response_url' => null,
                'icon' => 'media.gif',
                'type' => 'Folder',
                'is_approved' => true,
            ],
        ];

        foreach ($talentToolkits as $toolkit) {
            TalentToolkit::create($toolkit);
        }
    }
}
